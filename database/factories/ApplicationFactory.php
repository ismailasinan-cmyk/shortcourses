<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ShortCourse;

class ApplicationFactory extends Factory
{
    public function definition()
    {
        return [
            'application_ref' => $this->faker->unique()->uuid,
            'surname' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'gender' => 'Male',
            'date_of_birth' => '2000-01-01',
            'address' => $this->faker->address,
            'state' => 'Lagos',
            'lga' => 'Ikeja',
            'ssce_type' => 'WAEC',
            'ssce_year' => '2020',
            'ssce_exam_number' => '1234567890',
            'ssce_file_path' => 'dummy.pdf',
            'short_course_id' => ShortCourse::factory(),
            'amount' => 50000,
            'payment_status' => 'PENDING',
            'locale' => 'en',
        ];
    }
}
