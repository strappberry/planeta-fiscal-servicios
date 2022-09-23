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
        Schema::create('factura_cliente_numero_cuenta', function (Blueprint $table) {
            $table->foreignId('factura_cliente_id')->constrained()->cascadeOnDelete();
            $table->foreignId('numero_cuenta_id')->constrained()->cascadeOnDelete();
            $table->decimal('monto', 12, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factura_cliente_numero_cuenta');
    }
};
