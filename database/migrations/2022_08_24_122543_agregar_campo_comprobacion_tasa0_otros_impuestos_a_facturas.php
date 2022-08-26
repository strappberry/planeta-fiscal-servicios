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
        Schema::table('facturas', function (Blueprint $table) {
            $table->decimal('otros_impuestos', 16, 6)->default(0);
            $table->decimal('tasa_cero', 16, 6)->default(0);
            $table->decimal('monto_comprobacion', 16, 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn('otros_impuestos');
            $table->dropColumn('tasa_cero');
            $table->dropColumn('monto_comprobacion');
        });
    }
};
