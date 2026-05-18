# ERD v1 — Restaurant Ordering Platform

## Purpose

This document defines the initial database design for the restaurant ordering platform.

The ERD supports:
- Public website browsing
- Admin panel
- Client panel
- Restaurant approval flow
- Menu/product/gallery management
- Cart and checkout
- Order management
- Reviews, banners, coupons
- Reports and exports

---

## 1. Domain Breakdown

### Access & Identity
- admins
- roles
- permissions
- admin_role
- permission_role
- clients
- users
- password_reset_tokens

### Master Data
- cities
- categories
- settings

### Restaurant Domain
- restaurant_requests
- restaurants

### Content Domain
- menus
- products
- galleries
- banners

### Commerce Domain
- carts
- cart_items
- coupons
- orders
- order_items

### Engagement Domain
- reviews

---

## 2. Relationship Map

ADMINS >---< ROLES  
ROLES >---< PERMISSIONS  

CLIENTS 1---< RESTAURANT_REQUESTS  
CLIENTS 1---< RESTAURANTS  

CITIES 1---< RESTAURANT_REQUESTS  
CATEGORIES 1---< RESTAURANT_REQUESTS  

CITIES 1---< RESTAURANTS  
CATEGORIES 1---< RESTAURANTS  

ADMINS 1---< RESTAURANT_REQUESTS (reviewed_by)  
ADMINS 1---< RESTAURANTS (approved_by)  

RESTAURANT_REQUESTS 1---0..1 RESTAURANTS  

RESTAURANTS 1---< MENUS  
RESTAURANTS 1---< PRODUCTS  
RESTAURANTS 1---< GALLERIES  
RESTAURANTS 1---< ORDERS  
RESTAURANTS 1---< REVIEWS  

MENUS 1---< PRODUCTS  

USERS 1---< CARTS  
CARTS 1---< CART_ITEMS  
PRODUCTS 1---< CART_ITEMS  

USERS 1---< ORDERS  
ORDERS 1---< ORDER_ITEMS  
PRODUCTS 1---< ORDER_ITEMS  

COUPONS 1---< ORDERS  

USERS 1---< REVIEWS  
ORDERS 1---< REVIEWS  

---

## 3. Status Columns

restaurant_requests.status: pending, approved, rejected  
restaurants.status: pending, approved, rejected  
restaurants.is_active: true/false  
orders.status: submitted, confirmed, processing, delivered, cancelled  
reviews.status: pending, approved, rejected  

---

## 4. Key Design Decisions

- Separate auth tables for admins, clients, users  
- Admin RBAC using roles and permissions  
- Separate restaurant_requests from restaurants  
- One client can own multiple restaurants  
- One order belongs to one restaurant  
- order_items store snapshot fields  
- Coupons are global in v1  
- Settings use key-value structure  

---

## 5. Sprint Mapping

Sprint 0: core tables and foundation  
Sprint 1: restaurants, categories, cities, banners  
Sprint 2: admin auth and RBAC  
Sprint 3: master data and restaurant approval  
Sprint 4: client onboarding  
Sprint 5: menus, products, galleries  
Sprint 6–7: carts, orders  
Sprint 8: reviews, coupons, banners  
Sprint 9: reports  

---

## 6. Approval Criteria

- ERD reviewed and approved  
- Relationships validated  
- Status columns confirmed  
- Ready for migration implementation  