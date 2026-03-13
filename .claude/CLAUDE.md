# PHP & Laravel Learning Environment

## About Me
- **Level:** Complete beginner — new to both PHP and Laravel
- **Goal:** Build web apps from scratch: CRUD, authentication, REST APIs
- **Project name:** `belajar-php` (Indonesian for "learn PHP")

---

## How Claude Should Teach Me

- **Explain before writing code.** Tell me what a concept is and why it exists before showing the implementation.
- **Step by step.** Don't jump ahead. Build on what I already know.
- **Use analogies** when introducing abstract concepts (e.g., "a controller is like a traffic cop between the URL and the database").
- **Comment the code** you write so I understand every line.
- **Don't use advanced features** unless explicitly requested. Prefer clarity over cleverness.
- **Check my understanding.** After explaining something non-trivial, ask if it makes sense before moving on.
- **Point out common beginner mistakes** proactively — tell me what NOT to do and why.
- **When I make an error**, explain what went wrong before fixing it.

---

## PHP Fundamentals to Cover (in order)

1. Variables, types, and basic syntax
2. Arrays (indexed, associative, multidimensional)
3. Functions, scope, and return values
4. OOP: classes, objects, properties, methods
5. OOP: inheritance, interfaces, traits
6. Namespaces and autoloading (PSR-4)
7. Error handling: try/catch/finally
8. Working with files, forms, and superglobals ($_GET, $_POST)
9. PDO and basic database interaction
10. Composer and package management

---

## Laravel Learning Path (in order)

### Phase 1: Foundations
1. What is Laravel? MVC architecture overview
2. Setting up Laravel with Composer and Artisan CLI
3. Routes: web.php, route parameters, named routes
4. Controllers: creating, returning views and JSON
5. Blade templating: layouts, components, directives (@if, @foreach, @extends)
6. Migrations: creating and modifying tables
7. Eloquent ORM: models, CRUD operations, relationships

### Phase 2: Building Real Features
8. Form validation (Request classes)
9. Authentication with Laravel Breeze
10. Middleware: what it is, how to create and apply it
11. File uploads and storage
12. Relationships: hasOne, hasMany, belongsTo, belongsToMany
13. Seeders and Factories for test data

### Phase 3: APIs
14. API routes (api.php) vs web routes
15. Returning JSON responses
16. API Resources and Resource Collections
17. Authentication for APIs: Laravel Sanctum
18. Postman / testing API endpoints

### Phase 4: Going Deeper
19. Service Container and Dependency Injection
20. Service Providers
21. Events and Listeners
22. Jobs and Queues
23. Testing: PHPUnit and Laravel's testing helpers

---

## Code Style Guidelines

- Use **PHP 8.1+** features where appropriate (but explain them when used)
- Follow **PSR-12** coding standards
- Use **named arguments** and **typed properties** to make intent clear
- Prefer **Eloquent** over raw SQL while learning
- Use `php artisan make:*` commands — show the Artisan command before writing code manually
- Keep controllers **thin** — logic belongs in models or service classes

---

## Project Structure Reminders

When working in a Laravel project:
- Routes → `routes/web.php` or `routes/api.php`
- Controllers → `app/Http/Controllers/`
- Models → `app/Models/`
- Views → `resources/views/`
- Migrations → `database/migrations/`
- Config → `config/`
- Environment → `.env` (never commit this)

---

## Key Laravel Artisan Commands (remind me of these)

```bash
php artisan serve                   # Start local dev server
php artisan make:controller Name    # Create a controller
php artisan make:model Name -m      # Create model + migration
php artisan make:migration name     # Create a migration
php artisan migrate                 # Run migrations
php artisan migrate:fresh --seed    # Reset DB and seed
php artisan make:seeder Name        # Create a seeder
php artisan make:factory Name       # Create a factory
php artisan make:request Name       # Create a form request
php artisan route:list              # List all routes
php artisan tinker                  # Interactive REPL
```

---

## Learning Principles

- **Build, don't just read.** Every concept should come with a small working example I can run.
- **YAGNI for learning** — don't introduce patterns I don't need yet (repositories, CQRS, etc.)
- **One concept at a time** — don't mix multiple new ideas in a single example.
- **Real-world context** — frame examples around a realistic app (e.g., a blog, task manager, or bookstore).

---

## What to Avoid

- Don't use Laravel Facades without explaining what they do
- Don't skip explaining `$this` in classes
- Don't assume I know terminal/CLI basics — explain new commands
- Don't use helper functions without naming what they do
- Don't mix multiple concepts in one file without clear separation
