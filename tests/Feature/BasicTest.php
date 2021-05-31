<?php

namespace Tests\Feature;

use App\Attendance_KLIA;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Tag;
use App\Reader;
use App\TagData;
use Carbon\Carbon;

class BasicTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {


        $gateway_id_1 = Reader::where('mac_addr', '111111111111')->get()[0]->gateway_id;
        $gateway_id_2 = Reader::where('mac_addr', '222222222222')->get()[0]->gateway_id;

        $tag_data = TagData::create([
            'mac_addr' => 'AAAAAAAAAAAA',
            'gateway_mac' => '111111111111',
            'rssi' => -49,
            'created_at' => Carbon::now(),
        ]);
        sleep(1);
        $tag_data2 = TagData::create([
            'mac_addr' => 'AAAAAAAAAAAA',
            'gateway_mac' => '222222222222',
            'rssi' => -48,
            'created_at' => Carbon::now(),
        ]);
        sleep(1);
        $tag_data3 = TagData::create([
            'mac_addr' => 'AAAAAAAAAAAA',
            'gateway_mac' => '111111111111',
            'rssi' => -47,
            'created_at' => Carbon::now(),
        ]);
       

        $beacon = Tag::where('beacon_mac', 'AAAAAAAAAAAA')->get()[0];
        $attendance = Attendance_KLIA::where('tag_mac', 'AAAAAAAAAAAA')->where('gateway_id', $gateway_id_1)->orderBy('last_seen', 'desc')->get()[0];
        var_dump($gateway_id_1);
        $attendance2 = Attendance_KLIA::where('tag_mac', 'AAAAAAAAAAAA')->where('gateway_id', $gateway_id_2)->orderBy('last_seen', 'desc')->get()[0];
        $this->assertEquals($tag_data3->created_at->toDateTimeString(), $attendance->last_seen);
        $this->assertEquals($tag_data2->created_at->toDateTimeString(), $attendance2->last_seen);
        $this->assertEquals($tag_data3->created_at->toDateTimeString(), $beacon->updated_at);
    }

    public function assertSecondAttendanceCorrect(){
        $gateway_id_1 = Reader::where('mac_addr', '111111111111')->get()[0]->gateway_id;
        $gateway_id_2 = Reader::where('mac_addr', '222222222222')->get()[0]->gateway_id;

        $tag_data = TagData::create([
            'mac_addr' => 'AAAAAAAAAAAA',
            'gateway_mac' => '111111111111',
            'rssi' => -49,
            'created_at' => Carbon::now(),
        ]);
        sleep(1);
        $tag_data2 = TagData::create([
            'mac_addr' => 'AAAAAAAAAAAA',
            'gateway_mac' => '222222222222',
            'rssi' => -46,
            'created_at' => Carbon::now(),
        ]);
        sleep(1);
        $tag_data3 = TagData::create([
            'mac_addr' => 'AAAAAAAAAAAA',
            'gateway_mac' => '111111111111',
            'rssi' => -45,
            'created_at' => Carbon::now(),
        ]);
       

        $beacon = Tag::where('beacon_mac', 'AAAAAAAAAAAA')->get()[0];
        $attendance = Attendance_KLIA::where('tag_mac', 'AAAAAAAAAAAA')->where('gateway_id', $gateway_id_1)->orderBy('last_seen', 'desc')->get()[0];
        var_dump($gateway_id_1);
        $attendance2 = Attendance_KLIA::where('tag_mac', 'AAAAAAAAAAAA')->where('gateway_id', $gateway_id_2)->orderBy('last_seen', 'desc')->get()[0];
        $this->assertEquals($tag_data2->created_at->toDateTimeString(), $attendance2->last_seen);
        $this->assertEquals($tag_data3->created_at->toDateTimeString(), $beacon->updated_at);
    }

    public function assertLocationCorrect(){
        $gateway_id_1 = Reader::where('mac_addr', '111111111111')->get()[0]->gateway_id;
        $gateway_id_2 = Reader::where('mac_addr', '222222222222')->get()[0]->gateway_id;

        $tag_data = TagData::create([
            'mac_addr' => 'AAAAAAAAAAAA',
            'gateway_mac' => '111111111111',
            'rssi' => -49,
            'created_at' => Carbon::now(),
        ]);
        sleep(1);
        $tag_data2 = TagData::create([
            'mac_addr' => 'AAAAAAAAAAAA',
            'gateway_mac' => '222222222222',
            'rssi' => -48,
            'created_at' => Carbon::now(),
        ]);
        sleep(1);
        $tag_data3 = TagData::create([
            'mac_addr' => 'AAAAAAAAAAAA',
            'gateway_mac' => '111111111111',
            'rssi' => -44,
            'created_at' => Carbon::now(),
        ]);
       

        $beacon = Tag::where('beacon_mac', 'AAAAAAAAAAAA')->get()[0];
        $attendance = Attendance_KLIA::where('tag_mac', 'AAAAAAAAAAAA')->where('gateway_id', $gateway_id_1)->orderBy('last_seen', 'desc')->get()[0];
        var_dump($gateway_id_1);
        $attendance2 = Attendance_KLIA::where('tag_mac', 'AAAAAAAAAAAA')->where('gateway_id', $gateway_id_2)->orderBy('last_seen', 'desc')->get()[0];
        $this->assertEquals($tag_data3->created_at->toDateTimeString(), $beacon->updated_at);
    }
}
