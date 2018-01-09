<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdvertTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adverts')->insert([
            [
                'id' => 1,
                'name' => '东华国际广告',
                'path' => 'https://ss0.baidu.com/6ONWsjip0QIZ8tyhnq/it/u=1848882036,2165701458&fm=173&s=5780C1A04853BBFD2A2C848103001082&w=630&h=884&img.JPEG',
                'type' => 0,
                'order' => 1,
                'url' => 'https://mbd.baidu.com/newspage/data/landingsuper?context=%7B%22nid%22%3A%22news_17937185639396400392%22%7D&n_type=0&p_from=1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
