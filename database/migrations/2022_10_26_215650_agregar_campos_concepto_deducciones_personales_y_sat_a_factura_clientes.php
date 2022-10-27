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
        Schema::table('factura_clientes', function (Blueprint $table) {
            $table->foreignId('concepto_deduccion_personal_id')
                ->nullable()->constrained()->nullOnDelete();
            $table->foreignId('concepto_sat_id')
                ->nullable()->constrained()->nullOnDelete();
            $table->boolean('deducible')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factura_clientes', function (Blueprint $table) {
            $table->dropForeign(['concepto_deduccion_personal_id']);
            $table->dropForeign(['concepto_sat_id']);
            $table->dropColumn(['concepto_deduccion_personal_id', 'concepto_sat_id', 'deducible']);
        });
    }
};
