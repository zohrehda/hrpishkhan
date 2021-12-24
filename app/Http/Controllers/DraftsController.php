<?php

namespace App\Http\Controllers;

use App\Draft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DraftsController extends Controller
{
    //
    public function store(Request $request)
    {
        if (config('app.draft_db') == 'mongodb') {

            return $this->store_in_mongodb($request);
        }
        return $this->store_in_mysql($request);

    }

    public function list(Request $request,$id=null)
    {
        if (config('app.draft_db') == 'mongodb') {

            return $this->list_mongodb($request);
        }
        return $this->list_mysql($request,$id);

    }

    public function destroy(Request $request, $draft_id)
    {
        if (config('app.draft_db') == 'mongodb') {

            return $this->destroy_mongodb($request);
        }
        return $this->destroy_mysql($request, $draft_id);

    }

    public function destroy_mysql(Request $request, $draft_id)
    {
        Draft::find($draft_id)->delete();
        return [
            'success'=>true
        ] ;
        //return redirect()->back();

    }

    public function list_mongodb(Request $request)
    {

    }

    public function list_mysql(Request $request,$id)
    {
        if($id){
            $drafts = Draft::find($id);

        }else{
            $drafts = Draft::where('user_id', Auth::user()->id)->get()->merge(Draft::where('public','1')->get() );
        //    $drafts = Draft::where('public','1')->get() ;
           // dd($drafts);
           // $drafts=Draft::all() ;
        }
        return [
            'drafts' => $drafts ,
            'user_id'=>Auth::user()->id
        ];
    }

    public function store_in_mysql(Request $request)
    {
        //dd($request->input('draft_public')??'f');
        Draft::updateOrCreate([
            'name' => $request->input('draft_name'),
            'user_id' => Auth::user()->id,
        ], [
            'public' => $request->input('draft_public')??'0',
            'draft' => json_encode($request->except(['_token']))
        ]);
        return [
            'success' => true
        ];
    }


    public function store_in_mongodb(Request $request)
    {

    }
}
