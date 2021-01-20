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


        // update progress to accepted status.
        $this->current_progress()->update([
            'status' => RequisitionProgress::ACCEPTED_STATUS,
            'determiner_comment' => $comment
        ]);

        // HR manager is determining.
        // update "requisition" to accepted status.

        //   if (User::hr_manager()->id == Auth::id())
        if ($this->determiners->last()->id == Auth::id()) {
            $this->determiner_id = null;
            $this->status = Requisition::ACCEPTED_STATUS;

            $sender = User::find(Auth::id());
            $recipient = $this->owner;
            event(new RequisitionAccepted($sender, $recipient));


        } else {

            // send the requisition to next determiner.
            $this->determiner_id = $this->current_progress()->determiner_id;



            $sender = User::find(Auth::id());
            $recipient =User::find($this->current_progress()->determiner_id) ;
            event(new RequisitionSent($sender,$recipient));

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
        $recipient = User::find($this->current_progress()->determiner_id) ;
        event(new RequisitionAccepted($sender, $recipient));



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


}
