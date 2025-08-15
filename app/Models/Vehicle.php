<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate',
        'tipo_vehiculo_id',
        'brand',
        'model',
        'color',
        'owner_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(VehicleEntry::class);
    }

    public function getCurrentEntry()
    {
        return $this->entries()->whereNull('exit_time')->first();
    }

    public function isParked(): bool
    {
        return $this->getCurrentEntry() !== null;
    }


    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'tipo_vehiculo_id');
    }
    public function marca()
{
    return $this->belongsTo(Marca::class, 'brand'); // 'brand' es la columna que guarda el marca_id
}

}
