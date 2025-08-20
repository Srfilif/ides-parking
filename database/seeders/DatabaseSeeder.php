<?php

namespace Database\Seeders;

use App\Models\Espacios_parqueadero;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $user = User::firstOrCreate(
            ['email' => 'jimmyalexanderpalacios@gmail.com'], // buscar por email
            [
                'name' => 'palacios jimmy',
                'password' => Hash::make('1087617551'),
            ]
        );

        // Solo asignar rol si no lo tiene
        if (!$user->hasRole('ADMINISTRADOR')) {
            $user->assignRole('ADMINISTRADOR');
        }

        $this->call([
            CategoriaSeeder::class,
            TipoDocumentoSeeder::class,
            MarcaSeeder::class,

            // ParkingSpaceSeeder::class,
            ZonaSeeder::class,
            EspaciosParqueaderoSeeder::class,
        ]);

    }
}
