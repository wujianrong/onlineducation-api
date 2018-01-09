<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VideoControllerTest extends BaseControllertTest
{
    /** @test */
    public function visit_videos_lists_page()
    {

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET', '/api/admin/video/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                         'id','name','status','size','created_at', 'url', 'duration'
                 ]
                ]
            ]);
    }

    /** @test */
    public function visit_videos_names()
    {

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET', '/api/admin/video/names');

        $response
            ->assertStatus(200);
    }

    /** @test */
    public function visit_success_videos_lists_page()
    {

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET', '/api/admin/video/success_lists');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id','name','status','size','created_at', 'url', 'duration'
                    ]
                ]
            ]);
    }

    /** @test */
    public function visit_a_video_edit_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET', '/api/admin/video/'.$this->video->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
            	'video' => [
            		'id','name','status','size','created_at', 'url', 'duration'
				],'video_names'

            ]);
    }

    /**
     *
     * @test
     * 上传视频会影响其他视频接口测试，暂时注释。需要时候解开
     */
//    public function success_upload_a_video()
//    {

//        Storage::fake('avatars');
//
//        $data = [
//            'video' => UploadedFile::fake()->create('11111.mp4'),
//        ];
//
//        $response = $this->actingAs($this->user,'api')
//            ->json('POST','/api/admin/video/',$data);
//
//        $response
//            ->assertStatus(200)
//            ->assertJsonStructure(['status','message']);
//    }

    /**
     * @test
     */
    public function success_update_a_video()
    {
        $data = [
            'name' => '222323',
            'duration' => '5秒'
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/video/'.$this->video->id.'/update',$data);
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['status','message']);
    }

    /**
     *
     * @test
     * 删除视频会影响其他视频接口测试，暂时注释。需要时候解开
     */
//    public function success_delete_a_video()
//    {
//
//        $response = $this->actingAs($this->user,'api')
//            ->json('GET','/api/admin/video/'.$this->video->id.'/delete');
//
//        $response
//            ->assertStatus(200)
//            ->assertJsonStructure(['status','message']);
//    }
}
