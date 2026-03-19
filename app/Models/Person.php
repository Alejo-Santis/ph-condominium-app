<?php

namespace App\Models;

use App\Enums\Person\DocumentTypePerson;
use App\Enums\Roles\RoleName;
use App\Models\Concerns\HasUuid;
use App\Models\Pivots\UnitPerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'document_type',
        'document_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'alt_phone',
    ];

    protected $casts = [
        'document_type' => DocumentTypePerson::class,
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'unit_people')
            ->using(UnitPerson::class)
            ->withPivot(['uuid', 'role', 'start_date', 'end_date', 'is_active'])
            ->withTimestamps();
    }

    /** Unidades donde la persona tiene una relación activa (owner o tenant). */
    public function activeUnits(): BelongsToMany
    {
        return $this->units()->wherePivot('is_active', true);
    }

    /** Unidades donde es propietario activo. */
    public function ownedUnits(): BelongsToMany
    {
        return $this->units()
            ->wherePivot('role', RoleName::OWNER)
            ->wherePivot('is_active', true);
    }

    /** Unidades donde es arrendatario activo. */
    public function rentedUnits(): BelongsToMany
    {
        return $this->units()
            ->wherePivot('role', RoleName::TENANT)
            ->wherePivot('is_active', true);
    }

    public function unitPeople(): HasMany
    {
        return $this->hasMany(UnitPerson::class);
    }

    /** Cuenta de usuario del sistema asociado a esta persona. */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /** Suma de cargos pendientes + vencidos de esta persona. */
    public function pendingChargesTotal(): float
    {
        return (float) $this->charges()
            ->whereIn('status', ['pending', 'link_generated', 'overdue'])
            ->sum('amount');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeByDocument($query, string $type, string $number)
    {
        return $query->where('document_type', $type)->where('document_number', $number);
    }

    /** Solo personas que son propietarios activos en alguna unidad. */
    public function scopeOwners($query)
    {
        return $query->whereHas(
            'unitPeople',
            fn($q) => $q->where('role', RoleName::OWNER)->where('is_active', true)
        );
    }

    /** Solo personas que son arrendatarios activos en alguna unidad. */
    public function scopeTenants($query)
    {
        return $query->whereHas(
            'unitPeople',
            fn($q) => $q->where('role', RoleName::TENANT)->where('is_active', true)
        );
    }
}
