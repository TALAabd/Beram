<?php

namespace Modules\Resturant\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ResturantFactory extends Factory
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
            'title' => '',
            'slug' => '',
            'content' => '',
            'location_id' => '',
            'address' => '',
            'map_lat' => '',
            'map_lng' => '',
            'map_zoom' => '',
            'is_featured' => '',
            'policy' => '',
            'star_rate' => '',
            'check_in_time' => '',
            'check_out_time' => '',
            'status' => '',
            'user_id' => '',
            'update_user' => '',
            'user_id' => '',
            'location_id' => ''
        ];
    }
}
