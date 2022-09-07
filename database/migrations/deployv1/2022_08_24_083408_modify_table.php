<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_formations', function (Blueprint $table) {
            $table->string('plan_formation')->nullable();
        });
        Schema::table('mise_en_stages', function (Blueprint $table) {
            $table->dropColumn('nature_stage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_formations', function (Blueprint $table) {
            $table->dropColumn('plan_formation')->nullable();
        });
        Schema::table('mise_en_stages', function (Blueprint $table) {
            $table->string('nature_stage')->nullable();
        });
        
    }
}
