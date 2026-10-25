<?php

namespace App\Enums;

enum MailStatus: string
{
    case Pending = 'pending';
    case Sending = 'sending';
    case Sent = 'sent';
    case Failed = 'failed';

    /**
     * Statuses an admin may retry from.
     *
     * @return list<self>
     */
    public static function retryable(): array
    {
        return [self::Pending, self::Failed];
    }
}
