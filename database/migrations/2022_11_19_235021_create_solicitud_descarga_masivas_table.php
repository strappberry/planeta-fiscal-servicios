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
        Schema::create('solicitud_descarga_masivas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_inicial');
            $table->dateTime('fecha_final');
            $table->string('solicitud_id');
            $table->string('tipo_solicitud');
            $table->string('estado_solicitud')->nullable();
            $table->string('estado_sat')->nullable();
            $table->string('tipo_descarga_facturas');
            $table->mediumText('paquetes')->nullable();
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
        Schema::dropIfExists('solicitud_descarga_masivas');
    }
};
