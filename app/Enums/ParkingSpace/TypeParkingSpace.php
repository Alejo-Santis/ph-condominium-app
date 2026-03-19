<?php

namespace App\Enums\ParkingSpace;

enum TypeParkingSpace: string
{
    case VEHICLE = 'vehicle';
    case MOTORCYCLE = 'motorcycle';
    case BICYCLE = 'bicycle';

    public function label(): string
    {
        return match ($this) {
            self::VEHICLE => 'Vehículo',
            self::MOTORCYCLE => 'Motocicleta',
            self::BICYCLE => 'Bicicleta',
        };
    }
}
