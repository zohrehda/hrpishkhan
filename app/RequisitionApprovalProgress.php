<?php

namespace App;

use App\Classes\StaffHierarchy;
use Illuminate\Database\Eloquent\Model;

class RequisitionApprovalProgress extends Model
{


    protected $table = 'requisition_approval_progresses';
    protected $guarded = [];

    public $timestamps = false;

    protected $appends = ['status', 'role'];

    /**
     * convert status into human friendly string
     */
    public function getStatusAttribute()
    {
        if ($this->attributes['status'] == ACCEPTED_STATUS) {
            return "accepted";
        } elseif ($this->attributes['status'] == PENDING_STATUS) {
            return "pending";
        } else return "rejected";
    }


    /**
     * get progress's user
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'determiner_id');
    }

    /**
     * get progress's requisition
     */
    public function requisition()
    {
        return $this->hasOne(Requisition::class, 'id', 'requisition_id');
    }




}
