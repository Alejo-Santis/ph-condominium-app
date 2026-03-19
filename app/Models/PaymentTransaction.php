<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'charge_id',
        'wompi_link_id',
        'wompi_transaction_id',
        'wompi_reference',
        'payment_url',
        'wompi_status',
        'amount_paid',
        'paid_at',
        'webhook_payload',
    ];

    protected $casts = [
        'amount_paid'     => 'decimal:2',
        'paid_at'         => 'datetimeTz',
        'webhook_payload' => 'array',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class);
    }

    // ─── Status Helpers ───────────────────────────────────────────────────────

    public function isApproved(): bool
    {
        return $this->wompi_status === 'APPROVED';
    }

    public function isPending(): bool
    {
        return in_array($this->wompi_status, ['PENDING', 'PROCESSING', null]);
    }

    public function isDeclined(): bool
    {
        return $this->wompi_status === 'DECLINED';
    }

    public function isVoided(): bool
    {
        return $this->wompi_status === 'VOIDED';
    }

    /**
     * Procesa el payload de un webhook de Wompi.
     * Actualiza el estado de la transacción y marca el cargo como pagado si es APPROVED.
     *
     * Uso:
     *   $transaction->processWebhook($request->all());
     */
    public function processWebhook(array $payload): void
    {
        $status        = data_get($payload, 'data.transaction.status');
        $amountInCents = data_get($payload, 'data.transaction.amount_in_cents');

        $this->update([
            'wompi_status'    => $status,
            'amount_paid'     => $amountInCents ? $amountInCents / 100 : null,
            'paid_at'         => $status === 'APPROVED' ? now() : null,
            'webhook_payload' => $payload,
        ]);

        if ($status === 'APPROVED') {
            $this->charge->markAsPaid();
        }
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeApproved($query)
    {
        return $query->where('wompi_status', 'APPROVED');
    }

    public function scopePending($query)
    {
        return $query->where(function ($q) {
            $q->whereIn('wompi_status', ['PENDING', 'PROCESSING'])
              ->orWhereNull('wompi_status');
        });
    }

    public function scopeByReference($query, string $reference)
    {
        return $query->where('wompi_reference', $reference);
    }
}
