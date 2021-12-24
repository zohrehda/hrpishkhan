<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionProgress extends Model
{
    //
    protected $table = 'requisition_progresses';
    protected $fillable = ['requisition_id', 'status'];
    public $timestamps = false;


    public function requisition()
    {
        return $this->hasOne(Requisition::class, 'id', 'requisition_id');
    }
}
