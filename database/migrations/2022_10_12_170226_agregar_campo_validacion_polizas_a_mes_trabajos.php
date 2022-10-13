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
        Schema::table('mes_trabajos', function (Blueprint $table) {
            $table->after('configuraciones', function (Blueprint $table) {
                $table->boolean('polizas_validadas')->default(false);
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
        Schema::table('mes_trabajos', function (Blueprint $table) {
            $table->dropColumn('polizas_validadas');
        });
    }
};
