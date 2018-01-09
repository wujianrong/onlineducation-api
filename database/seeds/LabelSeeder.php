<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('labels')->insert([
            [
                'id' => 1,
                'name' => '标签1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ], [
                'id' => 2,
                'name' => '标签2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ], [
                'id' => 3,
                'name' => '标签3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ], [
                'id' => 4,
                'name' => '标签4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
