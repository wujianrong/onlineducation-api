<?php

namespace Tests\Feature;


class LogsControllerTest extends BaseControllertTest
{

    /** @test */
    public function visit_logs_lists_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/log/lists');

        $response->assertStatus(200)
            ->assertJsonStructure([
            	'current_page','data' => [
					'*' => [
						'user','id','revisionable_type','key','old_value','new_value','created_at','content'
					]
				],'last_page','last_page_url','next_page_url','per_page','to','total'

            ]);
    }
}
