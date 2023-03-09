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
            $table->after('determinacion', function (Blueprint $table) {
                $table->longText('deducciones')->default('[]');
                $table->longText('calculos_iva_isr')->default('[]');
                $table->longText('impuestos_federales')->default('[]');
            });
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
            $table->dropColumn('deducciones');
            $table->dropColumn('calculos_iva_isr');
            $table->dropColumn('impuestos_federales');
        });
    }
};
