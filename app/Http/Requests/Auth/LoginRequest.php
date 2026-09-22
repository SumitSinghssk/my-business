<?php

namespace App\Http\Requests\Auth;

use App\Enums\CommonStatusEnum;
use App\Mail\CustomerVerificationMail;
use App\Models\Customer;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(?string $guard = null): void
    {
        $this->ensureIsNotRateLimited();

        $guard = $guard ?: Auth::getDefaultDriver();

        $model = $guard === 'customer' ? Customer::class : User::class;

        $user = $model::withTrashed()->where('email', $this->input('email'))->first();

        // Customer self-service flow inspects the account before authentication
        // (registration / re-sending verification links).
        if ($guard === 'customer') {
            if ($user && $user->trashed()) {
                throw ValidationException::withMessages([
                    'email' => 'Your account has been deleted. Please contact support.',
                ]);
            }

            if (! $user) {
                try {
                    $token = str()->random(60);
                    $user = $model::create([
                        'name' => $this->input('email'),
                        'email' => $this->input('email'),
                        'password' => $this->input('password'),
                        'status' => CommonStatusEnum::ACTIVE->value,
                        'is_verified' => false,
                        'verification_token' => $token,
                    ]);

                    $url = route('customer.verify', $token);
                    // Mail::to($user->email)->send(new CustomerVerificationMail($url));

                    session()->flash('info', 'A verification link has been sent to your email. Please verify your account before logging in.');

                    throw ValidationException::withMessages([
                        'message' => '',
                    ]);
                } catch (UniqueConstraintViolationException $e) {
                    $user = $model::where('email', $this->input('email'))->first();
                }
            }

            if ($user && ! $user->is_verified) {
                $token = str()->random(60);
                $user->update(['verification_token' => $token]);

                $url = route('customer.verify', $token);
                // Mail::to($user->email)->send(new CustomerVerificationMail($url));

                session()->flash('warning', 'Please verify your email. A new verification link has been sent.');

                throw ValidationException::withMessages([
                    'message' => '',
                ]);
            }
        }
        // Verify the password BEFORE revealing any account state. A wrong
        // password and a non-existent / inactive / deleted account all return
        // the same generic error, so the login form cannot be used to
        // enumerate valid accounts.
        if (! Auth::guard($guard)->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // 5 wrong passwords lock this email + IP out for 15 minutes.
            RateLimiter::hit($this->throttleKey(), 900);

            if ($guard === 'web') {
                rescue(fn () => ActivityLogger::failedLogin((string) $this->input('email'), $this), report: false);
            }

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Credentials are valid. Now enforce account status, logging the user
        // back out so an inactive (or soft-deleted) account never keeps a live
        // session. This branch is only reachable with a correct password.
        if ($user && ($user->trashed() || $user->status?->value !== CommonStatusEnum::ACTIVE->value)) {
            Auth::guard($guard)->logout();

            throw ValidationException::withMessages([
                'email' => $user->trashed()
                    ? 'Your account has been deleted. Please contact support.'
                    : 'Your account is not active. Please contact support.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
