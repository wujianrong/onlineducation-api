<?php
namespace Tests\Feature;

use App\Models\Advert;
use App\Models\AuthPassword;
use App\Models\Discusse;
use App\Models\Educational;
use App\Models\Genre;
use App\Models\Guest;
use App\Models\Lesson;
use App\models\Menu;
use App\Models\Nav;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoUrl;
use App\Models\Vip;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Message;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class BaseControllertTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $auth_password;
    protected $guest_auth_password;
    protected $lesson;
    protected $first_genre;
    protected $second_genre;
    protected $educational;
    protected $nav;
    protected $teacher;
    protected $video;
    protected $section;
    protected $vip;
    protected $setting;
    protected $guest;
    protected $frozen_guest;
    protected $vip_order;
    protected $lesson_order;
    protected $message;
    protected $label;
    protected $discusse;
    protected $role;
    protected $permission;
    protected $advert;
    protected $genre;

    public function setUp()
    {
        parent::setUp();
        $this->auth_password = factory(AuthPassword::class)->create([
            'name' => 'admin',
            'password' => bcrypt('admin'),
        ]);
        $auth_password = factory(AuthPassword::class)->create([
            'name' => 'role',
            'password' => bcrypt('role'),
        ]);
        $this->guest_auth_password = factory(AuthPassword::class)->create([
            'name' => 'guest',
            'password' => bcrypt('oDMF40TjhXnYMy0e5RLPX3ZU-kzw'),
        ]);
        $guest_auth_password = factory(AuthPassword::class)->create([
            'name' => 'frozen_guest',
            'password' => bcrypt('oDMF40TjhXnYMy0e5RLPX3ZU-kzw'),
        ]);
        $this->vip = factory(Vip::class)->create();
        $this->setting = factory(Setting::class)->create();
        $this->permission = factory(Permission::class)->create();
        $this->role = factory(Role::class)->create();
        $this->advert = factory(Advert::class)->create();
        $this->user = factory(User::class)->create([
            'name' => 'admin',
            'auth_password_id' => $this->auth_password->id
       ]);
        
        factory(User::class)->create([
            'name' => 'role',
            'frozen' => 1,
            'auth_password_id' => $auth_password->id
        ]);
        $this->guest = factory(Guest::class)->create([
            'nickname' => 'guest',
            'openid' => 'oDMF40TjhXnYMy0e5RLPX3ZU-kzw',
            'auth_password_id' => $this->guest_auth_password->id,
            'vip_id' => $this->vip->id
        ]);
        $this->frozen_guest = factory(Guest::class)->create([
            'nickname' => 'frozen_guest',
            'frozen' => 1,
            'openid' => 'oDMF40TjhXnYMy0e5RLPX3ZU-kzw',
            'auth_password_id' => $guest_auth_password->id
        ]);
        $this->genre = factory(Genre::class)->create();
        $this->educational = factory(Educational::class)->create();
        $this->nav = factory(Nav::class)->create();
        $this->teacher = factory(Teacher::class)->create();
        $this->lesson = factory(Lesson::class)->create([
            'nav_id' =>   $this->nav->id,
            'teacher_id' =>   $this->teacher->id,
            'educational_id' =>   $this->educational->id,
        ]);
        $this->video = factory(Video::class)->create([
            'fileId' => '4564972818813772957',
            'name' => '小溪-480p.mp4',
            'status' => 2,
            'edk_key' => 'CiBs3r3EbiqHr678aL4/anThjNcJwAhfUW4988xjNwAbWxCO08TAChiaoOvUBCokMWUwYTU0MTMtOGE3YS00OGRjLTg0MTYtYjQ0OGUzMDc4MWMx',
        ]);
        factory(VideoUrl::class)->create([
            'url' => 'http://1255334727.vod2.myqcloud.com/c5939c06vodgzp1255334727/21cb8ffc4564972818813772957/vuizU3DKKioA.mp4',
            'size' => '10.48M',
            'duration' => '0时1分9秒',
            'video_id' => $this->video->id,
        ]);
        factory(VideoUrl::class)->create([
            'url' => 'http://1255334727.vod2.myqcloud.com/79bb3eaevodtransgzp1255334727/21cb8ffc4564972818813772957/v.f210.m3u8',
            'size' => '10.48M',
            'duration' => '0时1分9秒',
            'video_id' => $this->video->id,
        ]);
        factory(VideoUrl::class)->create([
            'url' => 'http://1255334727.vod2.myqcloud.com/79bb3eaevodtransgzp1255334727/21cb8ffc4564972818813772957/v.f220.m3u8',
            'size' => '10.48M',
            'duration' => '0时1分9秒',
            'video_id' => $this->video->id,
        ]);
        factory(VideoUrl::class)->create([
            'url' => 'http://1255334727.vod2.myqcloud.com/79bb3eaevodtransgzp1255334727/21cb8ffc4564972818813772957/v.f230.m3u8',
            'size' => '10.48M',
            'duration' => '0时1分9秒',
            'video_id' => $this->video->id,
        ]);

        $this->section = factory(Section::class)->create([
            'lesson_id' => $this->lesson->id,
            'video_id' => $this->video->id,
        ]);
        $this->vip_order = factory(Order::class)->create([
            'guest_id' => $this->guest->id,
            'name' => 'order',
            'order_type_id' => $this->vip->id,
            'type' => 2,
        ]);
        $this->lesson_order = factory(Order::class)->create([
            'guest_id' => $this->guest->id,
            'name' => 'order',
            'order_type_id' => $this->lesson->id,
            'type' => 1,
        ]);
        $this->label = factory(\App\Models\Label::class)->create();
        $this->message = factory(Message::class)->create([
                'user_id' => $this->user->id,
                'title' => '欢迎注册'
            ]
        );
        $this->discusse = factory(Discusse::class)->create([
            'guest_id' =>  $this->guest->id,
            'lesson_id' =>  $this->lesson->id,
            'content' => '这真的是个好课程'
        ]);

        $menus = factory(Menu::class)->create();
        $this->user->roles()->attach($this->role->id);
        $this->role->menus()->attach($menus->id);


        /*模拟观看*/
        $this->guest->lessons()->attach([ $this->lesson->id => ['sections' => $this->section->id,'type' => 7] ]);

    }

    /**
     * @test
     */
    public function no_test_for_base_controller()
    {
        $this->assertTrue(true);
    }
}