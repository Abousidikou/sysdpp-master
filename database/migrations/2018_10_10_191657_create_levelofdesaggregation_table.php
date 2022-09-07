<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelofdesaggregationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('levelofdisintegration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('wording');

            $table->integer('id_type')->unsigned()->nullable();
            $table->foreign('id_type')->references('id')
                ->on('type')
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
        Schema::dropIfExists('levelofdesaggregation');
    }
}
