<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientDataFieldAndMakeClientIdNullableToQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table
                ->json('client_data')
                ->nullable()
                ->after('share_token');
            $table
                ->integer('client_id')
                ->unsigned()
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('client_data');
            $table
                ->integer('client_id')
                ->unsigned()
                ->nullable(false)
                ->change();
        });
    }
}
