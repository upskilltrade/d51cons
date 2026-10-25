<?php

namespace App\Models;

use App\Enums\InquiryStatus;
use App\Enums\MailStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name', 'email', 'phone', 'service_type', 'location', 'budget_range', 'message', 'locale',
    'privacy_acknowledged_at', 'status', 'mail_status', 'provider_message_id', 'notified_at',
])]
class Inquiry extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => InquiryStatus::class,
            'mail_status' => MailStatus::class,
            'privacy_acknowledged_at' => 'datetime',
            'notified_at' => 'datetime',
        ];
    }

    public function canRetryMail(): bool
    {
        return in_array($this->mail_status, MailStatus::retryable(), true);
    }
}
