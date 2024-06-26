<?php

namespace App\Providers;

use App\Http\ViewComposers\Admin\AdminSyllabusPageViewComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('voyager::syllabuses.edit-add', AdminSyllabusPageViewComposer::class);
    }
}
