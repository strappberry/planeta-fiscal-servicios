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
            $table->after('formula', function (Blueprint $table) {
                $table->foreignId('balanza_comprobacion_id')->nullable()->constrained()->nullOnDelete();
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
            $table->dropForeign(['balanza_comprobacion_id']);
            $table->dropColumn('balanza_comprobacion_id');
        });
    }
};
