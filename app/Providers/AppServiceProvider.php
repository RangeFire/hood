<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;


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
        Request::setTrustedProxies(
            ['REMOTE_ADDR'], 
            Request::HEADER_X_FORWARDED_FOR
        );

        \App\Models\Project::created(function ($project) {
            \App\Models\ProjectSubscription::create([
                'project_id' => $project->id,
            ]);
            \App\Models\LivechatConfig::create([
                'project_id' => $project->id,
            ]);
            \App\Models\CommunityCenterConfig::create([
                'project_id' => $project->id,
            ]);
        });

    }
}
