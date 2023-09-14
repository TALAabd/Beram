<?php

namespace Modules\Resturant\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => '',
            'description' => '',
            'price' => '',
            'menu_type_id' => '',
            'menu_type_id' => ''
        ];
    }
}
