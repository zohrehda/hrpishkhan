<?php

namespace App\Http\Controllers;

use App\Requisition;
use App\RequisitionViewer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RequisitionViewersController extends Controller
{

    public function store(Request $request)
    {
        //dd($request->input('requisition_id')) ;
        $validator = Validator::make($request->all(), [
            'users' => 'array',
        ]);
        $validator->after(function ($validator) use ($request) {

            $requisition = Requisition::find($request->input('requisition_id'));
            $forbidden_users = $requisition->determiners->pluck('email')->merge($requisition->owner->email)
            ->merge($requisition->user_assigned->pluck('email'))
            ->merge($requisition->requisition_viewers->pluck('email'))
            ->toArray();
            
           
            $has_forbidden_user = array_intersect($request->input('users',[]), $forbidden_users);
           

            if (count($has_forbidden_user)) {

                $validator->errors()->add('users', 'You can not select determiners and requester of the requisition as viewer');
            }

        });
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $viewers = $request->input('users',[]);
       // dd($viewers) ;
        $requisition_id = $request->input('requisition_id');

        Requisition::find($requisition_id)->requisition_viewers()->delete();

        foreach ($viewers as $viewer) {
           // echo $viewer ;
            $credentials = [
                'user_id' => User::by_provider($viewer)->id,
                'requisition_id' => $requisition_id
            ];
            RequisitionViewer::create($credentials);
        }
        return response()->json(['success'=>'Done']);


    }
}
