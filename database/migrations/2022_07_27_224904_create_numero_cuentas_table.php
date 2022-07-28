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
        Schema::create('numero_cuentas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cuenta');
            $table->string('descripcion')->nullable();
            $table->boolean('ventas')->default(false);
            $table->boolean('gastos')->default(false);
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
        Schema::dropIfExists('numero_cuentas');
    }
};
