<?php

namespace App\Enums\Charge;

enum StatusCharge: string
{
    case PENDING = 'pending';
    case LINKGENERATED = 'link_generated';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendiente',
            self::LINKGENERATED => 'Link Generado',
            self::PAID => 'Pagada',
            self::OVERDUE => 'Vencida',
            self::CANCELLED => 'Cancelada',
        };
    }
}
