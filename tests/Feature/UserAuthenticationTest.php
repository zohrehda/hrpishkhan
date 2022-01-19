<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User ;

class UserAuthenticationTest extends TestCase
{
   // use RefreshDatabase ;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase ;
    public function setUp(): void
    {
        parent::setUp();
        
        $this->seed('UsersTableSeeder');
    }
    
    public function test_an_user_can_login()
    {
    
        $user=factory(User::class)->create() ;
        $this->post('/login',[
             'email'=>$user->email ,
             'password'=>'password'
         ]);
    
         $this->assertTrue(auth()->check()) ;
         $this->assertTrue($user->is(auth()->user())) ;
  //      $this->assertTrue(true) ;
      
    }


}
