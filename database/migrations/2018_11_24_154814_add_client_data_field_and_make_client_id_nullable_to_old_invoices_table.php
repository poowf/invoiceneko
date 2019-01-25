<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientDataFieldAndMakeClientIdNullableToOldInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('old_invoices', function (Blueprint $table) {
            $table->json('client_data')->nullable()->after('status');
            $table->integer('client_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('old_invoices', function (Blueprint $table) {
            $table->dropColumn('client_data');
            $table->integer('client_id')->unsigned()->nullable(false)->change();
        });
    }
}
