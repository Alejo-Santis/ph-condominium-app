<?php

namespace App\Models\Concerns;

use Ramsey\Uuid\Uuid;

/**
 * Trait HasUuid
 *
 * Genera automáticamente el campo uuid al crear cualquier modelo.
 * Configura getRouteKeyName() para que Laravel use uuid en las
 * rutas en lugar del id numérico.
 *
 * Uso: añadir `use HasUuid;` en cualquier modelo Eloquent.
 */
trait HasUuid
{
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function findByUuidOrFail(string $uuid): ?static
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }

    public function findByUuid(string $uuid): ?static
    {
        if (empty($uuid)) {
            return throw new \InvalidArgumentException('UUID no puede estar vacío');
        }
        return static::where('uuid', $uuid)->first();
    }
}
