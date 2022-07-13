<?php

namespace App\Http\Controllers;

use App\Classes\Determiners;
use App\Classes\StaffHierarchy;
use App\Events\RequisitionChanged;
use App\Events\RequisitionCreated;
use App\Events\RequisitionSent;
use App\Notifications\NewRequisition;
use App\Requisition;
use App\RequisitionSetting;
use App\RequisitionStatus;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Adldap\Laravel\Facades\Adldap;
use Adldap\Laravel\Commands\Import;
use  App\Classes\RequisitionItems;

class RequisitionController extends Controller
{
    public function index()
    {
        //  dd(Requisition::find(2)->unread_notifications->first()->type);
        $pending = Auth::user()->pending_determiner_requisitions;

        $rejected = Auth::user()->determiner_rejected_requisitions->merge(Auth::user()->rejected_user_requisitions);

        $in_progress = Auth::user()->pending_user_requisitions->merge(Auth::user()->determiner_requisitions)
            ->merge(Auth::user()->user_viewable_pending_requisitions);

        $accepted = Auth::user()->accepted_user_requisitions->merge(Auth::user()->determiner_accepted_requisitions)->merge(Auth::user()->user_viewable_accpeted_requisitions);

        $assignment = Auth::user()->user_assigned_to_requisitions->merge(Auth::user()->user_assigned_requisitions)
            ->merge(Auth::user()->user_viewable_assignment)
            ->merge(Auth::user()->determiner_assigned_requisitions)
            ->merge(Auth::user()->assigned_user_requisitions);
        //  ->merge(Auth::user()->user_assignments_do)->merge(Auth::user()->user_request_assignments);

        $closed = Auth::user()->user_closed_requisitions
            ->merge(Auth::user()->determiner_closed_requisitions)
            ->merge(Auth::user()->closed_user_assignment_requisitions)
            ->merge(Auth::user()->user_viewable_closed_requisitions);

        $holding = Auth::user()->holding_user_requisitions
            ->merge(Auth::user()->holding_determiner_requisitions)
            ->merge(Auth::user()->user_viewable_holding_requisitions)
            ->merge(Auth::user()->holding_user_assignment_requisitions);

        // $view = Auth::user()->user_viewable_requisitions;;

        $levels_array = $this->levels;
        $departments = $this->departments;
        $requisition_items = RequisitionItems::getItems();

        return view('panel.dashboard', compact('departments', 'levels_array', 'pending',
            'in_progress', 'accepted', 'assignment', 'rejected', 'closed', 'requisition_items', 'holding'));
    }

    public function create(Request $request)
    {
        $errors = $request->session()->get('errors');
        if (!$errors) {
            session(['termAccepted' => 0]);
        }

        $departments = StaffHierarchy::$departments;
        $drafts = Auth::user()->drafts;
        $form_sections_items = RequisitionSetting::sections();

        return view('requisitions.create', compact('departments', 'drafts', 'form_sections_items'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), RequisitionSetting::validation_rules());
        if ($validator->fails()) {
            session(['termAccepted' => 1]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $requisition = new Requisition();
        $requisition = $requisition->store($request, ['owner_id' => Auth::id()]);

        $determiners = Determiners::ordered($request->post('determiners', []), $requisition);
        $requisition->determiner_id = $determiners[0];
        $requisition->create_determiners($determiners);


        if ($request->file('attachment')) {
            $requisition->store_files($request->file('attachment'));
        }

        $this->send_email_to_determiner($requisition->determiner_id);
        $requisition->save();
        $requisition->create_progress(ADMIN_PRIMARY_PENDING_PRG_STATUS);

        $request->session()->flash('success', 'Requisition sent successfully.');
        return redirect()->route('dashboard');
    }

    public function update(Request $request)
    {
        $requisition = Requisition::find($request->post('id'));

        if ($request->post('only_titles')) {
            return $this->update_only_titles($request, $requisition);
        }

        $validator = Validator::make($request->all(), RequisitionSetting::validation_rules());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $requisition = $requisition->store($request);

        if ($request->file('attachment')) {
            $requisition->store_files($request->file('attachment'));
        }

        if (Auth::user()->can('update_determiners', $requisition)) {
            $determiners = Determiners::ordered($request->post('determiners', []), $requisition);
            $requisition->update_determiners($determiners);
        }

        if (Auth::user()->can('accept', $requisition)) {
            $this->determine($request, $requisition);

        } else $requisition->reset_determiner_progresses();

        $requisition->save();

        $request->session()->flash('success', 'Requisition updated successfully.');
        return redirect()->route('dashboard');
    }

    private function update_only_titles(Request $request, Requisition $requisition)
    {
        $this->validate($request,
            [
                'fa_title' => RequisitionSetting::validation_rules()['fa_title'],
                'en_title' => RequisitionSetting::validation_rules()['en_title'],
            ]
        );
        $requisition = $requisition->store($request);
        $requisition->save();
        $request->session()->flash('success', 'Requisition updated successfully.');
        return redirect()->route('dashboard');
    }

    public function edit(Requisition $requisition)
    {

        $departments = $this->departments;
        $levels = $this->levels;

        // authorize user to view edit page
        $this->authorize('edit', $requisition);
        $form_sections_items = RequisitionSetting::sections();

        return view('requisitions.edit', compact('requisition', 'departments', 'levels', 'form_sections_items'));
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
        // dd($request->all()) ;
        if ($request->post('progress_result') == ACCEPT_ACTION) {
            $requisition->accept($request->post('determiner_comment'));

        } elseif ($request->post('progress_result') == ASSIGN_ACTION) {

            $request->validate([
                'user_id' => 'required'
            ]);
            $requisition->assign($request->post('user_id'), $request->post('assign_type'));

        } elseif ($request->post('progress_result') ==REJECT_ACTION) {
            $requisition->reject($request->post('determiner_comment'));

        } elseif ($request->post('progress_result') == HOLD_ACTION) {
            $requisition->hold();
        } elseif ($request->post('progress_result') == CLOSE_ACTION) {
            $requisition->close();
        } elseif ($request->post('progress_result') == OPEN_ACTION) {
            $requisition->open();
        } elseif ($request->post('progress_result') == FINAL_ACCEPT_ACTION) {
            $requisition->final_accept();
        }
       // event(new RequisitionCreated($requisition,User::find(4)));

        event(new RequisitionChanged($requisition,$request->post('progress_result')));

        $request->session()->flash('success', 'Requisition updated successfully.');


        return redirect()->route('dashboard');
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


    public function send_email_to_determiner($determiner)
    {
        $sender = User::find(Auth::id());
        $recipient = User::find($determiner);
        event(new RequisitionSent($sender, $recipient));

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

    public function getLdapUsers(Request $request)
    {

        $term = $request->input('term');
        $users = Adldap::search()->where('userPrincipalName', '!=', Auth::user()->email)->whereStartsWith('userPrincipalName', $term)->get();
        $users = $users->map(function ($item, $key) {
            return $item->userPrincipalName [0];
        })->toArray();

        $result['results'] = [];
        foreach ($users as $k => $v) {
            $record = [];
            $record['id'] = $v;
            $record['text'] = $v;
            $result['results'][] = $record;
        }
        return json_encode($result);
    }

    public function ldapUsers(Request $request)
    {
        $term = $request->input('term');
        $users = Adldap::search()->where('userPrincipalName', '!=', Auth::user()->email)->whereStartsWith('userPrincipalName', $term)->get()
            ->map(function ($item, $key) {
                return $item->userPrincipalName [0];
            })->toArray();

        return $users;

    }

    public function formatUsers($users)
    {

        $result['results'] = [];
        foreach ($users as $k => $v) {
            $record = [];
            $record['id'] = $v;
            $record['text'] = $v;
            $result['results'][] = $record;
        }
        return json_encode($result);
    }

    public function getLdapEloquentUsers(Request $request)
    {

        $users = array_merge($this->ldapUsers($request), $this->eloquentUsers($request));
        return $this->formatUsers($users);
        //   strtolower
    }

    public function staff(Request $request)
    {

        $users_provider = config('app.users_provider');

        if ($users_provider == 'ldap') {
            return $this->getLdapEloquentUsers($request);
            return $this->getLdapUsers($request);

        } elseif ($users_provider == 'mysql') {

            return $this->mysqlUsers($request);
        }
    }

    public function eloquentUsers(Request $request)
    {
        $term = $request->input('term');

        $users = User::where('email', 'like', "%$term%")->where('email', '!=', Auth::user()->email)->pluck('email')->toArray();

        return $users;

    }

    public function mysqlUsers(Request $request)
    {
        $term = $request->input('term');

        $users = User::where('email', 'like', "%$term%")->where('email', '!=', Auth::user()->email)->get();

        $result['results'] = [];
        foreach ($users as $k => $v) {
            $record = [];
            $record['id'] = $v->email;
            $record['text'] = $v->email;
            $result['results'][] = $record;
        }

        return json_encode($result);

    }


}
