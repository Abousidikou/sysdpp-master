<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColumnAddMiseFormation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mise_en_stages', function (Blueprint $table) {
            $table->string('spec_option')->after('filiere')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mise_en_stages', function (Blueprint $table) {
            $table->dropColumn('spec_option');
        });
    }
}
