<?php

namespace Database\Seeders;

use App\Alert;
use App\Policy;
use App\Reader;
use App\Tag;
use Illuminate\Database\Seeder;

class InsertAlerts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 4; $i++){
            for ($j = 1; $j <= 4; $j++){
                $this->create_alert($j);
                if($j == 1){
                    $this->create_another_alert($j);
                }
            }
            sleep(10);
        }
    }

    public function create_alert(int $i){
        $alert = new Alert;
                
        $tag = Tag::find($i);
        $alert->tag()->associate($tag);

        $policy = Policy::find($i + 22);
        $alert->policy()->associate($policy);

        $reader = Reader::find($i);
        $alert->reader()->associate($reader);

        $alert->save();
    }

    public function create_another_alert(int $i){
        $alert = new Alert;
                
        $tag = Tag::find($i);
        $alert->tag()->associate($tag);

        $policy = Policy::find($i + 23);
        $alert->policy()->associate($policy);

        $reader = Reader::find($i);
        $alert->reader()->associate($reader);

        $alert->save();
    }
}
