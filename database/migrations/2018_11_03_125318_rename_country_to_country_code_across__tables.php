<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCountryToCountryCodeAcrossTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function(Blueprint $table) {
            $table->renameColumn('country', 'country_code');
        });

        Schema::table('users', function(Blueprint $table) {
            $table->renameColumn('country', 'country_code');
        });

        Schema::table('companies', function(Blueprint $table) {
            $table->renameColumn('country', 'country_code');
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
            $table->renameColumn('country_code', 'country');
        });

        Schema::table('users', function(Blueprint $table) {
            $table->renameColumn('country_code', 'country');
        });

        Schema::table('companies', function(Blueprint $table) {
            $table->renameColumn('country_code', 'country');
        });
    }
}
