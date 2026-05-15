# Route Structure

## 1. Overview

Routes are separated by application area so developers can work on one surface without mixing concerns from another surface.

This project has distinct route areas for:

- public website
- admin panel
- client panel
- user/customer flow

Benefits of separation:

- clearer ownership
- simpler middleware application
- predictable route naming
- lower risk of mixing guards
- easier future scaling

Route naming must stay consistent so links, redirects, policies, tests, and permissions remain predictable.

---

## 2. Route Files

### `routes/web.php`

Purpose:

- framework-level web routes
- local/dev test routes
- optional entry point for grouped route loading

This file should stay small and not become a dumping ground for unrelated feature routes.

### `routes/website.php`

Purpose:

- public website routes
- customer-facing browsing flow
- cart, checkout, order, and review routes until a dedicated user route file is needed

### `routes/admin.php`

Purpose:

- admin panel routes
- admin authentication routes
- protected platform management routes

### `routes/client.php`

Purpose:

- client panel routes
- client authentication routes
- protected restaurant-owner workflows

---

## 3. Route Loading Strategy

Two acceptable loading strategies:

### Option A: Separate route registration

Load `website.php`, `admin.php`, and `client.php` separately in bootstrap configuration or route provider setup.

This is the current preferred structure because it keeps each route file focused.

### Option B: Include from `web.php`

`routes/web.php` may require/include `website.php`, `admin.php`, and `client.php`.

This is acceptable if kept clean, but the route files themselves must still remain domain-focused.

Rule:

- keep route files small
- group by business area
- do not mix admin/client/public logic in one file

---

## 4. Website Routes

Website routes are public browsing routes by default.

Characteristics:

- no login required for browsing pages
- name prefix: `website.`
- middleware: `web`

Examples:

- `website.home`
- `website.restaurants.index`
- `website.restaurants.show`
- `website.restaurants.gallery`

Example route group:

```php
Route::name('website.')->group(function () {
    Route::get('/', ...)->name('home');

    Route::prefix('restaurants')->name('restaurants.')->group(function () {
        Route::get('/', ...)->name('index');
        Route::get('/{restaurant}', ...)->name('show');
        Route::get('/{restaurant}/gallery', ...)->name('gallery');
    });
});
```

---

## 5. Admin Routes

Admin routes serve the platform/admin panel.

Characteristics:

- URL prefix: `/admin`
- name prefix: `admin.`
- guest auth routes separated from protected routes
- protected routes use `auth:admin` and `active.admin`
- future support for Spatie permission middleware

Examples:

- `admin.dashboard`
- `admin.auth.login`
- `admin.roles.index`
- `admin.permissions.index`
- `admin.cities.index`
- `admin.restaurants.requests.index`

Example route group:

```php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', ...)->name('auth.login');
        Route::post('/login', ...)->middleware('throttle:admin-login')->name('auth.attempt');
        Route::get('/forgot-password', ...)->name('auth.forgot-password');
    });

    Route::middleware(['auth:admin', 'active.admin'])->group(function () {
        Route::get('/', ...)->name('dashboard');

        Route::resource('roles', ...);
        Route::resource('permissions', ...);
        Route::resource('cities', ...);

        Route::prefix('restaurants/requests')->name('restaurants.requests.')->group(function () {
            Route::get('/', ...)->name('index');
        });
    });
});
```

Spatie direction for admin routes:

- roles/permissions can later be protected with middleware such as:
  - `permission:roles.manage`
  - `permission:permissions.manage`

---

## 6. Client Routes

Client routes serve restaurant owners/operators.

Characteristics:

- URL prefix: `/client`
- name prefix: `client.`
- guest auth routes separated from protected routes
- protected routes use `auth:client` and `active.client`

Examples:

- `client.dashboard`
- `client.auth.login`
- `client.profile.edit`
- `client.restaurant-requests.index`
- `client.products.index`
- `client.galleries.index`

Example route group:

```php
Route::prefix('client')->name('client.')->group(function () {
    Route::middleware('guest:client')->group(function () {
        Route::get('/login', ...)->name('auth.login');
        Route::post('/login', ...)->middleware('throttle:client-login')->name('auth.attempt');
        Route::get('/forgot-password', ...)->name('auth.forgot-password');
    });

    Route::middleware(['auth:client', 'active.client'])->group(function () {
        Route::get('/', ...)->name('dashboard');
        Route::get('/profile/edit', ...)->name('profile.edit');
        Route::resource('restaurant-requests', ...);
        Route::resource('products', ...);
        Route::resource('galleries', ...);
    });
});
```

---

## 7. User Routes

Customer routes may remain inside `routes/website.php` in the early phases.

Characteristics:

- protected customer routes use `auth:web` and `active.user`
- customer pages are still part of the public website experience

Examples:

- `website.cart.index`
- `website.checkout.index`
- `website.orders.index`
- `website.reviews.store`

Current recommendation:

- keep user-facing routes under the `website` namespace until the project grows
- add `routes/user.php` later only if customer-authenticated routes become large enough to deserve their own file

Example:

```php
Route::name('website.')->group(function () {
    Route::middleware(['auth:web', 'active.user'])->group(function () {
        Route::get('/cart', ...)->name('cart.index');
        Route::get('/checkout', ...)->name('checkout.index');
        Route::get('/orders', ...)->name('orders.index');
        Route::post('/reviews', ...)->name('reviews.store');
    });
});
```

---

## 8. Route Naming Rules

Recommended pattern:

```text
panel.resource.action
```

Examples:

- `website.restaurants.index`
- `admin.roles.store`
- `client.products.update`
- `website.checkout.place-order`

Rules:

- use plural resource names where appropriate
- keep names predictable
- keep names stable once introduced
- avoid duplicate route names across route files
- avoid vague names such as:
  - `submitForm`
  - `page1`
  - `saveData`

Prefer explicit domain naming over generic naming.

---

## 9. Middleware Standards

### Website public routes

- `web`

### Admin guest routes

- `web`
- `guest:admin`
- `throttle:admin-login` for login submission
- `throttle:forgot-password` for forgot password submission

### Admin protected routes

- `web`
- `auth:admin`
- `active.admin`

### Client guest routes

- `web`
- `guest:client`
- `throttle:client-login` for login submission
- `throttle:forgot-password` for forgot password submission

### Client protected routes

- `web`
- `auth:client`
- `active.client`

### User protected routes

- `web`
- `auth:web`
- `active.user`
- `throttle:user-login` for login submission

Rule:

- apply middleware at group level whenever possible
- do not repeat middleware route by route unless there is a good reason

---

## 10. Resource Route Guidelines

Use `Route::resource` for standard CRUD when the action is a normal create/read/update/delete operation.

Use explicit routes for workflow actions that are not plain CRUD.

Good candidates for explicit workflow routes:

- approve
- reject
- activate
- deactivate
- restore
- submit for review

Examples:

```php
Route::post('admin/restaurants/{request}/approve', ...)->name('admin.restaurants.requests.approve');
Route::post('admin/restaurants/{request}/reject', ...)->name('admin.restaurants.requests.reject');
Route::patch('admin/restaurants/{restaurant}/activate', ...)->name('admin.restaurants.activate');
Route::patch('admin/restaurants/{restaurant}/deactivate', ...)->name('admin.restaurants.deactivate');
```

Guideline:

- do not hide business workflows inside a generic `update` route if the action is meaningfully different

---

## 11. Future Route Expansion

Add `routes/user.php` when:

- authenticated customer routes become large
- website browsing and authenticated user actions start to feel crowded in one file

Add `routes/api.php` when:

- mobile app or SPA/API endpoints are introduced
- token-based or stateless endpoints are needed

Add `routes/webhooks.php` when:

- third-party callbacks become part of the system
- payment, messaging, or external integrations need isolated endpoint handling

Rule:

- add new route files only when they reduce confusion
- do not split files too early without a real need

---

## 12. Final Checklist

- route files exist
- naming convention is followed
- admin, client, and website routes are separated
- middleware is applied correctly
- login routes are throttled
- protected routes use the correct guard
- active-account middleware is applied to protected routes
- `php artisan route:list` is reviewed regularly
