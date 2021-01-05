<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer(
            'actions.partials.form',
            'App\Http\ViewComposers\ActionsForm'
        );
    }
}
