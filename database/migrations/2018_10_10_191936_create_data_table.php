<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('data', function (Blueprint $table) {
            $table->increments('id');

            $table->text('observation')->nullable();
            $table->string('value')->nullable();
            $table->datetime('date_start')->nullable();
            $table->datetime('date_end')->nullable();
            $table->string('year')->nullable();
            $table->string('periodicity')->nullable();

            // $table->integer('id_level')->unsigned()->nullable();
            // $table->foreign('id_level')->references('id')
            //     ->on('levelofdisintegration')
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');

            $table->integer('id_indicator')->unsigned()->nullable();
            $table->foreign('id_indicator')->references('id')
                ->on('indicators')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('id_user')->unsigned()->nullable();
            $table->foreign('id_user')->references('id')
                ->on('users')
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
        Schema::dropIfExists('data');
    }
}
