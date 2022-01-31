<?php

namespace App\Providers;

use App\Events\RequisitionAccepted;
use App\Events\RequisitionRejected;
use App\Events\RequisitionSent;
use App\Events\RequisitionSubmitted;
use App\Listeners\SendAcceptedEmail;
use App\Listeners\SendNewEmail;
use App\Listeners\SendRejectedEmail;
use App\Listeners\SendSubmittedEmail;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RequisitionSent::class => [
            SendNewEmail::class,
        ],
        RequisitionAccepted::class => [
            SendAcceptedEmail::class,
        ],

        RequisitionRejected::class => [
            SendRejectedEmail::class,
        ],

        RequisitionSubmitted::class => [
            SendSubmittedEmail::class,
        ],


    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
