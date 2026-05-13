# Logging Conventions

## Log Levels

- `emergency` -> system unusable
- `alert` -> immediate action required
- `critical` -> critical system failure
- `error` -> failed operation requiring attention
- `warning` -> recoverable unexpected issue
- `info` -> important business action
- `debug` -> development-only details

---

## What Should Be Logged

### Authentication
- failed login attempts
- inactive account access attempts
- suspicious auth activity

### Admin Actions
- role creation/update
- permission assignment
- restaurant approval/rejection
- settings changes

### Client Actions
- restaurant request submission
- product creation/update/delete
- gallery uploads

### Orders
- order placed
- order status updated
- checkout failure
- coupon application failure

### Uploads
- upload failure
- delete failure
- invalid file attempts

---

## What Should NOT Be Logged

Never log:

- passwords
- tokens
- secrets
- payment details
- full credit card information
- sensitive personal information

---

## Required Context

Important logs should include:

- `action`
- `user_type`
- `user_id`
- `entity_type`
- `entity_id`
- `status`
- `error` message if failed

Optional useful context:

- `request_url`
- `request_method`
- `ip_address`
- `request_id`

---

## Structured Log Example

Success example:

```php
Log::info('Restaurant approved', [
    'action' => 'restaurant_approved',
    'user_type' => 'admin',
    'user_id' => auth('admin')->id(),
    'entity_type' => 'restaurant',
    'entity_id' => $restaurant->id,
    'status' => 'approved',
]);
```

Error example:

```php
Log::error('Restaurant approval failed', [
    'action' => 'restaurant_approval_failed',
    'user_type' => 'admin',
    'user_id' => auth('admin')->id(),
    'entity_type' => 'restaurant',
    'entity_id' => $restaurant->id ?? null,
    'status' => 'failed',
    'error' => $exception->getMessage(),
]);
```

---

## Project Notes

- Use structured context arrays instead of concatenated strings.
- Prefer `info` for meaningful business actions and `error` for failed operations that require attention.
- Keep `debug` logs local/dev focused and remove noisy temporary logs after use.
- Log one useful event per action, not every internal step of the same action.
- Exception messages may be logged, but sensitive request payloads must be sanitized first.
- Shared and feature services should use `App\Services\Shared\AppLogger` by default instead of calling the `Log` facade directly from controllers.

---

## Important Services Note

Use structured logging by default in existing and future services such as:

- `FileUploadService`
- restaurant approval services
- client onboarding services
- product and gallery services
- checkout and order services
- coupon and review moderation services

Recommended pattern:

```php
use App\Services\Shared\AppLogger;

class RestaurantApprovalService
{
    public function __construct(
        private readonly AppLogger $logger,
    ) {
    }

    public function approve(object $restaurant): void
    {
        $this->logger->info('Restaurant approved', [
            'action' => 'restaurant_approved',
            'user_type' => 'admin',
            'user_id' => auth('admin')->id(),
            'entity_type' => 'restaurant',
            'entity_id' => $restaurant->id,
            'status' => 'approved',
        ]);
    }
}
```
