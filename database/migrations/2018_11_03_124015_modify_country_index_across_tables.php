<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCountryIndexAcrossTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function(Blueprint $table) {
            $table->string('country', 2)->change();
        });

        Schema::table('users', function(Blueprint $table) {
            $table->string('country', 2)->change();
        });

        Schema::table('companies', function(Blueprint $table) {
            $table->string('country', 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function(Blueprint $table) {
            $table->string('country', 255)->change();
        });

        Schema::table('users', function(Blueprint $table) {
            $table->string('country', 255)->change();
        });

        Schema::table('companies', function(Blueprint $table) {
            $table->string('country', 255)->change();
        });
    }
}
