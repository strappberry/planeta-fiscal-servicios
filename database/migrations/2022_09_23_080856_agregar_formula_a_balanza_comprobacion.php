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
        Schema::table('balanza_comprobacions', function (Blueprint $table) {
            $table->after('tipo', function (Blueprint $table) {
                $table->text('formula')->nullable();
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
        Schema::table('balanza_comprobacions', function (Blueprint $table) {
            $table->dropColumn('formula');
        });
    }
};
