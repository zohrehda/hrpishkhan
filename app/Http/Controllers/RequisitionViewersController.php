<?php

namespace App\Http\Controllers;

use App\Requisition;
use App\RequisitionViewer;
use App\User;
use Illuminate\Http\Request;

class RequisitionViewersController extends Controller
{

    public function store(Request $request)
    {
        $viewers = $request->input('users');
        $requisition_id = $request->input('requisition_id');

        Requisition::find($requisition_id)->requisition_viewers()->delete();

        foreach ($viewers as $viewer) {
            $credentials = [
                'user_id' => User::byProvider($viewer)->id,
                'requisition_id' => $requisition_id
            ];
            RequisitionViewer::create($credentials);
        }

    }
}
