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
            'name' => 'user1',
            'email' => 'user1@snapp.cab',
            'password' => app('hash')->make('password'),
            'role'=>(config('app.users_provider')=='mysql')?'hr_admin':'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'user2',
            'email' => 'user2@snapp.cab',
            'password' => app('hash')->make('password'),
            'role'=>'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'name' => 'user3',
            'email' => 'user3@snapp.cab',
            'password' => app('hash')->make('password'),
            'role'=>'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'name' => 'user4',
            'email' => 'user4@snapp.cab',
            'password' => app('hash')->make('password'),
            'role'=>'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'name' => 'user5',
            'email' => 'user5@snapp.cab',
            'password' => app('hash')->make('password'),
            'role'=>'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);







    }
}
