<?php

namespace App\Support;

use App\Enums\MailStatus;
use App\Mail\NewInquiryMail;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

class InquiryNotifier
{
    /**
     * Notify the team about an inquiry that is already saved.
     *
     * The inquiry is claimed by moving it to "sending" in one conditional update,
     * so two concurrent attempts (double click, two admins) cannot both send.
     * A provider "sent" means the message was accepted, not delivered.
     */
    public function send(Inquiry $inquiry): bool
    {
        $claimed = Inquiry::query()
            ->whereKey($inquiry->getKey())
            ->whereIn('mail_status', array_map(fn (MailStatus $s) => $s->value, MailStatus::retryable()))
            ->update(['mail_status' => MailStatus::Sending->value]);

        if ($claimed === 0) {
            return false;
        }

        try {
            $recipient = config('d51.inquiry_to');

            if (blank($recipient)) {
                throw new RuntimeException('INQUIRY_TO_EMAIL is not configured.');
            }

            $sent = Mail::to($recipient)->send(new NewInquiryMail($inquiry));

            $inquiry->forceFill([
                'mail_status' => MailStatus::Sent,
                'provider_message_id' => $sent?->getMessageId(),
                'notified_at' => now(),
            ])->save();

            return true;
        } catch (Throwable $e) {
            $inquiry->forceFill(['mail_status' => MailStatus::Failed])->save();

            // Log the failure without the visitor's personal data.
            Log::warning('Inquiry notification failed.', [
                'inquiry_id' => $inquiry->getKey(),
                'exception' => $e::class,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
