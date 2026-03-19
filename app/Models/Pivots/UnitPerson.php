<?php

namespace App\Models\Pivots;

use App\Enums\Roles\RoleName;
use App\Models\Person;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * Pivot model para la tabla unit_people.
 *
 * Usa Model (no Pivot) porque tiene id, uuid y lógica propia.
 * Se referencia desde Unit y Person con:
 *   ->using(UnitPerson::class)->withPivot([...])
 */
class UnitPerson extends Model
{
    protected $table = 'unit_people';

    public $incrementing = true;

    protected $fillable = [
        'uuid',
        'unit_id',
        'person_id',
        'role',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetimeTz',
        'end_date'   => 'datetimeTz',
        'is_active'  => 'boolean',
        'role'       => RoleName::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (UnitPerson $pivot) {
            if (empty($pivot->uuid)) {
                $pivot->uuid = Uuid::uuid4()->toString();
            }

            // Garantiza un solo owner activo y un solo tenant activo por unidad.
            // (MySQL no soporta partial unique indexes, se valida aquí.)
            $exists = static::where('unit_id', $pivot->unit_id)
                ->where('role', $pivot->role)
                ->where('is_active', true)
                ->exists();

            if ($exists) {
                $role = $pivot->role === RoleName::OWNER ? 'propietario' : 'arrendatario';
                throw new \RuntimeException(
                    "La unidad ya tiene un {$role} activo. Finalice la relación actual antes de asignar una nueva."
                );
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isOwner(): bool
    {
        return $this->role === RoleName::OWNER;
    }

    public function isTenant(): bool
    {
        return $this->role === RoleName::TENANT;
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /** Finaliza la relación registrando la fecha de salida. */
    public function terminate(): void
    {
        $this->update([
            'is_active' => false,
            'end_date'  => now(),
        ]);
    }
}
