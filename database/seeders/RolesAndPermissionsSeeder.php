<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        //Hotel
        Permission::findOrCreate('hotels_manager');
        Permission::findOrCreate('rooms_manager');
        Permission::findOrCreate('update_rooms_manager');//for provider to update price and image

        //Public
        Permission::findOrCreate('permissions_manager');
        Permission::findOrCreate('roles_manager');
        Permission::findOrCreate('users_manager');
        Permission::findOrCreate('attributes_manager');
        Permission::findOrCreate('terms_manager');

        //Location
        Permission::findOrCreate('countries_manager');
        Permission::findOrCreate('cities_manager');

        //Bookings
        Permission::findOrCreate('hotel_bookings_manager');
        Permission::findOrCreate('get_confirmed_hotel_bookings_manager');
        Permission::findOrCreate('book_room');// for provider booking 

        Permission::findOrCreate('trip_bookings_manager');
        // Permission::findOrCreate('bookings_manager_other');
        Permission::findOrCreate('get_confirmed_trip_bookings_manager');
        Permission::findOrCreate('book_trip');// for provider booking 

        //wallet
        Permission::findOrCreate('wallet_manager');

        //Employees
        // Permission::findOrCreate('employees_manager');
        Permission::findOrCreate('customers_manager');
        Permission::findOrCreate('banners_manager');

        //Trips
        Permission::findOrCreate('trip_features_manager');
        Permission::findOrCreate('trips_manager');

        $this->initProvider();
        $this->initHotelProvider();
        $this->initTripProvider();
        $this->initAdministrator();
    }

    public function initProvider()
    {
        $provider = Role::findOrCreate('provider');
        $provider->givePermissionTo('get_confirmed_hotel_bookings_manager');
        $provider->givePermissionTo('update_rooms_manager');
        $provider->givePermissionTo('book_room');
        $provider->givePermissionTo('get_confirmed_trip_bookings_manager');
        $provider->givePermissionTo('book_trip');
       
    }

    public function initHotelProvider()
    {
        $provider = Role::findOrCreate('Hotel_provider');
        $provider->givePermissionTo('get_confirmed_hotel_bookings_manager');
        $provider->givePermissionTo('update_rooms_manager');
        $provider->givePermissionTo('book_room');

    }

    public function initTripProvider()
    {
        $provider = Role::findOrCreate('Trip_provider');
        $provider->givePermissionTo('get_confirmed_trip_bookings_manager');
        $provider->givePermissionTo('book_trip');
    }

    public function initAdministrator()
    {
        $administrator = Role::findOrCreate('administrator');
        $administrator->givePermissionTo(Permission::all());
    }
}
