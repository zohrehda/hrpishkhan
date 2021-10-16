<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

/*    public function test_an_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Afshin',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertCount(1, User::get());
    }*/

    public function test_an_user_can_login()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password' ,
            'role'=>1 ,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
    }

    public function test_an_user_can_reset_password()
    {
        $user = factory(User::class)->create();

        $request = $this->post('/password/reset', [
            'token' => Password::broker()->createToken($user),
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $user->refresh();

        $request->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }
}
