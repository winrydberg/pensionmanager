<?php

namespace App\Providers;

use App\Models\Claim;
use App\Models\Issue;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // View::share('notifCount',  Notification::where('user_id', Auth::user()->id)->where('read', false)->count());

        
        View::composer('*', function ($view) {
            $exempted = ['master.login', 'master.forgotpassword'];
            $user = Auth::user();
            if($user != null){
                if (!in_array($view->getName(), $exempted, true)) {
                    $view->with('notifCount', Notification::where('user_id', Auth::user()->id)->where('read', false)->count());
                    $view->with('issueCount', Issue::where('resolved', false)->count());
                }
            }
        });

        //  View::composer('*', function($view){
        //     $view->with('notifCount', Notification::where('user_id', Auth::user()->id)->where('read', false)->count());
        // });
    }
}