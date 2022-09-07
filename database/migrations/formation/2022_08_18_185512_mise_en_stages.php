<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MiseEnStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mise_en_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_agent')->unsigned()->nullable();
            $table->foreign('id_agent')->references('id')
                ->on('agent_formations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('nature_stage')->nullable();
            $table->string('nature_bourse')->nullable();
            $table->string('numero_decision_ms')->nullable();
            $table->string('ecole_stage')->nullable();
            $table->string('niveau')->nullable();
            $table->string('filiere')->nullable();
            $table->string('date_signature')->nullable();
            $table->string('date_demarrage_stage')->nullable();
            $table->string('duree')->nullable();
            $table->string('annee_stage')->nullable();
            $table->integer('pays_stage_id')->unsigned()->nullable();
            $table->foreign('pays_stage_id')->references('id')
                ->on('countries')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('region_stage_id')->unsigned()->nullable();
            $table->foreign('region_stage_id')->references('id')
                ->on('states')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('ville_stage_id')->unsigned()->nullable();
            $table->foreign('ville_stage_id')->references('id')
                ->on('cities')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

            Schema::dropIfExists('mise_en_stages');
    }
}
