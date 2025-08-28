<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Espacios_parqueadero;
use App\Models\Zona;
use App\Models\TipoVehiculo;

class EspaciosParqueaderoSeeder extends Seeder
{
    public function run()
    {

      /* $zonaGeneral = Zona::firstOrCreate(
    ['nombre' => 'general'],
    ['descripcion' => 'Zona general de parqueo']
);*/




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

        // Obtener tipos de vehículo (nombres como los tienes: "carro", "moto")
        $tipoCarro = TipoVehiculo::whereRaw('LOWER(nombre) LIKE ?', ['car%'])->firstOrFail();
        $tipoMoto  = TipoVehiculo::whereRaw('LOWER(nombre) LIKE ?', ['moto%'])->firstOrFail();

        // Zonas (según tu mención: "General" y "Motocicletas")
        $zonaGeneral      = Zona::whereRaw('LOWER(nombre) LIKE ?', ['general%'])->firstOrFail();
        $zonaMotocicletas = Zona::whereRaw('LOWER(nombre) LIKE ?', ['%moto%'])->firstOrFail();

        // Crear espacios para CARROS en zona General: 007..040 (evitamos chocar con 001..006 que ya tienes)
        $this->crearEspacios(zonaId: $zonaGeneral->id, tipoVehiculoId: $tipoCarro->id, cantidad: 34, desde: 7);

        // Crear espacios para MOTOS en zona Motocicletas: 101..140 (rango separado para no chocar)
        $this->crearEspacios(zonaId: $zonaMotocicletas->id, tipoVehiculoId: $tipoMoto->id, cantidad: 40, desde: 101);
    }

    private function crearEspacios(int $zonaId, int $tipoVehiculoId, int $cantidad, int $desde): void
    {
        for ($i = 0; $i < $cantidad; $i++) {
            $numero = str_pad($desde + $i, 3, '0', STR_PAD_LEFT);

            // Evita duplicados por (zona + número)
            Espacios_parqueadero::firstOrCreate(
                [
                    'numero_espacio' => $numero,
                    'zona_id'        => $zonaId,
                ],
                [
                    'tipo_vehiculo_id' => $tipoVehiculoId,
                ]
            );
        }

    }
}
