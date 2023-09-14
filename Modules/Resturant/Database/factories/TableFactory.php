<?php

namespace Modules\Resturant\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => '',
            'content' => '',
            'price' => '',
            'number' => '',
            'size' => '',
            'status' => '',
            'create_user' => '',
            'update_user' => '',
            'resturant_id' => '',
            'resturant_id' => ''
        ];
    }
}
