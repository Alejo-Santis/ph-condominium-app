<?php

namespace App\Enums\Notification;

enum StatusNotification: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
    case FAILED = 'failed';
    case BOUNCED = 'bounced';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendiente',
            self::SENT => 'Enviado',
            self::FAILED => 'Fallido',
            self::BOUNCED => 'Rebotado',
        };
    }
}
