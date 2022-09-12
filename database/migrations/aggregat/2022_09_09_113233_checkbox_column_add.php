<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CheckboxColumnAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mise_en_stages', function (Blueprint $table) {
            $table->boolean('isBoursier')->after('id_agent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mise_en_stages', function (Blueprint $table) {
            $table->dropColumn('isBoursier');
        });
    }
}
