<?php

namespace App\Providers;

use App\Enums\StatusEnum;
use App\Models\BomCreation;
use App\Models\PriceIndication;
use App\Models\PriceIndicationItem;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('is-ad-user',function($user){
            return $user->guid && $user->domain;
        });

        Gate::define('is-admin',function($user){
            return in_array('MAINTENANCE', $user->role->access ?? []);
        });

        Gate::define('is-supervisor',function($user){
            return in_array('SUPERVISOR_ACCESS', $user->role->access ?? []);
        });
        Gate::define('is-artist',function($user){
            return in_array('DESIGN', $user->role->access ?? []);
        });
          Gate::define('is-traffic',function($user){
            return in_array('TRAFFIC', $user->role->access ?? []);
        });

        Gate::define('view-all-requests',function($user){
            return $user->role->view_all_requests == 1;
        });

        Gate::define('is-readonly',function($user){
            return $user->role->read_only == 1;
        });

        Gate::define('password-change',function($user){
            return $user->password_reset == 1;
        });

    }
}
