<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $fillable = [
        'zona_id',
        'tipo_vehiculo_id',
        'fraccion_hora',
        'hora_adicional',
        'media_jornada',
        'jornada_completa',
        'mensualidad_diurna',
    ];

    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class);
    }


    public function calcularCosto($entryTime, $exitTime, $tipoVehiculo, $zonaId)
{
    $minutos = $exitTime->diffInMinutes($entryTime);

    // Buscar la tarifa correspondiente
    $tarifa = Tarifa::where('zona_id', $zonaId)
        ->where('tipo_vehiculo_id', $tipoVehiculo->id)
        ->first();

    if (!$tarifa) {
        return [0, "Sin tarifa configurada"];
    }

    $costo = 0;
    $tarifaAplicada = "Por horas";

    // Reglas especiales
    if (
        $entryTime->between(Carbon::parse($entryTime->format('Y-m-d 07:30')), Carbon::parse($entryTime->format('Y-m-d 08:00'))) &&
        $exitTime->between(Carbon::parse($exitTime->format('Y-m-d 12:00')), Carbon::parse($exitTime->format('Y-m-d 12:30')))
    ) {
        $costo = $tarifa->media_jornada ?? 0;
        $tarifaAplicada = "Media jornada AM";
    }
    elseif (
        $entryTime->between(Carbon::parse($entryTime->format('Y-m-d 12:30')), Carbon::parse($entryTime->format('Y-m-d 13:00'))) &&
        $exitTime->between(Carbon::parse($exitTime->format('Y-m-d 18:00')), Carbon::parse($exitTime->format('Y-m-d 18:30')))
    ) {
        $costo = $tarifa->media_jornada ?? 0;
        $tarifaAplicada = "Media jornada PM";
    }
    elseif (
        $entryTime->between(Carbon::parse($entryTime->format('Y-m-d 07:30')), Carbon::parse($entryTime->format('Y-m-d 08:00'))) &&
        $exitTime->between(Carbon::parse($exitTime->format('Y-m-d 15:30')), Carbon::parse($exitTime->format('Y-m-d 18:30')))
    ) {
        $costo = $tarifa->jornada_completa ?? 0;
        $tarifaAplicada = "Jornada completa";
    }
    // Por fracción / horas
    else {
        if ($minutos <= 30) {
            $costo = $tarifa->fraccion_hora ?? 0;
            $tarifaAplicada = "Fracción (30 min)";
        } elseif ($minutos <= 60) {
            $costo = $tarifa->fraccion_hora ?? 0; 
            $tarifaAplicada = "1 Hora";
        } else {
            $horas = ceil($minutos / 60);
            $costo = ($tarifa->fraccion_hora ?? 0) + ($horas - 1) * ($tarifa->hora_adicional ?? 0);
            $tarifaAplicada = "Por horas";
        }
    }

    // Tope máximo ( 6000)
    if ($costo > 6000) {
        $costo = 6000;
    }

    return [$costo, $tarifaAplicada];
}


}
