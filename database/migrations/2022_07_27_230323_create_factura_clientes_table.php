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
        Schema::create('factura_clientes', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_emision')->nullable();
            $table->boolean('considerado')->default(false);
            $table->string('cliente_id');
            $table->foreignId('factura_id')->constrained()->cascadeOnDelete();
            $table->foreignId('numero_cuenta_id')->nullable()->constrained()->nullOnDelete();
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
        Schema::dropIfExists('factura_clientes');
    }
};
