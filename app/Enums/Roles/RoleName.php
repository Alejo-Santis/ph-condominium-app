<?php

namespace App\Enums\Roles;

enum RoleName: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case OWNER = 'owner';
    case TENANT = 'tenant';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::MANAGER => 'Gerente',
            self::OWNER => 'Propietario',
            self::TENANT => 'Inquilino',
        };
    }
}
