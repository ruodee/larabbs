<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Carbon\Carbon;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
		 \App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
		 \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        'App\Model' => 'App\Policies\ModelPolicy',
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Passport的路由
        Passport::routes();
        //access_token过期时间
       Passport::tokensExpireIn(Carbon::now()->addDays(15));
       //refreshTokens过期时间
       Passport::refreshTokensExpireIn(Carbon::now()->addDays(30)); 
        //Horizon的访问权限
        \Horizon::auth(function($request){
            return \Auth::user()->hasRole('Founder');
            });

    }
}
