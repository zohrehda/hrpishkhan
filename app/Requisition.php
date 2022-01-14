<?php

namespace App;

use Adldap\Laravel\Commands\Import;
use Adldap\Laravel\Facades\Adldap;
use App\Classes\StaffHierarchy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Events\RequisitionSent;
use App\Events\RequisitionAccepted;
use App\Events\RequisitionRejected;


use App\Classes\RequisitionItems;


class Requisition extends Model
{
    use SoftDeletes;

    protected $guarded = [];


    protected $appends = ['updated_at'];

    /**
     * get human friendly updated at
     */
    public function getUpdatedAtAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->attributes['updated_at']))->diffForHumans();
    }

    public function getDepartmentAttribute()
    {
        return RequisitionItems::getItems('department')['options'][$this->attributes['department']] ?? $this->attributes['department'];

    }

    public function getLevelAttribute()
    {
        return StaffHierarchy::$levels[$this->attributes['level']] ?? $this->attributes['level'];
    }

    public function getIsFullTimeAttribute()
    {
        return RequisitionItems::getItems('is_full_time')['radios'][$this->attributes['is_full_time']] ?? $this->attributes['is_full_time'];

    }

    public function getIsNewAttribute()
    {
        return RequisitionItems::getItems('is_new')['radios'][$this->attributes['is_new']] ?? $this->attributes['is_new'];

    }

    public function getShiftAttribute()
    {
        return RequisitionItems::getItems('shift')['data']['options'][$this->attributes['shift']] ?? $this->attributes['shift'];
    }

    public function getDegreeAttribute()
    {
        return RequisitionItems::getItems('degree')['options'][$this->attributes['degree']] ?? $this->attributes['degree'];

    }

    public function getExperienceYearAttribute()
    {
        return RequisitionItems::getItems('experience_year')['options'][$this->attributes['experience_year']] ?? $this->attributes['experience_year'];

    }

    public function getCompetencyAttribute($value)
    {
        return array_map(function ($value) {
            return [
                'text' => $value[0],
                'status' => $value[1]
            ];
        }, json_decode($value, true));
    }

    public function setCompetencyAttribute($value)
    {
        $this->attributes['competency'] = json_encode($value);
    }

    public function getInterviewersAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function setInterviewersAttribute($value)
    {
        $interviewers = null;
        if ($value) {

            $array = [];
            foreach ($value as $k => $v) {
                if (!empty($v[0]) || !empty($v[1])) {
                    $array[$k] = $v;
                }
            }
            $interviewers = (count($array) > 0) ? json_encode($array) : null;
        }

        $this->attributes['interviewers'] = $interviewers;
    }

    public function setDeterminerAttribute($value)
    {
        //   dd($value);
        $determiners = self::getOrderedDeterminers($value);
        $sender = User::find(Auth::id());

        $determiners_id = [];
        foreach ($determiners as $d) {
            $determiners_id[] = User::by_provider($d)->id;
        }
        $determiner_id = $determiners_id[0];
        $recipient = User::find($determiners_id[0]);
        event(new RequisitionSent($sender, $recipient));
        $this->attributes['determiner_id'] = $determiner_id;
    }

    public function setDeterminerIdAttribute($value)
    {
       // $determiner_id = $value;
     // $user=  User::by_provider($value) ;
      //  if (config('app.users_provider') == 'ldap') {
       //     $determiner_id = User::where('email', $value)->first()->id;
       // }
     //  $user=  User::by_provider($value) ;
        $this->attributes['determiner_id'] = $value;

    }

    public function getLabelAttribute()
    {
        $label = '';
        if (Auth::user()->user_viewable_requisitions()->get()->where('id', $this->id)->count()) {
            $label = 'only view';
        }
        if (Auth::user()->user_assigned_to_requisitions()->get()->where('id', $this->id)->count()) {
            $label = 'assignment';
        }

        return $label;
    }

    /**
     * get all approval_progresses for requisition
     */
    public function approval_progresses()
    {
        return $this->hasMany(RequisitionApprovalProgress::class, 'requisition_id');
    }

    /**
     * get remaining approval_progresses for requisition
     */
    public function pending_approval_progresses()
    {
        return $this->hasMany(RequisitionApprovalProgress::class, 'requisition_id')
            ->where('status', '=', RequisitionStatus::PENDING_STATUS)
            ->orWhere('status', '=', RequisitionStatus::REJECTED_STATUS);
    }


    /**
     * get current progress for requisition
     */
    public function current_approval_progress()
    {
        return $this->pending_approval_progresses()->first();
    }

    public function rest_approval_progress()
    {
        return $this->hasMany(RequisitionApprovalProgress::class, 'requisition_id')
            ->where('role', '>', $this->current_approval_progress()->role);
    }


    /**
     * get accepted progressed for requisition
     */
    public function accepted_approval_progresses()
    {
        return $this->hasMany(RequisitionApprovalProgress::class, 'requisition_id')
            ->where('status', '=', RequisitionStatus::ACCEPTED_STATUS);
    }

    /**
     * get all determiners for requisition
     */
    public function determiners()
    {
        return $this->belongsToMany(User::class, 'requisition_approval_progresses', 'requisition_id', 'determiner_id');
    }


    /**
     * get remaining determiners for requisition
     */
    public function pending_determiners()
    {
        return $this->belongsToMany(User::class, 'requisition_approval_progresses', 'requisition_id', 'determiner_id')
            ->Where('status', '=', RequisitionStatus::PENDING_STATUS)
            ->orWhere('status', '=', RequisitionStatus::REJECTED_STATUS);
    }

    /**
     * get current determiner for requisition
     */
    public function current_determiner()
    {
        return $this->pending_determiners()->first();
    }

    /**
     * get requisition's owner(creator)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    public function progresses()
    {
        return $this->hasMany(RequisitionProgress::class, 'requisition_id');
    }

    public function current_progress()
    {
        return $this->progresses()->latest()->first();
    }


    public function open()
    {
        if ($this->current_progress()->status == RequisitionStatus::HOLDING_STATUS) {
            $this->current_progress()->delete();
        }

        $this->update([
            'status' => (in_array($this->current_progress()->status, RequisitionStatus::PENDING_GROUP)) ? RequisitionStatus::PENDING_STATUS : $this->current_progress()->status
        ]);
        $this->save();
    }

    /**
     * accept requisition
     */
    public function accept($comment = null)
    {
        $c = $this->current_approval_progress()->id;
        $this->status = RequisitionStatus::PENDING_STATUS;
       
        $this->current_approval_progress()->update([
            'status' => RequisitionStatus::ACCEPTED_STATUS,
            'determiner_comment' => $comment
        ]);
        $this->current_approval_progress()->save() ;

         if (Auth::user()->is_hr_admin() && $this->approval_progresses()->get()->last()->id == $c) {
            $this->determiner_id = null;
            $this->status = RequisitionStatus::ACCEPTED_STATUS;
            event(new RequisitionAccepted(User::find(Auth::id()), $this->owner));

            $this->create_progress(RequisitionStatus::ACCEPTED_STATUS);

        } else {
            // send the requisition to next determiner.
            $this->determiner_id = $this->current_approval_progress()->determiner_id;
            $sender = User::find(Auth::id());
            $recipient = User::find($this->current_approval_progress()->determiner_id);
            event(new RequisitionSent($sender, $recipient));

            $status = RequisitionStatus::DETERMINERS_PENDING;
            if ($this->determiner_id == User::hr_admin()->id) {
                $status = RequisitionStatus::ADMIN_FINAL_PENDING;
            }
            $this->create_progress($status);

        }
        $this->save();
    }


    public function create_progress($status)
    { //firstOrCreate
        $this->progresses()->create([
            'requisition_id' => $this->id,
            'status' => $status
        ]);
    }


    public function hold()
    {
        $this->update(
            ['status' => RequisitionStatus::HOLDING_STATUS
            ]);
        $this->save();
        $this->create_progress(RequisitionStatus::HOLDING_STATUS);

    }


    public function close()
    {
        $this->update(
            ['status' => RequisitionStatus::CLOSED_STATUS
            ]);
        $this->save();
        $this->create_progress(RequisitionStatus::CLOSED_STATUS);

    }


    /**
     * reject requisition
     */
    public function reject($comment = null)
    {
        // rejecting progress
        // update progress to rejected status.
        $this->current_approval_progress()->update([
            'status' => RequisitionStatus::REJECTED_STATUS,
            'determiner_comment' => $comment
        ]);
        $this->current_approval_progress()->save() ;
        //  if ($this->accepted_approval_progresses()->isNotEmpty()) {
        if ($this->accepted_approval_progresses()->count()) {
            // update requisition's last progress to pending
            $this->accepted_approval_progresses->last()->update([
                'status' => RequisitionStatus::PENDING_STATUS,
            ]);
        }

        // send the requisition to last determiner.
        $this->determiner_id = $this->current_approval_progress()->determiner_id;

        $sender = User::find(Auth::id());

        if($this->approval_progresses()->first()->status==RequisitionStatus::REJECTED_STATUS  ){
            $this->update([
                'status'=>RequisitionStatus::REJECTED_STATUS ,
            ]) ;
        }

        if($this->status==RequisitionStatus::REJECTED_STATUS){
            $recipient = $this->owner;
            
            event(new RequisitionRejected($sender, $recipient));

        }else{
            $recipient = User::find($this->current_approval_progress()->determiner_id);
   event(new RequisitionSent($sender, $recipient));
        }

 
        if ($this->current_progress()->status != RequisitionStatus::ADMIN_PRIMARY_PENDING)
            $this->current_progress()->delete();

        $this->save();
    }

    public function current_progress_status()
    {
        $status = RequisitionStatus::DETERMINERS_PENDING;
        if ($this->is_first_approval_progress()) {
            $status = RequisitionStatus::ADMIN_PRIMARY_PENDING;

        } elseif ($this->is_last_approval_progress()) {
            $status = RequisitionStatus::ADMIN_FINAL_PENDING;
        }
        return $status;
    }


    public function is_first_approval_progress()
    {
        if ($this->approval_progresses()->first()->id == $this->current_approval_progress()->id
            && $this->current_determiner()->id == User::hr_admin()->id) {
            return true;
        }

        return false;

    }

    public function is_last_approval_progress()
    {
        if ($this->approval_progresses()->get()->last()->id == $this->current_approval_progress()->id
            && $this->current_determiner()->id == User::hr_admin()->id) {
            return true;
        }
        return false;
    }


    /**
     * reset status on every requisition progress status
     */
    public function reset_determiner_progresses()
    {

        $this->determiner_id = $this->approval_progresses()->first()->determiner_id;
        // update all approval_progresses to pending status.
        $this->approval_progresses()->update([
            'status' => RequisitionStatus::PENDING_STATUS,
            'determiner_comment' => null
        ]);
        $this->approval_progresses()->save() ;
     }


    public function approval_progress_status()
    {
        
        $status_array = $this->approval_progresses()->getResults()->map(function ($item) {
            return $item->getOriginal('status');
        })->toArray();



        if (count(array_unique($status_array)) == RequisitionStatus::ACCEPTED_STATUS) {
            $status = array_unique($status_array) [0];

        } else {
            $status = 0;
        }
        return $status;
    }


    public function assignments()
    {
        return $this->hasMany(RequisitionAssignment::class, 'requisition_id');
    }

    public function assignment_type()
    {
        $assignment = $this->hasMany(RequisitionAssignment::class, 'requisition_id')->first();
        if ($assignment) {
            return $assignment->type;
        }
        return null;

    }

    public function type_assign_assignment()
    {
        return $this->hasOne(RequisitionAssignment::class, 'requisition_id')->where('type', 'assign');
    }

    public function type_do_assignment()
    {
        return $this->hasOne(RequisitionAssignment::class, 'requisition_id')->where('type', 'do');
    }

    public function prettyAssignments()
    {
        $text = '';

        if (Auth::user()->id == User::hr_admin()->id && $this->assignment_time()) {

            $text .= "<div class='text-danger'>" . 'Time:' . $this->assignment_time() . "</div><br>";
        }

        foreach ($this->assignments as $asgm) {

            $user = (User::find($asgm->to)->id == Auth::user()->id) ? 'you' : User::find($asgm->to)->name;
            $type_text = ($asgm->type == 'assign') ? 'assign' : 'do';
            $text .= User::find($asgm->from)->name . ' assigned to ' . $user . ' to ' . $type_text . "<br>";
        }
        return $text;
    }

    public function assigned_to_user()
    {
        return $this->belongsToMany(User::class, 'requisition_assignments', 'requisition_id', 'to')
            ->where('requisition_assignments.from', Auth::user()->id)->first();;
    }

    public function user_assigned()
    {
        return $this->belongsToMany(User::class, 'requisition_assignments', 'requisition_id', 'to')
            ;
    }

    public function assignment_time()
    {
        $last = $this->assignments()->latest()->first();

        if ($this->status == RequisitionStatus::CLOSED_STATUS) {
            return Carbon::parse($this->getOriginal('updated_at'))->longAbsoluteDiffForHumans(Carbon::parse($last->updated_at));
        }
        if (!$last) {
            return false;
        }
        return Carbon::createFromTimeStamp(strtotime($last->updated_at))->diffForHumans();
    }

    public function assign($to, $type)
    {
        // RequisitionAssignment::where('from', Auth::user()->id)->delete();
        // $this->auth_user_assignment_assigned()->delete();
        $to=User::by_provider($to) ;
        
        if ($this->assignments()->count() > 1) {
            $this->type_do_assignment()->delete();
        }
        $this->create_progress(RequisitionStatus::ASSIGN_STATUS);
        $this->update([
            'status' => RequisitionStatus::ASSIGN_STATUS
        ]);
        RequisitionAssignment::updateOrCreate([
            'requisition_id' => $this->id,
            'from' => Auth::user()->id,

        ], [
            'to' => $to->id,
            'type' => $type
        ]);
    }

    public static function getOrderedDeterminers($value)
    {
        $value = ($value) ?: [];

        if ($value && $value[0] != User::hr_admin()->id) {
            array_unshift($value, User::hr_admin()->id);
        }

        if (last($value) != User::hr_admin()->id) {
            $value[] = User::hr_admin()->id;
        }

        return $value;
    }


    public function approver_determiners()
    {
        return $this->belongsToMany(User::class, 'requisition_approval_progresses', 'requisition_id', 'determiner_id')
            ->where('requisition_approval_progresses.type', RequisitionStatus::DETERMINERS_PENDING);

    }

    public function holding_requisitions()
    {
        return $this->where('status', RequisitionStatus::HOLDING_STATUS);
    }

    public function viewers()
    {
        return $this->belongsToMany(User::class, 'requisition_viewers', 'requisition_id', 'user_id');

    }

    public function requisition_viewers()
    {
        return $this->hasMany(RequisitionViewer::class, 'requisition_id');
    }


}
