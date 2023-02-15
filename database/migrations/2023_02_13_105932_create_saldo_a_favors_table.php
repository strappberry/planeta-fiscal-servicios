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
        Schema::create('saldo_a_favors', function (Blueprint $table) {
            $table->id();
            $table->string('numero_operacion');
            $table->string('origen');
            $table->string('tipo');
            $table->date('fecha')->nullable();
            $table->date('fecha_presentacion')->nullable();
            $table->decimal('saldo_original', 16, 2)->default(0);
            $table->decimal('suma_comp_acred_ejer_ant', 16, 2)->default(0);
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
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
        Schema::dropIfExists('saldo_a_favors');
    }
};
