# Student Dashboard CRUD — Design Spec

**Date:** 2026-03-14
**Status:** Approved

---

## Overview

A beautiful, fully-functioning student management dashboard built with Laravel 12 and Bootstrap 5 (CDN). Covers full CRUD: list, create, view, edit, delete. Designed to teach core Laravel concepts (routes, controllers, models, migrations, Blade views) step by step.

---

## Visual Design

**Layout:** Top header + slim sidebar + main content area
- Fixed top header with branding and admin avatar
- Left sidebar (200px) with navigation links
- Main content area with light indigo-tinted background

**Color Scheme:** Indigo & Deep Purple
- Header/sidebar background: `#1e1b4b` / `#312e81`
- Primary accent: `#4f46e5` (indigo-600)
- Page background: `#f5f3ff` (indigo-50)
- Cards: white with `border-left: 4px solid` accent colors
- Typography: dark indigo headings, gray body text

**Bootstrap CDN:** Bootstrap 5.3 loaded via CDN (no npm/Vite required)

---

## Data Model

**Table:** `students`

| Column | Type | Notes |
|---|---|---|
| id | bigint (PK) | Auto-increment |
| name | string(100) | Required |
| email | string | Required, unique |
| phone | string(20) | Nullable |
| date_of_birth | date | Nullable |
| major | string(100) | Required (e.g. Computer Science) |
| enrollment_year | unsignedSmallInteger | Required (e.g. 2022) — use `$table->unsignedSmallInteger()`, NOT `$table->year()`, because `year()` is unsupported in SQLite (Laravel's default dev DB) |
| status | string | Default: `'active'`. Store as plain string (`'active'` / `'inactive'`) — no Enum class needed at this stage |
| created_at / updated_at | timestamps | Auto-managed by Laravel |

**Migration `down()` method** must call `Schema::dropIfExists('students')` so `php artisan migrate:rollback` works correctly.

---

## Routes

Register all routes in `routes/web.php` using `Route::resource()` which auto-names all routes and requires only one line:

```php
use App\Http\Controllers\StudentController; // ← MUST be imported

Route::resource('students', StudentController::class);
```

This generates all 7 routes automatically with named routes (`students.index`, `students.create`, `students.store`, `students.show`, `students.edit`, `students.update`, `students.destroy`).

Always use named routes in Blade and controllers:
- `route('students.index')` not `'/students'`
- `redirect()->route('students.index')` not `redirect('/students')`

| Method | URI | Controller Method | Named Route |
|---|---|---|---|
| GET | /students | index | students.index |
| GET | /students/create | create | students.create |
| POST | /students | store | students.store |
| GET | /students/{student} | show | students.show |
| GET | /students/{student}/edit | edit | students.edit |
| PUT/PATCH | /students/{student} | update | students.update |
| DELETE | /students/{student} | destroy | students.destroy |

---

## Controller Method Signatures

`StudentController` stubs exist but have no parameters. Each method needs the correct signature:

```php
public function index(Request $request): View
public function create(): View
public function store(StoreStudentRequest $request): RedirectResponse
public function show(Student $student): View          // route model binding
public function edit(Student $student): View          // route model binding
public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
public function destroy(Student $student): RedirectResponse
```

**Route Model Binding:** Using `Student $student` instead of `$id` lets Laravel automatically find the model — no manual `Student::findOrFail($id)` needed.

---

## Views (Blade Files)

All views share a common layout (`resources/views/layouts/dashboard.blade.php`) with:
- Bootstrap 5 CDN in `<head>`
- Top header component
- Sidebar component
- `@yield('content')` slot for page body

| View File | Route | Description |
|---|---|---|
| `layouts/dashboard.blade.php` | — | Shared layout: header + sidebar |
| `students/index.blade.php` | GET /students | Table + stat cards + search/filter |
| `students/create.blade.php` | GET /students/create | Create form |
| `students/show.blade.php` | GET /students/{student} | Read-only detail card |
| `students/edit.blade.php` | GET /students/{student}/edit | Pre-filled edit form |

Delete uses a small inline `<form>` with `@method('DELETE')` and `@csrf` — no separate view needed.

---

## Index Page Features

- **Stat cards (4):** Total Students, Active, New This Year, Inactive — computed in controller
- **Search:** GET query param `?search=name` — filters by `name` LIKE
- **Status filter:** GET query param `?status=active|inactive` — filters by `status`
- **Table columns:** Name (with avatar initials), Email, Major, Enrollment Year, Status badge, Actions (View / Edit / Delete)
- **Pagination:** `paginate(10)->withQueryString()` — the `withQueryString()` call is essential to preserve `?search=` and `?status=` across pages. Without it, clicking page 2 drops the filters.

---

## Forms (Create & Edit)

Both forms have identical fields:
- Name (text, required)
- Email (email, required)
- Phone (text, optional)
- Date of Birth (date, optional)
- Major (text, required)
- Enrollment Year (number, required, e.g. 2022)
- Status (select: Active / Inactive)

Validation handled via **Form Request classes** — `StoreStudentRequest` and `UpdateStudentRequest`.
Errors displayed inline below each field using Bootstrap's `is-invalid` + `invalid-feedback`.

The `create` page does not show a success message — the user is redirected to index after a successful create, which shows the flash message there.

---

## Validation Rules

**StoreStudentRequest:**
```php
public function rules(): array
{
    return [
        'name'            => ['required', 'string', 'max:100'],
        'email'           => ['required', 'email', Rule::unique('students', 'email')],
        'phone'           => ['nullable', 'string', 'max:20'],
        'date_of_birth'   => ['nullable', 'date'],
        'major'           => ['required', 'string', 'max:100'],
        'enrollment_year' => ['required', 'integer', 'min:2000', 'max:2099'],
        'status'          => ['required', 'in:active,inactive'],
    ];
}
```

**UpdateStudentRequest:** Same, but the unique email rule ignores the current student using the fluent `Rule::unique()` syntax:

```php
'email' => ['required', 'email', Rule::unique('students', 'email')->ignore($this->route('student'))],
```

`$this->route('student')` resolves to the bound `Student` model from the route. `Rule::unique()->ignore()` accepts a model instance and excludes it from the uniqueness check.

---

## Flash Messages

Use `->with('success', '...')` on redirects — this is the standard Laravel idiom:

```php
return redirect()->route('students.index')->with('success', 'Student created successfully.');
```

Display in Blade:
```html
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

Show flash messages on: `index` and `show` pages only. Do NOT add a success display to `create` or `edit` pages — the user is always redirected away after a successful form submit.

---

## Delete Flow

- Delete button on index page is a `<form method="POST">` with `@method('DELETE')` and `@csrf`
- JavaScript `confirm()` dialog via `onsubmit` before submit
- On success: `redirect()->route('students.index')->with('success', 'Student deleted.')`

---

## Model

**`app/Models/Student.php`** — key properties:

```php
protected $fillable = [
    'name', 'email', 'phone', 'date_of_birth', 'major', 'enrollment_year', 'status',
];

protected function casts(): array
{
    return [
        'date_of_birth' => 'date',
    ];
}
```

Status is stored as a plain string (`'active'` / `'inactive'`) — no Enum cast needed at this stage.

---

## Files to Create / Modify

1. **`routes/web.php`** — add `use App\Http\Controllers\StudentController;` + `Route::resource('students', StudentController::class)`
2. **Migration:** `create_students_table` — `php artisan make:migration create_students_table`
3. **Model:** `app/Models/Student.php` — `php artisan make:model Student`
4. **Form Requests:** `php artisan make:request StoreStudentRequest` and `php artisan make:request UpdateStudentRequest`
5. **Controller:** Fill in all 7 methods in `StudentController` with correct signatures
6. **Layout:** `resources/views/layouts/dashboard.blade.php`
7. **Views:** `students/index.blade.php`, `create.blade.php`, `show.blade.php`, `edit.blade.php`
8. **Factory + Seeder:** `php artisan make:factory StudentFactory` + `php artisan make:seeder StudentSeeder`

---

## What This Teaches

- Laravel migrations (`up()` and `down()`) and Eloquent models
- `Route::resource()` for auto-generating all 7 named routes
- Route Model Binding — Laravel resolves `Student $student` automatically
- Form Request validation classes with `rules()` method
- `Rule::unique()->ignore()` for update uniqueness
- Controller resource methods with correct signatures
- Blade layouts with `@extends` / `@yield` / `@section`
- `@method('PUT')` / `@method('DELETE')` form spoofing
- Flash messages with `->with('success', '...')` and `session('success')`
- Eloquent filtering with `where()` and pagination with `paginate()->withQueryString()`
- Bootstrap 5 CDN integration in Laravel Blade
