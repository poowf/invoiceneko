<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('contactemail');
            $table->string('contactphone');
            $table->timestamps();
            $table->softDeletes();
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
