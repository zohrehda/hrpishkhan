<?php

namespace App\Http\Controllers;

use Adldap\Laravel\Commands\Import;
use Adldap\Laravel\Facades\Adldap;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users_provider = config('app.users_provider');

        $users = [];
        if ($users_provider == 'ldap') {
            $users = $this->getMultipleLdapMysql($request);

        } elseif ($users_provider == 'mysql') {
            $users = $this->mysqlUsers($request);
        }

        $result['results'] = $users ;

        return json_encode($result);
    }

    private function getMultipleLdapMysql(Request $request)
    {
        return $this->ldapUsers($request)->merge($this->mysqlUsers($request));
    }

    private function ldapUsers(Request $request)
    {
        $term = $request->input('term');
        return Adldap::search()/*->where('userPrincipalName', '!=', Auth::user()->email)*/->whereStartsWith('userPrincipalName', $term)->get()
            ->map(function ($item) {
                return [
                    'id' => $item->userPrincipalName [0],
                    'text' => $item->userPrincipalName [0]
                ];
            });
    }

    private function mysqlUsers(Request $request)
    {
        $term = $request->input('term');

        return User::where('email', 'like', "%$term%")/*->where('email', '!=', Auth::user()->email)*/->get()
            ->map(function ($item) {
                return [
                    'id' => $item->email,
                    'text' => $item->email
                ];
            });
    }



    public function ImportLdapToModel($userPrincipalName)
    {
        $user = Adldap::search()->users()->findBy('userPrincipalName', $userPrincipalName);
        $credentials = [
            'email' => $user->getEmail(),
        ];

// Create the importer:
        $importer = new Import($user, new User(), $credentials);

// Run the importer. The synchronized *unsaved* model will be returned:
        $model = $importer->handle();

// Save the returned model.
        $model->save();
    }





}
