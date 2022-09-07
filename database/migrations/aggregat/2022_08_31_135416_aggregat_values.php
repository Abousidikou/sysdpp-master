<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AggregatValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aggregat_values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash_value')->nullable();
            $table->string('annee')->nullable();
            $table->string('value_statistic')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aggregat_values');
    }
}
