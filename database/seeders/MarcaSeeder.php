<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas = [
            [
                'nombre' => 'Chevrolet',
                'pais_origen' => 'Estados Unidos',
                'descripcion' => 'Marca líder en ventas de vehículos en Colombia.'
            ],
            [
                'nombre' => 'Renault',
                'pais_origen' => 'Francia',
                'descripcion' => 'Muy popular en Colombia, con gran presencia en autos de uso urbano.'
            ],
            [
                'nombre' => 'Mazda',
                'pais_origen' => 'Japón',
                'descripcion' => 'Fabricante japonés con gran acogida en vehículos familiares y deportivos.'
            ],
            [
                'nombre' => 'Yamaha',
                'pais_origen' => 'Japón',
                'descripcion' => 'Marca de motocicletas muy popular en Colombia.'
            ],
            [
                'nombre' => 'AKT',
                'pais_origen' => 'Colombia',
                'descripcion' => 'Marca colombiana de motocicletas de bajo y mediano cilindraje.'
            ],
            [
                'nombre' => 'Bavaria',
                'pais_origen' => 'Colombia',
                'descripcion' => 'Empresa productora de bebidas como Águila, Poker y Club Colombia.'
            ],
            [
                'nombre' => 'Postobón',
                'pais_origen' => 'Colombia',
                'descripcion' => 'Compañía líder en bebidas no alcohólicas en Colombia.'
            ],
            [
                'nombre' => 'Arturo Calle',
                'pais_origen' => 'Colombia',
                'descripcion' => 'Marca reconocida de moda masculina.'
            ],
            [
                'nombre' => 'Samsung',
                'pais_origen' => 'Corea del Sur',
                'descripcion' => 'Marca líder en smartphones y electrodomésticos en Colombia.'
            ],
            [
                'nombre' => 'Apple',
                'pais_origen' => 'Estados Unidos',
                'descripcion' => 'Marca premium de tecnología y smartphones muy usada en Colombia.'
            ],
        ];

        foreach ($marcas as $marca) {
            Marca::create($marca);
        }
    }
}
