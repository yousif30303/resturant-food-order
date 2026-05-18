# Folder Structure

## Services

Business logic and workflows.

Examples:
- RestaurantApprovalService
- OrderPlacementService
- CouponValidationService

## Repositories

Reusable query logic for complex filters, reports, and searches.

## Policies

Authorization rules.

## Requests

Validation logic using Form Request classes.

## Rules

- Controllers must stay thin.
- Business logic belongs in services.
- Complex queries belong in repositories.
- Validation belongs in Form Requests.
- Authorization belongs in Policies.