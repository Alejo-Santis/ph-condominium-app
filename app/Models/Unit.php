<?php

namespace App\Models;

use App\Enums\Roles\RoleName;
use App\Enums\Unit\StatusUnit;
use App\Enums\Unit\TypeUnit;
use App\Models\Concerns\HasUuid;
use App\Models\Pivots\UnitPerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'tower_id',
        'number',
        'floor',
        'area_sqm',
        'coefficient',
        'type',
        'status',
    ];

    protected $casts = [
        'floor'       => 'integer',
        'area_sqm'    => 'decimal:2',
        'coefficient' => 'decimal:6',
        'status'      => StatusUnit::class,
        'type'        => TypeUnit::class,
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function tower(): BelongsTo
    {
        return $this->belongsTo(Tower::class);
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'unit_people')
            ->using(UnitPerson::class)
            ->withPivot(['uuid', 'role', 'start_date', 'end_date', 'is_active'])
            ->withTimestamps();
    }

    /** Propietarios activos de la unidad. */
    public function owners(): BelongsToMany
    {
        return $this->people()
            ->wherePivot('role', RoleName::OWNER)
            ->wherePivot('is_active', true);
    }

    /** Arrendatarios activos de la unidad. */
    public function tenants(): BelongsToMany
    {
        return $this->people()
            ->wherePivot('role', RoleName::TENANT)
            ->wherePivot('is_active', true);
    }

    public function unitPeople(): HasMany
    {
        return $this->hasMany(UnitPerson::class);
    }

    public function parkingSpaces(): HasMany
    {
        return $this->hasMany(ParkingSpace::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /** Acceso directo a la propiedad sin pasar por el scope de Torre. */
    public function getProperty(): Property
    {
        return $this->tower->property;
    }

    public function activeOwner(): ?Person
    {
        return $this->owners()->first();
    }

    public function activeTenant(): ?Person
    {
        return $this->tenants()->first();
    }

    /** Suma de cargos pendientes + vencidos de esta unidad. */
    public function pendingChargesTotal(): float
    {
        return (float) $this->charges()
            ->whereIn('status', ['pending', 'link_generated', 'overdue'])
            ->sum('amount');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeOccupied($query)
    {
        return $query->where('status', StatusUnit::OCCUPIED);
    }

    public function scopeVacant($query)
    {
        return $query->where('status', StatusUnit::VACANT);
    }

    public function scopeForSale($query)
    {
        return $query->where('status', StatusUnit::FORSALE);
    }

    public function scopeResidential($query)
    {
        return $query->where('type', TypeUnit::RESIDENTIAL);
    }

    public function scopeCommercial($query)
    {
        return $query->where('type', TypeUnit::COMMERCIAL);
    }

    public function scopeInTower($query, int $towerId)
    {
        return $query->where('tower_id', $towerId);
    }

    public function scopeOnFloor($query, int $floor)
    {
        return $query->where('floor', $floor);
    }
}
