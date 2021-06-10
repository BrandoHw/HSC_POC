<?php

use App\Color;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colors_table', function (Blueprint $table) {
            $table->id('color_id');
            $table->string('color_code');
            $table->string('color_name');
        });

        $colors = [
            'black' => 'Black',
            'grey' => 'Grey',
            'saddleBrown' => 'Brown',
            'peru' => 'Peru',
            'indianRed' => 'Red',
            'darkOrange' => 'Orange',
            'gold' => 'Yellow',
            'olive' => 'Olive',
            'darkGreen' => 'Forest',
            'green' => 'Green',
            'limeGreen' => 'Lime',
            'lightSeaGreen' => 'Sea Green',
            'midnightBlue' => 'Navy',
            'blue' => 'Blue',
            'dodgerBlue' => 'Aqua',
            'purple' => 'Purple',
            'MediumVioletRed' => 'Violet',
            'mediumOrchid' => 'Orchid',
            'paleVioletRed' => 'Pink',
            'darkSalmon' => 'Salmon',
        ];

        foreach($colors as $key => $color){
            Color::firstOrCreate([
                'color_code' => $key,
                'color_name' => $color
            ]);
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colors_table');
    }
}
