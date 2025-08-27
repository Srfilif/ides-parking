<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehiclesSeeder extends Seeder
{
    public function run()
    {
        DB::table('vehicles')->insert([
            [
                'plate'             => 'ABC123',
                'brand'             => 'Toyota',
                'model'             => 'Corolla',
                'color'             => 'Rojo',
                'owner_id'          => 1,
                'is_active'         => true,
                'tipo_vehiculo_id'  => 1, // 1 = carro
                'is_mensualidad'    => false,
                'mensualidad_inicio'=> null,
                'mensualidad_fin'   => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'plate'             => 'XYZ456',
                'brand'             => 'Chevrolet',
                'model'             => 'Spark',
                'color'             => 'Negro',
                'owner_id'          => 1,
                'is_active'         => true,
                'tipo_vehiculo_id'  => 1, // carro
                'is_mensualidad'    => false,
                'mensualidad_inicio'=> null,
                'mensualidad_fin'   => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'plate'             => 'MOTO01',
                'brand'             => 'Yamaha',
                'model'             => 'FZ',
                'color'             => 'Azul',
                'owner_id'          => 1,
                'is_active'         => true,
                'tipo_vehiculo_id'  => 2, // 2 = moto
                'is_mensualidad'    => false,
                'mensualidad_inicio'=> null,
                'mensualidad_fin'   => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}
