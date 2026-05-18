# View Structure

## 1. Overview

Views are separated by application area so UI concerns stay isolated:

- public website
- admin panel
- client panel
- shared error pages

This separation keeps theme usage clear, prevents layout mixing, and makes future feature work easier to organize.

Recommended Blade structure:

- layouts for area-level page wrappers
- partials for repeated layout fragments
- page folders for feature/module screens

General rule:

- layout -> page wrapper
- partial -> reusable section
- page -> feature-specific screen

---

## 2. Main View Structure

```text
resources/views/
├── website/
├── admin/
├── client/
└── errors/
```

Purpose of each folder:

- `website/`
  - public and customer-facing pages
- `admin/`
  - platform management pages
- `client/`
  - restaurant owner/operator pages
- `errors/`
  - safe framework error pages

---

## 3. Website Views

Recommended structure:

```text
resources/views/website/
├── layouts/
│   └── app.blade.php
├── partials/
│   ├── header.blade.php
│   └── footer.blade.php
├── home/
│   └── index.blade.php
├── restaurants/
│   ├── index.blade.php
│   ├── show.blade.php
│   └── gallery.blade.php
├── cart/
├── checkout/
├── orders/
└── reviews/
```

Guidelines:

- website views are public and user-facing
- pages should use the website layout
- do not place admin or client UI inside website views
- customer pages may stay inside the website area until the customer section becomes large

---

## 4. Admin Views

Recommended structure:

```text
resources/views/admin/
├── layouts/
│   └── app.blade.php
├── partials/
│   ├── header.blade.php
│   ├── sidebar.blade.php
│   └── footer.blade.php
├── dashboard/
├── auth/
├── roles/
├── permissions/
├── admin-users/
├── cities/
├── categories/
├── restaurant-requests/
├── restaurants/
├── banners/
├── coupons/
├── reviews/
├── orders/
└── settings/
```

Guidelines:

- admin views are for platform management only
- admin theme assets come from `public/admin`
- admin routes use `admin.*` route names
- admin pages must not use website layouts
- admin partials should hold shared admin shell pieces such as header, sidebar, and footer

---

## 5. Client Views

Recommended structure:

```text
resources/views/client/
├── layouts/
│   └── app.blade.php
├── partials/
│   ├── header.blade.php
│   ├── sidebar.blade.php
│   └── footer.blade.php
├── dashboard/
├── auth/
├── profile/
├── restaurant-requests/
├── restaurants/
├── menus/
├── products/
├── galleries/
└── orders/
```

Guidelines:

- client views are for restaurant owners/operators
- client UI may reuse the admin theme structure initially
- client routes use `client.*` route names
- client views must remain separate from admin views even if the shell looks similar

---

## 6. Asset Structure

Recommended public asset structure:

```text
public/
├── admin/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── vendor/
├── website/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── vendor/
└── storage/
```

Rules:

- admin assets use `asset('admin/...')`
- website assets use `asset('website/...')`
- uploaded files use Storage URLs or `/storage/...`
- avoid hardcoded absolute asset paths
- do not mix theme asset roots between areas

Examples:

```blade
<link rel="stylesheet" href="{{ asset('admin/css/app.css') }}">
<script src="{{ asset('website/js/app.js') }}"></script>
<img src="{{ Storage::url($restaurant->logo_path) }}" alt="">
```

---

## 7. Layout Rules

Rules:

- each area has its own layout
- use `@extends` for page templates
- use `@include` for partials
- use `@section` and `@yield` for page content
- avoid duplicated header/footer/sidebar HTML
- never mix admin, client, and website layouts

Website example:

```blade
@extends('website.layouts.app')

@section('content')
    <h1>Restaurant Listing</h1>
@endsection
```

Admin example:

```blade
@extends('admin.layouts.app')

@section('content')
    <h1>Dashboard</h1>
@endsection
```

Client example:

```blade
@extends('client.layouts.app')

@section('content')
    <h1>My Restaurants</h1>
@endsection
```

Partial example:

```blade
@include('admin.partials.sidebar')
```

---

## 8. Naming Conventions

Rules:

- folder names should match route/resource names where practical
- use standard page names:
  - `index.blade.php`
  - `create.blade.php`
  - `edit.blade.php`
  - `show.blade.php`
- use kebab-case for multi-word folders where useful
- keep naming consistent across admin, client, and website areas

Examples:

- `admin/restaurant-requests/index.blade.php`
- `client/products/create.blade.php`
- `website/restaurants/show.blade.php`

Avoid:

- random page names like `page1.blade.php`
- feature folders that do not match route/resource meaning

---

## 9. Component and Partial Guidelines

Guidelines:

- partials are for layout pieces such as header, sidebar, footer, topbar, breadcrumbs
- reusable UI blocks can move into `partials/` or `components/`
- repeated forms should be extracted when reused across create/edit flows
- keep Blade files focused and readable
- avoid large duplicated page templates

Good use cases for extraction:

- filter bars
- table headers/actions
- modal markup
- create/edit form blocks

---

## 10. Error Views

Error view structure:

```text
resources/views/errors/
├── 403.blade.php
├── 404.blade.php
├── 419.blade.php
└── 500.blade.php
```

Guidelines:

- error pages must be safe for public display
- do not expose stack traces or internal technical details
- production should use `APP_DEBUG=false`
- keep language simple and user-facing

---

## 11. Future Expansion

Add `components/` when:

- Blade partial reuse becomes broad across multiple modules
- UI fragments are reused heavily

Add `emails/` when:

- notification and transactional mail templates are introduced

Add `pdf/` when:

- printable invoices, summaries, or reports are required

Add `reports/` when:

- reporting screens or export-oriented report views grow into their own area

Add `exports/` when:

- downloadable export templates need dedicated presentation files

Rule:

- add new top-level view folders only when they reduce confusion and repeated structure

---

## 12. Final Checklist

- website, admin, and client views are separated
- layouts are created per area
- partials are extracted for repeated shell elements
- assets are loaded using `asset()`
- no hardcoded theme paths are used
- uploaded files use storage URLs
- error views folder is prepared
