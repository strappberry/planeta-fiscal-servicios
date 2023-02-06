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
            $table->dropColumn('coeficiente_utilidad');
            $table->dropColumn('campos_editables');
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
            $table->float('coeficiente_utilidad')->default(0)->nullable();
            $table->mediumText('campos_editables')->default('[]')->nullable();
        });
    }
};
