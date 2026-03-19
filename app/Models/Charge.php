<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Charge extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'unit_id',
        'person_id',
        'concept',
        'amount',
        'billing_month',
        'origin',
        'external_reference',
        'status',
    ];

    protected $casts = [
        'amount'        => 'decimal:2',
        'billing_month' => 'date',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /** Última transacción de pago registrada. */
    public function latestTransaction(): HasOne
    {
        return $this->hasOne(PaymentTransaction::class)->latestOfMany();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // ─── Status Helpers ───────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }

    public function markAsOverdue(): void
    {
        $this->update(['status' => 'overdue']);
    }

    public function markLinkGenerated(): void
    {
        $this->update(['status' => 'link_generated']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'canceled']);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /** Todos los cargos sin pagar (pendiente + enlace generado + vencido). */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['pending', 'link_generated', 'overdue']);
    }

    /**
     * Filtra por mes de facturación.
     * @param string $yearMonth Formato 'Y-m', ej: '2026-03'
     */
    public function scopeForBillingMonth($query, string $yearMonth)
    {
        return $query->whereYear('billing_month', substr($yearMonth, 0, 4))
            ->whereMonth('billing_month', substr($yearMonth, 5, 2));
    }

    public function scopeFromApi($query)
    {
        return $query->where('origin', 'external_api');
    }

    public function scopeManual($query)
    {
        return $query->where('origin', 'manual');
    }
}
