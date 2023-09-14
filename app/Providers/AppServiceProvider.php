<?php

namespace App\Providers;

use Modules\Hotels\Providers\RepositoryServiceProvider;
use Illuminate\Support\ServiceProvider;

use  Modules\Authentication\RepositoryInterface\UserRepositoryInterface;
use Modules\Authentication\Repositories\UserRepository;

use  Modules\Authentication\RepositoryInterface\EmployeeRepositoryInterface;
use Modules\Authentication\Repositories\EmployeeRepository;


use  Modules\Authentication\RepositoryInterface\CustomersRepositoryInterface;
use Modules\Authentication\Repositories\CustomerRepository;

use App\RepositoryInterface\CoreAttributeRepositoryInterface;
use App\Repositories\CoreAttributeRepository;

use App\RepositoryInterface\CoreTermRepositoryInterface;
use App\Repositories\CoreTermRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CoreAttributeRepositoryInterface::class, CoreAttributeRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(CoreTermRepositoryInterface::class, CoreTermRepository::class);
        $this->app->bind(CustomersRepositoryInterface::class, CustomerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
