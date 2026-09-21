<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('admin.enquiries.view');

        $query = Enquiry::with('seenBy:id,name')
            ->select(['id', 'source', 'source_url', 'data', 'status', 'seen_at', 'seen_by', 'created_at', 'deleted_at'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('seen')) {
            match ($request->seen) {
                'unseen' => $query->whereNull('seen_at'),
                'seen' => $query->whereNotNull('seen_at'),
                default => null,
            };
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $term = '%'.strtolower(trim($request->search)).'%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(data, '$.name'))) LIKE ?", [$term])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(data, '$.email'))) LIKE ?", [$term])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(data, '$.phone'))) LIKE ?", [$term])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(data, '$.company'))) LIKE ?", [$term]);
            });
        }

        // Cache sources for 5 min — this rarely changes
        $sources = cache()->remember('enquiry_sources', 300, fn () => Enquiry::distinct()->orderBy('source')->pluck('source')
        );

        $enquiries = $query->paginate(20)->withQueryString();

        return view('admin.enquiries.index', compact('enquiries', 'sources'));
    }

    /**
     * Called when an admin opens an enquiry, so the unread counts (sidebar badge,
     * dashboard) and the seen/unseen filter reflect what has actually been read.
     */
    public function markSeen(Request $request, Enquiry $enquiry)
    {
        Gate::authorize('admin.enquiries.view');

        if ($enquiry->is_unseen) {
            $enquiry->forceFill([
                'seen_at' => now(),
                'seen_by' => $request->user()->id,
                'status' => $enquiry->status === 'new' ? 'seen' : $enquiry->status,
            ])->save();
        }

        return response()->json(['seen' => true]);
    }

    public function destroy(Enquiry $enquiry)
    {
        Gate::authorize('admin.enquiries.delete');

        $enquiry->delete();

        return back()->with('success', 'Enquiry Deleted');
    }
}
