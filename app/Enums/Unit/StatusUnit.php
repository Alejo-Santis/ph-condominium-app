<?php

namespace App\Enums\Unit;

enum StatusUnit: string
{
    case OCCUPIED = 'occupied';
    case VACANT = 'vacant';
    case FORSALE = 'for_sale';

    public function label(): string
    {
        return match ($this) {
            self::OCCUPIED => 'Ocupado',
            self::VACANT => 'Vacante',
            self::FORSALE => 'En venta',
        };
    }
}
