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
        Schema::create('poliza_nominas', function (Blueprint $table) {
            $table->id();
            $table->date('mes_trabajo');
            $table->string('segmento');
            $table->string('clave');
            $table->string('descripcion');
            $table->string('cuenta');
            $table->string('columna');
            $table->decimal('cargo', 16, 2)->default(0);
            $table->decimal('abono', 16, 2)->default(0);
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
        Schema::dropIfExists('poliza_nominas');
    }
};
