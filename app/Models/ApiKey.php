<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class ApiKey extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'property_id',
        'name',
        'key_hash',
        'is_active',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetimeTz',
    ];

    protected $hidden = [
        'key_hash',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Genera una nueva API Key, almacena su hash y retorna la clave en texto plano.
     * La clave en texto plano se muestra UNA SOLA VEZ al administrador.
     *
     * Uso:
     *   $plainKey = ApiKey::generateFor($property);
     *   Mostrar $plainKey al admin y nunca almacenarlo.
     *
     * @return array{model: ApiKey, plain_key: string}
     */
    public static function generateFor(Property $property, string $name): array
    {
        $plainKey = Uuid::uuid4()->toString();

        $model = static::create([
            'property_id' => $property->id,
            'name'        => $name,
            'key_hash'    => hash('sha256', $plainKey),
            'is_active'   => true,
        ]);

        return [
            'model'     => $model,
            'plain_key' => $plainKey,
        ];
    }

    /**
     * Busca y valida una API Key a partir del valor en texto plano.
     * Retorna el modelo si es válida y activa, null si no.
     */
    public static function findByPlainKey(string $plainKey): ?static
    {
        $hash = hash('sha256', $plainKey);

        return static::where('key_hash', $hash)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Registra el uso de la key (actualiza last_used_at sin tocar updated_at).
     */
    public function touch_usage(): void
    {
        $this->timestamps = false;
        $this->update(['last_used_at' => now()]);
        $this->timestamps = true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForProperty($query, int $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }
}
