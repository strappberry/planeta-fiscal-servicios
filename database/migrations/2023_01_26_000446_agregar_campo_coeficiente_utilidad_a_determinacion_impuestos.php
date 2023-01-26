<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('determinacion_impuestos', function (Blueprint $table) {
            $table->decimal('coeficiente_utilidad', 16, 2)->default(0)->after('isr_actividad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('determinacion_impuestos', function (Blueprint $table) {
            $table->dropColumn('coeficiente_utilidad');
        });
    }
};
