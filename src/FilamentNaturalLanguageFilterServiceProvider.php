<?php

namespace HayderHatem\FilamentNaturalLanguageFilter;

use Illuminate\Support\ServiceProvider;
use HayderHatem\FilamentNaturalLanguageFilter\Services\ProcessorManager;
use HayderHatem\FilamentNaturalLanguageFilter\Contracts\NaturalLanguageProcessorInterface;

class FilamentNaturalLanguageFilterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/filament-natural-language-filter.php',
            'filament-natural-language-filter'
        );

        $this->app->singleton(
            NaturalLanguageProcessorInterface::class,
            function ($app) {
                return new ProcessorManager();
            }
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-natural-language-filter');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-natural-language-filter');

        // Register the modal globally using Filament render hooks
        if (class_exists(\Filament\Support\Facades\FilamentView::class)) {
            \Filament\Support\Facades\FilamentView::registerRenderHook(
                'panels::body.end',
                fn(): string => view('filament-natural-language-filter::components.help-modal')->render()
            );
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/filament-natural-language-filter.php' => config_path('filament-natural-language-filter.php'),
            ], 'filament-natural-language-filter-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-natural-language-filter'),
            ], 'filament-natural-language-filter-views');

            $this->publishes([
                __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/filament-natural-language-filter'),
            ], 'filament-natural-language-filter-lang');

            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
}
