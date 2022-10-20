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
        Schema::create('tabla_tarifas', function (Blueprint $table) {
            $table->id();
            $table->string('segmento');
            $table->integer('anio');
            $table->string('clave_tabla');
            $table->decimal('limite_inferior', 16, 2)->default(0);
            $table->decimal('limite_superior', 16, 2)->default(0);
            $table->decimal('cuota_fija', 16, 2)->default(0);
            $table->decimal('porcentaje_excedente',16, 6)->default(0);
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
        Schema::dropIfExists('tabla_tarifas');
    }
};
