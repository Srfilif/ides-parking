<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\VehicleEntry;
use App\Models\Espacios_parqueadero;
use App\Models\Tarifa;
use Carbon\Carbon;
use Faker\Factory as Faker;

class VehicleEntrySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Tomamos algunos vehículos y espacios
        $vehicles = Vehicle::inRandomOrder()->take(20)->get();
        $espacios = Espacios_parqueadero::inRandomOrder()->take(20)->get();

        foreach ($vehicles as $index => $vehicle) {
            $entryTime = Carbon::now()->subHours(rand(1, 48));
            $exitTime  = (clone $entryTime)->addMinutes(rand(30, 300));

            $espacio = $espacios[$index] ?? Espacios_parqueadero::inRandomOrder()->first();

            // tarifa según zona y tipo de vehículo
            $tarifa = Tarifa::where('zona_id', $espacio->zona_id ?? 1)
                ->where('tipo_vehiculo_id', $vehicle->tipo_vehiculo_id)
                ->first();

            // Calculamos costo
            $costo = 0;
            if ($tarifa) {
                $costo = $tarifa->calcularCosto($entryTime, $exitTime, $vehicle->tipoVehiculo, $tarifa->zona_id);
            }

            $duracion = $exitTime->diffInMinutes($entryTime);

            VehicleEntry::create([
                'vehicle_id'       => $vehicle->id,
                'espacio_id'       => $espacio?->id,
                'ticket_code'      => $faker->uuid,
                'casco'            => $faker->boolean,
                'chaleco'          => $faker->boolean,
                'llaves'           => $faker->boolean,
                'otro'             => $faker->boolean,
                'otro_texto'       => $faker->boolean ? $faker->sentence(3) : null,
                'costo_total'      => (int) $costo,
                'duracion_minutos' => $duracion,
                'tarifa_aplicada'  => $tarifa
                    ? "Zona {$tarifa->zona_id} - Vehiculo {$tarifa->tipo_vehiculo_id} - Valor {$tarifa->valor}"
                    : "Sin tarifa",
                'entry_time'       => $entryTime,
                'exit_time'        => $exitTime,
            ]);
        }
    }
}
