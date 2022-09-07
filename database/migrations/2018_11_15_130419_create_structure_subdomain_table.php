<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStructureSubdomainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structures_subdomains', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('id_subdomain')->unsigned()->nullable();
            $table->foreign('id_subdomain')->references('id')
                    ->on('sub_domains')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            
            $table->integer('id_structure')->unsigned()->nullable();
            $table->foreign('id_structure')->references('id')
                    ->on('structures')
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
        Schema::dropIfExists('structures_subdomains');
    }
}
