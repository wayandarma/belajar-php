<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the student dashboard', function () {
    Student::factory()->count(3)->create();

    $response = $this->get(route('students.index'));

    $response->assertSuccessful()
        ->assertSee('Student Dashboard')
        ->assertSee('Student records');
});

it('stores a student and redirects to the detail page', function () {
    $payload = [
        'name' => 'Alya Prameswari',
        'email' => 'alya.prameswari@example.test',
        'phone' => '+62 812 0000 0000',
        'date_of_birth' => '2004-05-15',
        'major' => 'Computer Science',
        'enrollment_year' => 2024,
        'status' => 'active',
    ];

    $response = $this->post(route('students.store'), $payload);

    $student = Student::query()
        ->where('email', $payload['email'])
        ->firstOrFail();

    $response->assertRedirect(route('students.show', $student))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('students', [
        'email' => $payload['email'],
        'major' => $payload['major'],
        'status' => $payload['status'],
    ]);
});

it('validates required fields when storing a student', function () {
    $response = $this
        ->from(route('students.create'))
        ->post(route('students.store'), []);

    $response->assertRedirect(route('students.create'))
        ->assertInvalid(['name', 'email', 'major', 'enrollment_year', 'status']);
});

it('updates a student while allowing the current email address', function () {
    $student = Student::factory()->create([
        'email' => 'kept-email@example.test',
        'status' => 'inactive',
    ]);

    $response = $this->put(route('students.update', $student), [
        'name' => 'Updated Student',
        'email' => 'kept-email@example.test',
        'phone' => '+62 811 9999 8888',
        'date_of_birth' => '2003-08-19',
        'major' => 'Information Systems',
        'enrollment_year' => 2023,
        'status' => 'active',
    ]);

    $response->assertRedirect(route('students.show', $student))
        ->assertValid()
        ->assertSessionHas('success');

    $student->refresh();

    expect($student->name)->toBe('Updated Student')
        ->and($student->email)->toBe('kept-email@example.test')
        ->and($student->status)->toBe('active');
});

it('deletes a student and redirects back to the directory', function () {
    $student = Student::factory()->create();

    $response = $this->delete(route('students.destroy', $student));

    $response->assertRedirect(route('students.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('students', [
        'id' => $student->id,
    ]);
});
