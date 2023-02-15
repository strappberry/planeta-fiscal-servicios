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
        Schema::create('saldo_favor_acreditamientos', function (Blueprint $table) {
            $table->id();
            $table->decimal('remanente_historico', 16, 2)->default(0);
            $table->decimal('importe', 16, 2);
            $table->date('periodo')->nullable();
            $table->string('concepto')->nullable();
            $table->foreignId('saldo_a_favor_id')->constrained('saldo_a_favors')->cascadeOnDelete();
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
        Schema::dropIfExists('saldo_favor_acreditamientos');
    }
};
