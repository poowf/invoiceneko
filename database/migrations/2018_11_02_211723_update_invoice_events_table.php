<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInvoiceEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_events', function (Blueprint $table) {
            $table->string('until_meta')->nullable()->after('id');
            $table->string('until_type')->after('id');
            $table->string('time_period')->after('id');
            $table->integer('time_interval')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_events', function (Blueprint $table) {
            $table->dropColumn('until_meta');
            $table->dropColumn('until_type');
            $table->dropColumn('time_period');
            $table->dropColumn('time_interval');
        });
    }
}
