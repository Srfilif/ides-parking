<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vehicle_entries', function (Blueprint $table) {
            $table->boolean('casco')->default(false);
            $table->boolean('chaleco')->default(false);
            $table->boolean('llaves')->default(false);
            $table->boolean('otro')->default(false);
            $table->text('otro_texto')->nullable();
        });
    }

    public function down()
    {
        Schema::table('vehicle_entries', function (Blueprint $table) {
            $table->dropColumn(['casco', 'chaleco', 'llaves', 'otro', 'otro_texto']);
        });
    }
};
