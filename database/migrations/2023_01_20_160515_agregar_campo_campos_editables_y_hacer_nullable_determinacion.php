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
        Schema::table('determinacion_impuestos', function (Blueprint $table) {
            $table->longText('determinacion')->nullable()->change();
            $table->longText('campos_editables')->nullable()->after('determinacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('determinacion_impuestos', function (Blueprint $table) {
            $table->longText('determinacion')->nullable(false)->change();
            $table->dropColumn('campos_editables');
        });
    }
};
