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
        Schema::table('saldo_a_favors', function (Blueprint $table) {
            $table->dropColumn('fecha');
            $table->after('tipo', function ($table) {
                $table->string('mes')->nullable();
                $table->string('anio', 4)->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saldo_a_favors', function (Blueprint $table) {
            $table->date('fecha')->nullable()->after('tipo');
            $table->dropColumn('mes');
            $table->dropColumn('anio');
        });
    }
};
