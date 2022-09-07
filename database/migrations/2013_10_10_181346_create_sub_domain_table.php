<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubDomainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_domains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('wording')->nullable();

            $table->integer('id_domain')->unsigned()->nullable();
            $table->foreign('id_domain')->references('id')
                    ->on('domains')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
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
        Schema::dropIfExists('sub_domains');
    }
}
