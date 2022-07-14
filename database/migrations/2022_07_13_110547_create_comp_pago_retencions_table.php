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
        Schema::create('comp_pago_retencions', function (Blueprint $table) {
            $table->id();
            $table->string('impuesto')->nullable();
            $table->decimal('importe', 16, 6)->default(0);
            $table->foreignId('comp_pago_pago_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('comp_pago_retencions');
    }
};
