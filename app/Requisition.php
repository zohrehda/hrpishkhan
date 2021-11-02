<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Events\RequisitionSent;
use App\Events\RequisitionAccepted;


class Requisition extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    const PENDING_STATUS = 0;
    const ACCEPTED_STATUS = 1;
    const ASSIGN_STATUS = 2;
    const CLOSED_STATUS = 3;

    protected $appends = ['updated_at'];

    /**
     * get human friendly updated at
     */
    public function getUpdatedAtAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->attributes['updated_at']))->diffForHumans();
    }

    /**
     * get all progresses for requisition
     */
    public function progresses()
    {
        return $this->hasMany(RequisitionProgress::class, 'requisition_id');
    }

    /**
     * get remaining progresses for requisition
     */
    public function pending_progresses()
    {
        return $this->hasMany(RequisitionProgress::class, 'requisition_id')
            ->where('status', '=', RequisitionProgress::PENDING_STATUS)
            ->orWhere('status', '=', RequisitionProgress::REJECTED_STATUS);
    }

    /**
     * get current progress for requisition
     */
    public function current_progress()
    {
        return $this->pending_progresses()->first();
    }

    /**
     * get accepted progressed for requisition
     */
    public function accepted_progresses()
    {
        return $this->hasMany(RequisitionProgress::class, 'requisition_id')
            ->where('status', '=', RequisitionProgress::ACCEPTED_STATUS);
    }

    /**
     * get all determiners for requisition
     */
    public function determiners()
    {
        return $this->belongsToMany(User::class, 'requisition_progresses', 'requisition_id', 'determiner_id');
    }

    /**
     * get remaining determiners for requisition
     */
    public function pending_determiners()
    {
        return $this->belongsToMany(User::class, 'requisition_progresses', 'requisition_id', 'determiner_id')
            ->Where('status', '=', RequisitionProgress::PENDING_STATUS)
            ->orWhere('status', '=', RequisitionProgress::REJECTED_STATUS);
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

    /**
     * accept requisition
     */
    public function accept($comment = null)
    {

        $c = $this->current_progress()->id;

        // update progress to accepted status.
        $this->current_progress()->update([
            'status' => RequisitionProgress::ACCEPTED_STATUS,
            'determiner_comment' => $comment
        ]);


        // HR manager is determining.
        // update "requisition" to accepted status.
        //   if (User::hr_manager()->id == Auth::id())
        //  if ($this->determiners->last()->id == Auth::id()) {
        if (User::hrAdmin()->id == Auth::id() && $this->progresses()->get()->last()->id == $c) {
            $this->determiner_id = null;
            $this->status = Requisition::ACCEPTED_STATUS;

            $sender = User::find(Auth::id());
            $recipient = $this->owner;
            event(new RequisitionAccepted($sender, $recipient));


        } else {

            // send the requisition to next determiner.
            $this->determiner_id = $this->current_progress()->determiner_id;


            $sender = User::find(Auth::id());
            $recipient = User::find($this->current_progress()->determiner_id);
            event(new RequisitionSent($sender, $recipient));

        }
        $this->save();
    }


    /**
     * reject requisition
     */
    public function reject($comment = null)
    {
        // rejecting progress
        // update progress to rejected status.
        $this->current_progress()->update([
            'status' => RequisitionProgress::REJECTED_STATUS,
            'determiner_comment' => $comment
        ]);

        if ($this->accepted_progresses->isNotEmpty()) {
            // update requisition's last progress to pending
            $this->accepted_progresses->last()->update([
                'status' => RequisitionProgress::PENDING_STATUS,
            ]);
        }

        // send the requisition to last determiner.
        $this->determiner_id = $this->current_progress()->determiner_id;

        $sender = User::find(Auth::id());


        if ($this->progress_status() == 0) {
            $recipient = User::find($this->current_progress()->determiner_id);

            event(new RequisitionSent($sender, $recipient));

        } elseif ($this->progress_status() == 2) {
            $recipient = $this->owner;
            event(new RequisitionRejected($sender, $recipient));
        }


        $this->save();
    }

    /**
     * reset status on every requisition progress status
     */
    public function reset_determiner_progresses()
    {
        // requisition creator editing
        // send requisition to first determiner
        $this->determiner_id = $this->progresses()->first()->determiner_id;
        // update all progresses to pending status.
        $this->progresses()->update([
            'status' => RequisitionProgress::PENDING_STATUS,
            'determiner_comment' => null
        ]);
    }

    public function get_shift()
    {
        $sift = $this->shift;
        switch ($sift) {
            case null :
                $result = '0';
                break;
            case 1 :
                $result = 'morning';
                break;
            case 2 :
                $result = 'midday';
                break;
            case 3 :
                $result = 'evening';
                break;
            case 4 :
                $result = 'night';
                break;
            default:
                $result = 'night';


        }

        return $result;
    }

    public function get_degree()
    {
        $sift = $this->degree;
        switch ($sift) {


            case 1 :
                $result = 'Diploma';
                break;
            case 2 :
                $result = 'B.A/BSc.';
                break;
            case 3 :
                $result = 'M.A/MSc.';
                break;
            case 4 :
                $result = 'PHD';
                break;
            default:
                $result = '0';


        }

        return $result;
    }

    public function get_experience_year()
    {
        $experience_year = $this->experience_year;
        switch ($experience_year) {

            case 1 :
                $result = 'Fresh Graduate';
                break;
            case 2 :
                $result = '1';
                break;
            case 3 :
                $result = '(1-2)';
                break;
            case 4 :
                $result = '(2-4)';
                break;
            case 5 :
                $result = '(4-6)';
                break;
            case 6 :
                $result = '(6-10)';
                break;
            case 7 :
                $result = 'More than 10';
                break;
            default:
                $result = '0';


        }

        return $result;
    }

    public function progress_status()
    {
        $status_array = $this->progresses()->getResults()->map(function ($item) {
            return $item->getOriginal('status');
        })->toArray();

        if (count(array_unique($status_array)) == 1) {
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

        if (Auth::user()->id == User::hrAdmin()->id && $this->assignment_time()) {

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

    public function assignment_time()
    {
        $last = $this->assignments()->latest()->first();

        if ($this->status == self::CLOSED_STATUS) {
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
        if ($this->assignments()->count() > 1) {
            $this->type_do_assignment()->delete();
        }

        $this->update([
            'status' => Requisition::ASSIGN_STATUS
        ]);
        RequisitionAssignment::updateOrCreate([
            'requisition_id' => $this->id,
            'from' => Auth::user()->id,

        ], [
            'to' => $to,
            'type' => $type
        ]);
    }


}
