<?php

namespace Tests\Feature;


class DiscusseControllerTest extends BaseControllertTest
{

    /**
     * @test
     */
    public function visit_discusses_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/discusse/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','content','is_better','created_at', 'guest', 'lesson'
                ]
            ]);
    }

    /**
     * @test
     */
    public function set_a_discusse_better()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/discusse/'.$this->discusse->id.'/better');

        $response->assertStatus(200);

    }

    /**
     * @test
     */
    public function set_some_discusses_better()
    {
        $data =[
            'discusse_ids' => [$this->discusse->id]
        ] ;

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/discusse/better_some',$data);

        $response->assertStatus(200);

    }

    /**
     * @test
     */
    public function unset_a_discusse_better()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/discusse/'.$this->discusse->id.'/un_better');

        $response->assertStatus(200);

    }

    /**
     * @test
     */
    public function unset_some_discusses_better()
    {
        $data =[
            'discusse_ids' => [$this->discusse->id]
        ] ;

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/discusse/un_better_some',$data);

        $response->assertStatus(200);

    }
    
    /**
     * @test
     */
    public function delelte_a_discusse()
    {

        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/discusse/'.$this->discusse->id.'/delete');

        $response->assertStatus(200);

    }

    /**
     * @test
     */
    public function delelte_some_discusse()
    {

        $data =[
            'discusse_ids' => [$this->discusse->id]
        ] ;

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/discusse/delete_some',$data);

        $response->assertStatus(200);

    }
}
