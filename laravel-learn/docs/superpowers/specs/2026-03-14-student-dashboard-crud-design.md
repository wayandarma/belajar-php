# Student Dashboard CRUD — Design Spec

**Date:** 2026-03-14
**Status:** Approved

---

## Overview

A beautiful, fully-functioning student management dashboard built with Laravel 12 and Bootstrap 5 (CDN). Covers full CRUD: list, create, view, edit, delete. Designed to teach core Laravel concepts (routes, controllers, models, migrations, Blade views) step by step.

---

## Visual Design

**Layout:** Top header + slim sidebar + main content area (Option C)
- Fixed top header with branding and admin avatar
- Left sidebar (200px) with navigation links
- Main content area with light indigo-tinted background

**Color Scheme:** Indigo & Deep Purple (Option A)
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
| enrollment_year | year / smallint | Required (e.g. 2022) |
| status | enum: active, inactive | Default: active |
| created_at / updated_at | timestamps | Auto-managed by Laravel |

---

## Routes & Controllers

All routes already exist in `routes/web.php`. `StudentController` stubs exist in `app/Http/Controllers/StudentController.php`.

| Method | URI | Controller Method | Description |
|---|---|---|---|
| GET | /students | index | List all students with search + filter |
| GET | /students/create | create | Show create form |
| POST | /students | store | Save new student |
| GET | /students/{id} | show | Show student detail |
| GET | /students/{id}/edit | edit | Show edit form |
| PUT | /students/{id} | update | Save updated student |
| DELETE | /students/{id} | destroy | Delete student |

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
| `students/show.blade.php` | GET /students/{id} | Read-only detail card |
| `students/edit.blade.php` | GET /students/{id}/edit | Pre-filled edit form |

Delete uses a small inline `<form>` with `@method('DELETE')` — no separate view needed.

---

## Index Page Features

- **Stat cards (4):** Total Students, Active, New This Year, Inactive — computed in controller
- **Search:** GET query param `?search=name` — filters by `name` LIKE
- **Status filter:** GET query param `?status=active|inactive` — filters by `status`
- **Table columns:** Name (with avatar initials), Email, Major, Enrollment Year, Status badge, Actions (View / Edit / Delete)
- **Pagination:** Laravel's built-in `paginate(10)` — Bootstrap-styled

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

Validation handled via **Form Request class** (`StoreStudentRequest` / `UpdateStudentRequest`).
Errors displayed inline below each field using Bootstrap's `is-invalid` + `invalid-feedback`.

---

## Validation Rules

**StoreStudentRequest:**
```php
'name'            => ['required', 'string', 'max:100'],
'email'           => ['required', 'email', 'unique:students,email'],
'phone'           => ['nullable', 'string', 'max:20'],
'date_of_birth'   => ['nullable', 'date'],
'major'           => ['required', 'string', 'max:100'],
'enrollment_year' => ['required', 'integer', 'min:2000', 'max:2099'],
'status'          => ['required', 'in:active,inactive'],
```

**UpdateStudentRequest:** Same but email unique rule ignores current student: `unique:students,email,{id}`.

---

## Delete Flow

- Delete button on index page is a `<form method="POST">` with `@method('DELETE')` and `@csrf`
- JavaScript `confirm()` dialog before submit: *"Are you sure you want to delete this student?"*
- On success: redirect to `/students` with a flash success message

---

## Flash Messages

- Success messages displayed at top of index/show pages using Bootstrap `alert-success`
- Validation errors shown inline per field

---

## Files to Create / Modify

1. **Migration:** `create_students_table`
2. **Model:** `app/Models/Student.php` (with `$fillable`, casting for status)
3. **Form Requests:** `StoreStudentRequest`, `UpdateStudentRequest`
4. **Controller:** Fill in all 7 methods in `StudentController`
5. **Layout:** `resources/views/layouts/dashboard.blade.php`
6. **Views:** `students/index.blade.php`, `create.blade.php`, `show.blade.php`, `edit.blade.php`
7. **Seeder + Factory:** Sample student data for development

---

## What This Teaches

- Laravel migrations and Eloquent models
- Form Request validation classes
- Controller resource methods (index, create, store, show, edit, update, destroy)
- Blade layouts with `@extends` / `@yield` / `@section`
- `@method('PUT')` / `@method('DELETE')` spoofing
- Flash messages with `session()->flash()`
- Query scopes / `where()` filtering with Eloquent
- Bootstrap 5 CDN integration in Laravel Blade
