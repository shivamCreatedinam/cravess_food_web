<?php

namespace App\Providers;

use App\Interfaces\CommonServiceInterface;
use App\Interfaces\UserInterface;
use App\Models\User;
use App\Repositories\CommonServiceRepository;
use App\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(UserInterface::class, function () {
            return new EloquentUserRepository(new User());
        });
        $this->app->singleton(CommonServiceInterface::class, CommonServiceRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
