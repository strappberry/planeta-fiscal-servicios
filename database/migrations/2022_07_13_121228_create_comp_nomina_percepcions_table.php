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
        Schema::create('comp_nomina_percepcions', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_percepcion')->nullable();
            $table->string('clave')->nullable();
            $table->string('concepto')->nullable();
            $table->decimal('importe_gravado', 16, 6)->default(0);
            $table->decimal('importe_exento', 16, 6)->default(0);
            $table->foreignId('comp_nomina_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('comp_nomina_percepcions');
    }
};
