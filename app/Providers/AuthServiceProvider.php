<?php

namespace App\Providers;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Passport::tokensCan([
        //     'edit' => 'can edit record available',
        //     'create' => 'can add new record',
        //     'delete' => 'can delete record',
        // ]);   

        Passport::routes();

        Passport::tokensExpireIn(now()->addDays(30));

        Passport::refreshTokensExpireIn(now()->addDays(30));

        Passport::personalAccessTokensExpireIn(now()->addDays(30));
    }
}
