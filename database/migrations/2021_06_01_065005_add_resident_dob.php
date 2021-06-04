<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResidentDob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('residents_table', function($table) {
            $table->date('resident_dob')->nullable();
            $table->renameColumn('gender', 'resident_gender');
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
            $table->dropColumn('resident_dob');
            $table->renameColumn('resident_gender', 'gender');
        });
    }
}
