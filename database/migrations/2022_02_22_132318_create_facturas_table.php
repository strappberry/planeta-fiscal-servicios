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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('rfc_emisor');
            $table->string('nombre_emisor');
            $table->string('rfc_receptor');
            $table->string('nombre_receptor');
            $table->dateTime('fecha_emision');
            $table->dateTime('fecha_certificacion');
            $table->string('pac_certifico')->nullable();
            $table->string('efecto_comprobante')->nullable();
            $table->string('estatus_cancelacion')->nullable();
            $table->string('estado_comprobante')->nullable();
            $table->string('estatus_proceso_cancelacion')->nullable();
            $table->dateTime('fecha_proceso_cancelacion')->nullable();
            $table->decimal('descuento', 16, 6)->nullable();
            $table->decimal('subtotal', 16, 6)->nullable();
            $table->decimal('total', 16, 6)->nullable();
            $table->string('serie')->nullable();
            $table->string('folio')->nullable();
            $table->string('tipo_comprobante')->nullable();
            $table->boolean('xml_procesado')->default(false);
            $table->text('complementos')->nullable();
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
        Schema::dropIfExists('facturas');
    }
};
