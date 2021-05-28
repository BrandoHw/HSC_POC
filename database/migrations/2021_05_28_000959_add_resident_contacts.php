<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResidentContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('residents_table', function($table) {
            $table->string('contact_name')->nullable();
            $table->string('contact_phone_num_1')->nullable();
            $table->string('contact_phone_num_2')->nullable();
            $table->text('contact_address')->nullable();
            $table->tinyText('contact_relationship')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('residents_table', function($table) {
            $table->dropColumn(['contact_name', 
            'contact_phone_num_1', 
            'contact_phone_num_2',
            'contact_address',
            'contact_relationship']);
        });
    }
}
