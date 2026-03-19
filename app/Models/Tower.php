<?php

namespace App\Models;

use App\Enums\Tower\StatusTower;
use App\Enums\Unit\StatusUnit;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tower extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'property_id',
        'name',
        'floors',
        'has_elevator',
        'has_parking',
        'status',
        'description',
    ];

    protected $casts = [
        'floors'       => 'integer',
        'has_elevator' => 'boolean',
        'has_parking'  => 'boolean',
        'status'       => StatusTower::class,
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function parkingSpaces(): HasMany
    {
        return $this->hasMany(ParkingSpace::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function occupiedUnitsCount(): int
    {
        return $this->units()->where('status', StatusUnit::OCCUPIED)->count();
    }

    /** Porcentaje de unidades desocupadas. */
    public function vacancyRate(): float
    {
        $total = $this->units()->count();
        if ($total === 0) return 0.0;

        return round(($this->units()->where('status', StatusUnit::VACANT)->count() / $total) * 100, 2);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', StatusTower::ACTIVE);
    }

    public function scopeForProperty($query, int $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }
}
