<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShortCourseFactory extends Factory
{
    public function definition()
    {
        return [
            'category' => $this->faker->word,
            'course_name' => $this->faker->sentence(3),
            'code' => $this->faker->unique()->bothify('SC-####'),
            'fee' => $this->faker->numberBetween(10000, 100000),
            'duration' => '3 Months',
            'status' => true,
        ];
    }
}
