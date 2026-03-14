<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Create 30 students with random data using the factory
        Student::factory()->count(30)->create();
    }
}
