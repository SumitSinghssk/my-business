<?php

use App\Helpers\Settings;
use App\Models\Notification;

if (! function_exists('settings')) {
    function settings(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return Settings::get('', []);
        }

        return Settings::get($key, $default);
    }
}

if (! function_exists('notify')) {
    function notify(
        string $type,
        string $title,
        ?string $message = null,
        array $data = [],
        ?string $url = null
    ): Notification {
        return Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'url' => $url,
        ]);
    }
}
