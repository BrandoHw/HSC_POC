<?php

namespace Database\Seeders;

use App\Resident;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PopulateResidentDob extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $residents = Resident::get();

        $faker = Faker::create();
        foreach($residents as $resident){
            $resident['resident_dob'] = $faker->date($format = 'Y-m-d', $max = '1981-12-31', $min = '1926-01-01');
            $resident['resident_gender'] = $faker->randomElement($array = array('F','M'));
            $resident->save();
        }
    }
}
