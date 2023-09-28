<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeedr extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'Cach', 'E-cach'
        ];
        foreach ($array as $arr) {
            DB::table('payment_methods')->insert([
                'name'          => $arr,
                'status'        => '0',
            ]);
        }
    }
}
