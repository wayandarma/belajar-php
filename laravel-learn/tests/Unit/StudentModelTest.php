<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

// RefreshDatabase wraps each test in a transaction and rolls back after,
// so tests don't leave records in the database between runs.
uses(TestCase::class, RefreshDatabase::class);

it('has the correct fillable fields', function () {
    $student = new Student;

    expect($student->getFillable())->toBe([
        'name',
        'email',
        'phone',
        'date_of_birth',
        'major',
        'enrollment_year',
        'status',
    ]);
});

it('casts date_of_birth to a Carbon date after retrieval', function () {
    // We save to DB first, then retrieve fresh — this confirms the cast works
    // on hydrated models (not just in-memory instances).
    $student = Student::create([
        'name' => 'Cast Test',
        'email' => 'cast@example.com',
        'major' => 'Physics',
        'enrollment_year' => 2022,
        'status' => 'active',
        'date_of_birth' => '2000-05-15',
    ])->fresh();

    expect($student->date_of_birth)->toBeInstanceOf(Carbon::class)
        ->and($student->date_of_birth->format('Y-m-d'))->toBe('2000-05-15');
});

it('can be created and retrieved from the database', function () {
    $student = Student::create([
        'name' => 'Test Student',
        'email' => 'test@example.com',
        'major' => 'Computer Science',
        'enrollment_year' => 2022,
        'status' => 'active',
    ]);

    expect(Student::find($student->id)->name)->toBe('Test Student');
});
