<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()   // at least one uppercase + one lowercase
                ->numbers()     // at least one digit
                ->symbols()     // at least one special character
                ->uncompromised(); // not found in known data breaches (HaveIBeenPwned)
        });
    }
}
