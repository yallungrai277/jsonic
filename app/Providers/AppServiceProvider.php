<?php

namespace App\Providers;

use App\Contracts\ConversationStore;
use App\Contracts\NavigationBuilder;
use App\Services\Menu\NavigationMenuBuilder;
use App\Stores\DatabaseConversationStore;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register application services here if needed.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerAppBindings();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);
        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );

        Model::preventAccessingMissingAttributes();
        Model::preventSilentlyDiscardingAttributes();
        Model::preventLazyLoading();
        Model::automaticallyEagerLoadRelationships();
    }

    /**
     * Register application bindings.
     */
    protected function registerAppBindings(): void
    {
        $this->app->singleton(ConversationStore::class, fn () => new DatabaseConversationStore);
        $this->app->singleton(NavigationBuilder::class, fn () => new NavigationMenuBuilder);
    }
}
