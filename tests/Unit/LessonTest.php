<?php

namespace Tests\Unit;

use App\Http\Controllers\Frontend\LessonController;
use App\Models\Lesson;
use App\Models\Section;
use Tests\Feature\BaseControllertTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonTest extends BaseControllertTest
{
    /** @test */
    public function store_a_lesson()
    {
        $lessons = Lesson::all()->count();
        $data = [
            'name' => 'aeo',
            'title' => 'aeo',
            'type' => 0,
            'educational_id' => $this->educational->id,
            'nav_id' => $this->nav->id,
            'teacher_id' => $this->teacher->id,
            'status' => 0,
            'pictrue' => 0,
            'learning' => '11-dsfsdfdsf-sfdsfsd',
            'price' => 0,
            'like' => 0,
            'for' => 0,
            'describle' => 0,
        ];

        Lesson::storeCache($data,'lessons');

        $lessons_after_store = Lesson::all()->count();
        $this->assertEquals($lessons_after_store,$lessons+1);
    }

    /** @test */
    public function update_a_lesson()
    {
        $data = [
            'name' => '更新课程名称',
            'title' => '更新课程title',
            'type' => 1,
            'educational_id' => $this->educational->id,
            'nav_id' => $this->nav->id,
            'teacher_id' => $this->teacher->id,
            'status' => 1,
            'pictrue' => 'http://test',
            'price' => 1.00,
            'learning' => '11-dsfsdfdsf-sfdsfsd',
            'like' => 1,
            'for' => '11-11-1',
            'describle' => '更新课程描述',
        ];

        $lesson = Lesson::updateCache($this->lesson->id,$data,'lessons');

        $this->assertEquals('更新课程名称',$lesson->name);
        $this->assertEquals('更新课程title',$lesson->title);
        $this->assertEquals(1,$lesson->type);
        $this->assertEquals($this->educational->id,$lesson->educational_id);
        $this->assertEquals($this->nav->id,$lesson->nav_id);
        $this->assertEquals($this->teacher->id,$lesson->teacher_id);
        $this->assertEquals(1,$lesson->status);
        $this->assertEquals('http://test',$lesson->pictrue);
        $this->assertEquals(1.00,$lesson->price);
        $this->assertEquals('11-dsfsdfdsf-sfdsfsd',$lesson->learning);
        $this->assertEquals(1,$lesson->like);
        $this->assertEquals('11-11-1',$lesson->for);
        $this->assertEquals('更新课程描述',$lesson->describle);

        foreach ([$this->section->id] as $section_id){
            $section =  Section::updateCache($section_id,['lesson_id' => $lesson->id],'sections');
            $this->assertEquals($lesson->id,$section->lesson_id);
        }
        
        foreach ([$this->section->id] as $section_id){
            $section = Section::updateCache($section_id,['is_free' => 1],'sections');
            $this->assertEquals(1,$section->is_free);
        }

        $lesson->genres()->sync([$this->genre->id]);
        $this->assertCount(1,$lesson->genres);
    }

    /** @test */
    public function success_delete_a_lesson()
    {
        $lessons = Lesson::all()->count();

        Lesson::deleteCache($this->lesson->id,'lessons');

        $lessons_after_del = Lesson::all()->count();
        $this->assertEquals($lessons_after_del,$lessons-1);
    }

    /** @test */
    public function up_a_lesson()
    {
        $lesson = Lesson::updateCache($this->lesson->id,[
            'status' => 3
        ],'lessons');

        $this->assertEquals(3,$lesson->status);
    }

    /** @test */
    public function down_a_lesson()
    {
        $lesson = Lesson::updateCache($this->lesson->id,[
            'status' => 2
        ],'lessons');

        $this->assertEquals(2,$lesson->status);
    }

    
    /** @test */
    public function get_mobile_index_data()
    {
        factory(Lesson::class)->create([
            'nav_id' =>   $this->nav->id,
            'teacher_id' =>   $this->teacher->id,
            'educational_id' =>   $this->educational->id,
            'status' => 3,
            'type' => 1,
            'is_nav' => 1,
        ]);
        factory(Lesson::class)->create([
            'nav_id' =>   $this->nav->id,
            'teacher_id' =>   $this->teacher->id,
            'educational_id' =>   $this->educational->id,
            'status' => 3,
            'type' => 3,
            'is_nav' => 1
        ]);
        factory(Lesson::class)->create([
            'nav_id' =>   $this->nav->id,
            'teacher_id' =>   $this->teacher->id,
            'educational_id' =>   $this->educational->id,
            'status' => 3,
            'type' => 2,
            'is_nav' => 1
        ]);

        $lesson_index = new LessonController();
        $lesson_indexs = $lesson_index->index()->original;

        $last_lessons = $lesson_indexs['last_lessons'];
        $nav = $lesson_indexs['nav'];
        $lesson_names = $lesson_indexs['lesson_names'];

        $this->assertEquals(3,$last_lessons->count());
        $this->assertEquals(3,$last_lessons[0]->status);
        $this->assertEquals(1,$nav->count());
        $this->assertEquals(3,count($lesson_names));
    }

}
