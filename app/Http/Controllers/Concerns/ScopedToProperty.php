<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait para scoping multi-tenant en controladores admin.
 *
 * - super_admin: ve todos los registros (sin filtro)
 * - admin_property / owner: ve solo los de su property_id
 */
trait ScopedToProperty
{
    /**
     * Devuelve el property_id al que está limitado el usuario actual,
     * o null si es super_admin (sin restricción).
     */
    protected function scopedPropertyId(): ?int
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return null;
        }

        return $user->property_id;
    }

    /**
     * Aplica el scope de propiedad a un Builder si el usuario no es super_admin.
     * Úsalo en índices: $query->tap(fn($q) => $this->applyPropertyScope($q, 'property_id'))
     */
    protected function applyPropertyScope(Builder $query, string $column = 'property_id'): Builder
    {
        $propertyId = $this->scopedPropertyId();

        return $propertyId ? $query->where($column, $propertyId) : $query;
    }

    /**
     * Verifica que un modelo pertenece a la propiedad del usuario.
     * Lanza 403 si no tiene acceso.
     */
    protected function authorizeProperty(int $modelPropertyId): void
    {
        $propertyId = $this->scopedPropertyId();

        if ($propertyId && $modelPropertyId !== $propertyId) {
            abort(403, 'No tienes acceso a este recurso.');
        }
    }
}
