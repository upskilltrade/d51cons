<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InquiryStatus;
use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Support\InquiryNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $status = InquiryStatus::tryFrom((string) $request->query('status'));

        return view('admin.inquiries.index', [
            'inquiries' => Inquiry::query()
                ->when($status, fn ($query) => $query->where('status', $status))
                ->latest()
                ->paginate(30)
                ->withQueryString(),
            'status' => $status,
            'statuses' => InquiryStatus::cases(),
        ]);
    }

    public function show(Inquiry $inquiry): View
    {
        return view('admin.inquiries.show', [
            'inquiry' => $inquiry,
            'statuses' => InquiryStatus::cases(),
        ]);
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $inquiry->update($request->validate([
            'status' => ['required', Rule::enum(InquiryStatus::class)],
        ]));

        return back()->with('status', __('admin.saved'));
    }

    public function resend(Inquiry $inquiry, InquiryNotifier $notifier): RedirectResponse
    {
        if (! $inquiry->canRetryMail()) {
            return back()->with('status', __('admin.inquiry.not_retryable'));
        }

        return back()->with('status', $notifier->send($inquiry)
            ? __('admin.inquiry.resent')
            : __('admin.inquiry.resend_failed'));
    }
}
