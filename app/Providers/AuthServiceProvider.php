<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\TeamResource;
use App\Models\User;
use App\Policies\TeamPolicy;
use App\Policies\TeamResourcePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        TeamResource::class => TeamResourcePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register policies
        $this->registerPolicies();

        // Define gates for user permissions
        Gate::define('update-team', function (User $user, Team $team) {
            return $user->ownsTeam($team);
        });

        Gate::define('manage-team-members', function (User $user, Team $team) {
            return $user->ownsTeam($team) || $user->hasTeamRole($team, 'admin');
        });

        Gate::define('invite-to-team', function (User $user, Team $team) {
            return $user->ownsTeam($team) || $user->hasTeamRole($team, 'admin');
        });

        Gate::define('remove-team-member', function (User $user, Team $team) {
            return $user->ownsTeam($team) || $user->hasTeamRole($team, 'admin');
        });

        Gate::define('update-team-member-role', function (User $user, Team $team) {
            return $user->ownsTeam($team);
        });
    }
}