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
        Schema::create('determinacion_campo_editables', function (Blueprint $table) {
            $table->id();
            $table->date('mes_trabajo');
            $table->string('clave');
            $table->string('valor')->default('')->nullable();
            $table->string('regimen');
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
        Schema::dropIfExists('determinacion_campo_editables');
    }
};
