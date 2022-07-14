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
        Schema::create('comp_pago_docto_relacionados', function (Blueprint $table) {
            $table->id();
            $table->decimal('equivalencia', 16, 6)->default(0);
            $table->string('folio')->nullable();
            $table->string('serie')->nullable();
            $table->string('uuid')->nullable();
            $table->string('moneda')->nullable();
            $table->string('objeto_impuesto')->nullable();
            $table->string('numero_parcialidad')->nullable();
            $table->decimal('importe_pagado', 16, 6)->default(0);
            $table->decimal('importe_saldo_anterior', 16, 6)->default(0);
            $table->decimal('importe_saldo_insoluto', 16, 6)->default(0);
            $table->foreignId('comp_pago_pago_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('comp_pago_docto_relacionados');
    }
};
