<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Self_;

class Draft extends Model
{
    //
    protected $fillable = ['user_id', 'name', 'public', 'draft'];
    public $timestamps = false;

    public function get_user_drafts()
    {
       return Draft::where('user_id', Auth::user()->id)->get();
    }
}
