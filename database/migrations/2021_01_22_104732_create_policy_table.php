<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('namespace');
            $table->boolean('status');
            $table->boolean('isAllDevices');
            $table->timestamps();
        });

        Schema::create('policy_devices', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('policy_id');
            $table->foreignId('reader_id')->nullable();
            $table->foreignId('tag_id')->nullable();
            $table->timestamps();

            $table->foreign('policy_id')
            ->references('id')
            ->on('policies')
            ->onDelete('cascade');

            $table->foreign('reader_id')
            ->references('id')
            ->on('readers')
            ->onDelete('cascade');

            $table->foreign('tag_id')
            ->references('id')
            ->on('tags')
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
        Schema::dropIfExists('policy_devices');
        Schema::dropIfExists('policies');
    }
}
