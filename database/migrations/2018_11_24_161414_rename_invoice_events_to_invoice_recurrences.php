<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameInvoiceEventsToInvoiceRecurrences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_events', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['invoice_event_id']);
        });

        Schema::table('invoice_templates', function (Blueprint $table) {
            $table->dropForeign(['invoice_event_id']);
        });

        Schema::rename('invoice_events', 'invoice_recurrences');

        Schema::table('invoice_recurrences', function (Blueprint $table) {
            $table
                ->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->renameColumn('invoice_event_id', 'invoice_recurrence_id');
            $table
                ->foreign('invoice_recurrence_id')
                ->references('id')
                ->on('invoice_recurrences')
                ->onDelete('cascade');
        });

        Schema::table('invoice_templates', function (Blueprint $table) {
            $table->renameColumn('invoice_event_id', 'invoice_recurrence_id');
            $table
                ->foreign('invoice_recurrence_id')
                ->references('id')
                ->on('invoice_recurrences')
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
        Schema::table('invoice_recurrences', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['invoice_recurrence_id']);
            $table->renameColumn('invoice_recurrence_id', 'invoice_event_id');
        });

        Schema::table('invoice_templates', function (Blueprint $table) {
            $table->dropForeign(['invoice_recurrence_id']);
            $table->renameColumn('invoice_recurrence_id', 'invoice_event_id');
        });

        Schema::rename('invoice_recurrences', 'invoice_events');

        Schema::table('invoice_events', function (Blueprint $table) {
            $table
                ->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table
                ->foreign('invoice_event_id')
                ->references('id')
                ->on('invoice_events')
                ->onDelete('cascade');
        });

        Schema::table('invoice_templates', function (Blueprint $table) {
            $table
                ->foreign('invoice_event_id')
                ->references('id')
                ->on('invoice_events')
                ->onDelete('cascade');
        });
    }
}
