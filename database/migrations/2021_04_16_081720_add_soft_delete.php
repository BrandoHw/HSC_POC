<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alerts_table', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('buildings_table', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('floors_table', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('gateway_zones', function($table) {
            $table->softDeletes();
        });

        Schema::table('locations_master_table', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('map_files', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('rules_table', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('gateways_table', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('residents_table', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('beacons_table', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('users_table', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('users_right_table', function($table) {
            $table->softDeletes();
        });
        
        Schema::table('users_type_table', function($table) {
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alerts_table', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('buildings_table', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('floors_table', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('gateway_zones', function($table) {
            $table->dropSoftDeletes();
        });

        Schema::table('locations_master_table', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('map_files', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('rules_table', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('gateways_table', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('residents_table', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('beacons_table', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('users_table', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('users_right_table', function($table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('users_type_table', function($table) {
            $table->dropSoftDeletes();
        });
    }
}
