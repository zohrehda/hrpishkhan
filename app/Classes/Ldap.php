<?php

namespace App\Classes;

use Adldap\Laravel\Commands\Import;
use Adldap\Laravel\Facades\Adldap;

class Ldap
{
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
