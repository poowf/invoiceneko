<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipientablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipientables', function (Blueprint $table) {
            $table->unsignedInteger('recipient_id');
            $table->unsignedInteger('recipientable_id');
            $table->string('recipientable_type');

            $table->foreign('recipient_id')->references('id')->on('recipients')->onDelete('cascade');
            $table->unique(['recipient_id', 'recipientable_id', 'recipientable_type'], 'recipientable_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipientables');
    }
}
