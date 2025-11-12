<?php

namespace DrPshtiwan\LivewireAsyncSelect;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AsyncSelectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/async-select.php', 'async-select');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'async-select');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'async-select');

        if (class_exists(Livewire::class)) {
            Livewire::component('async-select', \DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect::class);
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                \DrPshtiwan\LivewireAsyncSelect\Console\GenerateInternalSecretCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../config/async-select.php' => config_path('async-select.php'),
        ], 'async-select-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/async-select'),
        ], 'async-select-views');

        $this->publishes([
            __DIR__.'/../dist/async-select.css' => public_path('vendor/async-select/async-select.css'),
        ], 'async-select-assets');

        $this->publishes([
            __DIR__.'/../resources/lang' => $this->app->langPath('vendor/async-select'),
        ], 'async-select-lang');

        $this->registerBladeDirectives();
    }

    protected function registerBladeDirectives(): void
    {
        \Illuminate\Support\Facades\Blade::directive('asyncSelectStyles', function () {
            return "<?php echo '<link rel=\"stylesheet\" href=\"' . asset('vendor/async-select/async-select.css') . '\">'; ?>";
        });
    }

    public static function getInternalAuthMiddlewareClass(): string
    {
        return \DrPshtiwan\LivewireAsyncSelect\Http\Middleware\InternalAuthenticate::class;
    }
}
