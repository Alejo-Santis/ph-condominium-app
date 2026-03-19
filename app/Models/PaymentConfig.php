<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentConfig extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'property_id',
        'wompi_public_key',
        'wompi_private_key',
        'wompi_secret',
        'webhook_secret',
        'currency',
        'is_active',
    ];

    protected $hidden = [
        'wompi_private_key',
        'wompi_secret',
        'webhook_secret',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'wompi_private_key' => 'encrypted',
        'wompi_secret' => 'encrypted',
        'webhook_secret' => 'encrypted',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Verifica la firma de un webhook de Wompi.
     *
     * Wompi envía: X-Event-Checksum = SHA256(event + timestamp + webhook_secret)
     * Ref: https://docs.wompi.co/docs/en/widget-checkout-colombia
     *
     * @param array  $payload  Cuerpo del webhook parseado
     * @param string $checksum Header X-Event-Checksum enviado por Wompi
     */
    public function verifyWebhookSignature(array $payload, string $checksum): bool
    {
        $chain = data_get($payload, 'event')
            . data_get($payload, 'timestamp')
            . $this->webhook_secret;

        return hash_equals(hash('sha256', $chain), $checksum);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
