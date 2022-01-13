<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function initialLogin(Request $request){

      if(!HrAdminSetup()){

        if(!config('app.hr_admin_email') || $request->input('email',null)!=config('app.hr_admin_email')){

          throw ValidationException::withMessages([
            $this->username() => [trans('auth.hr_admin_setup')],
        ]);
 
        }

      }
     return  $this->login($request) ;
     }
    public function __construct()
    {
            
        $this->middleware('guest')->except('logout');
      //  dd('f') ;
    }

    /*public function username()
    {
 return config('ldap_auth.identifiers.ldap.locate_users_by');
    }*/
}
