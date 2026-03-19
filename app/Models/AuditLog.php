<?php

namespace App\Models;

use App\Enums\AuditLog\ActionAuditLog;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;


class AuditLog extends Model
{
    use HasFactory, HasUuid;

    public const UPDATED_AT = null;

    protected $fillable = [
        'uuid',
        'user_id',
        'property_id',
        'entity',
        'entity_id',
        'action',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'entity_id' => 'integer',
        'created_at' => 'datetimeTz',
        'action'    => ActionAuditLog::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Registra una entrada en el audit log.
     * Uso desde cualquier parte de la aplicación:
     *
     *   AuditLog::record(
     *       action: 'UPDATE',
     *       entity: 'unit',
     *       entityId: $unit->id,
     *       old: $unit->getOriginal(),
     *       new: $unit->getChanges(),
     *   );
     */
    public static function record(
        string  $action,
        string  $entity,
        int     $entityId,
        array   $old = [],
        array   $new = [],
        ?int    $userId = null,
        ?int    $propertyId = null,
    ): static {
        return static::create([
            'user_id'     => $userId ?? Auth::id(),
            'property_id' => $propertyId,
            'entity'      => $entity,
            'entity_id'   => $entityId,
            'action'      => $action,
            'old_values'  => $old ?: null,
            'new_values'  => $new ?: null,
            'ip_address'  => request()?->ip(),
            'user_agent'  => request()?->userAgent(),
        ]);
    }

    public function scopeForEntity($query, string $entity, int $entityId)
    {
        return $query->where('entity', $entity)->where('entity_id', $entityId);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeForProperty($query, int $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
