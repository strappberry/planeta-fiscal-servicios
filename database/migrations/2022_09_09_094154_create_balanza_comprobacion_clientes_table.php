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
        Schema::create('balanza_comprobacion_clientes', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->decimal('saldo_inicial', 16, 2)->default(0);
            $table->decimal('saldo_final', 16, 2)->default(0);
            $table->foreignId('balanza_comprobacion_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('balanza_comprobacion_clientes');
    }
};
