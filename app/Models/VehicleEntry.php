<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'espacio_id',
        'entry_time',
        'exit_time',
        'ticket_code',
        'casco',
    'chaleco',
    'llaves',
    'otro',
    'otro_texto',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
        'casco' => 'boolean',
    'chaleco' => 'boolean',
    'llaves' => 'boolean',
    'otro' => 'boolean',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // public function parkingSpace(): BelongsTo
    // {
    //     return $this->belongsTo(ParkingSpace::class);
    // }

public function espacio(): BelongsTo
{
    return $this->belongsTo(Espacios_parqueadero::class, 'espacio_id');
}

    public function getDurationAttribute()
    {
        if (!$this->exit_time) {
            return null;
        }
        
        return $this->entry_time->diffInMinutes($this->exit_time);
    }
}