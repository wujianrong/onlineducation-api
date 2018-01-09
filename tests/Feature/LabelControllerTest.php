<?php

namespace Tests\Feature;

use App\Models\Label;

class LabelControllerTest extends BaseControllertTest
{
    /** @test */
    public function visit_labels_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/label/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                   '*' => [
                       'id','name','created_at'
                   ]
            ]);
    }

	/** @test */
	public function visit_labels_names()
	{
		$response = $this->actingAs($this->auth_password,'api')
			->json('GET','api/admin/label/names');

		$response
			->assertStatus(200);
	}

    /**
     * @test
     */
    public function visit_label_data()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/label/'.$this->label->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
            	'label' => [
            		'id','name','created_at'
				],'label_names'

            ]);
    }

    /** @test */
    public function store_a_label()
    {

        $data = [
            'name' => 'vip'
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/label/',$data);

        $response->assertStatus(200);

    }

    /** @test */
    public function update_a_label()
    {
        $data = [
            'name' => '更新标签'
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/label/'.$this->label->id.'/update',$data);

        $response->assertStatus(200);
    }

    /** @test */
    public function success_delete_a_label()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/label/'.$this->label->id.'/delete');

        $response->assertStatus(200);
    }

    /** @test */
    public function fail_delete_a_label()
    {
        $label = factory(Label::class)->create();
        $label->guests()->sync([$this->guest->id]);

        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/label/'.$label->id.'/delete');

        $response->assertStatus(201);

    }
}
