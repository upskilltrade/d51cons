<?php

namespace App\Http\Controllers;

use App\Enums\InquiryStatus;
use App\Enums\MailStatus;
use App\Http\Requests\StoreInquiryRequest;
use App\Models\Inquiry;
use App\Models\Service;
use App\Support\InquiryNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function create(): View
    {
        return view('contact.create', [
            'services' => Service::query()->published()->ordered()->get(),
            'budgetRanges' => config('d51.budget_ranges'),
        ]);
    }

    public function store(StoreInquiryRequest $request, InquiryNotifier $notifier): RedirectResponse
    {
        // Bots that fill the hidden field get the normal success page and nothing is stored.
        if ($request->isSpam()) {
            return redirect()->route('contact.thanks');
        }

        $inquiry = Inquiry::create([
            ...$request->safe()->except(['privacy', 'website']),
            'locale' => app()->getLocale(),
            'privacy_acknowledged_at' => now(),
            'status' => InquiryStatus::New,
            'mail_status' => MailStatus::Pending,
        ]);

        // The inquiry is saved either way; a failed email is retried from the admin.
        $notifier->send($inquiry);

        return redirect()->route('contact.thanks');
    }

    public function thanks(): View
    {
        return view('contact.thanks');
    }
}
