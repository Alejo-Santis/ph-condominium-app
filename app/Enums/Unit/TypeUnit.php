<?php

namespace App\Enums\Unit;

enum TypeUnit: string
{
    case RESIDENTIAL = 'residential';
    case COMMERCIAL = 'commercial';
    case MIXED = 'mixed';

    public function label(): string
    {
        return match ($this) {
            self::RESIDENTIAL => 'Residencial',
            self::COMMERCIAL => 'Comercial',
            self::MIXED => 'Mixto',
        };
    }
}
