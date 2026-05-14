# Restaurant Ordering Platform

Laravel-based restaurant ordering platform with three application surfaces:

- Public website
- Admin panel
- Client panel

This repository is currently focused on Sprint 0 foundation work: auth structure, schema, seeders, file storage, logging, security baseline, and project architecture.

## Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL
- Blade
- Vite
- Tailwind CSS 4

## Project Overview

The system is being built as a modular monolith so future sprint work can stay organized without introducing premature complexity.

Core architectural areas already prepared:

- multi-guard authentication
- base service/repository/request structure
- upload strategy
- logging baseline
- security baseline
- foundational database schema and seeders

## Quick Start

1. Clone the repository.
2. Install PHP dependencies.
3. Create and configure `.env`.
4. Generate the app key.
5. Create the database.
6. Run migrations and seeders.
7. Create the storage symlink.
8. Start the app.

## Installation

### 1. Install dependencies

```bash
composer install
npm install
```

### 2. Create `.env`

```bash
copy .env.example .env
```

If `copy` does not work in your shell:

```bash
cp .env.example .env
```

### 3. Configure `.env`

Minimum required values:

```env
APP_NAME="Restaurant Ordering Platform"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_db
DB_USERNAME=root
DB_PASSWORD=

LOG_CHANNEL=daily
LOG_STACK=daily
LOG_LEVEL=debug
LOG_DAILY_DAYS=30

FILESYSTEM_DISK=public
UPLOAD_DISK=public
```

### 4. Generate app key

```bash
php artisan key:generate
```

### 5. Create database

Create a MySQL database before running migrations:

```text
restaurant_db
```

### 6. Run migrations

```bash
php artisan migrate
```

### 7. Seed demo data

```bash
php artisan db:seed
```

Fresh rebuild with seeders:

```bash
php artisan migrate:fresh --seed
```

### 8. Create storage link

```bash
php artisan storage:link
```

### 9. Start the app

```bash
php artisan serve
```

Optional frontend dev server:

```bash
npm run dev
```

Default local URL:

```text
http://127.0.0.1:8000
```

## Example Setup Commands

Run step by step:

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
```

Full rebuild:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

## Demo Accounts

Seeders currently provide these accounts:

### Admin

- Email: `admin@restaurant.com`
- Password: `password`

### Clients

- Email: `client1@restaurant.com`
- Password: `password`
- Email: `client2@restaurant.com`
- Password: `password`
- Email: `client3@restaurant.com`
- Password: `password`

### Users

- No end-user demo account is seeded yet in the current foundation scope.

## Guard Strategy

The project uses separate guards and tables by design:

- `admin` guard -> `admins` table
- `client` guard -> `clients` table
- `web` guard -> `users` table

Why they are separated:

- admin users need platform-level access and RBAC
- clients represent business owners/operators
- users represent public customers
- each actor type will grow different permissions, workflows, and profile data

Access responsibilities:

- Admin: platform management, approvals, RBAC, settings, moderation
- Client: restaurant onboarding, restaurant management, menus, products, galleries
- User: browsing, cart, checkout, orders, reviews

## Route Structure

Route files:

- `routes/web.php`
- `routes/admin.php`
- `routes/client.php`
- `routes/website.php`
- `routes/console.php`

Current organization:

- `routes/website.php`
  - public website routes
  - route name prefix: `website.`
  - example: homepage `/`

- `routes/admin.php`
  - admin panel routes
  - URL prefix: `/admin`
  - route name prefix: `admin.`

- `routes/client.php`
  - reserved for client panel routes
  - intended future prefix: `/client`
  - intended future route name prefix: `client.`

- `routes/web.php`
  - framework-level or temporary local routes
  - currently used for local/dev utilities

Public vs protected route direction:

- public website routes stay in `website.php`
- admin routes should use `auth:admin` and `active.admin`
- client routes should use `auth:client` and `active.client`
- authenticated user routes should use `auth:web` and `active.user`

## View Structure

Main view folders:

- `resources/views/admin`
- `resources/views/client`
- `resources/views/website`
- `resources/views/layouts`
- `resources/views/errors`

Expected organization:

- `admin`
  - admin pages
  - admin partials
  - dashboard modules

- `client`
  - client panel pages
  - client partials
  - onboarding and restaurant management modules

- `website`
  - public pages
  - home/menu/cart/checkout/profile/auth modules
  - website partials

Layouts and partials:

- `resources/views/layouts`
  - shared layout files
- `resources/views/*/partials`
  - reusable UI fragments such as header, sidebar, footer

Module organization rule:

- keep pages grouped by domain/module
- do not place unrelated pages in flat view folders
- extract repeatable UI into partials early

## Storage Setup

Uploads use Laravel public storage.

Important points:

- default disk: `public`
- upload disk: `public`
- public link: `public/storage -> storage/app/public`

Run:

```bash
php artisan storage:link
```

Related docs:

- `config/uploads.php`
- `docs/development/upload-strategy.md`

## Useful Artisan Commands

```bash
php artisan serve
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed
php artisan storage:link
php artisan route:list
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
php artisan app:log-sample
```

## Architecture References

Read these after setup:

- `docs/database/erd.md`
- `docs/architecture/folder-structure.md`
- `docs/architecture/logging.md`
- `docs/architecture/security-baseline.md`
- `docs/development/upload-strategy.md`

## Troubleshooting

### Storage link issues

If uploaded files are not accessible:

```bash
php artisan storage:link
```

If the link already exists but behaves incorrectly:

```bash
php artisan storage:unlink
php artisan storage:link
```

Also verify:

- files are stored under `storage/app/public`
- generated paths are relative
- `public/storage` exists

### Database connection issues

Check:

- MySQL service is running
- database exists
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` are correct in `.env`

Then clear config:

```bash
php artisan config:clear
```

### Config cache issues

If `.env` changes are not reflected:

```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

### Permissions issues

Make sure these paths are writable:

- `storage/`
- `bootstrap/cache/`

This is especially important for:

- logs
- cache
- sessions
- compiled views
- uploaded files

### Migration conflicts

If your local schema is out of sync during development:

```bash
php artisan migrate:fresh --seed
```

Use this only when it is safe to reset local data.

### Frontend assets not loading

If Vite assets are needed during development:

```bash
npm install
npm run dev
```

If only server-rendered Blade pages are being tested, `php artisan serve` is usually enough.

## Final Onboarding Check

A new developer should be able to do all of the following using this README:

1. clone the repository
2. install Composer and NPM dependencies
3. create and configure `.env`
4. generate `APP_KEY`
5. create the database
6. run migrations
7. seed demo data
8. create the storage symlink
9. start the Laravel app
10. log in with a demo admin/client account
11. understand route, guard, and view structure
12. find deeper architecture docs when needed
