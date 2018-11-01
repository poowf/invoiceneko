<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultValueForArchivedToQuotesAndInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('archived')->default(0)->change();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->boolean('archived')->default(0)->change();
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
            $table->boolean('archived')->default(NULL)->change();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->boolean('archived')->default(NULL)->change();
        });
    }
}
