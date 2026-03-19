<?php

namespace App\Models;

use App\Enums\Property\StatusProperty;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'nit',
        'address',
        'city',
        'department',
        'admin_email',
        'phone',
        'status',
    ];

    protected $casts = [
        'status' => StatusProperty::class,
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function towers(): HasMany
    {
        return $this->hasMany(Tower::class);
    }

    public function parkingSpaces(): HasMany
    {
        return $this->hasMany(ParkingSpace::class);
    }

    public function paymentConfig(): HasOne
    {
        return $this->hasOne(PaymentConfig::class);
    }

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Query de todas las unidades de la propiedad a través de sus torres.
     */
    public function units()
    {
        return Unit::whereIn('tower_id', $this->towers()->select('id'));
    }

    /**
     * Suma de coeficientes de todas las unidades (debe ser ≈ 100%).
     */
    public function totalCoefficient(): float
    {
        return (float) Unit::whereIn('tower_id', $this->towers()->select('id'))
            ->sum('coefficient');
    }

    /**
     * Retorna la API key activa más reciente para esta propiedad.
     */
    public function activeApiKey(): ?ApiKey
    {
        return $this->apiKeys()->where('is_active', true)->latest()->first();
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', StatusProperty::ACTIVE);
    }
}
