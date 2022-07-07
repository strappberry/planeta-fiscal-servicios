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
            $table->string('forma_pago')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->string('moneda')->nullable();
            $table->decimal('tipo_cambio', 12, 2)->default(1);
            $table->decimal('retencion_isr', 12, 2)->default(0);
            $table->decimal('retencion_iva', 12, 2)->default(0);
            $table->decimal('retencion_ieps', 12, 2)->default(0);
            $table->decimal('traslado_iva', 12, 2)->default(0);
            $table->decimal('traslado_ieps', 12, 2)->default(0);
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
            $table->dropColumn('forma_pago');
            $table->dropColumn('metodo_pago');
            $table->dropColumn('moneda');
            $table->dropColumn('retencion_isr');
            $table->dropColumn('retencion_iva');
            $table->dropColumn('retencion_ieps');
            $table->dropColumn('traslado_iva');
            $table->dropColumn('traslado_ieps');
        });
    }
};
