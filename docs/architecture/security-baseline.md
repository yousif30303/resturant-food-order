# Security Baseline

## Security Architecture Overview

- Use Form Requests as the standard validation layer.
- Keep controllers thin and pass only `$request->validated()` data into services.
- Protect role-specific routes with guard middleware first, then active-account middleware.
- Use named Laravel rate limiters for authentication-sensitive endpoints.
- Use Laravel Storage only for uploads.
- Store relative file paths only in the database.
- Use UUID-based filenames for uploaded files.
- Keep environment secrets in `.env` only and disable debug in production.
- Use policies as the baseline authorization direction for restaurant, product, order, and review modules.

---

## Validation Standards

- All feature endpoints should use Form Requests.
- Avoid large inline validation blocks in controllers.
- Each request class should live inside:
  - `app/Http/Requests/Admin`
  - `app/Http/Requests/Client`
  - `app/Http/Requests/User`
  - `app/Http/Requests/Website`
- Use `$request->validated()` only.
- Extend `App\Http\Requests\BaseFormRequest` by default for shared validation helpers.

Example:

```php
class StoreRestaurantLogoRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'logo' => ['required', ...$this->imageRules('restaurant_logos')],
        ];
    }
}
```

Controller usage:

```php
public function store(StoreRestaurantLogoRequest $request)
{
    $data = $request->validated();
}
```

---

## Middleware Standards

### Route protection strategy

- Public website routes:
  - `web`
- Admin routes:
  - `web`
  - `auth:admin`
  - `active.admin`
- Client routes:
  - `web`
  - `auth:client`
  - `active.client`
- User-authenticated routes:
  - `web`
  - `auth:web`
  - `active.user`

### Registered middleware aliases

- `active.admin`
- `active.client`
- `active.user`

### Inactive account strategy

- Authentication may succeed technically, but inactive accounts must not proceed.
- After `auth:*`, the active middleware:
  - checks `is_active`
  - logs the user out
  - invalidates the session
  - regenerates the CSRF token
  - returns `403`

Middleware example:

```php
Route::prefix('admin')
    ->middleware(['web', 'auth:admin', 'active.admin'])
    ->group(function () {
        // protected admin routes
    });
```

---

## RateLimiter Setup

Configured named rate limiters:

- `admin-login` -> `5/min`
- `client-login` -> `5/min`
- `user-login` -> `10/min`
- `forgot-password` -> `3/min`

Implementation location:

- `App\Providers\AppServiceProvider`

Recommended route usage:

```php
Route::post('/admin/login', ...)->middleware('throttle:admin-login');
Route::post('/client/login', ...)->middleware('throttle:client-login');
Route::post('/login', ...)->middleware('throttle:user-login');
Route::post('/forgot-password', ...)->middleware('throttle:forgot-password');
```

Throttle key convention:

- lowercase identity field when available
- fallback to IP
- IP always included

---

## Upload Security Rules

- Use Laravel Storage only.
- Never move uploaded files manually with raw PHP filesystem calls.
- Allow image uploads only for:
  - `jpg`
  - `jpeg`
  - `png`
  - `webp`
- Validate both file type and max size.
- Use `config/uploads.php` as the source of truth for:
  - paths
  - disk
  - allowed mimes
  - max sizes
- Never trust original filenames.
- Generate UUID filenames only.
- Store relative paths only, not full URLs.

Current helper:

- `App\Services\Shared\FileUploadService::imageRules($key)`

Example validation rule:

```php
'image' => ['required', ...FileUploadService::imageRules('product_images')]
```

---

## Environment Safety Checklist

- `.env` must never be committed.
- `APP_KEY` is required in every environment.
- `APP_DEBUG=true` is allowed locally only.
- `APP_DEBUG=false` is mandatory in production.
- Secrets must remain in environment variables.
- Verify write permissions for:
  - `storage/`
  - `bootstrap/cache/`
- Keep production logging on rotated daily files.

### Local example

```env
APP_ENV=local
APP_DEBUG=true
LOG_CHANNEL=daily
LOG_LEVEL=debug
FILESYSTEM_DISK=public
UPLOAD_DISK=public
```

### Production example

```env
APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=daily
LOG_LEVEL=warning
FILESYSTEM_DISK=public
UPLOAD_DISK=public
```

---

## CSRF Rules

- All web forms must use `@csrf`.
- All state-changing browser requests must remain inside the `web` middleware group.
- Never disable CSRF protection casually for convenience.

---

## Password Rules

- Always use `Hash::make()`.
- Never use:
  - `md5`
  - `sha1`
  - plain text passwords
- Password reset and login flows must follow Laravel auth conventions.

---

## Authorization Baseline

Policy direction for future feature work:

- `RestaurantPolicy`
- `ProductPolicy`
- `OrderPolicy`
- `ReviewPolicy`

Guidelines:

- prefer policies for ownership and role-based authorization
- keep controller authorization explicit
- keep business rules in policies or dedicated services, not Blade views

---

## Error and Logging Notes

- Security-sensitive failures should log structured context without secrets.
- Do not log passwords, tokens, or private personal data.
- Inactive account access attempts, auth failures, upload failures, and moderation actions should produce structured logs.

