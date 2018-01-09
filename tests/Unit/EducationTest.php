<?php

namespace Tests\Unit;

use App\Models\Educational;
use Tests\Feature\BaseControllertTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EducationTest extends BaseControllertTest
{
    /** @test */
    public function create_a_eduction()
    {
        $educations = Educational::all()->count();
        $data = [
            'name' => '付费模版',
            'content' => '11111111'
        ];

        Educational::storeCache($data,'educationals');

        $educations_after_store = Educational::all()->count();
        $this->assertEquals($educations_after_store,$educations+1);
    }

    /** @test */
    public function update_a_eduction()
    {
        $data = [
            'name' => '更新付费模版',
            'content' => '更新付费模版内容'
        ];

        $education = Educational::updateCache($this->educational->id,$data,'educationals');

        $this->assertEquals('更新付费模版',$education->name);
        $this->assertEquals('更新付费模版内容',$education->content);
    }

    /** @test */
    public function success_del_a_eduction()
    {
        $educational = factory(Educational::class)->create();
        $educations = Educational::all()->count();

        Educational::deleteCache($educational->id,'educationals');

        $educations_after_del = Educational::all()->count();
        $this->assertEquals($educations_after_del,$educations-1);
    }
    
}
