<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LandingBuilderProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $files = [
            'input', 'file', 'switch', 'addable-text-input', 'addable-file-input', 'textarea', 'make-button', 'select', 'addable-accordions', 'accordion',
            'searchable-course', 'searchable-user', 'video-content', 'icons-select', 'addable-search-course', 'searchable-meeting-package',
        ];

        foreach ($files as $file) {
            Blade::component("landingBuilder.admin.atoms.{$file}", "landingBuilder-{$file}");
        }
    }
}
