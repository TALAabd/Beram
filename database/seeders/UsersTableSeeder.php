<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Authentication\Models\Customer;
use Modules\Authentication\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //admin
        DB::table('users')->insert([
            'name'        => 'System',
            'first_name'        => 'System',
            'last_name'         => 'Admin',
            'email'             => 'admin@admin.com',
            'password'          => bcrypt('123456789'),
            'phone'             => '991833807',
            'status'            => true,
            'city'            => 'New York',
            'country'            => 'US',
            'created_at'        => date("Y-m-d H:i:s"),
            'role'        => 'administrator',
        ]);
        $user = User::where('email', 'admin@admin.com')->first();
        $user->save();
        $user->assignRole('administrator');


        //provider
        DB::table('users')->insert([
            'name'        => 'Vendor',
            'first_name'        => 'Vendor',
            'last_name'         => '01',
            'email'             => 'provider@provider.com',
            'password'          => bcrypt('123456789'),
            'phone'             => '991833806',
            'status'            => true,
            'city'            => 'New York',
            'country'            => 'US',
            'created_at'        => date("Y-m-d H:i:s"),
            'role'        => 'provider',
        ]);
        $user = User::where('email', 'provider@provider.com')->first();
        $user->save();
        $user->assignRole('provider');


        //customer
        DB::table('customers')->insert([
            'name'              => 'Customer',
            'first_name'        => 'Customer',
            'last_name'         => '01',
            'email'             => 'customer@customer.com',
            'password'          => bcrypt('123456789'),
            'phone'             => '991833806',
            'status'            => true,
            'city'              => 'New York',
            'country'           => 'US',
            'address'           => 'address',
            'birthday'          => '1999-5-2',
            'gender'            => 'male',
            'zip_code'          => '20202',
            'created_at'        => date("Y-m-d H:i:s"),
            'email_verified_at' => date("Y-m-d H:i:s"),
            'bio'               => 'We\'re designers who have fallen in love with creating spaces for others to reflect, reset, and create. We split our time between two deserts (the Mojave, and the Sonoran). We love the way the heat sinks into our bones, the vibrant sunsets, and the wildlife we get to call our neighbors.'
        ]);
        $user = Customer::where('email', 'customer@customer.com')->first();
        $user->save();
    }
}
