<?php

namespace Tests\Feature;

class OrderControllerTest extends BaseControllertTest
{

    /**
     * @test
     */
    public function visit_orders_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/order/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','status','order_no','type','price','mouth','guest','pay_type','pay_date','start','end','created_at','order_type_id'
                ]
            ]);
    }

    /**
     * @test
     */
    public function visit_vip_orders_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/order/vip_order_list');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','status','order_no','type','price','mouth','guest','pay_type','pay_date','start','end','created_at','order_type_id'
                ]
            ]);
    }
    
    /**
     * @test
     */
    public function delete_a_order()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/order/'.$this->vip_order->id.'/delete');

        $response->assertStatus(200);
    }
    
}
