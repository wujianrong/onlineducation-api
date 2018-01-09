<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VideoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('videos')->insert([
            [
                'id' => 1,
                'fileId' => '4564972818532249357',
                'status' => 4,
                'name' => '5秒',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
