<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EducationalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('educationals')->insert([
            [
                'id' => 1,
                'name' =>'模板名字',
                'content' =>'模板内容',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
