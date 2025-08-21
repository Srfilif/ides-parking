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
       $zonaGeneral = Zona::firstOrCreate(
    ['nombre' => 'general'],
    ['descripcion' => 'Zona general de parqueo']
);




$tipoAuto = TipoVehiculo::firstOrCreate(
    ['codigo' => 'AUT'], // 3 letras
    ['nombre' => 'Automóvil']
);
for ($i = 1; $i <= 5; $i++) {
    Espacios_parqueadero::create([
        'numero_espacio' => 'A' . $i,
        'zona_id' => $zonaGeneral->id,
        'tipo_vehiculo_id' => $tipoAuto->id,
    ]);
}
    }
}
