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
        Schema::table('numero_cuentas', function (Blueprint $table) {
            $table->dropColumn('ventas');
            $table->dropColumn('gastos');
            $table->string('tipo_cuenta');
            $table->string('subtipo')->nullable();
            $table->boolean('poliza')->default(false);
            $table->boolean('cargo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('numero_cuentas', function (Blueprint $table) {
            $table->boolean('ventas')->default(false);
            $table->boolean('gastos')->default(false);
            $table->dropColumn('tipo_cuenta');
            $table->dropColumn('subtipo');
            $table->dropColumn('poliza');
            $table->dropColumn('cargo');
        });
    }
};
