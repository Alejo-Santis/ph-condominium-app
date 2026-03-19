<?php

namespace App\Enums\Charge;

enum OriginCharge: string
{
    case EXTERNALAPI = 'external_api';
    case MANUAL = 'manual';

    public function label(): string
    {
        return match ($this) {
            self::EXTERNALAPI => 'API Externa',
            self::MANUAL => 'Manual',
        };
    }
}
