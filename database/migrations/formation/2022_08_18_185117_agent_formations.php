<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgentFormations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_formations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('matricule')->unique();
            $table->string('nom_prenoms');
            $table->string('diplome_base');
            $table->string('sexe');
            $table->string('status')->nullable();
            $table->string('corps')->nullable();
            $table->string('categorie')->nullable();
            $table->string('structure')->nullable(); 

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_formations');
    }
}
