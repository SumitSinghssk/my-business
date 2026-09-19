<?php

namespace App\Http\Controllers;

use App\Http\Requests\Website\ContactRequest;
use App\Models\Enquiry;

class ContactController extends Controller
{
    public function index()
    {
        return view('website.contact.index', [
            'services' => ContactRequest::SERVICES,
            'budgets' => ContactRequest::BUDGETS,
        ]);
    }

    public function store(ContactRequest $request)
    {
        $data = $request->validated();
        unset($data['website']);

        // Store readable labels rather than option keys so the admin sees plain text.
        $data['service'] = ContactRequest::SERVICES[$data['service'] ?? ''] ?? null;
        $data['budget'] = ContactRequest::BUDGETS[$data['budget'] ?? ''] ?? null;

        $enquiry = Enquiry::create([
            'source' => 'contact-form',
            'source_url' => url()->previous(),
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
}
