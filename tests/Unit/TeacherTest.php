<?php

namespace Tests\Unit;

use App\Models\Teacher;
use Tests\Feature\BaseControllertTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherTest extends BaseControllertTest
{
    /**
     * @test
     */
    public function store_a_teacher()
    {
        $teachers_count = Teacher::all()->count();
        $data = [
           'name' => '这是一个讲师',
           'office' => '只是一个职位',
           'avatar' => '头像',
           'describle' => '讲师介绍',
        ];

        Teacher::storeCache($data,'teachers');
        $teachers_after_store_count = Teacher::all()->count();

        $this->assertEquals($teachers_after_store_count,$teachers_count+1);
    }

    /**
     * @test
     */
    public function update_a_teache()
    {
        $data = [
            'name' => '这是一个讲师',
            'office' => '只是一个职位',
            'avatar' => '头像',
            'describle' => '讲师介绍',
        ];

        $teacher = Teacher::updateCache($this->teacher->id,$data,'teachers');

        $this->assertEquals('这是一个讲师',$teacher->name);
        $this->assertEquals('只是一个职位',$teacher->office);
        $this->assertEquals('头像',$teacher->avatar);
        $this->assertEquals('讲师介绍',$teacher->describle);
    }

    /**
     * @test
     */
    public function del_a_teacher()
    {
        $teachers_count = Teacher::all()->count();

        Teacher::deleteCache($this->teacher->id,'teachers');
        $teachers_after_del_count = Teacher::all()->count();

        $this->assertEquals($teachers_after_del_count,$teachers_count-1);
    }
}
