<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GerenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->insert([
            [
                'id' => 1,
                'name' => '关务系列',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
