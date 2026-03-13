# Learning Plan: PHP Modern & Laravel → Agentic Engineering

## Context for AI Assistant

This file describes the learning plan and current curriculum for a JavaScript-native developer
transitioning to PHP/Laravel with a focus on agentic engineering (tool calling, queues, structured outputs, MCP).
Use this as context when answering questions, suggesting code patterns, reviewing code, or giving guidance.

---

## Learner Profile

- **Background:** JavaScript native developer (Node.js, Express, React, Next.js, Prisma/Sequelize, Zod)
- **Goal:** Build production-grade Laravel backends capable of powering agentic AI workflows
- **Stack target:** PHP 8.x + Laravel 12 + Inertia 2 + React 19 + TypeScript + Tailwind 4 + shadcn/ui
- **AI stack target:** OpenAI Responses API + tool/function calling + structured outputs + MCP PHP SDK

---

## Mental Model: JS → PHP/Laravel Mapping

| JavaScript / Node.js | PHP / Laravel |
|---|---|
| `npm` / `package.json` | `composer` / `composer.json` |
| `package.json scripts` | Artisan commands / composer scripts |
| Express route handlers | Laravel routes + controllers |
| Express middleware | Laravel middleware |
| Prisma / Sequelize | Eloquent ORM |
| Zod (server-side validation) | Laravel Form Request validation |
| Custom cron / worker | Laravel Scheduler + Queue |
| Background worker (Node) | Laravel Jobs + Queues |
| `modules` / `import` | Namespace + Composer autoload |
| Object literal / Map | Associative array / Collection |
| `async/await` everywhere | Request lifecycle (sync per request) |
| Schema evolution (Prisma migrate) | Laravel migrations |

**Key mindset shift:** Laravel is a backend operating system — batteries included, strong in auth, routing, queues, jobs, validation, and ready for AI layer integration.

---

## Curriculum Overview: 16 Weeks, 5 Phases

### Summary by Month

| Month | Focus |
|---|---|
| Month 1 | PHP Modern + Laravel Fundamentals |
| Month 2 | Laravel + React Inertia + Multi-user Dashboard |
| Month 3 | Queues + Tool Calling + Structured Outputs + AI Workflows |

---

## Phase 0 — Mindset Orientation (2–3 days)

**Goal:** Reset mental model, not write code yet.

Coming from JS, the learner is used to:
- `npm`, async-centric runtime, Express route handlers, object/array-heavy patterns, frontend/backend split

In PHP/Laravel, the learner needs to get comfortable with:
- Composer as package manager
- Request lifecycle per request (not event loop)
- `controller / service / job / policy / model` structure
- Framework that is more "batteries included"
- More structured server-side flow

**Output:** Understand that Laravel is a backend operating system, not a React killer.

---

## Phase 1 — PHP Modern for JS Developers (2 weeks)

### Week 1: Core Syntax and Mental Model

Topics:
- Variable, array, associative array
- Function, class & object, visibility (`public/private/protected`)
- Type hints and return types
- Null handling, exceptions, namespace
- Composer autoloading

JS parallels:
- Associative array ≈ plain object literal
- Class syntax is similar to JS class but with stricter typing
- Exception handling is more central in backend flow
- `modules/import` → namespace + Composer autoload

**Exercise:** Mini CLI script — parse array of users, filter + map data, validate input, output JSON.

### Week 2: Modern PHP (Not Legacy PHP)

Topics:
- Clean OOP: interfaces, traits, enums
- Collections mindset and basic DTOs
- Installing Composer packages
- Testing basics with PHPUnit / Pest mindset

**Important note for AI assistant:** When suggesting PHP code, always use modern PHP style — typed properties, return types, PSR-friendly, avoid legacy procedural patterns.

**Mini project:** Simple task runner — create task, list tasks, mark done, export JSON.

**Phase 1 target:** Read modern PHP code without panic, write non-framework logic comfortably.

---

## Phase 2 — Laravel Fundamentals (3 weeks)

**Note:** Laravel 12 React starter kit uses Inertia 2, React 19, TypeScript, Tailwind 4, shadcn/ui.

### Week 3: Laravel Structure

Topics: install, folder structure, routes, controllers, request/response lifecycle, config, env, artisan, migrations, seeding, Blade vs Inertia overview.

**Exercise:** Notes Dashboard app — index, create, edit, delete routes.

### Week 4: Database + Eloquent

Topics: models, relationships, eager loading, query scopes, mass assignment, factories, seeders.

Must know:
- `hasMany`, `belongsTo`, `belongsToMany`
- N+1 problem and `with()` eager loading
- Pagination, filter/sort basics

**Exercise:** User has Projects, Project has Tasks — dashboard with status filter.

### Week 5: Auth, Middleware, Validation, Authorization

Topics: starter kit auth (login/register), middleware, form request validation, policies/gates, roles & permissions.

**Exercise:** Roles: admin / staff / viewer. Only admin can delete. Staff can only update their own records. Proper form create/update validation.

**Phase 2 target:** Build a real backend app, not just a CRUD tutorial.

---

## Phase 3 — Laravel for Modern Dashboard (JS Perspective) (3 weeks)

### Week 6: Laravel + Inertia + React

Topics: why Inertia fits React developers, server-side routing + React pages, props from controller to React page, form handling, layouts, shared data.

**Mental model:**
- Backend logic → Laravel
- Pages → React
- Transport → Inertia
- This is NOT a full API split

**Exercise:** Simple analytics dashboard — data table + filter + create/edit modal.

### Week 7: Service Layer and Clean Architecture

Topics: avoid fat controllers, when to create service class, action class, repository (when needed), DTOs/request objects, domain-ish separation.

**Goal:** Write scalable Laravel, not "fat controller" style.

**AI assistant note:** When reviewing code, flag if business logic is placed directly in controllers. Suggest extracting to service or action class when appropriate.

### Week 8: File Upload, Notifications, Logs, Audit Trail

Topics: file storage (local/S3), signed access, private files, notifications (email, database), activity logs, admin observability.

**Why this matters for agentic apps:** Agents frequently deal with documents, artifacts, and execution traces — this infrastructure is foundational.

**Phase 3 milestone:** Multi-user modern dashboard with auth, roles, CRUD, file upload, activity log, React UI via Inertia.

---

## Phase 4 — Backend Engineering Ready for AI / Agentic Workflows (4 weeks)

### Week 9: Queues, Jobs, Scheduler

Topics: queue concept, async jobs, retries, failed jobs, scheduler, idempotency mindset.

**Exercise:** Upload file → parse in background → generate summary → save result → send notification after job completes.

**Why critical for agentic engineering:** Agent loops need to call a tool, wait for result, save state, continue to next step. Queues are the backbone.

### Week 10: API Design and Structured Backend Contracts

Topics: REST API basics, standard JSON response shape, API Resources/transformers, request validation, lightweight versioning, webhook handling.

**Connection to AI:** LLMs work best when the backend has:
- Clear schema
- Consistent output shape
- Consistent error shape
- Actions that can be called deterministically

OpenAI function calling and Structured Outputs are designed to call tools/functions with arguments matching a JSON Schema.

### Week 11: Tool Calling, Responses API, Structured Outputs

Topics: what is tool/function calling, how models decide when to call a tool, JSON schema for tool definitions, structured outputs, orchestration loop in backend.

**Important:** Use **Responses API** as the primary primitive for agentic apps. Assistants API is deprecated (sunset: August 26, 2026).

**Exercise:** Build `AI Actions` module:
- `summarize_ticket`
- `extract_invoice_fields`
- `route_customer_issue`
- `create_followup_task`

Each action: has an input schema, is called by the model, Laravel backend executes, result saved to DB.

### Week 12: Agent Workflows, Memory, Guardrails

Topics: single-shot AI vs agent loop, state persistence, step execution log, retry & fallback, human-in-the-loop, permission checks before tool runs, audit trail.

**Exercise:** "Support Ops Agent" — read ticket, extract intent, suggest action, admin approval, create internal task, save reasoning summary and tool outputs.

**Phase 4 target:** Understand that agentic engineering = backend workflow + schema/tool contracts + queues + state + auth + observability. Not just a chatbot.

---

## Phase 5 — MCP, Integrations, Production Readiness (4 weeks)

MCP (Model Context Protocol) is the standard way to expose tools, prompts, and resources to AI apps. OpenAI Responses API supports remote MCP servers. PHP ecosystem now has an official MCP PHP SDK.

### Week 13: MCP Fundamentals

Topics: what is MCP, tools vs resources vs prompts, when a plain Laravel API is enough vs when to expose via MCP.

**Exercise (design):** MCP tool surface for the app:
- `list_projects`
- `get_project_summary`
- `create_internal_task`
- `search_uploaded_documents`

### Week 14: External System Integrations

Topics: inbound/outbound webhooks, email integration, calendar/task integration, file/internal docs search patterns, permission-aware actions.

**Agentic principle:** Do not give the model unrestricted access. Wrap all capabilities as narrow, validated, audited tools.

### Week 15: Testing, Tracing, Observability

Topics: Laravel feature tests, unit tests for services/actions, fake queues/notifications in tests, logging, agent execution tracing, sensitive data redaction.

**Exercises:** Test tool action end-to-end, test job retry, test authorization before tool runs.

### Week 16: Deployment and Capstone

Topics: production env, queue worker setup, scheduler, storage, rate limiting, secret management, monitoring.

**Capstone project: Operations Copilot Dashboard**

Features:
- Multi-user auth with roles & permissions
- CRUD for projects/tickets
- File upload + async parsing job
- AI tool actions via Responses API
- Admin approval workflow
- Execution log / audit trail
- Simple MCP-ready design
- Production-ready deployment

---

## Technology Stack Reference

```
Backend:        PHP 8.x + Laravel 12
Frontend:       React 19 + TypeScript + Tailwind 4 + shadcn/ui
Bridge:         Inertia.js 2
Database:       MySQL / PostgreSQL + Eloquent ORM
Queue:          Laravel Queue (Redis or database driver)
Auth:           Laravel Breeze / starter kit
AI:             OpenAI Responses API + function calling + structured outputs
Protocol:       MCP PHP SDK (for tool/resource exposure)
Testing:        Pest / PHPUnit + Laravel test helpers
Deployment:     Laravel Forge / Vapor / VPS with queue worker + scheduler
```

---

## Code Style Preferences

When the AI assistant generates or reviews code, follow these conventions:

**PHP / Laravel:**
- Always use typed properties and return types
- Prefer service classes or action classes over fat controllers
- Use Form Request classes for validation (not inline `$request->validate()` in controllers)
- Use Eloquent relationships with eager loading to avoid N+1
- Use jobs/queues for anything that doesn't need to be synchronous
- Use policies for authorization logic
- Follow PSR-12 code style

**API responses:**
```php
// Consistent JSON response shape
return response()->json([
    'data' => $resource,
    'message' => 'Success',
]);

// Errors
return response()->json([
    'message' => 'Validation failed',
    'errors' => $validator->errors(),
], 422);
```

**Tool/action pattern:**
```php
// Each AI-callable action should be a dedicated class
class SummarizeTicketAction
{
    public function execute(Ticket $ticket): SummaryResult
    {
        // ...
    }
}
```

---

## What to Avoid (Steer the Learner Away From)

- Full microservices architecture (not yet)
- Full React SPA + Laravel API split (use Inertia instead)
- Over-abstracted repository pattern everywhere
- Learning 20 packages at once
- Focusing on "AI chat UI" before backend contracts are solid
- Using Assistants API (deprecated — use Responses API)

**Bottlenecks in agentic engineering are usually NOT the chat UI.** They are: auth, tool contracts, validation, queues, retries, permission boundaries, logging.

---

## Competency Progression

```
JS Native Beginner       → "How do I make logic work?"
PHP Learner              → "How do I write typed, clean, maintainable backend logic?"
Laravel App Builder      → "How do I build a multi-user app quickly and safely?"
Agentic Backend Engineer → "How do I build backend capabilities that a model can call
                            safely, structured, observable, and async?"
```

**Curriculum target: Agentic Backend Engineer.**

---

## Learning Resources Priority

**Level 1 — Required:**
- php.net (modern PHP docs)
- laravel.com/docs (Laravel 12)
- Laravel starter kits docs
- OpenAI Responses API + function calling docs
- MCP official docs + PHP SDK

**Level 2 — Important:**
- Laravel queues/jobs/scheduler
- Laravel testing (feature & unit)
- Authorization / policies
- Deployment basics

**Level 3 — After strong foundation:**
- Event sourcing
- Domain-driven design
- Microservices / GraphQL
- Full frontend-backend split