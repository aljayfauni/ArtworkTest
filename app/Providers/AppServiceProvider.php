<?php

namespace App\Providers;

use App\Enums\StatusEnum;
use App\Models\BomCreation;
use App\Models\PriceIndication;
use App\Models\PriceIndicationItem;
use App\Services\AuditLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
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




        // 1. Log successful logins
        Event::listen(Login::class, function (Login $event) {
            $user = $event->user;

            AuditLogger::activity(
                event: 'user_login',
                description: "{$user->name} logged into the system."
            );

            AuditLogger::audit(
                event: 'user_login',
                auditable: $user,
                metadata: ['email' => $user->email],
                remarks: "{$user->name} signed in successfully."
            );
        });

        // 2. Log user logouts
        Event::listen(Logout::class, function (Logout $event) {
            if (!$event->user) {
                return;
            }

            $user = $event->user;

            AuditLogger::activity(
                event: 'user_logout',
                description: "{$user->name} logged out of the system."
            );

            AuditLogger::audit(
                event: 'user_logout',
                auditable: $user,
                metadata: ['email' => $user->email],
                remarks: "{$user->name} signed out."
            );
        });

        // 3. Log failed login attempts
        Event::listen(Failed::class, function (Failed $event) {
            $email = $event->credentials['email'] ?? 'Unknown';

            AuditLogger::audit(
                event: 'failed_login',
                auditable: $event->user,
                metadata: ['attempted_email' => $email],
                remarks: "Failed login attempt for email: {$email}"
            );
        });

    }
}
