<?php

namespace App\Enums\Person;

enum DocumentTypePerson: string
{
    case CC = 'cc';
    case CE = 'ce';
    case NIT = 'nit';
    case PASSPORT = 'passport';

    public function label(): string
    {
        return match ($this) {
            self::CC => 'Cédula de Ciudadanía',
            self::CE => 'Cédula de Extranjería',
            self::NIT => 'NIT',
            self::PASSPORT => 'Pasaporte',
        };
    }
}
