<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'story_id' => '',
            'story_type' => '',
            'description' => ''
        ];
    }
}
