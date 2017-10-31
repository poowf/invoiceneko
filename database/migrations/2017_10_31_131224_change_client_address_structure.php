<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeClientAddressStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function(Blueprint $table) {
            $table->renameColumn('address', 'block');
            $table->renameColumn('address_second', 'street');
            $table->renameColumn('zipcode', 'postalcode');
            $table->string('unitnumber')->nullable()->after('address_second');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function(Blueprint $table) {
            $table->renameColumn('block', 'address');
            $table->renameColumn('street', 'address_second');
            $table->renameColumn('postalcode', 'zipcode');
            $table->dropColumn('unitnumber');
        });
    }
}
