<?php

namespace App\Classes;

use Adldap\Laravel\Commands\Import;
use Adldap\Laravel\Facades\Adldap;

use App\User as EloquentUser;
use Adldap\Models\User as LdapUser;

class Ldap
{
    public function ImportLdapToModel($userPrincipalName)
    {
        $user = Adldap::search()->users()->findBy('userPrincipalName', $userPrincipalName);
        $credentials = [
            'email' => $user->getEmail(),
        ];

// Create the importer:
        $importer = new Import($user, new EloquentUser(), $credentials);

// Run the importer. The synchronized *unsaved* model will be returned:
        $model = $importer->handle();

// Save the returned model.
        $model->save();
    }


    public function handle(LdapUser $ldapUser, EloquentUser $eloquentUser)
    {
        $eloquentUser->name = $ldapUser->getCommonName();
        $eloquentUser->email = $ldapUser->getEmail();
        $eloquentUser->role = (!HrAdminSetup()
            &&  $ldapUser->getEmail()==config('app.hr_admin_email')
         )?(EloquentUser::ROLE_HR_ADMIN):(EloquentUser::ROLE_USER);

    }
}
