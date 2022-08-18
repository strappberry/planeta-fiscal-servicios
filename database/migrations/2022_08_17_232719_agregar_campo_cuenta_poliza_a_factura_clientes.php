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
            $table->foreignId('cuenta_poliza')->nullable()->constrained('numero_cuentas')->nullOnDelete();
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
            $table->dropForeign(['cuenta_poliza']);
            $table->dropColumn('cuenta_poliza');
        });
    }
};
