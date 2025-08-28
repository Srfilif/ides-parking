<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->boolean('is_mensualidad')->default(false)->after('placa'); 
            $table->dateTime('mensualidad_inicio')->nullable()->after('is_mensualidad');
            $table->dateTime('mensualidad_fin')->nullable()->after('mensualidad_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['is_mensualidad', 'mensualidad_inicio', 'mensualidad_fin']);
        });
    }
};
