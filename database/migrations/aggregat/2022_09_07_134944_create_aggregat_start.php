<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAggregatStart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aggregat_start', function (Blueprint $table) {
            $table->increments('id');
            $table->string('start');
        });

        Schema::create('aggregat_inputs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('indic');
            $table->string('annee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aggregat_start');
        Schema::dropIfExists('aggregat_inputs');
    }
}
