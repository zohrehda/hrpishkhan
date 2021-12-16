<?php

namespace App\Http\Controllers;

use App\Classes\StaffHierarchy;
use App\Events\RequisitionSent;
use App\Requisition;
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
        $pending = Auth::user()->pending_determiner_requisitions;

        $in_progress = Auth::user()->pending_user_requisitions->merge(Auth::user()->determiner_requisitions);

        $accepted = Auth::user()->accepted_user_requisitions->merge(Auth::user()->determiner_accepted_requisitions)->merge(Auth::user()->user_viewable_accpeted_requisitions);

        $assignment = Auth::user()->user_assigned_to_requisitions->merge(Auth::user()->user_assigned_requisitions)
            ->merge(Auth::user()->user_viewable_assignment)
            ->merge(Auth::user()->determiner_assigned_requisitions)
            ->merge(Auth::user()->assigned_user_requisitions)
        ;
        //  ->merge(Auth::user()->user_assignments_do)->merge(Auth::user()->user_request_assignments);

        $closed = Auth::user()->user_closed_requisitions
            ->merge(Auth::user()->determiner_closed_requisitions)
            ->merge(Auth::user()->closed_user_assignment_requisitions)
            ->merge(Auth::user()->user_viewable_closed_requisitions)
        ;

        $holding = Auth::user()->holding_user_requisitions
            ->merge(Auth::user()->holding_determiner_requisitions)
            ->merge(Auth::user()->user_viewable_holding_requisitions)
            ->merge(Auth::user()->holding_user_assignment_requisitions)
        ;

       // $view = Auth::user()->user_viewable_requisitions;;

        $levels_array = $this->levels;
        $departments = $this->departments;
        $requisition_items = RequisitionItems::getItems();

        return view('panel.dashboard', compact('departments', 'levels_array', 'pending',
            'in_progress', 'accepted', 'assignment', 'closed', 'requisition_items', 'holding' ));
    }

    public function create(Request $request)
    {
        $errors = $request->session()->get('errors');
        if (!$errors) {
            session(['termAccepted' => 0]);
        }

        $departments = StaffHierarchy::$departments;
        $drafts = Auth::user()->drafts;
        $form_sections_items = RequisitionItems::getSections();

        return view('requisitions.create', compact('departments', 'drafts', 'form_sections_items'));
    }

    public function store(Request $request)
    {
       // dd($request->all());
        $validator = Validator::make($request->all(), $this->get_validation_rules());

        if ($validator->fails()) {
            session(['termAccepted' => 1]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $requisition = new Requisition();
        $requisition = $this->set_requisition_items($requisition);
        $requisition->owner_id = Auth::id();
        $requisition->save();

        $determiners = Requisition::getOrderedDeterminers($request->post('determiners', []));
        $requisition->determiner_id = $determiners[0];

        $this->send_email_to_determiner($determiners[0]);

        $requisition = $this->set_requisition_progresses_determiners($requisition, $determiners);
        $requisition->save();

        $requisition->create_progress(RequisitionStatus::ADMIN_PRIMARY_PENDING);
        $request->session()->flash('success', 'Requisition sent successfully.');
        return redirect()->route('dashboard');

    }

    public function update(Request $request)
    {

        $requisition = Requisition::find($request->post('id'));

        $validator = Validator::make($request->all(), $this->get_validation_rules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (Auth::user()->can('update_determiners', $requisition)) {
            $this->update_determiners($requisition);
            $requisition->save();
        }

        if (Auth::user()->can('accept', $requisition)) {
            $this->determine($request, $requisition);

        } else $requisition->reset_determiner_progresses();

        $requisition = $this->set_requisition_items($requisition);
        $requisition->save();

        $request->session()->flash('success', 'Requisition updated successfully.');
        return redirect()->route('dashboard');
    }

    private function update_determiners($requisition)
    {
        $determiners = Requisition::getOrderedDeterminers(request()->post('determiners', []));
     //   dd($determiners);
        unset($determiners[0]);
        $this->delete_previous_determiners($requisition);
        $this->set_requisition_progresses_determiners($requisition, $determiners);
    }

    private function delete_previous_determiners($requisition)
    {
        foreach ($requisition->approval_progresses as $key => $progress) {
            if ($key != 0) {
                $progress->delete();
            }
        }
    }

    private function set_requisition_progresses_determiners($requisition, $determiners)
    {
        foreach ($determiners as $key => $value) {
            $determiner_id = ((config('app.users_provider') == 'ldap')) ? User::where('email', $value)->first()->id : $value;
            $requisition->approval_progresses()->create([
                'requisition_id' => $requisition->id,
                'determiner_id' => $determiner_id,
                'role' => ($determiner_id == User::hrAdmin()->id) ? 'hr_admin' : 'approver']);
        }
        return $requisition;
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

    private function get_validation_rules()
    {
        return array_map(function ($item) {
                return $item['validate_rules'];
            }, RequisitionItems::getItems()) +
            ['competency.*' => 'required|array|min:2'];
    }

    public function send_email_to_determiner($determiner)
    {
        $sender = User::find(Auth::id());
        $recipient = User::find($determiner);
        event(new RequisitionSent($sender, $recipient));

    }

    private function updateOrCreateItems($requisition)
    {
        $items = RequisitionItems::getItems();
        foreach ($items as $name => $value) {
            if (!in_array($name, ['determiners'])) {
                $requisition->$name = request()->post($name);
            }
        }
        return $requisition;
    }

    private function set_requisition_items($requisition)
    {
        $items = RequisitionItems::getItems();
        foreach ($items as $name => $value) {
            if (!in_array($name, ['determiners'])) {
                $requisition->$name = request()->post($name);
            }
        }
        return $requisition;
    }

    public function edit(Requisition $requisition)
    {

        $departments = $this->departments;
        $levels = $this->levels;

        // authorize user to view edit page
        $this->authorize('edit', $requisition);
        $form_sections_items = RequisitionItems::getSections();


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
        if ($request->post('progress_result') == RequisitionStatus::ACCEPTED_STATUS) {

            $requisition->accept($request->post('determiner_comment'));
        //  $requisition->current_approval_progress->determiner_id  ==

        } elseif ($request->post('progress_result') == RequisitionStatus::ASSIGN_STATUS) {

            $request->validate([
                'user_id' => 'required'
            ]);
            $requisition->assign($request->post('user_id'), $request->post('assign_type'));

        } elseif ($request->post('progress_result') == RequisitionStatus::REJECTED_STATUS) {
            $requisition->reject($request->post('determiner_comment'));

        } elseif ($request->post('progress_result') == RequisitionStatus::HOLDING_STATUS) {
            $requisition->hold();
        } elseif ($request->post('progress_result') == RequisitionStatus::CLOSED_STATUS) {
            $requisition->close();
        } elseif ($request->post('progress_result') == RequisitionStatus::OPEN_STATUS) {
            $requisition->open();
        }


        $request->session()->flash('success', 'Requisition updated successfully.');


        return redirect()->route('dashboard');
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

    public function staff(Request $request)
    {
        $users_provider = config('app.users_provider');

        if ($users_provider == 'ldap') {
            return $this->ldapUsers($request);

        } elseif ($users_provider == 'mysql') {

            return $this->mysqlUsers($request);
        }
    }

    public function mysqlUsers(Request $request)
    {
        $term = $request->input('term');

        $users = User::where('email', 'like', "%$term%")->where('email', '!=', Auth::user()->email)->get();

        $result['results'] = [];
        foreach ($users as $k => $v) {
            $record = [];
            $record['id'] = $v->id;
            $record['text'] = $v->email;
            $result['results'][] = $record;
        }

        return json_encode($result);

    }


}
