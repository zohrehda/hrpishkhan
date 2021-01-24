<?php

namespace App\Http\Controllers;

use App\Events\RequisitionSent;
use App\Requisition;
use App\RequisitionProgress;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Str;
use Adldap\Laravel\Commands\Import;


class RequisitionController extends Controller
{

    public function create(Request $request)
    {

        $errors = $request->session()->get('errors');
        if ($errors == null) {
            session(['termAccepted' => 0]);
        }
        //   dd($errors);

        $users = $this->determiners();
        $departments = $this->departments;
        //    $levels=['']
        $hr_manager_user = User::hr_manager();


        return view('requisitions.create', compact('users', 'hr_manager_user', 'departments'));
    }

    public function customizeReceiver()
    {
        $users = $this->determiners();
        $user_array = [];
        foreach ($users as $u) {
            $user_array[$u->id] = $u->email;
        }
        $algorithm_array = $this->algorithm_array;
        $levels_array = $this->levels;
        $approver_array = $this->Approver;

        $department = \request()->post('department');
        $level = \request()->post('level');

        if (isset($department) && !isset($level)) {

            $levels = $algorithm_array[$department];


            $result = [];
            foreach (array_keys($levels) as $v) {
                $result['l' . "$v"] = $levels_array[$v];
            }

            //  dd($result) ;
            return ['levels' => $result];
        } else {

            $result = [];
            foreach ($algorithm_array[$department][$level] as $v) {
                $result[$v] = $approver_array[$v];
            }
            return ['approver' => $result,
                'users' => $user_array
            ];
            //   dd($result);
            //  return ['receivers' =>$result ];
            //  dd('ff');
        }


    }

    public function store(Request $request)
    {
        $determiners = $request->post('determiners', []);
       // dd($determiners);


        $messages = [
            'determiners.*.distinct' => "Can't select same determiner on two or more progresses"
        ];
        $validator = Validator::make($request->all(), [
            'department' => 'required',
            'level' => 'required',
            'fa_title' => 'required',
            'en_title' => 'required',
            'competency' => 'required',
            'time' => 'required',
            'hiring_type' => 'required',
            'replacement' => 'required_if:hiring_type,0',
            'mission' => 'required',
            'outcome' => 'required',
            'position_count' => 'required',
            'report_to' => 'required',
            'location' => 'required',
            'experience_year' => 'required',
            'field_of_study' => 'required',
            'degree' => 'required',
            'determiners.*' => 'distinct',
        ], $messages);

        $validator->after(function ($validator) {
            if (!request()->post('determiners')) {
                $validator->errors()->add('determiner', 'choose one receiver');
            }

        });

        if ($validator->fails()) {

            session(['termAccepted' => 1]);

            return redirect()->back()->withErrors($validator)->withInput();

        }

        //    $hr_manager = [5 => User::hr_manager()->id];

        $determiners = $request->post('determiners', []);
        //  $determiners = $determiners + $hr_manager;

        foreach ($determiners as $d)
        {
            $this->ImportLdapToModel($d) ;
        }


        $requisition = new Requisition();
        $requisition->department = $request->post('department');
        $requisition->level = $request->post('level');
        $requisition->fa_title = $request->post('fa_title');
        $requisition->en_title = $request->post('en_title');
        $requisition->competency = $request->post('competency');
        $requisition->mission = $request->post('mission');
        $requisition->outcome = $request->post('outcome');
        $requisition->position_count = $request->post('position_count');
        $requisition->shift = ($request->post('shift') == 0) ? null : $request->post('shift');
        $requisition->report_to = $request->post('report_to');
        $requisition->location = $request->post('location');
        $requisition->experience_year = $request->post('experience_year');
        $requisition->field_of_study = $request->post('field_of_study');
        $requisition->degree = $request->post('degree');
        $requisition->owner_id = Auth::id();
     //   $requisition->determiner_id = array_values($determiners)[0];
        $requisition->determiner_id = User::where('email',array_values($determiners)[0])->first()->id;
        $requisition->is_full_time = $request->post('time');
        $requisition->is_new = $request->post('hiring_type');
        $requisition->replacement = $request->post('replacement');
        $requisition->about = $request->post('about');

        /* if ($request->filled('is_full_time')) {
             $requisition->is_full_time = 1;
         }
         if ($request->filled('is_new')) {
             $requisition->is_new = 1;
         }*/
        $requisition->save();

        $sender = User::find(Auth::id());
        $recipient = User::find(array_values($determiners)[0]);
        event(new RequisitionSent($sender, $recipient));

        foreach ($determiners as $key => $value) {

            $requisition->progresses()->create([
                'requisition_id' => $requisition->id,
               // 'determiner_id' => $value,
                'determiner_id' => User::where('email',$value)->first()->id,
                'role' => $key
            ]);
            //   $user=User::find()

        }

        $request->session()->flash('success', 'Requisition sent successfully.');
        return redirect()->route('dashboard');
    }

    public function edit(Requisition $requisition)
    {
        $departments = $this->departments;
        $levels = $this->levels;

        // authorize user to view edit page
        $this->authorize('edit', $requisition);

        return view('requisitions.edit', compact('requisition', 'departments', 'levels'));
    }

    public function update(Request $request)
    {
        $requisition = Requisition::find($request->post('id'));
        $validator = Validator::make($request->all(), [
            'fa_title' => 'required',
            'en_title' => 'required',
            'competency' => 'required',
            'time' => 'required',
            'hiring_type' => 'required',
            'replacement' => 'required_if:hiring_type,0',
            'mission' => 'required',
            'outcome' => 'required',
            'position_count' => 'required',
            'report_to' => 'required',
            'location' => 'required',
            'experience_year' => 'required',
            'field_of_study' => 'required',
            'degree' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $requisition->fa_title = $request->post('fa_title');
        $requisition->en_title = $request->post('en_title');
        $requisition->competency = $request->post('competency');
        $requisition->mission = $request->post('mission');
        $requisition->outcome = $request->post('outcome');
        $requisition->shift = ($request->post('shift') == 0) ? null : $request->post('shift');
        $requisition->position_count = $request->post('position_count');
        $requisition->report_to = $request->post('report_to');
        $requisition->location = $request->post('location');
        $requisition->experience_year = $request->post('experience_year');
        $requisition->field_of_study = $request->post('field_of_study');
        $requisition->degree = $request->post('degree');
        $requisition->is_full_time = $request->post('time');
        $requisition->is_new = $request->post('hiring_type');
        $requisition->replacement = $request->post('replacement');
        $requisition->about = $request->post('about');


        /*  if ($request->filled('is_full_time')) {
              $requisition->is_full_time = 1;
          } else $requisition->is_full_time = 0;
          if ($request->filled('is_new')) {
              $requisition->is_new = 1;
          } else $requisition->is_new = 0;*/

        if (Auth::user()->can('accept', $requisition)) {
            $this->determine($request, $requisition);
        } else $requisition->reset_determiner_progresses();

        $requisition->save();

        $request->session()->flash('success', 'Requisition updated successfully.');
        return redirect()->route('dashboard');
    }

    public function destroy(Requisition $requisition)
    {
        // authorize user to delete requisition
        $this->authorize('destroy', $requisition);

        $requisition->delete();

        Session()->flash('success', 'Requisition deleted successfully.');
        return redirect()->route('dashboard');
    }

    public function determine(Request $request, Requisition $requisition)
    {
        if ($request->post('progress_result') == RequisitionProgress::ACCEPTED_STATUS) {
            $requisition->accept($request->post('determiner_comment'));


        } else $requisition->reject($request->post('determiner_comment'));

        $request->session()->flash('success', 'Requisition updated successfully.');
        return redirect()->route('dashboard');
    }

    public function index()
    {


        $pending = Auth::user()->pending_determiner_requisitions;
        $in_progress = Auth::user()->pending_user_requisitions->merge(Auth::user()->determiner_assigned_requisitions);
        $accepted = Auth::user()->accepted_user_requisitions->merge(Auth::user()->determiner_accepted_requisitions);


        $levels_array = $this->levels;
        $departments = $this->departments;

        return view('panel.dashboard', compact('departments', 'levels_array', 'pending', 'in_progress', 'accepted'));
    }

    public function determiners()
    {
        return User::whereNotIn('id', [Auth::id()/*, User::hr_manager()->id*/])
            ->get(['id', 'email']);
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

    public function getLdapUsers($userPrincipalName)
    {

        // $users = Adldap::search()->users()->get();
        $user = Adldap::search()->users()->findBy('userPrincipalName', $userPrincipalName);

        return $user;
    }

    public function ldapUsers(Request $request)
    {
        $term=$request->input('term') ;
       $users = Adldap::search()->where('userPrincipalName','!=',Auth::user()->email)->whereStartsWith('userPrincipalName',$term)->get();
        $users=  $users->map(function ($item,$key)
       {
         return $item->userPrincipalName [0] ;
       })->toArray() ;

     $result['results']=[] ;
     foreach ($users as $k=>$v)
     {
         $record=[] ;
         $record['id']=$v ;
         $record['text']=$v ;
         $result['results'][]=$record ;
     }
     return json_encode($result) ;
    }


}
