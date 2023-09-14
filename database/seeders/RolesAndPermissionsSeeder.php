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
        Permission::findOrCreate('bookings_manager');
        Permission::findOrCreate('bookings_manager_other');

        //Employees
        Permission::findOrCreate('employees_manager');

        Permission::findOrCreate('resturants_manager');
        Permission::findOrCreate('tables_manager');
        Permission::findOrCreate('menus_manager');
        Permission::findOrCreate('meals_manager');
        Permission::findOrCreate('customers_manager');
        Permission::findOrCreate('banners_manager');

        $this->initProvider();
        $this->initAdministrator();
    }

    public function initProvider()
    {
        $provider = Role::findOrCreate('provider');
        $provider->givePermissionTo('hotels_manager');
        $provider->givePermissionTo('rooms_manager');
        $provider->givePermissionTo('bookings_manager');
        $provider->givePermissionTo('employees_manager');
        $provider->givePermissionTo('resturants_manager');
        $provider->givePermissionTo('menus_manager');
        $provider->givePermissionTo('meals_manager');
        $provider->givePermissionTo('tables_manager');
    }

    public function initAdministrator()
    {
        $administrator = Role::findOrCreate('administrator');
        $administrator->givePermissionTo(Permission::all());
    }
}
