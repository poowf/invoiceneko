<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('time_interval');
            $table->string('time_period');
            $table->string('until_type');
            $table->string('until_meta')->nullable();
            $table->string('rule');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');
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
        Schema::dropIfExists('invoice_events');
    }
}
