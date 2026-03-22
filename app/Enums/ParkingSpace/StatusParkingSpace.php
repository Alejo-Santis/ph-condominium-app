<?php

namespace App\Enums\ParkingSpace;

enum StatusParkingSpace: string
{
    case AVAILABLE = 'available';
    case ASSIGNED = 'assigned';
    case BLOCKED = 'blocked';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Disponible',
            self::ASSIGNED => 'Asignado',
            self::BLOCKED => 'Bloqueado',
        };
    }
}
