# Student Dashboard CRUD Implementation Plan

> **For agentic workers:** REQUIRED: Use superpowers:subagent-driven-development (if subagents available) or superpowers:executing-plans to implement this plan. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a fully-functioning, beautifully-styled student management dashboard with CRUD operations using Laravel 12, Blade templating, and Bootstrap 5 CDN.

**Architecture:** Route::resource() registers all 7 named routes pointing to StudentController. Route Model Binding resolves `Student $student` automatically in controller methods. Four separate Blade views (index, create, show, edit) extend a shared dashboard layout. Form Requests validate input. Eloquent handles all DB queries.

**Tech Stack:** Laravel 12, PHP 8.5, Pest 4, SQLite (dev), Bootstrap 5.3 (CDN), Blade templating.

---

## Chunk 1: Foundation — Migration, Model, Routes, Form Requests

### Task 1: Replace manual routes with Route::resource()

**Files:**
- Modify: `routes/web.php`

- [ ] **Step 1: Write a failing feature test that verifies the students routes exist**

Create `tests/Feature/StudentRoutesTest.php`:

```php
<?php

it('has a students index route', function () {
    expect(route('students.index'))->toBe(url('/students'));
});

it('has a students create route', function () {
    expect(route('students.create'))->toBe(url('/students/create'));
});

it('has a students store route', function () {
    expect(route('students.store'))->toBe(url('/students'));
});
```

- [ ] **Step 2: Run the test to confirm it fails**

```bash
php artisan test --compact --filter=StudentRoutesTest
```

Expected: FAIL — `Route [students.index] not defined`

- [ ] **Step 3: Replace the manual student routes in `routes/web.php`**

Replace lines 13–19 (the 7 manual `/students` routes) with:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', [HelloController::class, 'hello']);

Route::resource('students', StudentController::class);
```

- [ ] **Step 4: Run the test to confirm it passes**

```bash
php artisan test --compact --filter=StudentRoutesTest
```

Expected: 3 PASSED

- [ ] **Step 5: Run Pint then commit**

```bash
vendor/bin/pint --dirty --format agent
git add routes/web.php tests/Feature/StudentRoutesTest.php
git commit -m "feat: replace manual student routes with Route::resource()"
```

---

### Task 2: Create the students migration

**Files:**
- Create: `database/migrations/YYYY_MM_DD_HHMMSS_create_students_table.php`

- [ ] **Step 1: Generate the migration file**

```bash
php artisan make:migration create_students_table --no-interaction
```

Expected output: `Created Migration: YYYY_MM_DD_HHMMSS_create_students_table`

- [ ] **Step 2: Open the generated file (in `database/migrations/`) and replace its contents**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the students table.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('major', 100);
            $table->unsignedSmallInteger('enrollment_year');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Drop the students table.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
```

- [ ] **Step 3: Run the migration**

```bash
php artisan migrate --no-interaction
```

Expected output: `Running migrations... DONE`

- [ ] **Step 4: Verify the table was created**

```bash
php artisan tinker --execute "echo Schema::hasTable('students') ? 'students table exists' : 'MISSING';"
```

Expected: `students table exists`

- [ ] **Step 5: Run Pint then commit**

```bash
vendor/bin/pint --dirty --format agent
git add database/migrations/
git commit -m "feat: add students migration"
```

---

### Task 3: Create the Student model

**Files:**
- Create: `app/Models/Student.php`

- [ ] **Step 1: Generate the model**

```bash
php artisan make:model Student --no-interaction
```

Expected output: `Model [app/Models/Student.php] created successfully.`

- [ ] **Step 2: Replace the generated model's contents**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'major',
        'enrollment_year',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }
}
```

- [ ] **Step 3: Write a unit test for the model fillable and casts**

Create `tests/Unit/StudentModelTest.php`:

```php
<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

// RefreshDatabase wraps each test in a transaction and rolls back after,
// so tests don't leave records in the database between runs.
uses(RefreshDatabase::class);

it('has the correct fillable fields', function () {
    $student = new Student();

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
        'name'            => 'Cast Test',
        'email'           => 'cast@example.com',
        'major'           => 'Physics',
        'enrollment_year' => 2022,
        'status'          => 'active',
        'date_of_birth'   => '2000-05-15',
    ])->fresh();

    expect($student->date_of_birth)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($student->date_of_birth->format('Y-m-d'))->toBe('2000-05-15');
});

it('can be created and retrieved from the database', function () {
    $student = Student::create([
        'name'            => 'Test Student',
        'email'           => 'test@example.com',
        'major'           => 'Computer Science',
        'enrollment_year' => 2022,
        'status'          => 'active',
    ]);

    expect(Student::find($student->id)->name)->toBe('Test Student');
});
```

- [ ] **Step 4: Run the unit tests**

```bash
php artisan test --compact --filter=StudentModelTest
```

Expected: 3 PASSED

- [ ] **Step 5: Run Pint then commit**

```bash
vendor/bin/pint --dirty --format agent
git add app/Models/Student.php tests/Unit/StudentModelTest.php
git commit -m "feat: add Student model with fillable and casts"
```

---

### Task 4: Create Form Request classes

**Files:**
- Create: `app/Http/Requests/StoreStudentRequest.php`
- Create: `app/Http/Requests/UpdateStudentRequest.php`

- [ ] **Step 1: Generate both Form Request files**

```bash
php artisan make:request StoreStudentRequest --no-interaction
php artisan make:request UpdateStudentRequest --no-interaction
```

Expected: Both files created in `app/Http/Requests/`

- [ ] **Step 2: Replace the contents of `StoreStudentRequest.php`**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Anyone can submit this form (no auth required for this learning project).
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', Rule::unique('students', 'email')],
            'phone'           => ['nullable', 'string', 'max:20'],
            'date_of_birth'   => ['nullable', 'date'],
            'major'           => ['required', 'string', 'max:100'],
            'enrollment_year' => ['required', 'integer', 'min:2000', 'max:2099'],
            'status'          => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
```

- [ ] **Step 3: Replace the contents of `UpdateStudentRequest.php`**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:100'],
            // ignore() excludes the current student from the unique check
            'email'           => ['required', 'email', Rule::unique('students', 'email')->ignore($this->route('student'))],
            'phone'           => ['nullable', 'string', 'max:20'],
            'date_of_birth'   => ['nullable', 'date'],
            'major'           => ['required', 'string', 'max:100'],
            'enrollment_year' => ['required', 'integer', 'min:2000', 'max:2099'],
            'status'          => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
```

- [ ] **Step 4: Write a feature test to confirm validation rules fire**

Create `tests/Feature/StudentValidationTest.php`:

```php
<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('rejects a store request with missing required fields', function () {
    $response = $this->post(route('students.store'), []);

    $response->assertSessionHasErrors(['name', 'email', 'major', 'enrollment_year', 'status']);
});

it('rejects a store request with a duplicate email', function () {
    Student::create([
        'name'            => 'Existing',
        'email'           => 'taken@example.com',
        'major'           => 'Physics',
        'enrollment_year' => 2021,
        'status'          => 'active',
    ]);

    $response = $this->post(route('students.store'), [
        'name'            => 'New Student',
        'email'           => 'taken@example.com',
        'major'           => 'Math',
        'enrollment_year' => 2022,
        'status'          => 'active',
    ]);

    $response->assertSessionHasErrors(['email']);
});

it('allows updating a student with their own email', function () {
    $student = Student::create([
        'name'            => 'Budi',
        'email'           => 'budi@example.com',
        'major'           => 'CS',
        'enrollment_year' => 2022,
        'status'          => 'active',
    ]);

    $response = $this->put(route('students.update', $student), [
        'name'            => 'Budi Updated',
        'email'           => 'budi@example.com', // same email — should be allowed
        'major'           => 'CS',
        'enrollment_year' => 2022,
        'status'          => 'active',
    ]);

    $response->assertSessionHasNoErrors();
});
```

- [ ] **Step 5: Run the validation tests**

```bash
php artisan test --compact --filter=StudentValidationTest
```

Expected: 3 PASSED

> **Note:** These tests will currently fail because the controller methods are empty (they return nothing). That's expected at this stage — the tests will pass after Task 5 fills in the controller. When you run them now, expect a failure like: `Expected response status 302 but received 200` or a blank response error — that's the controller stub returning nothing, not a test logic problem.

- [ ] **Step 6: Run Pint then commit**

```bash
vendor/bin/pint --dirty --format agent
git add app/Http/Requests/ tests/Feature/StudentValidationTest.php
git commit -m "feat: add StoreStudentRequest and UpdateStudentRequest with validation rules"
```

---

## Chunk 2: Controller Implementation

### Task 5: Implement all 7 StudentController methods

**Files:**
- Modify: `app/Http/Controllers/StudentController.php`

- [ ] **Step 1: Write failing feature tests for all controller actions**

Create `tests/Feature/StudentControllerTest.php`:

```php
<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the student list', function () {
    $this->get(route('students.index'))->assertStatus(200);
});

it('shows the create form', function () {
    $this->get(route('students.create'))->assertStatus(200);
});

it('stores a new student and redirects to index', function () {
    $response = $this->post(route('students.store'), [
        'name'            => 'Sari Andini',
        'email'           => 'sari@example.com',
        'major'           => 'Mathematics',
        'enrollment_year' => 2023,
        'status'          => 'active',
    ]);

    $response->assertRedirect(route('students.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('students', ['email' => 'sari@example.com']);
});

it('shows a student detail page', function () {
    // Student::factory() uses the factory we build in Chunk 5 — it generates
    // realistic fake data so we don't need to manually specify every field.
    $student = Student::factory()->create();

    $this->get(route('students.show', $student))->assertStatus(200);
});

it('shows the edit form pre-filled', function () {
    $student = Student::factory()->create();

    $this->get(route('students.edit', $student))->assertStatus(200);
});

it('updates a student and redirects to index', function () {
    $student = Student::factory()->create();

    $response = $this->put(route('students.update', $student), [
        'name'            => 'Updated Name',
        'email'           => $student->email, // keep same email (no unique conflict)
        'major'           => 'Physics',
        'enrollment_year' => 2021,
        'status'          => 'inactive',
    ]);

    $response->assertRedirect(route('students.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('students', ['name' => 'Updated Name', 'status' => 'inactive']);
});

it('deletes a student and redirects to index', function () {
    $student = Student::factory()->create();

    $response = $this->delete(route('students.destroy', $student));

    $response->assertRedirect(route('students.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('students', ['id' => $student->id]);
});
```

- [ ] **Step 2: Run tests to confirm they fail**

```bash
php artisan test --compact --filter=StudentControllerTest
```

Expected: FAIL — controller methods return empty responses / no views exist yet.

- [ ] **Step 3: Implement StudentController**

Replace the entire contents of `app/Http/Controllers/StudentController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * List all students with optional search and status filter.
     */
    public function index(Request $request): View
    {
        $query = Student::query();

        // Filter by name if a search term is present
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // paginate(10) shows 10 students per page
        // withQueryString() keeps ?search=... and ?status=... in pagination links
        $students = $query->latest()->paginate(10)->withQueryString();

        // Stat cards for the top of the page
        $stats = [
            'total'    => Student::count(),
            'active'   => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'new_year' => Student::where('enrollment_year', now()->year)->count(),
        ];

        return view('students.index', compact('students', 'stats'));
    }

    /**
     * Show the form to create a new student.
     */
    public function create(): View
    {
        return view('students.create');
    }

    /**
     * Validate and save a new student, then redirect to the list.
     * StoreStudentRequest automatically handles validation before this runs.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        // validated() returns only the fields that passed validation
        Student::create($request->validated());

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Show a single student's detail.
     * Laravel resolves $student automatically via Route Model Binding.
     */
    public function show(Student $student): View
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the pre-filled edit form for a student.
     */
    public function edit(Student $student): View
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Validate and update a student record, then redirect to the list.
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Delete a student and redirect back to the list.
     */
    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
```

- [ ] **Step 4: Run the controller tests (they will still fail — views don't exist yet)**

```bash
php artisan test --compact --filter=StudentControllerTest
```

Expected: Some FAILs about "View [students.index] not found" — that's fine, views come next.

- [ ] **Step 5: Run Pint then commit the controller**

```bash
vendor/bin/pint --dirty --format agent
git add app/Http/Controllers/StudentController.php tests/Feature/StudentControllerTest.php
git commit -m "feat: implement all 7 StudentController methods"
```

---

## Chunk 3: Dashboard Layout + Index View

### Task 6: Create the shared dashboard layout

**Files:**
- Create: `resources/views/layouts/dashboard.blade.php`

- [ ] **Step 1: Create the layouts directory and the dashboard layout file**

```bash
mkdir -p resources/views/layouts
```

- [ ] **Step 2: Create `resources/views/layouts/dashboard.blade.php`**

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StudyTrack') — StudyTrack</title>

    {{-- Bootstrap 5.3 from CDN — no npm/Vite needed --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ── CUSTOM INDIGO THEME ── */
        :root {
            --indigo-900: #1e1b4b;
            --indigo-800: #312e81;
            --indigo-700: #3730a3;
            --indigo-600: #4f46e5;
            --indigo-50:  #f5f3ff;
        }

        body {
            background-color: var(--indigo-50);
            min-height: 100vh;
        }

        /* Top header */
        .top-header {
            background-color: var(--indigo-900);
            height: 56px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            gap: 1rem;
            border-bottom: 1px solid var(--indigo-800);
        }

        .brand-logo {
            color: #c7d2fe;
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .brand-logo:hover { color: white; }

        .brand-icon {
            width: 30px; height: 30px;
            background: var(--indigo-600);
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
        }

        .header-avatar {
            width: 32px; height: 32px;
            background: var(--indigo-600);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 700;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 56px;
            left: 0;
            width: 200px;
            height: calc(100vh - 56px);
            background-color: var(--indigo-800);
            padding-top: 1rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-label {
            color: #818cf8;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.5rem 1rem 0.25rem;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.55rem 1rem;
            color: #a5b4fc;
            font-size: 0.82rem;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: background 0.15s, color 0.15s;
        }

        .nav-link-item:hover {
            background-color: var(--indigo-700);
            color: #e0e7ff;
        }

        .nav-link-item.active {
            background-color: var(--indigo-700);
            color: white;
            border-left-color: #818cf8;
            font-weight: 600;
        }

        /* Main content area */
        .main-content {
            margin-left: 200px;
            margin-top: 56px;
            padding: 1.75rem;
            min-height: calc(100vh - 56px);
        }

        /* Stat cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.1rem 1.25rem;
            box-shadow: 0 1px 4px rgba(79, 70, 229, 0.08);
            border-left: 4px solid var(--indigo-600);
        }

        .stat-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #9ca3af;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--indigo-900);
            line-height: 1.2;
        }

        /* Table wrapper */
        .table-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(79, 70, 229, 0.08);
        }

        .table-toolbar {
            padding: 0.9rem 1.1rem;
            border-bottom: 1px solid #ede9fe;
            background: white;
        }

        .table thead th {
            background: #ede9fe;
            color: #4c1d95;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-bottom: none;
            padding: 0.7rem 0.9rem;
        }

        .table tbody td {
            padding: 0.75rem 0.9rem;
            vertical-align: middle;
            color: #374151;
            font-size: 0.85rem;
            border-bottom: 1px solid #f5f3ff;
        }

        .table tbody tr:hover td {
            background: #faf5ff;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Avatar circle for student initials */
        .avatar-circle {
            width: 30px; height: 30px;
            background: var(--indigo-600);
            color: white;
            border-radius: 50%;
            font-size: 0.65rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Badges */
        .badge-active   { background: #d1fae5; color: #059669; }
        .badge-inactive { background: #fee2e2; color: #dc2626; }

        /* Form pages */
        .form-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 1px 4px rgba(79, 70, 229, 0.08);
            max-width: 680px;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--indigo-600);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
        }

        /* Detail card (show page) */
        .detail-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 1px 4px rgba(79, 70, 229, 0.08);
            max-width: 700px;
        }

        .detail-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
            font-weight: 600;
            margin-bottom: 0.15rem;
        }

        .detail-value {
            font-size: 0.95rem;
            color: #1e1b4b;
            font-weight: 500;
        }

        /* Primary button */
        .btn-indigo {
            background: var(--indigo-600);
            color: white;
            border: none;
            border-radius: 7px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .btn-indigo:hover {
            background: var(--indigo-700);
            color: white;
        }
    </style>
</head>
<body>

{{-- ── TOP HEADER ── --}}
<header class="top-header">
    <a href="{{ route('students.index') }}" class="brand-logo">
        <div class="brand-icon">🎓</div>
        STUDYTRACK
    </a>
    <div class="ms-auto d-flex align-items-center gap-2">
        <span style="color: #a5b4fc; font-size: 0.8rem;">Admin</span>
        <div class="header-avatar">A</div>
    </div>
</header>

{{-- ── SIDEBAR ── --}}
<nav class="sidebar">
    <span class="sidebar-label">Main Menu</span>

    <a href="{{ route('students.index') }}"
       class="nav-link-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
        👥 Students
    </a>
</nav>

{{-- ── MAIN CONTENT ── --}}
<main class="main-content">
    {{--
        NOTE: Flash messages are NOT placed here in the shared layout.
        The spec requires them to show only on the index and show pages.
        Each of those views includes the flash block directly.
    --}}
    @yield('content')
</main>

{{-- Bootstrap JS bundle (includes Popper) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
```

- [ ] **Step 3: Commit the layout**

```bash
git add resources/views/layouts/dashboard.blade.php
git commit -m "feat: add dashboard layout with indigo theme and Bootstrap 5 CDN"
```

---

### Task 7: Create the students index view

**Files:**
- Create: `resources/views/students/index.blade.php`

- [ ] **Step 1: Create the students views directory**

```bash
mkdir -p resources/views/students
```

- [ ] **Step 2: Create `resources/views/students/index.blade.php`**

```html
@extends('layouts.dashboard')

@section('title', 'Students')

@section('content')

{{-- Flash message — only shown on pages that redirect here (create, update, delete) --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        ✅ {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── PAGE HEADER ── --}}
<div class="d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1" style="color: #1e1b4b;">Students</h1>
        <p class="text-muted small mb-0">Manage all enrolled students</p>
    </div>
    <a href="{{ route('students.create') }}" class="btn btn-indigo">
        + Add Student
    </a>
</div>

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card" style="border-left-color: #4f46e5;">
            <div class="stat-label">Total Students</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <span class="badge" style="background:#ede9fe; color:#4f46e5; font-size:0.7rem;">All time</span>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card" style="border-left-color: #10b981;">
            <div class="stat-label">Active</div>
            <div class="stat-value">{{ $stats['active'] }}</div>
            @if ($stats['total'] > 0)
                <span class="badge" style="background:#d1fae5; color:#059669; font-size:0.7rem;">
                    {{ round($stats['active'] / $stats['total'] * 100) }}%
                </span>
            @endif
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <div class="stat-label">New This Year</div>
            <div class="stat-value">{{ $stats['new_year'] }}</div>
            <span class="badge" style="background:#fef3c7; color:#d97706; font-size:0.7rem;">{{ now()->year }}</span>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card" style="border-left-color: #ef4444;">
            <div class="stat-label">Inactive</div>
            <div class="stat-value">{{ $stats['inactive'] }}</div>
            <span class="badge" style="background:#fee2e2; color:#dc2626; font-size:0.7rem;">Inactive</span>
        </div>
    </div>
</div>

{{-- ── TABLE ── --}}
<div class="table-card">

    {{-- Search & Filter Toolbar --}}
    <div class="table-toolbar">
        <form method="GET" action="{{ route('students.index') }}" class="d-flex gap-2 flex-wrap">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name..."
                class="form-control form-control-sm"
                style="max-width: 240px;"
            >
            <select name="status" class="form-select form-select-sm" style="max-width: 140px;">
                <option value="">All Status</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn btn-sm btn-indigo">Filter</button>
            @if (request('search') || request('status'))
                <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
            <span class="ms-auto text-muted small align-self-center">
                {{ $students->total() }} student(s)
            </span>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Major</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr>
                        {{-- Avatar initials + name --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">
                                    {{-- Take first letter of first and last name --}}
                                    {{ strtoupper(substr($student->name, 0, 1)) }}{{ strtoupper(substr(strrchr($student->name, ' '), 1, 1)) }}
                                </div>
                                <span class="fw-semibold">{{ $student->name }}</span>
                            </div>
                        </td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->major }}</td>
                        <td>{{ $student->enrollment_year }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $student->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('students.show', $student) }}"
                               class="btn btn-sm" style="background:#ede9fe; color:#4f46e5;">View</a>

                            <a href="{{ route('students.edit', $student) }}"
                               class="btn btn-sm" style="background:#fef3c7; color:#d97706;">Edit</a>

                            {{-- Delete uses a POST form with method spoofing --}}
                            <form method="POST" action="{{ route('students.destroy', $student) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete {{ addslashes($student->name) }}? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm"
                                        style="background:#fee2e2; color:#dc2626;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No students found.
                            <a href="{{ route('students.create') }}">Add the first one →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($students->hasPages())
        <div class="p-3 border-top">
            {{ $students->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>

@endsection
```

- [ ] **Step 3: Run the controller tests — index, create, and list tests should now pass**

```bash
php artisan test --compact --filter=StudentControllerTest
```

Expected: `shows the student list` → PASS, others may still fail (views missing).

- [ ] **Step 4: Manually verify the index page in your browser**

```bash
php artisan serve
```

Visit `http://localhost:8000/students` — you should see the dashboard with empty stats and an empty table.

- [ ] **Step 5: Commit**

```bash
git add resources/views/students/index.blade.php
git commit -m "feat: add student index view with stat cards, table, search, and filter"
```

---

## Chunk 4: CRUD Views (Create, Show, Edit)

### Task 8: Create the "Add Student" form view

**Files:**
- Create: `resources/views/students/create.blade.php`

- [ ] **Step 1: Create `resources/views/students/create.blade.php`**

```html
@extends('layouts.dashboard')

@section('title', 'Add Student')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('students.index') }}" class="text-decoration-none" style="color:#4f46e5;">
        ← Back to Students
    </a>
</div>

<h1 class="h4 fw-bold mb-4" style="color: #1e1b4b;">Add New Student</h1>

<div class="form-card">
    {{--
        The form posts to students.store (POST /students).
        @csrf adds a hidden token to prevent cross-site request forgery.
    --}}
    <form method="POST" action="{{ route('students.store') }}">
        @csrf

        <div class="row g-3">

            {{-- Name --}}
            <div class="col-md-6">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                {{--
                    old('name') restores the value if validation fails,
                    so the user doesn't retype everything.
                    is-invalid applies red border when there's an error for this field.
                --}}
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="e.g. Budi Wijaya">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="col-md-6">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="e.g. budi@email.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="col-md-6">
                <label for="phone" class="form-label">Phone <span class="text-muted small">(optional)</span></label>
                <input type="text" id="phone" name="phone"
                       value="{{ old('phone') }}"
                       class="form-control @error('phone') is-invalid @enderror"
                       placeholder="e.g. 081234567890">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Date of Birth --}}
            <div class="col-md-6">
                <label for="date_of_birth" class="form-label">Date of Birth <span class="text-muted small">(optional)</span></label>
                <input type="date" id="date_of_birth" name="date_of_birth"
                       value="{{ old('date_of_birth') }}"
                       class="form-control @error('date_of_birth') is-invalid @enderror">
                @error('date_of_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Major --}}
            <div class="col-md-6">
                <label for="major" class="form-label">Major / Program <span class="text-danger">*</span></label>
                <input type="text" id="major" name="major"
                       value="{{ old('major') }}"
                       class="form-control @error('major') is-invalid @enderror"
                       placeholder="e.g. Computer Science">
                @error('major')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Enrollment Year --}}
            <div class="col-md-3">
                <label for="enrollment_year" class="form-label">Enrollment Year <span class="text-danger">*</span></label>
                <input type="number" id="enrollment_year" name="enrollment_year"
                       value="{{ old('enrollment_year', now()->year) }}"
                       min="2000" max="2099"
                       class="form-control @error('enrollment_year') is-invalid @enderror">
                @error('enrollment_year')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select id="status" name="status"
                        class="form-select @error('status') is-invalid @enderror">
                    <option value="active"   {{ old('status', 'active') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>{{-- /row --}}

        <hr class="my-4">

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-indigo">Save Student</button>
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>

    </form>
</div>

@endsection
```

- [ ] **Step 2: Run the tests for create and store**

```bash
php artisan test --compact --filter="shows the create form|stores a new student"
```

Expected: Both PASSED.

- [ ] **Step 3: Manually test the form**

Visit `http://localhost:8000/students/create` and try:
- Submitting with empty fields → should show red validation errors under each field
- Submitting with valid data → should redirect to index with green success message

- [ ] **Step 4: Commit**

```bash
git add resources/views/students/create.blade.php
git commit -m "feat: add student create form view with inline validation"
```

---

### Task 9: Create the student detail (show) view

**Files:**
- Create: `resources/views/students/show.blade.php`

- [ ] **Step 1: Create `resources/views/students/show.blade.php`**

```html
@extends('layouts.dashboard')

@section('title', $student->name)

@section('content')

{{-- Flash message — shown here when redirected to show after edit, etc. --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        ✅ {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('students.index') }}" class="text-decoration-none" style="color:#4f46e5;">
        ← Back to Students
    </a>
</div>

<div class="d-flex align-items-start justify-content-between mb-4">
    <h1 class="h4 fw-bold mb-0" style="color: #1e1b4b;">Student Detail</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-indigo">Edit</a>
        <form method="POST" action="{{ route('students.destroy', $student) }}"
              onsubmit="return confirm('Delete {{ addslashes($student->name) }}? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
        </form>
    </div>
</div>

<div class="detail-card">

    {{-- Student name header --}}
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div style="width:52px; height:52px; background:#4f46e5; border-radius:50%;
                    display:flex; align-items:center; justify-content:center;
                    color:white; font-size:1.1rem; font-weight:700; flex-shrink:0;">
            {{ strtoupper(substr($student->name, 0, 1)) }}{{ strtoupper(substr(strrchr($student->name, ' '), 1, 1)) }}
        </div>
        <div>
            <div class="fw-bold fs-5" style="color:#1e1b4b;">{{ $student->name }}</div>
            <span class="badge rounded-pill {{ $student->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                {{ ucfirst($student->status) }}
            </span>
        </div>
    </div>

    {{-- Detail fields --}}
    <div class="row g-4">

        <div class="col-sm-6">
            <div class="detail-label">Email Address</div>
            <div class="detail-value">{{ $student->email }}</div>
        </div>

        <div class="col-sm-6">
            <div class="detail-label">Phone</div>
            <div class="detail-value">{{ $student->phone ?? '—' }}</div>
        </div>

        <div class="col-sm-6">
            <div class="detail-label">Date of Birth</div>
            <div class="detail-value">
                {{-- $student->date_of_birth is cast to Carbon, so we can format it --}}
                {{ $student->date_of_birth ? $student->date_of_birth->format('d M Y') : '—' }}
            </div>
        </div>

        <div class="col-sm-6">
            <div class="detail-label">Major / Program</div>
            <div class="detail-value">{{ $student->major }}</div>
        </div>

        <div class="col-sm-6">
            <div class="detail-label">Enrollment Year</div>
            <div class="detail-value">{{ $student->enrollment_year }}</div>
        </div>

        <div class="col-sm-6">
            <div class="detail-label">Record Created</div>
            <div class="detail-value">{{ $student->created_at->format('d M Y, H:i') }}</div>
        </div>

    </div>
</div>

@endsection
```

- [ ] **Step 2: Add a flash message test for the show page**

The spec says flash messages must display on the `show` page. Add this test to `tests/Feature/StudentControllerTest.php`:

```php
it('shows a success flash message on the show page', function () {
    $student = Student::factory()->create();

    // withSession() pre-loads the flash message as if a redirect just set it
    $this->withSession(['success' => 'Student updated successfully.'])
         ->get(route('students.show', $student))
         ->assertStatus(200)
         ->assertSee('Student updated successfully.');
});
```

- [ ] **Step 3: Run the show and flash tests**

```bash
php artisan test --compact --filter="shows a student detail page|flash message on the show"
```

Expected: Both PASSED.

- [ ] **Step 4: Run Pint then commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/students/show.blade.php tests/Feature/StudentControllerTest.php
git commit -m "feat: add student show view with detail card"
```

---

### Task 10: Create the edit form view

**Files:**
- Create: `resources/views/students/edit.blade.php`

- [ ] **Step 1: Create `resources/views/students/edit.blade.php`**

```html
@extends('layouts.dashboard')

@section('title', 'Edit ' . $student->name)

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('students.index') }}" class="text-decoration-none" style="color:#4f46e5;">
        ← Back to Students
    </a>
</div>

<h1 class="h4 fw-bold mb-4" style="color: #1e1b4b;">Edit Student</h1>

<div class="form-card">
    {{--
        HTML forms only support GET and POST.
        @method('PUT') adds a hidden _method field so Laravel knows this is a PUT request.
    --}}
    <form method="POST" action="{{ route('students.update', $student) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <div class="col-md-6">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                {{--
                    old('name', $student->name) shows:
                    - the old input if validation failed (so they don't retype),
                    - or the current DB value on first load.
                --}}
                <input type="text" id="name" name="name"
                       value="{{ old('name', $student->name) }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $student->email) }}"
                       class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label">Phone <span class="text-muted small">(optional)</span></label>
                <input type="text" id="phone" name="phone"
                       value="{{ old('phone', $student->phone) }}"
                       class="form-control @error('phone') is-invalid @enderror">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="date_of_birth" class="form-label">Date of Birth <span class="text-muted small">(optional)</span></label>
                <input type="date" id="date_of_birth" name="date_of_birth"
                       value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                       class="form-control @error('date_of_birth') is-invalid @enderror">
                @error('date_of_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="major" class="form-label">Major / Program <span class="text-danger">*</span></label>
                <input type="text" id="major" name="major"
                       value="{{ old('major', $student->major) }}"
                       class="form-control @error('major') is-invalid @enderror">
                @error('major')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="enrollment_year" class="form-label">Enrollment Year <span class="text-danger">*</span></label>
                <input type="number" id="enrollment_year" name="enrollment_year"
                       value="{{ old('enrollment_year', $student->enrollment_year) }}"
                       min="2000" max="2099"
                       class="form-control @error('enrollment_year') is-invalid @enderror">
                @error('enrollment_year')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select id="status" name="status"
                        class="form-select @error('status') is-invalid @enderror">
                    <option value="active"   {{ old('status', $student->status) === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-indigo">Update Student</button>
            <a href="{{ route('students.show', $student) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>

    </form>
</div>

@endsection
```

- [ ] **Step 2: Run the edit and update tests**

```bash
php artisan test --compact --filter="shows the edit form|updates a student"
```

Expected: Both PASSED.

- [ ] **Step 3: Run the full test suite to verify everything passes**

```bash
php artisan test --compact
```

Expected: All tests PASSED (StudentRoutesTest, StudentModelTest, StudentValidationTest, StudentControllerTest).

- [ ] **Step 4: Commit**

```bash
git add resources/views/students/edit.blade.php
git commit -m "feat: add student edit view with pre-filled form"
```

---

## Chunk 5: Factory, Seeder & Final Pint Pass

### Task 11: Create StudentFactory and StudentSeeder

**Files:**
- Create: `database/factories/StudentFactory.php`
- Create: `database/seeders/StudentSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 1: Generate factory and seeder**

```bash
php artisan make:factory StudentFactory --model=Student --no-interaction
php artisan make:seeder StudentSeeder --no-interaction
```

- [ ] **Step 2: Replace the contents of `database/factories/StudentFactory.php`**

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        // fake() generates realistic-looking random data
        return [
            'name'            => fake()->name(),
            'email'           => fake()->unique()->safeEmail(),
            'phone'           => fake()->optional()->phoneNumber(),
            'date_of_birth'   => fake()->optional()->dateTimeBetween('-30 years', '-17 years')?->format('Y-m-d'),
            'major'           => fake()->randomElement([
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
            'status'          => fake()->randomElement(['active', 'active', 'active', 'inactive']),
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
```

- [ ] **Step 3: Replace the contents of `database/seeders/StudentSeeder.php`**

```php
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
```

- [ ] **Step 4: Register StudentSeeder in `database/seeders/DatabaseSeeder.php`**

Open `database/seeders/DatabaseSeeder.php` and add the StudentSeeder call:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StudentSeeder::class,
        ]);
    }
}
```

- [ ] **Step 5: Run the seeder to populate sample data**

```bash
php artisan db:seed --class=StudentSeeder --no-interaction
```

Expected: `Seeded: Database\Seeders\StudentSeeder`

- [ ] **Step 6: Verify data in the browser**

Visit `http://localhost:8000/students` — the table should now show 30 students with pagination, and the stat cards should show real counts.

Try: searching by name, filtering by status, clicking View/Edit on a student.

- [ ] **Step 7: Write a factory smoke test**

> **Important:** Before adding these tests, confirm that `tests/Unit/StudentModelTest.php` already has `uses(RefreshDatabase::class);` at the top (it was added in Task 3). These tests write to the database, so isolation is required.

Add to `tests/Unit/StudentModelTest.php`:

```php
it('can be created using the factory', function () {
    $student = Student::factory()->create();

    expect($student)->toBeInstanceOf(Student::class)
        ->and($student->name)->not->toBeEmpty()
        ->and($student->status)->toBeIn(['active', 'inactive']);
});

it('factory active state creates active student', function () {
    $student = Student::factory()->active()->create();

    expect($student->status)->toBe('active');
});
```

- [ ] **Step 8: Run all tests**

```bash
php artisan test --compact
```

Expected: All PASSED.

- [ ] **Step 9: Run Laravel Pint to fix any code style issues**

```bash
vendor/bin/pint --dirty --format agent
```

Expected: Either `No files changed` or a list of auto-fixed style issues.

- [ ] **Step 10: Commit everything**

```bash
git add database/factories/StudentFactory.php \
        database/seeders/StudentSeeder.php \
        database/seeders/DatabaseSeeder.php \
        tests/Unit/StudentModelTest.php
git commit -m "feat: add StudentFactory and StudentSeeder with 30 sample students"
```

---

### Task 12: Final verification

- [ ] **Step 1: Fresh migration + seed to verify the whole stack works from zero**

```bash
php artisan migrate:fresh --seed --no-interaction
```

Expected: All migrations run, seeder populates 30 students.

- [ ] **Step 2: Run the full test suite one final time**

```bash
php artisan test --compact
```

Expected: All PASSED. No failures.

- [ ] **Step 3: Start the dev server and do a manual walkthrough**

```bash
php artisan serve
```

Verify each page works:
- `http://localhost:8000/students` — list with stats, search, filter, pagination
- `http://localhost:8000/students/create` — form, submit empty (validation errors), submit valid (success)
- `http://localhost:8000/students/1` — detail page for student #1
- `http://localhost:8000/students/1/edit` — edit form pre-filled, submit changes (success)
- Delete a student from the index table — confirm dialog, then deleted with success flash

- [ ] **Step 4: Final commit**

Run `git status` first to confirm only expected files are unstaged, then commit:

```bash
git status
git add routes/web.php app/Http/Controllers/StudentController.php \
        app/Models/Student.php app/Http/Requests/ \
        database/migrations/ database/factories/StudentFactory.php \
        database/seeders/ \
        resources/views/ \
        tests/
git commit -m "feat: complete student dashboard CRUD with indigo theme and Bootstrap 5"
```
