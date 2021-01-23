<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','objectguid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * get Human Resources manager user
     *
     */
    public static function hr_manager()
    {
       // return User::find(5);
         return User::where('role','hr_manager')->first();
    }

    /**
     * get user pending requisitions
     */
    public function pending_user_requisitions()
    {
        return $this->hasMany(Requisition::class, 'owner_id')
            ->where('status', '=', Requisition::PENDING_STATUS);
    }

    /**
     * get accepted requisitions
     */
    public function accepted_user_requisitions()
    {
        return $this->hasMany(Requisition::class, 'owner_id')
            ->where('status', '=', Requisition::ACCEPTED_STATUS);
    }

    /**
     * get determiner pending requisitions to accept
     */
    public function pending_determiner_requisitions()
    {
        return $this->hasMany(Requisition::class, 'determiner_id')
            ->where('status', '=', Requisition::PENDING_STATUS);
    }

    /**
     * get determiner involved requisitions
     */
    public function determiner_assigned_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_progresses', 'determiner_id')
            ->where('requisitions.status', '=', Requisition::PENDING_STATUS)
            ->where('requisitions.determiner_id', '!=', $this->attributes['id']);
    }

    /**
     * get determiner accepted requisitions
     */
    public function determiner_accepted_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_progresses', 'determiner_id')
            ->where('requisitions.status', '=', Requisition::ACCEPTED_STATUS);
    }
}
