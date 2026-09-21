<?php

namespace App\Http\Controllers;

use App\Http\Requests\Website\ContactRequest;
use App\Models\Enquiry;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function index()
    {
        return view('website.contact.index', [
            'services' => ContactRequest::serviceOptions(),
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

        notify(
            'Enquiry',
            'New Contact Enquiry',
            "{$enquiry->data['name']} ({$enquiry->data['email']}) sent a message via the contact form",
            ['enquiry_id' => $enquiry->id],
            route('admin.enquiries.index')
        );

        return to_route('contact')
            ->with('contact_success', "Thanks, {$enquiry->data['name']}. Your message is with our team and we'll reply within one business day.");
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
