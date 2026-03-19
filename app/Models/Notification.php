<?php

namespace App\Models;

use App\Enums\Notification\StatusNotification;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'person_id',
        'charge_id',
        'channel',
        'type',
        'recipient',
        'subject',
        'body',
        'status',
        'data',
        'sent_at',
        'read_at',
    ];

    protected $casts = [
        'data'    => 'array',
        'sent_at' => 'datetimeTz',
        'read_at' => 'datetimeTz',
        'status'  => StatusNotification::class,
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class);
    }

    // ─── Status Helpers ───────────────────────────────────────────────────────

    public function isSent(): bool
    {
        return $this->status === StatusNotification::SENT;
    }

    public function isFailed(): bool
    {
        return $this->status === StatusNotification::FAILED;
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsSent(): void
    {
        $this->update([
            'status'  => StatusNotification::SENT,
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => StatusNotification::FAILED]);
    }

    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', StatusNotification::PENDING);
    }

    public function scopeSent($query)
    {
        return $query->where('status', StatusNotification::SENT);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', StatusNotification::FAILED);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeByChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }

    public function scopeForPerson($query, int $personId)
    {
        return $query->where('person_id', $personId);
    }
}
