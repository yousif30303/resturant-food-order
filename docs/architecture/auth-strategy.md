# Authentication & Authorization Strategy

## 1. Overview

Authentication answers: who is the actor?

Authorization answers: what is the actor allowed to do?

This platform uses separate actor types because the business responsibilities are different:

- admins manage the platform
- clients manage restaurants they own
- users browse, order, and review

Keeping actors separate avoids permission overlap, keeps guard logic clear, and supports cleaner future scaling.

---

## 2. Actor Types

### Admin

- Purpose: platform operator for the admin panel
- Table: `admins`
- Guard: `admin`
- Provider: `admins`
- Access area: admin panel

### Client

- Purpose: restaurant owner/operator for the client panel
- Table: `clients`
- Guard: `client`
- Provider: `clients`
- Access area: client panel

### User

- Purpose: end customer using the public website and user flow
- Table: `users`
- Guard: `web`
- Provider: `users`
- Access area: public website, cart, checkout, profile, orders, reviews

---

## 3. Guard Strategy

| Actor | Guard | Provider | Table | Area |
|---|---|---|---|---|
| Admin | `admin` | `admins` | `admins` | Admin panel |
| Client | `client` | `clients` | `clients` | Client panel |
| User | `web` | `users` | `users` | Public website / user flow |

Guard mapping:

- `admin` guard -> `admins` table
- `client` guard -> `clients` table
- `web` guard -> `users` table

This separation is intentional and should remain the default direction for v1.

---

## 4. Spatie Permission Strategy

Spatie Laravel Permission is used for Admin RBAC only in v1.

Rules:

- `Admin` model should use `HasRoles`
- roles and permissions control admin panel access
- clients do not use Spatie permissions in v1
- users do not use Spatie permissions in v1
- client and user access is controlled by guards plus ownership policies

Why:

- admin access is role-driven and operational
- client and user access is mostly ownership-based in v1
- restricting Spatie to admins keeps the authorization model simpler and more maintainable

---

## 5. Recommended Admin Roles

Recommended v1 roles:

- `Super Admin`
- `Admin`
- `Moderator`

Suggested intent:

- `Super Admin`: full platform access
- `Admin`: operational management access
- `Moderator`: limited approval/moderation access

---

## 6. Recommended Permission Groups

Recommended permission groups for admin RBAC:

- admin management
- role management
- permission management
- city management
- category management
- restaurant approval
- order management
- review moderation
- banner management
- coupon management
- settings management

These groups should later be broken into specific permissions such as:

- `admins.manage`
- `roles.manage`
- `permissions.manage`
- `restaurant-requests.review`
- `orders.manage`
- `reviews.moderate`

---

## 7. Route Protection Strategy

### Public website routes

- open by default
- use `web` middleware group

### Admin routes

- use `auth:admin`
- use `active.admin`

### Client routes

- use `auth:client`
- use `active.client`

### User routes

- use `auth:web`
- use `active.user`

### Active account middleware

- `active.admin`
- `active.client`
- `active.user`

Purpose:

- block inactive accounts even if authentication succeeds
- force logout if an account is inactive
- keep access handling consistent across all protected areas

---

## 8. Password Broker Strategy

Password reset handling is separated by actor type:

- `admins`
- `clients`
- `users`

Each broker uses its own provider while sharing the same reset token table in v1:

- table: `password_reset_tokens`

Why:

- reset logic remains isolated by actor type
- guard/provider separation is preserved
- future customization remains possible without rewriting auth structure

---

## 9. Authorization Rules

### Admins

- use Spatie roles and permissions
- access is permission-driven

Examples:

- admin permission required to approve restaurants
- admin permission required to manage roles
- admin permission required to moderate reviews

### Clients

- use ownership policies
- client can manage only records they own

Examples:

- client can manage only own restaurants
- client can manage only menus/products/galleries of own restaurants
- client cannot access another client’s restaurant data

### Users

- use ownership policies
- user can access only own customer resources

Examples:

- user can view only own orders
- user can review only own completed orders
- user cannot access another user’s profile/order data

---

## 10. Implementation Notes

- `Admin` model uses `Authenticatable` plus Spatie `HasRoles`
- `Client` model uses `Authenticatable`
- `User` model uses `Authenticatable`
- configure Spatie `guard_name` as `admin` where needed
- roles and permissions are seeded during the seeding foundation work
- middleware must be applied consistently across route groups
- controllers should not contain large authorization logic blocks
- use policies for ownership checks and Spatie middleware/checks for admin RBAC

---

## 11. Security Notes

- do not mix admin/client/user sessions
- do not collapse all actors into one users table
- do not give Spatie permissions to clients or users in v1
- apply throttling on login and password reset routes
- block inactive accounts with active-account middleware
- keep authentication and authorization concerns separate
- do not trust UI visibility alone for access control

---

## 12. Final Checklist

- guards configured
- providers configured
- password brokers configured
- Spatie installed and configured
- `Admin` model uses `HasRoles`
- roles and permissions seeded
- admin routes protected
- client routes protected
- user routes protected
- ownership policies planned for restaurant, product, order, and review access
