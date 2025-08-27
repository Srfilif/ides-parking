<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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
        'duracion_minutos',
        'costo_total',
        'tarifa_aplicada',
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

    public function isActive()
    {
        return is_null($this->exit_time);
    }

    /**
     * Obtener la duración de la estancia
     */
    public function getDurationAttribute()
    {
        if (!$this->exit_time) {
            return $this->entry_time->diffInMinutes(Carbon::now());
        }

        return $this->entry_time->diffInMinutes($this->exit_time);
    }
    /**
     * Obtener la duración en horas
     */
    public function getDurationHoursAttribute()
    {
        return ceil($this->duration / 60);
    }

    /**
     * Obtener la duración en días
     */
    public function getDurationDaysAttribute()
    {
        if (!$this->exit_time) {
            return $this->entry_time->diffInDays(Carbon::now());
        }

        return $this->entry_time->diffInDays($this->exit_time);
    }

    /**
     * Scope para entradas activas
     */
    public function scopeActive($query)
    {
        return $query->whereNull('exit_time');
    }

    /**
     * Scope para entradas completadas
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('exit_time');
    }

    public function esMensualidad()
    {
        return $this->tarifa_aplicada && str_contains($this->tarifa_aplicada, 'Mensualidad');
    }
}
