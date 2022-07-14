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
        Schema::create('comp_pagos', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->decimal('monto_total_pagos', 16, 6)->nullable();
            $table->decimal('total_traslados_base_iva_16', 16, 6)->nullable();
            $table->decimal('total_traslados_impuesto_iva_16', 16, 6)->nullable();
            $table->decimal('total_retenciones_isr', 16, 6)->nullable();
            $table->foreignId('factura_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comp_pagos');
    }
};
