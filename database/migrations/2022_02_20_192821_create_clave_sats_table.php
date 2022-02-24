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
        Schema::create('clave_sats', function (Blueprint $table) {
            $table->id();
            $table->string('cer');
            $table->string('key');
            $table->text('password');
            $table->string('numero_certificado')->nullable();
            $table->dateTime('caducidad');
            $table->boolean('activo')->default(false);
            $table->enum('tipo', ['fiel', 'csd']);
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
        Schema::dropIfExists('clave_sats');
    }
};
