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
        Schema::table('poliza_nominas', function (Blueprint $table) {
            $table->decimal('deducible_isr', 16, 2)->default(0)->after('abono');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('poliza_nominas', function (Blueprint $table) {
            $table->dropColumn('deducible_isr');
        });
    }
};
