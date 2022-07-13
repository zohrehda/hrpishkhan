<?php

namespace App;

use App\Classes\Ldap;
use App\Extract\StaffInfo;
use App\Notifications\NewRequisition;
use Exception;
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
        'name', 'email', 'password', 'objectguid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $appends = ['is_hr_admin'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const ROLE_HR_ADMIN = 'hr_admin';
    const ROLE_USER = 'user';

    /**
     * get Human Resources manager user
     *
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public static function hr_admin_setup()
    {

        if (self::where('role', self::ROLE_HR_ADMIN)->first()) {
            return true;
        }
        return false;
    }


    public static function hr_manager()
    {
        // return User::find(5);
        return User::where('role', 'hr_manager')->first();
    }

    /**
     * get user pending requisitions
     */
    public function pending_user_requisitions()
    {
        return $this->hasMany(Requisition::class, 'owner_id')
            ->where('status', '=', PENDING_STATUS);
    }

    /**
     * get accepted requisitions
     */
    public function accepted_user_requisitions()
    {
        return $this->hasMany(Requisition::class, 'owner_id')
            ->where('status', '=', ACCEPTED_STATUS);
    }

    public function user_viewable_accpeted_requisitions()
    {

        return $this->belongsToMany(Requisition::class, 'requisition_viewers', 'user_id', 'requisition_id')
            ->where('requisitions.status', ACCEPTED_STATUS);

    }


    /**
     * get determiner pending requisitions to accept
     */
    public function pending_determiner_requisitions()
    {
        return $this->hasMany(Requisition::class, 'determiner_id')
            ->where('status', '=', PENDING_STATUS);
    }


    public function user_viewable_pending_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_viewers', 'user_id', 'requisition_id')
            ->where('status', '=', PENDING_STATUS);

    }

    /**
     * get determiner involved requisitions
     */
    public function determiner_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_approval_progresses', 'determiner_id')
            ->where('requisitions.status', '=', PENDING_STATUS)
            ->where('requisitions.determiner_id', '!=', $this->attributes['id']);
    }

    /**
     * get determiner accepted requisitions
     */
    public function determiner_accepted_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_approval_progresses', 'determiner_id')
            ->where('requisitions.status', '=', ACCEPTED_STATUS);
    }

    public function determiner_rejected_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_approval_progresses', 'determiner_id')
            ->where('requisitions.status', '=', REJECTED_STATUS);
    }

    public function rejected_user_requisitions()
    {
        return $this->hasMany(Requisition::class, 'owner_id')
            ->where('status', '=', REJECTED_STATUS);

    }

    public function details()
    {
        // $this->email = 'maryam.delbari@snapp.cab';
        // return StaffInfo::get() ;
        // return [
        //     'name'=>'ff' ,
        //     'email'=>'ff' ,
        // ] ;

        try {
            return StaffInfo::get()->where('email', $this->email)->first() ?? [
                'name' => $this->name,
                'email' => $this->email
            ];
        } catch (Exception $e) {
            return [
                'name' => $this->name,
                'email' => $this->email
            ];
        }


    }

    public static function hr_admin()
    {
        return self::where('role', 'hr_admin')->first();
    }

    public function is_hr_admin()
    {
        return ($this->role == 'hr_admin');
    }

    public function getIsHrAdminAttribute()
    {
        return $this->attributes['is_hr_admin'] = ($this->role == 'hr_admin');

    }


    public function user_assigned_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_assignments', 'from')->where('requisitions.status', '=', ASSIGNED_STATUS);
    }

    public function user_assigned_to_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_assignments', 'to')->where('requisitions.status', '=', ASSIGNED_STATUS);
    }

    public function assigned_user_requisitions()
    {
        return $this->hasMany(Requisition::class, 'owner_id')
            ->where('status', '=', ASSIGNED_STATUS);

    }

    public function determiner_assigned_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_approval_progresses', 'determiner_id')->where('requisitions.status', '=', ASSIGNED_STATUS);

    }

    public function user_viewable_assignment()
    {

        return $this->belongsToMany(Requisition::class, 'requisition_viewers', 'user_id', 'requisition_id')
            ->where('requisitions.status', ASSIGNED_STATUS);

    }


    public function user_assigned_to_assign_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_assignments', 'to')->where('requisitions.status', '=', ASSIGNED_STATUS)
            ->where('type', 'assign');

    }

    public function user_closed_requisitions()
    {
        return $this->hasMany(Requisition::class, 'owner_id')
            ->where('status', '=', CLOSED_STATUS);
    }

    public function determiner_closed_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_approval_progresses', 'determiner_id')
            ->where('requisitions.status', '=', CLOSED_STATUS);
    }

    public function closed_user_assignment_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_assignments', 'to')
            ->where('requisitions.status', '=', CLOSED_STATUS);
    }

    public function user_viewable_closed_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_viewers', 'user_id', 'requisition_id')
            ->where('requisitions.status', '=', CLOSED_STATUS);

    }


    public function holding_user_requisitions()
    {
        return $this->hasMany(Requisition::class, 'owner_id')
            ->where('requisitions.status', HOLDING_STATUS);
    }

    public function holding_determiner_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_approval_progresses', 'determiner_id')
            ->where('requisitions.status', HOLDING_STATUS);
    }

    public function holding_user_assignment_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_assignments', 'to')
            ->where('requisitions.status', '=', HOLDING_STATUS);
    }

    public function user_viewable_holding_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_viewers', 'user_id', 'requisition_id')
            ->where('requisitions.status', HOLDING_STATUS);;

    }

    // get id or email of user
    public static function by_provider($value)
    {
        if (config('app.users_provider') == 'ldap') {
            $ldap = new Ldap();
            $ldap->ImportLdapToModel($value);


        }
        return self::where('email', $value)->first();

        // return self::find($value);
    }

    public function drafts()
    {
        return $this->hasMany(Draft::class, 'user_id');
    }

    public function user_viewable_requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'requisition_viewers', 'user_id', 'requisition_id');

    }


}
