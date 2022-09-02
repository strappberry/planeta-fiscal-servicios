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
        Schema::table('numero_cuentas', function (Blueprint $table) {
            $table->dropColumn('poliza');
            $table->dropColumn('cargo');

            $table->boolean('automatico')->default(false);
            $table->string('columna_calculo');
            $table->text('formula')->nullable();
            $table->string('cliente_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('numero_cuentas', function (Blueprint $table) {
            $table->boolean('poliza')->default(false);
            $table->boolean('cargo')->default(true);

            $table->dropColumn('automatico');
            $table->dropColumn('columna_calculo');
            $table->dropColumn('formula');
            $table->dropColumn('cliente_id');
        });
    }
};
