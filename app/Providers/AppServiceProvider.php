<?php

namespace App\Providers;

use App\Composers\MenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        View::composer(['layouts.app'], MenuComposer::class);
    }
}
