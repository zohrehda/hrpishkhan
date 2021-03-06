<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //   $this->call(UsersTableSeeder::class);
        //  $this->call(ExpireTableSeeder::class);
        //  $this->call(FoodReservedTableSeeder::class);
        $this->call(RequisitionProgressTableSeeder::class);
        $this->call(RequisitionTableSeeder::class);

    }
}
