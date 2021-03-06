<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\Weixin\WeixinExtendSocialite@handle',
        ],
        'App\Events\CommentSent' => [
            'App\Listeners\SendCommentNotification',
        ],
        //\SocialiteProviders\Manager\SocialiteWasCalled::class => [
        //    // add your listeners (aka providers) here
        //    'SocialiteProviders\WeixinWeb\WeixinWebExtendSocialite@handle',
        //],

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
