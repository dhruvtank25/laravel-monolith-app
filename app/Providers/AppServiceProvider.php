<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if ($this->app->isLocal()) {
        //     $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        // }
        
        // Register User Observer
        User::observe(UserObserver::class);

        // Default title when not set (For all view)
        $title       = '';
        $categories  = array();
        if (Schema::hasTable('categories')) {
            // Share categories data on all views
            $categories  = Category::all();
        }
        view()->share(['categories' => $categories, 'title' => $title]);

        Relation::morphMap([
            'user'        => 'App\Models\User',
            'appointment' => 'App\Models\Appointment'
        ]);
    }
}
