<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceEventsForeignKeyOnInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table
                ->integer('invoice_event_id')
                ->nullable()
                ->unsigned()
                ->after('company_id');
            $table
                ->foreign('invoice_event_id')
                ->references('id')
                ->on('invoice_events')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['invoice_event_id']);
            $table->dropColumn('invoice_event_id');
        });
    }
}
