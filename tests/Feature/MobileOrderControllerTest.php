<?php

namespace Tests\Feature;

use App\Models\Order;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileOrderControllerTest extends BaseControllertTest
{

//    /**
//     * @test
//     */
//    public function create_a_lesson_order()
//    {
//
//        $orders = Order::all();
//
//        $response = $this->actingAs($this->guest_auth_password,'api')
//            ->json('GET','api/item/order/'.$this->lesson->id.'/lesson');
//
//        $response->assertStatus(200);
//
//        $orders_after_create = Order::all();
//
//        $this->assertEquals($orders_after_create,$orders+1);
//        $this->assertCount(1,$this->guest->lessons()->count());
//    }
//
//      /**
// * @test
// */
//    public function create_a_vip_order()
//    {
//
//        $orders = Order::all();
//
//        $response = $this->actingAs($this->guest_auth_password,'api')
//            ->json('GET','api/item/order/'.$this->vip->id.'/vip');
//
//
//        $response->assertStatus(200);
//
//        $orders_after_create = Order::all();
//
//        $this->assertEquals($orders_after_create,$orders+1);
//        $this->assertCount($this->vip->id,$this->guest->vip_id);
//    }

}
