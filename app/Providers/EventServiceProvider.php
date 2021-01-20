<?php

namespace App\Providers;

use App\Listeners\SendEmailToOwner;
use Illuminate\Auth\Events\Registered;
use App\Events\RequisitionSent;
use App\Listeners\SendEmialToHeader;
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
            SendEmialToHeader::class,
        ],
        RequisitionAccepted::class => [
            SendEmailToOwner::class,
        ]

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
