<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionViewer extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['user_id', 'requisition_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }
}
