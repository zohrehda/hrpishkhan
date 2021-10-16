<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Afshin',
            'email' => 'bigspiky@gmail.com',
            'password' => app('hash')->make('password'),
            'role'=>'supervisor',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Akbar',
            'email' => 'bigspikyy@gmail.com',
            'password' => app('hash')->make('password'),
            'role'=>'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'name' => 'Asghar',
            'email' => 'bigspikyyy@gmail.com',
            'password' => app('hash')->make('password'),
            'role'=>'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 4,
            'name' => 'Naghi',
            'email' => 'bigspikyyyy@gmail.com',
            'password' => app('hash')->make('password'),
            'role'=>'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 5,
            'name' => 'hr-manager',
            'email' => 'bigspikyyyyy@gmail.com',
            'password' => app('hash')->make('password'),
            'role'=>'supervisor',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('food_reserved')->insert([
            'id' => 1,
            'user_id' => 1,
            'date'=> '1399-07-09' ,
            'food_id' => 1,

        ]);






    }
}
