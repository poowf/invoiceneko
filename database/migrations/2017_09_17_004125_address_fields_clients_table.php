<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddressFieldsClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table
                ->string('country')
                ->nullable()
                ->after('address');
            $table
                ->string('zipcode')
                ->nullable()
                ->after('address');
            $table
                ->string('address_second')
                ->nullable()
                ->after('address');
            $table
                ->string('phone')
                ->nullable()
                ->after('companyname');
            $table
                ->string('website')
                ->nullable()
                ->after('crn');
            $table
                ->string('contactsalutation')
                ->nullable()
                ->after('photo');
            $table
                ->string('contactlastname')
                ->nullable()
                ->after('contactsalutation');
            $table
                ->string('contactfirstname')
                ->nullable()
                ->after('contactsalutation');
            $table
                ->string('address')
                ->nullable()
                ->change();
            $table
                ->string('contactemail')
                ->nullable()
                ->change();
            $table
                ->string('contactphone')
                ->nullable()
                ->change();
            $table->dropColumn('contactname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('zipcode');
            $table->dropColumn('address_second');
            $table->dropColumn('phone');
            $table->dropColumn('website');
            $table->dropColumn('contactsalutation');
            $table->dropColumn('contactfirstname');
            $table->dropColumn('contactlastname');
            $table->string('contactname')->after('photo');
            $table
                ->string('address')
                ->nullable(false)
                ->change();
            $table
                ->string('contactemail')
                ->nullable(false)
                ->change();
            $table
                ->string('contactphone')
                ->nullable(false)
                ->change();
        });
    }
}
