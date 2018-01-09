<?php

namespace Tests\Unit;

use App\Http\Controllers\Backend\VideoController;
use App\Models\Section;
use App\Models\Video;
use Tests\Feature\BaseControllertTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideoTest extends BaseControllertTest
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {

		 factory(Video::class)->create([
			'status' =>2
		]);

		 $video = factory(Video::class)->create([
			'status' =>2
		]);


		factory(Section::class)->create([
			'lesson_id' => $this->lesson->id,
			'video_id' => $video->id
		]);

		$video_controller = new VideoController();
		$video_success_lists = $video_controller->successList();

		$this->assertCount(1,$video_success_lists);
		$this->assertEquals(2,$video_success_lists->first()->status);

    }
}
