<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionProgress extends Model
{
    const PENDING_STATUS = 0;
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;
protected $table='requisition_progresses' ;
    protected $guarded = [];

    public $timestamps = false;

    protected $appends = ['status', 'role'];

    /**
     * convert status into human friendly string
     */
    public function getStatusAttribute()
    {
        if ($this->attributes['status'] == self::ACCEPTED_STATUS) {
            return "accepted";
        } elseif ($this->attributes['status'] == self::PENDING_STATUS) {
            return "pending";
        } else return "rejected";
    }

    /**
     * convert role into human friendly string
     */
    public function getRoleAttribute()
    {
      /*  if ($this->attributes['role'] == 1) {
            return "Hiring manager";
        } elseif ($this->attributes['role'] == 2) {
            return "Head of hiring manager";
        } elseif ($this->attributes['role'] == 3) {
            return "Department manager";
        } elseif ($this->attributes['role'] == 4) {
            return "cxo";
        } else return "HR manager";*/
        
        switch($this->attributes['role'])
        {
           case 1: $result='CPO' ;
            break ;
             case 2: $result='CTO' ;
            break ;
             case 3: $result='Director/VP' ;
            break ;
             case 4: $result='CXO' ;
              break ;
           case 5: $result='HRBP' ;
            break ;
             case 6: $result='HR Director' ;
            break ;
             default:  $result='manager' ;
        }
        
       return $result ;
        
        
        
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
