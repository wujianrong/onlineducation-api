<?php

namespace Tests\Feature;


use App\Models\Vip;
use Carbon\Carbon;

class VipControllerTest extends BaseControllertTest
{
    
    /**
     * @test
     */
    public function visit_vips_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/vip/lists');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','status','expiration','price','count','describle','up','down','created_at'
                ]
            ]);
    }

	/**
	 * @test
	 */
	public function visit_vips_names()
	{
		$response = $this->actingAs($this->auth_password,'api')
			->json('GET','api/admin/vip/names');

		$response
			->assertStatus(200);
	}

    /**
     * @test
     */
    public function visit_vip_datas_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/vip/'.$this->vip->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
            	'vip' => [ 'id','name','status','expiration','price','count','describle','up','down','created_at'],'vip_names'
            ]);
    }

    /**
     * @test
     */
    public function up_a_vip()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/vip/'.$this->vip->id.'/up');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
               'status','message'
            ]);
    }

    /**
     * @test
     */
    public function down_a_vip()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/vip/'.$this->vip->id.'/down');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ]);
    }

    /**
     * @test
     */
    public function update_a_vip()
    {
        $data = [
            'name' => '11111',
            'status' => 1,
            'expiration' => 1,
            'price' => 1.00,
            'count' => 1,
            'describle' => '111111111',
            'start' => Carbon::now()->timestamp,
            'end' => Carbon::now()->addDays(3)->timestamp,
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/vip/'.$this->vip->id.'/update',$data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ]);
    }


    /**
     * @test
     */
    public function set_upTime_to_vip()
    {
        $data = [
            'up' => Carbon::now()->timestamp,
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/vip/'.$this->vip->id.'/set_up_time',$data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ]);
    }

    /**
     * @test
     */
    public function set_downTime_to_vip()
    {
        $data = [
            'down' => Carbon::now()->timestamp,
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/vip/'.$this->vip->id.'/set_down_time',$data);
 
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ]);
    }


    /**
     * @test
     */
    public function create_a_vip()
    {
        $data = [
            'name' => '11111',
            'status' => 1,
            'expiration' => Carbon::now()->timestamp,
            'price' => 1.00,
            'count' => 1,
            'describle' => '111111111',
            'up' => Carbon::now()->timestamp,
            'down' => Carbon::now()->addDays(3)->timestamp,
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/vip/',$data);


        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ]);
    }

    /**
     * @test
     */
    public function success_delete_a_vip()
    {
        $vip = factory(Vip::class)->create();
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/vip/'.$vip->id.'/delete');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function fail_delete_a_vip()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/vip/'.$this->vip->id.'/delete');

        $response->assertStatus(201);
    }
}
