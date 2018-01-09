<?php

use Illuminate\Database\Seeder;

class GuestLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guest_label')->insert([
           [
               'guest_id' => 1,
               'label_id' => 1,
           ],  [
               'guest_id' => 1,
               'label_id' => 2,
           ],  [
               'guest_id' => 1,
               'label_id' => 3,
           ],  [
               'guest_id' => 1,
               'label_id' => 4,
           ],
        ]);
    }
}
