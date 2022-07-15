<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    //
    protected $guarded = [];
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return url('storage/attachments/'.$this->attributes['name']);
    }

}
