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
        Schema::create('comp_nomina_deduccions', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_deduccion')->nullable();
            $table->string('clave')->nullable();
            $table->string('concepto')->nullable();
            $table->decimal('importe', 16, 6)->default(0);
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
        Schema::dropIfExists('comp_nomina_deduccions');
    }
};
