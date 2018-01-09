<?php

namespace Tests\Unit;

use App\Models\Label;
use Tests\Feature\BaseControllertTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LabelTest extends BaseControllertTest
{
    /** @test */
    public function store_a_label()
    {
        $labels = Label::all()->count();
        $data = [
            'name' => 'vip'
        ];

        Label::storeCache($data,'labels');

        $labels_after_store = Label::all()->count();
        $this->assertEquals($labels_after_store,$labels+1);
    }

    /** @test */
    public function update_a_label()
    {
        $data = [
            'name' => '更新标签'
        ];

        $label = Label::updateCache($this->label->id,$data,'labels');

        $this->assertEquals('更新标签',$label->name);
    }

    /** @test */
    public function success_delete_a_label()
    {
        $labels = Label::all()->count();

        Label::deleteCache($this->label->id,'labels');

        $labels_after_del= Label::all()->count();
        $this->assertEquals($labels_after_del,$labels-1);
    }
}
