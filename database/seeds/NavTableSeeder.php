<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NavTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('navs')->insert([
            [
                'id' => 1,
                'name' => '免费vip',
                'pictrue' => 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=3374989003,48831532&fm=173&s=F02CBE5721E192A86184B4770300B068&w=550&h=309&img.JPEG',
                'order_type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
