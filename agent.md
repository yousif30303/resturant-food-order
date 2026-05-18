# Engineering Standards

## Tech Stack

- Laravel 10/11
- PHP 8.2+
- MySQL
- Blade Templates
- Spatie Laravel Permission

---

# Project Architecture

This project follows a modular monolith architecture.

Main areas:
- Public Website
- Admin Panel
- Client Panel
- User/Customer Flow

Core principles:
- follow Laravel conventions
- keep implementation scalable
- avoid overengineering
- keep controllers thin
- centralize business logic
- centralize validation
- centralize security standards

---

# Documentation References

Always follow the detailed project documentation under:

```text
docs/
├── architecture/
├── database/
└── development/
```

Important references:

## Architecture
- docs/architecture/auth-strategy.md
- docs/architecture/folder-structure.md
- docs/architecture/route-structure.md
- docs/architecture/view-structure.md
- docs/architecture/logging.md
- docs/architecture/security-baseline.md

## Database
- docs/database/erd.md

## Development
- docs/development/upload-strategy.md

Rules:
- follow documented architecture decisions
- do not bypass documented standards
- keep implementation aligned with Sprint foundation decisions

---

# Controllers

Controllers must remain thin.

Controllers should only handle:
- request
- response
- authorization
- service orchestration

Rules:
- business logic belongs in Services
- validation belongs in Form Requests
- avoid large controllers
- avoid duplicated logic

---

# Services

Business workflows belong in Services.

Shared reusable logic belongs in:

```text
app/Services/Shared
```

Examples:
- FileUploadService
- ActivityLogService
- SlugService

Rules:
- keep services focused
- avoid God services
- prefer constructor dependency injection

---

# Repositories

Use repositories only when needed.

Good use cases:
- reusable complex queries
- reports
- filters
- search logic

Avoid:
- repository-per-model overengineering
- unnecessary abstraction

---

# Validation Rules

Use Form Requests for validation.

Structure:

```text
app/Http/Requests/
├── Admin/
├── Client/
├── User/
└── Website/
```

Rules:
- avoid inline validation in major features
- controllers should use `$request->validated()`
- validation logic belongs inside Form Requests

---

# Authentication & Authorization

Authentication domains are separated.

| Actor | Guard | Table |
|---|---|---|
| Admin | admin | admins |
| Client | client | clients |
| User | web | users |

Rules:
- never mix admin/client/user sessions
- use correct middleware per area
- use Policies for ownership authorization
- use Spatie Laravel Permission for Admin RBAC only
- clients/users use ownership-based authorization

Examples:
- client manages only own restaurants
- user accesses only own orders

---

# Route Rules

Route structure:

```text
routes/
├── web.php
├── website.php
├── admin.php
└── client.php
```

Naming convention examples:

```text
website.restaurants.index
admin.roles.index
client.products.index
```

Rules:
- keep route names predictable
- use plural resource naming
- separate admin/client/website routes
- use middleware consistently
- apply login throttling

---

# View Rules

View structure:

```text
resources/views/
├── website/
├── admin/
├── client/
└── errors/
```

Rules:
- separate website/admin/client layouts
- use layouts + partials
- avoid duplicated Blade code
- avoid hardcoded asset paths

---

# File Upload Rules

Use Laravel Storage only.

Rules:
- store relative paths only
- use UUID filenames
- never trust original filenames
- use FileUploadService for uploads/deletes
- use config/uploads.php for upload rules
- do not store absolute file paths

Allowed image types:
- jpg
- jpeg
- png
- webp

---

# Logging Rules

Use structured logging.

Rules:
- use ActivityLogService where applicable
- log important business actions
- logs must be readable and useful
- include useful context

Never log:
- passwords
- tokens
- secrets
- payment details

---

# Security Rules

Rules:
- use Form Requests
- use CSRF protection for web forms
- use login throttling
- use Hash::make() for passwords
- never use md5/sha1/plain passwords
- APP_DEBUG must be false in production
- .env must never be committed
- secrets must remain in environment variables

---

# Database Rules

Rules:
- use proper foreign keys
- use timestamps on main tables
- use indexes on:
  - foreign keys
  - slug
  - email
  - status
  - is_active
- use soft deletes where appropriate
- avoid unnecessary nullable columns
- prefer explicit relationships

---

# Coding Standards

Rules:
- use clear naming
- avoid duplicated logic
- keep methods small and focused
- prefer constructor dependency injection
- avoid static helper overuse
- prefer services over fat controllers

---

# Before Completing Any Task

Verify:
- implementation follows documented architecture
- validation exists
- authorization exists
- uploads use FileUploadService
- no hardcoded paths
- no sensitive data exposed
- route/view naming conventions followed
- logs are useful
- implementation matches Sprint foundation decisions