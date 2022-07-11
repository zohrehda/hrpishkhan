<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DraftCategory extends Model
{
    //
    protected $table = 'draft_categories';
    protected $fillable = ['name', 'user_id'];
    public $timestamps = false;

    public function drafts()
    {
        return $this->hasMany(Draft::class,'cat_id');
    }

}
