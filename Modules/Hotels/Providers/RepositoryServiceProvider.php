<?php


namespace Modules\Hotels\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Hotels\RepositoryInterface\HotelRepositoryInterface;
use Modules\Hotels\Repositories\HotelRepository;
use Modules\Hotels\RepositoryInterface\RoomRepositoryInterface;
use Modules\Hotels\Repositories\RoomRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(HotelRepositoryInterface::class, HotelRepository::class);
        $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
