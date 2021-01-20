<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodReservedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('food_reserved')->insert([
            'id' => 1,
            'user_id' => 1,
            'date'=> '1399-07-09' ,
            'food_id' => 1,

        ]);
    }
}
