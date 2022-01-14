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
        $validator = Validator::make($request->all(), [
            'users' => 'array',
        ]);
        $validator->after(function ($validator) use ($request) {

            $requisition = Requisition::find($request->input('requisition_id'));
            $ids = $requisition->determiners->pluck('id')->merge($requisition->owner_id)
            ->merge($requisition->user_assigned->pluck('id'))->toArray();
            
            $ee = array_intersect($request->input('users',[]), $ids);

            if (count($ee)) {
                $validator->errors()->add('users', 'You can not select determiners and requester of the requisition as viewer');
            }

        });
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $viewers = $request->input('users',[]);
        $requisition_id = $request->input('requisition_id');

        Requisition::find($requisition_id)->requisition_viewers()->delete();

        foreach ($viewers as $viewer) {
            $credentials = [
                'user_id' => User::by_provider($viewer)->id,
                'requisition_id' => $requisition_id
            ];
            RequisitionViewer::create($credentials);
        }
        return response()->json(['success'=>'Done']);


    }
}
