<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        date_default_timezone_set('Asia/Jakarta');
        Relation::morphMap([
            'customers'                     => 'App\Models\Customer',
            'users'                         => 'App\Models\User',
            'invoices'                      => 'App\Models\Invoice',
            'documentations'                => 'App\Models\Documentation',
            'andalalins'                    => 'App\Models\Andalalin', 
        ]);
    }
}
