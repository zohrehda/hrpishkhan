<?php

namespace App\Http\Controllers;

use App\Classes\StaffHierarchy;
use App\Draft;
use App\Events\RequisitionSent;
use App\Requisition;
use App\RequisitionAssignment;
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
use Maatwebsite\Excel\Facades\Excel;
use \App\Extract\StaffInfo;
use  App\Classes\RequisitionItems;

class RequisitionController extends Controller
{
    private $common_validate_rules = [
        'fa_title' => 'required',
        'en_title' => 'required',
        'competency' => 'required',
        'is_full_time' => 'required',
        'is_new' => 'required',
        'replacement' => 'required_if:hiring_type,0',
        'mission' => 'required',
        'outcome' => 'required',
        'position_count' => 'required',
        //   'report_to' => 'required',
        'location' => 'required',
        //  'city' => 'required',
        'direct_manger' => 'required',
        'venture' => 'required',
        'vertical' => 'required',
        'seniority' => 'required',
        'experience_year' => 'required',
        'field_of_study' => 'required',
        'degree' => 'required'];


    private function common_validate_rules()
    {
        return array_map(function ($item) {
            return $item['validate_rules'];
        }, RequisitionItems::getCommonDb());
    }

    private function commonDb($requisition, $request)
    {

        $items = RequisitionItems::getCommonDb();
        foreach ($items as $name => $value) {
            $requisition->$name = $request->post($name);
        }

        /*$requisition->fa_title = $request->post('fa_title');
        $requisition->en_title = $request->post('en_title');
        $requisition->competency = $request->post('competency');
        $requisition->mission = $request->post('mission');
        $requisition->outcome = $request->post('outcome');
        $requisition->shift = ($request->post('shift') == 0) ? null : $request->post('shift');
        $requisition->position_count = $request->post('position_count');
        $requisition->location = $request->post('location');
        $requisition->direct_manger = $request->post('direct_manger');
        $requisition->venture = $request->post('venture');
        $requisition->vertical = $request->post('vertical', 'g');
        $requisition->seniority = $request->post('seniority');
        $requisition->experience_year = $request->post('experience_year');
        $requisition->field_of_study = $request->post('field_of_study');
        $requisition->degree = $request->post('degree');
        $requisition->time = $request->post('time');
        $requisition->is_new = $request->post('is_new');
        $requisition->replacement = $request->post('replacement');
        $requisition->about = $request->post('about');*/

        $intw = $request->post('interviewers');
        if (!$intw) {
            $interviewers = null;
        } else {
            $array = [];
            foreach ($intw as $k => $v) {
                if (!empty($v[0]) || !empty($v[1])) {
                    $array[$k] = $v;
                }
            }
            $interviewers = (count($array) > 0) ? json_encode($array) : null;

        }
        $requisition->interviewers = $interviewers;
        $requisition->competency = json_encode($request->post('competency'));

    }


    public function create(Request $request)
    {

        $errors = $request->session()->get('errors');
        if ($errors == null) {
            session(['termAccepted' => 0]);
        }


        $users = $this->determiners();
        $departments = StaffHierarchy::$departments;
        //    $levels=['']
        $hr_manager_user = User::hr_manager();
        $drafts = Draft::where('user_id', Auth::user()->id)->get();

        $form_sections_items = RequisitionItems::getPartsItems();

        // dd($form_sections_items);

        return view('requisitions.create', compact('users', 'hr_manager_user', 'departments', 'drafts', 'form_sections_items'));
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
        //  dd($request->all());
        $determiners = $request->post('determiners', []);
        $messages = [
            'determiners.*.distinct' => "Can't select same determiner on two or more progresses"
        ];
        $validator = Validator::make($request->all(), [
                'department' => 'required',
                'position' => 'required',
                'determiners.*' => 'distinct',
                'competency.1' => 'required|array|min:2'
            ] + $this->common_validate_rules()
            , $messages);

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
        $hr_admin = [0 => User::hrAdmin()->id];

        $determiners = $request->post('determiners', []);
        $determiners = $hr_admin + $determiners + [100 => User::hrAdmin()->id];

        if (config('app.users_provider') == 'ldap') {
            foreach ($determiners as $d) {
                $this->ImportLdapToModel($d);
            }
        }


        $requisition = new Requisition();
        $this->commonDb($requisition, $request);
        $requisition->department = $request->post('department');
        $requisition->position = $request->post('position');
        $requisition->owner_id = Auth::id();
        $requisition->determiner_id = array_values($determiners)[0];
        if (config('app.users_provider') == 'ldap') {
            $requisition->determiner_id = User::where('email', array_values($determiners)[0])->first()->id;
        }
        $requisition->save();

        $sender = User::find(Auth::id());
        $recipient = User::find(array_values($determiners)[0]);

        if (config('app.users_provider') == 'ldap') {
            $recipient = User::where('email', array_values($determiners)[0])->first();
        }
        $recipient = User::find(array_values($determiners)[0]);

        event(new RequisitionSent($sender, $recipient));

        foreach ($determiners as $key => $value) {
            $requisition->progresses()->create([
                'requisition_id' => $requisition->id,
                // 'determiner_id' => $value,
                'determiner_id' => ((config('app.users_provider') == 'ldap')) ? User::where('email', $value)->first()->id : $value,
                'role' => $key
            ]);
            //   $user=User::find()

        }

        $request->session()->flash('success', 'Requisition sent successfully.');
        return redirect()->route('dashboard');
    }


    public function update(Request $request)
    {
        $requisition = Requisition::find($request->post('id'));
        $validator = Validator::make($request->all(),[
                'competency.1' => 'required|array|min:2'
            ] + $this->common_validate_rules());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->commonDb($requisition, $request);

        if (Auth::user()->can('accept', $requisition)) {
            $this->determine($request, $requisition);
        } else $requisition->reset_determiner_progresses();

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
        $form_sections_items = RequisitionItems::getPartsItems();


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

    public function close(Requisition $requisition)
    {
        $requisition->update([
            'status' => Requisition::CLOSED_STATUS
        ]);
        return redirect()->route('dashboard');
    }

    public function determine(Request $request, Requisition $requisition)
    {
        if ($request->post('progress_result') == RequisitionProgress::ACCEPTED_STATUS) {
            $requisition->accept($request->post('determiner_comment'));


        } elseif ($request->post('progress_result') == RequisitionProgress::ASSIGN_STATUS) {
            $request->validate([
                'user_id' => 'required'
            ]);
            $requisition->assign($request->post('user_id'), $request->post('assign_type'));

        } else $requisition->reject($request->post('determiner_comment'));

        $request->session()->flash('success', 'Requisition updated successfully.');
        return redirect()->route('dashboard');
    }

    public function index()
    {

        $pending = Auth::user()->pending_determiner_requisitions;
        $in_progress = Auth::user()->pending_user_requisitions->merge(Auth::user()->determiner_assigned_requisitions);

        $accepted = Auth::user()->accepted_user_requisitions->merge(Auth::user()->determiner_accepted_requisitions)
            ->merge(Auth::user()->determiner_assignedd_requisitions)->merge(Auth::user()->accepted_user_requisitions);

        $assignment = Auth::user()->user_assigned_to_requisitions->merge(Auth::user()->user_assigned_requisitions);
        //  ->merge(Auth::user()->user_assignments_do)->merge(Auth::user()->user_request_assignments);

        $closed = Auth::user()->user_closed_requisitions->merge(Auth::user()->determiner_closed_requisitions)->merge(Auth::user()->closed_user_assignment_requisitions);

        $levels_array = $this->levels;
        $departments = $this->departments;
        $requisition_items = RequisitionItems::getItems();

        return view('panel.dashboard', compact('departments', 'levels_array', 'pending',
            'in_progress', 'accepted', 'assignment', 'closed', 'requisition_items'));
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
