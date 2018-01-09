<?php

namespace Tests\Feature;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class ImageControllerTest extends BaseControllertTest
{

    /**
     * @test
     */
    public function upload_a_image()
    {
        Storage::fake('avatars');

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/image/upload/',[
                  'image' => UploadedFile::fake()->image('avatar.jpg', 200, 200)->size(100)
            ]);

        $response->assertStatus(200);
        
//        Storage::disk('avatars')->assertExists('avatar.jpg');
    }
}
