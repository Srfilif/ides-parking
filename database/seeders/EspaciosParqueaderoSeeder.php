<?php

namespace Database\Seeders;

use App\Models\Espacios_parqueadero; // Modelo existente
use Illuminate\Database\Seeder;
use App\Models\Zona;
use App\Models\TipoVehiculo;

class EspaciosParqueaderoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zonaGeneral = Zona::where('nombre', 'general')->first();
        $tipoAuto = TipoVehiculo::where('codigo', 'AUTO')->first();

        for ($i = 1; $i <= 5; $i++) {
            Espacios_parqueadero::create([
                'numero_espacio' => 'A' . $i,
                'zona_id' => $zonaGeneral->id,
                'tipo_vehiculo_id' => $tipoAuto->id,
            ]);
        }
    }
}
