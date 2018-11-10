<?php

namespace InWeb\TranslationsParser;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use InWeb\TranslationsParser\Parser;

class TranslationsParserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('translations-parser.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'translations-parser');

        App::bind('translations-parser', function () {
            return new Parser();
        });

        $this->commands(TranslationsParseCommand::class);
    }
}
