<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Events\RequisitionSent;
use App\Events\RequisitionAccepted;

class RequisitionAssignment extends Model
{
    protected $fillable = ['requisition_id', 'from', 'to', 'type'];
    public $timestamps = true;


}
