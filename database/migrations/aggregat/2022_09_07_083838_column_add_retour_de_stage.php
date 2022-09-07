<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColumnAddRetourDeStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retour_de_stages', function (Blueprint $table) {
            $table->string('categorie_rs')->after('date_reprise_service')->nullable();
            $table->string('annee_rs')->after('categorie_rs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retour_de_stages', function (Blueprint $table) {
            $table->dropColumn('categorie_rs');
            $table->dropColumn('annee_rs');
        });
    }
}
