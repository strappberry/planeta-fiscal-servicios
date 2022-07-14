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
        Schema::create('comp_pago_docto_relacionado_traslados', function (Blueprint $table) {
            $table->id();
            $table->string('impuesto')->nullable();
            $table->string('tipo_factor')->nullable();
            $table->decimal('base', 16, 6)->default(0);
            $table->decimal('importe', 16, 6)->default(0);
            $table->decimal('tasa_cuota', 16, 6)->default(0);
            $table->foreignId('doc_rel_id')->constrained('comp_pago_docto_relacionados')->cascadeOnDelete();
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
        Schema::dropIfExists('comp_pago_docto_relacionado_traslados');
    }
};
