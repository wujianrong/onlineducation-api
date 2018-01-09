<?php

namespace Tests\Unit;

use App\Models\Discusse;
use Tests\Feature\BaseControllertTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiscusseTest extends BaseControllertTest
{
    /**
     * @test
     */
    public function store_a_discusse()
    {
        $data = [
            'content' => '这是一条评论',
            'guest_id' => $this->guest->id,
            'lesson_id' => $this->lesson->id,
        ];

        $discusses = Discusse::all()->count();
        Discusse::storeCache($data,'discusses');
        $discusses_after_del = Discusse::all()->count();

        $this->assertEquals($discusses_after_del,$discusses+1);

    }

    /**
     * @test
     */
    public function set_a_discusse_better()
    {
        $discusse = Discusse::updateCache($this->discusse->id,['is_better' => 1],'discusses');

        $this->assertEquals(1,$discusse->is_better);
    }


    /**
     * @test
     */
    public function unset_a_discusse_better()
    {
        $discusse = Discusse::updateCache($this->discusse->id,['is_better' => 0],'discusses');

        $this->assertEquals(0,$discusse->is_better);
    }


    /**
     * @test
     */
    public function delelte_a_discusse()
    {
        $discusses = Discusse::all()->count();

        Discusse::deleteCache($this->discusse->id,'discusses');

        $discusses_after_del = Discusse::all()->count();
        $this->assertEquals($discusses_after_del,$discusses-1);
    }


    /**
     * @test
     */
    public function set_some_discusse_better()
    {

        $result = Discusse::whereIn('id',[$this->discusse->id])->update(['is_better' => 1]);

        $this->assertEquals(1,$result);
    }


    /**
     * @test
     */
    public function unset_some_discusse_better()
    {
        $result = Discusse::whereIn('id',[$this->discusse->id])->update(['is_better' => 0]);

        $this->assertEquals(1,$result);
    }


    /**
     * @test
     */
    public function delelte_some_discusse()
    {
        $discusses = Discusse::all()->count();

        Discusse::whereIn('id',[$this->discusse->id])->delete();

        $discusses_after_del = Discusse::all()->count();
        $this->assertEquals($discusses_after_del,$discusses-1);
    }

}
