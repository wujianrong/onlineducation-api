<?php

namespace Tests\Feature;

use App\models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuControllerTest extends BaseControllertTest
{

    /** @test */
    public function return_all_menus_data()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/menu/lists');


        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'menus' => [
                    '*' => [
                        'id','name','parent_id','icon','url','description','is_nav','order','created_at','updated_at'
                    ]
                ]
            ])
        ;
    }
}
