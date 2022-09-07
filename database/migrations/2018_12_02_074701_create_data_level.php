<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_levels', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('id_level')->unsigned()->nullable();
            $table->foreign('id_level')->references('id')
                ->on('levelofdisintegration')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->integer('id_data')->unsigned()->nullable();
            $table->foreign('id_data')->references('id')
                ->on('data')
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
        Schema::dropIfExists('data_levels');
    }
}
