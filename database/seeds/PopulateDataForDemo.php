<?php

use App\Reader;
use App\Tag;
use Illuminate\Database\Seeder;

class PopulateDataForDemo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Reader creation */
        
        /* For demo purpose*/
        // AC233FC0806A
        // AC233FC08070
        // AC233FC08080
        // AC233FC08064
        // AC233FC08069
        // AC233FC0806D
        // AC233FC08067
        // AC233FC08028

        $readers[0] = Reader::create([
            'serial' => 'R0000001',
            'mac_addr' => 'AC:23:3F:C0:80:6A'                   
        ]);
        // $this->command->info('Reader 1 created!');

        $readers[1] = Reader::create([
            'serial' => 'R0000002',
            'mac_addr' => 'AC:23:3F:C0:80:70'                    
        ]);
        // $this->command->info('Reader 2 created!');
        
        $readers[2] = Reader::create([
            'serial' => 'R0000003',
            'mac_addr' => 'AC:23:3F:C0:80:80'                    
        ]);
        // $this->command->info('Reader 3 created!');

        $readers[3] = Reader::create([
            'serial' => 'R0000004',
            'mac_addr' => 'AC:23:3F:C0:80:64'                    
        ]);
        // $this->command->info('Reader 4 created!');

        $readers[4] = Reader::create([
            'serial' => 'R0000005',
            'mac_addr' => 'AC:23:3F:C0:80:69'                    
        ]);
        // $this->command->info('Reader 5 created!');

        $readers[5] = Reader::create([
            'serial' => 'R0000006',
            'mac_addr' => 'AC:23:3F:C0:80:6D'                    
        ]);
        // $this->command->info('Reader 6 created!');

        $readers[6] = Reader::create([
            'serial' => 'R0000007',
            'mac_addr' => 'AC:23:3F:C0:80:67'                    
        ]);
        // $this->command->info('Reader 7 created!');

        $readers[7] = Reader::create([
            'serial' => 'R0000008',
            'mac_addr' => 'AC:23:3F:C0:80:28'                    
        ]);
        // $this->command->info('Reader 8 created!');

        /* Tag Creation */

        /* For demo purpose*/
        // AC233FA25668
        // AC233FA2566A
        // AC233FA25669
        // AC233FA25667
        // AC233FA25666

        $tags[0] = Tag::create([
            'serial' => 'T0000001',
            'mac_addr' => 'AC:23:3F:A2:56:68',           
        ]);
        // $this->command->info('Tag 1 created!');

        $tags[1] = Tag::create([
            'serial' => 'T0000002',
            'mac_addr' => 'AC:23:3F:A2:56:6A'            
        ]);
        // $this->command->info('Tag 2 created!');

        $tags[2] = Tag::create([
            'serial' => 'T0000003',
            'mac_addr' => 'AC:23:3F:A2:56:69'            
        ]);
        // $this->command->info('Tag 3 created!');

        $tags[3] = Tag::create([
            'serial' => 'T0000004',
            'mac_addr' => 'AC:23:3F:A2:56:67'           
        ]);
        // $this->command->info('Tag 4 created!');

        $tags[4] = Tag::create([
            'serial' => 'T0000005',
            'mac_addr' => 'AC:23:3F:A2:56:66'           
        ]);
        // $this->command->info('Tag 5 created!');
    }
}
