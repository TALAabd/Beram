<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
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
            'body' => '',
            'payload' => '',
            'notification_type_id' => '',
            'from_type' => '',
            'from_id' => ''
        ];
    }
}
