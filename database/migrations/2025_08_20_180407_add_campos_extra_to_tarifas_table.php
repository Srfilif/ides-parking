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
    Schema::table('tarifas', function (Blueprint $table) {
        $table->unsignedInteger('fraccion_hora')->nullable();
        $table->unsignedInteger('hora_adicional')->nullable();
        $table->unsignedInteger('media_jornada')->nullable();
        $table->unsignedInteger('jornada_completa')->nullable();
        $table->unsignedInteger('mensualidad_diurna')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */

public function down(): void
{
    Schema::table('tarifas', function (Blueprint $table) {
        $table->dropColumn([
            'fraccion_hora',
            'hora_adicional',
            'media_jornada',
            'jornada_completa',
            'mensualidad_diurna',
        ]);
    });
}
};
