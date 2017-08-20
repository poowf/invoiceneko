<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('companyname');
            $table->string('address');
            $table->string('nickname')->nullable();
            $table->string('crn')->nullable();
            $table->string('photo')->nullable();
            $table->string('contactname');
            $table->string('contactgender')->nullable();
            $table->string('contactemail')->unique();
            $table->string('contactphone')->unique();
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
        Schema::dropIfExists('clients');
    }
}
