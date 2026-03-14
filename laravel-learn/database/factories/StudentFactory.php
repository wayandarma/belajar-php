<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        // fake() generates realistic-looking random data
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'date_of_birth' => fake()->optional()->dateTimeBetween('-30 years', '-17 years')?->format('Y-m-d'),
            'major' => fake()->randomElement([
                'Computer Science',
                'Information Systems',
                'Mathematics',
                'Physics',
                'Engineering',
                'Biology',
                'Economics',
                'Psychology',
            ]),
            'enrollment_year' => fake()->numberBetween(2018, (int) now()->format('Y')),
            'status' => fake()->randomElement(['active', 'active', 'active', 'inactive']),
        ];
    }

    /** State: create an active student */
    public function active(): static
    {
        return $this->state(['status' => 'active']);
    }

    /** State: create an inactive student */
    public function inactive(): static
    {
        return $this->state(['status' => 'inactive']);
    }
}
