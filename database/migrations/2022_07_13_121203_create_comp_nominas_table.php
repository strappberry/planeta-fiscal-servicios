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
        Schema::create('comp_nominas', function (Blueprint $table) {
            $table->id();
            $table->string('version')->nullable();
            $table->string('tipo_nomina')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->date('fecha_inicial')->nullable();
            $table->date('fecha_final')->nullable();
            $table->float('num_dias_pagados', 10, 4)->default(0);
            $table->decimal('total_percepciones', 16, 6)->default(0);
            $table->decimal('total_deducciones', 16, 6)->default(0);
            $table->decimal('total_otros_pagos', 16, 6)->default(0);
            $table->decimal('percepciones_total_sueldos', 16, 6)->default(0);
            $table->decimal('percepciones_total_gravado', 16, 6)->default(0);
            $table->decimal('percepciones_total_exento', 16, 6)->default(0);
            $table->decimal('deducciones_total_otras_deducciones', 16, 6)->default(0);
            $table->decimal('deducciones_total_imp_retenidos', 16, 6)->default(0);
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
        Schema::dropIfExists('comp_nominas');
    }
};
