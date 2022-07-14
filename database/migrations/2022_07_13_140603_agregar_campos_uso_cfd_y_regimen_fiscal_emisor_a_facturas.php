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
        Schema::table('facturas', function (Blueprint $table) {
            $table->string('regimen_fiscal_emisor')->nullable()->after('nombre_emisor');
            $table->string('uso_cfdi_receptor')->nullable()->after('nombre_receptor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn('uso_cfdi_receptor');
            $table->dropColumn('regimen_fiscal_emisor');
        });
    }
};
