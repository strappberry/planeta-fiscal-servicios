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
        Schema::create('determinacion_impuestos', function (Blueprint $table) {
            $table->id();
            $table->date('mes_trabajo');
            $table->decimal('ingresos_acumulados', 16, 2)->default(0);
            $table->decimal('deducciones_acumuladas', 16, 2)->default(0);
            $table->decimal('pp_pagados', 16, 2)->default(0);
            $table->decimal('isr_actividad', 16, 2)->default(0);
            $table->longText('determinacion');
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('determinacion_impuestos');
    }
};
