<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floors_table', function (Blueprint $table) {
            $table->id('floor_id');
            $table->integer('id');
            $table->integer('number');
            $table->string('alias')->nullable();
            $table->unsignedBigInteger('building_id')->nullable();
            $table->timestamps();

            $table->foreign('building_id')
                ->references('building_id')
                ->on('buildings')
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
        Schema::dropIfExists('floors');
    }
}
