<?php

namespace App\Http\Controllers;

use App\Helpers\Settings;
use App\Http\Requests\Website\ContactRequest;
use App\Models\Enquiry;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function index()
    {
        $emails = Settings::emails();
        $phones = Settings::phones();
        $socialLinks = Settings::socialLinks();

        return view('website.contact.index', [
            'services' => ContactRequest::serviceOptions(),
            'emails' => $emails,
            'phones' => $phones,
            'socialLinks' => $socialLinks,
            'offices' => Settings::offices(),
            // The right-hand column only appears when there is something to put in it.
            'hasSidebar' => $emails || $phones || $socialLinks,
        ]);
    }

    public function store(ContactRequest $request)
    {
        $data = $request->validated();
        unset($data['website']);

        // Store readable labels rather than option keys so the admin sees plain text.
        $data['service'] = ContactRequest::serviceOptions()[$data['service'] ?? ''] ?? null;

        $enquiry = Enquiry::create([
            'source' => 'contact-form',
            'source_url' => $this->sourceUrl($request),
            'status' => 'new',
            'data' => array_filter($data, fn ($value) => filled($value)),
        ]);

        // The enquiry is already saved: a failed notification must not show the visitor an error.
        rescue(fn () => notify(
            'Enquiry',
            'New Contact Enquiry',
            "{$enquiry->data['name']} ({$enquiry->data['email']}) sent a message via the contact form",
            ['enquiry_id' => $enquiry->id],
            route('admin.enquiries.index')
        ));

        $message = "Thanks, {$enquiry->data['name']}. Your message is with our team and we'll reply within one business day.";

        // The form submits with fetch (no page reload); a plain POST (JavaScript off) still redirects back.
        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->to(route('contact').'#contact-form')->with('contact_success', $message);
    }

    /**
     * The page the visitor came from, only when it is on this website. The Referer
     * header is visitor-controlled, so anything else (other sites, javascript: URLs)
     * is dropped rather than shown to admins as a clickable link.
     */
    private function sourceUrl(ContactRequest $request): ?string
    {
        $previous = url()->previous();
        $host = parse_url($previous, PHP_URL_HOST);
        $scheme = parse_url($previous, PHP_URL_SCHEME);

        return $host === $request->getHost() && in_array($scheme, ['http', 'https'], true)
            ? Str::limit($previous, 2000, '')
            : null;
    }
}
