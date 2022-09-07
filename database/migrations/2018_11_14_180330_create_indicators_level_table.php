<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorsLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators_level', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_level')->unsigned()->nullable();
            $table->foreign('id_level')->references('id')
                ->on('levelofdisintegration')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('id_indicator')->unsigned()->nullable();
            $table->foreign('id_indicator')->references('id')
                ->on('indicators')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicators_level');
    }
}
