<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_entries', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_entries', 'parking_space_id')) {
                $table->renameColumn('parking_space_id', 'espacio_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_entries', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_entries', 'espacio_id')) {
                $table->renameColumn('espacio_id', 'parking_space_id');
            }
        });
    }
};
