<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RetourDeStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retour_de_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_agent')->unsigned()->nullable();
            $table->foreign('id_agent')->references('id')
                ->on('agent_formations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('numero_decision_rs')->nullable();
            $table->string('date_signature')->nullable();
            $table->string('date_fin_formation')->nullable();
            $table->string('date_reprise_service')->nullable();
            $table->string('incidence_bn')->nullable();
            $table->string('structure_rs')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retour_de_stages');
    }
}
