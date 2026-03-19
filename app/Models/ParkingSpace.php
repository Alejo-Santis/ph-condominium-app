<?php

namespace App\Models;

use App\Enums\ParkingSpace\StatusParkingSpace;
use App\Enums\ParkingSpace\TypeParkingSpace;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingSpace extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'property_id',
        'tower_id',
        'unit_id',
        'code',
        'type',
        'status',
    ];

    protected $casts = [
        'status' => StatusParkingSpace::class,
        'type'   => TypeParkingSpace::class,
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function tower(): BelongsTo
    {
        return $this->belongsTo(Tower::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isAvailable(): bool
    {
        return $this->status === StatusParkingSpace::AVAILABLE;
    }

    public function isAssigned(): bool
    {
        return $this->status === StatusParkingSpace::ASSIGNED;
    }

    public function isBlocked(): bool
    {
        return $this->status === StatusParkingSpace::BLOCKED;
    }

    /** Asigna el parqueadero a una unidad. */
    public function assignTo(Unit $unit): void
    {
        $this->update([
            'unit_id' => $unit->id,
            'status'  => StatusParkingSpace::ASSIGNED,
        ]);
    }

    /** Libera el parqueadero. */
    public function unassign(): void
    {
        $this->update([
            'unit_id' => null,
            'status'  => StatusParkingSpace::AVAILABLE,
        ]);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('status', StatusParkingSpace::AVAILABLE);
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', StatusParkingSpace::ASSIGNED);
    }

    public function scopeForProperty($query, int $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
