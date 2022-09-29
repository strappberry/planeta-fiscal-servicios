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
        Schema::table('balanza_comprobacion_clientes', function (Blueprint $table) {
            $table->after('saldo_inicial', function (Blueprint $table) {
                $table->float('cargo')->default(0);
                $table->float('abono')->default(0);
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
        Schema::table('balanza_comprobacion_clientes', function (Blueprint $table) {
            $table->dropColumn('cargo');
            $table->dropColumn('abono');
        });
    }
};
