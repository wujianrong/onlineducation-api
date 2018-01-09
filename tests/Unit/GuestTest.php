<?php

namespace Tests\Unit;

use App\Models\Guest;
use Tests\Feature\BaseControllertTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestTest extends BaseControllertTest
{
    
    /**
     * @test
     */
    public function add_tow_labels_to_guest()
    {
        $labels = $this->guest->labels()->get()->count();
        $data = [$this->label->id];

        $this->guest->setLabel($data);

        $labels_after_add_label = $this->guest->labels()->get()->count();
        $this->assertEquals($labels_after_add_label,$labels+1);
    }

}
