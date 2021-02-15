<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('detail')->nullable();
            $table->integer('floor_num');
            $table->string('address');
			$table->float('lat');
            $table->float('lng');
            // $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();

            // $table->foreign('company_id')
            //     ->references('id')
            //     ->on('companies')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
