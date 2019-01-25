<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->boolean('archived')->default(null)->change();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->boolean('archived')->default(null)->change();
        });
    }
}
