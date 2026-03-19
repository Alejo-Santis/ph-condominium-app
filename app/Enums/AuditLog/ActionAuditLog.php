<?php

namespace App\Enums\AuditLog;

enum ActionAuditLog: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';

    public function label(): string
    {
        return match ($this) {
            self::CREATE => 'Creación',
            self::UPDATE => 'Actualización',
            self::DELETE => 'Eliminación',
        };
    }
}
