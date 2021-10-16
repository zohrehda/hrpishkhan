<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpireTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('food_plans_expire_date')->insert([
            'id' => 1,
            'plan_date_range'=> '1399-07-05/1399-07-11' ,
            'expire'=> '1399-07-09' ,

        ]);
    }
}
