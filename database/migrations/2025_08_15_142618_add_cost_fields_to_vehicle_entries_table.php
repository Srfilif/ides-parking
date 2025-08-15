<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostFieldsToVehicleEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_entries', function (Blueprint $table) {
            $table->decimal('costo_total', 10, 2)->nullable()->after('exit_time');
            $table->integer('duracion_minutos')->nullable()->after('costo_total');
            $table->string('tarifa_aplicada')->nullable()->after('duracion_minutos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_entries', function (Blueprint $table) {
            $table->dropColumn(['costo_total', 'duracion_minutos', 'tarifa_aplicada']);
        });
    }
}