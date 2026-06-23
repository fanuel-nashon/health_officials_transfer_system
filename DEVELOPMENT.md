# Development Guide

This document covers the internal architecture, file structure, and conventions for the Nurses & Doctors Transfer Management System.

---

## Architecture Overview

The system is a standard Laravel MVC application with role-based access enforced at the route middleware, controller, and view layers using Spatie Laravel Permission.

```
Request → Route middleware (role:/permission:) → Controller → Model → View
                                                      ↓
                                              Notification dispatch
                                          (database + mail channels)
```

---

## Database Schema

### Core Tables

| Table | Purpose |
|---|---|
| `users` | Authentication accounts |
| `roles` / `permissions` | Spatie RBAC tables |
| `model_has_roles` | Pivot: user ↔ role |
| `locations` | Geographic locations (name, district, region) |
| `facilities` | Health facilities linked to a location |
| `employee_records` | Nurse/doctor profile — title + assigned facility |
| `official_records` | Reviewer profile — title, facility, district, region |
| `transfers` | Transfer requests with approval trail |
| `notifications` | Laravel database notifications |

### Transfers Table Key Columns

| Column | Description |
|---|---|
| `user_id` | The applicant (nurse/doctor) |
| `from_facility_id` | Source facility |
| `to_facility_id` | Destination facility |
| `reason` | Applicant's reason for transfer |
| `level` | Current approval level: `facility`, `district`, `region`, `ministry` |
| `level_status` | Status at current level: `pending`, `approved`, `rejected` |
| `facility_comment` | Comment left by facility admin |
| `district_comment` | Comment left by district officer |
| `region_comment` | Comment left by region officer |
| `ministry_comment` | Comment left by ministry official |

### Jurisdiction Scoping

District and region officers are scoped to their jurisdiction via `official_records.district` and `official_records.region`. Transfers are filtered by matching the `from_facility → location → district/region` chain.

---

## File Structure

```
app/
├── Http/Controllers/
│   ├── AdminController.php          # User listing, show, role assignment, create user
│   ├── DashboardController.php      # Routes each role to its dashboard view
│   ├── FacilityController.php       # Facility CRUD
│   ├── LocationController.php       # Location CRUD
│   ├── NotificationController.php   # Mark read / mark all read
│   ├── ReportController.php         # Stats and transfer report (role-scoped)
│   ├── TransferController.php       # Full transfer lifecycle + notification dispatch
│   └── Auth/
│       └── RegisteredUserController.php  # Extended registration with role/title/facility
├── Models/
│   ├── User.php                     # Notifiable + HasRoles; role_display accessor
│   ├── Transfers.php                # status_label/status_color accessors; helpers
│   ├── EmployeeRecords.php          # Nurse/doctor profile
│   ├── OfficialRecords.php          # Reviewer profile (district/region scope)
│   └── Facility.php                 # location(), transfersFrom(), transfersTo()
└── Notifications/
    ├── TransferSubmittedNotification.php      # Sent to reviewers on submission
    └── TransferStatusUpdatedNotification.php  # Sent to applicant after each review

resources/views/
├── layouts/
│   ├── app.blade.php          # Sidebar + topbar layout; notification bell
│   ├── guest.blade.php        # Split-panel auth layout (green + white)
│   └── sidebar.blade.php      # Role-aware green-900 sidebar
├── components/
│   ├── password-input.blade.php   # Show/hide password toggle (Alpine.js)
│   ├── primary-button.blade.php   # Green primary button
│   └── text-input.blade.php       # Green focus ring input
├── auth/                      # login, register, forgot-password, reset, verify
├── transfers/                 # index, create, show, review
├── admin/                     # dashboard, users (index/show/create), facilities, locations
├── nurse/                     # nurse dashboard
├── facility-admin/            # facility admin dashboard
├── district/                  # district officer dashboard
├── region/                    # region officer dashboard
├── ministry/                  # ministry official dashboard
├── reports/                   # reports index
└── welcome.blade.php          # Custom landing page

database/
├── migrations/
│   ├── ...laravel defaults...
│   ├── 2026_06_23_000001_add_reason_and_comments_to_transfers_table.php
│   ├── 2026_06_23_000002_add_district_region_to_official_records_table.php
│   └── 2026_06_23_100000_create_notifications_table.php
└── seeders/
    ├── DatabaseSeeder.php        # Orchestrates seed order
    ├── RolePermissionSeeder.php  # 6 roles + scoped permissions
    ├── AdminUserSeeder.php       # admin@health.go.tz
    ├── FacilitySeeder.php        # 36 facilities + 22 locations across 13 regions
    └── NurseSeeder.php           # 20 nurse/doctor accounts

routes/
└── web.php                    # All application routes (auth, transfers, admin, reports)

bootstrap/
└── app.php                    # Spatie middleware aliases registered here (Laravel 12)
```

---

## Transfer Approval Workflow

```
Nurse submits → [level: facility, status: pending]
                        ↓
         Facility Admin reviews
         ├── Reject → [status: rejected]  ← END
         └── Approve → [level: district, status: pending]
                        ↓
         District Officer reviews
         ├── Reject → [status: rejected]  ← END
         └── Approve → [level: region, status: pending]
                        ↓
         Region Officer reviews
         ├── Reject → [status: rejected]  ← END
         └── Approve → [level: ministry, status: pending]
                        ↓
         Ministry Official reviews
         ├── Reject → [status: rejected]  ← END
         └── Approve → [status: approved] ← FINAL
```

Each transition fires `TransferStatusUpdatedNotification` to the applicant and `TransferSubmittedNotification` to the next level's reviewers.

---

## Notification System

### Channels

Both notification classes implement `via()` returning `['database', 'mail']`.

- **`database`** — stored in the `notifications` table; consumed by the bell dropdown in the topbar
- **`mail`** — dispatched via Laravel's mail system; requires SMTP config in `.env`

### Trigger Points

| Event | Notification | Recipients |
|---|---|---|
| Nurse submits transfer | `TransferSubmittedNotification` | Facility admins at `from_facility` |
| Reviewer approves (not final) | `TransferStatusUpdatedNotification` (forwarded) | Applicant |
| Transfer forwarded to next level | `TransferSubmittedNotification` | Next level reviewers |
| Reviewer rejects | `TransferStatusUpdatedNotification` (rejected) | Applicant |
| Ministry approves (final) | `TransferStatusUpdatedNotification` (approved) | Applicant |

### In-app Bell

The notification bell in `layouts/app.blade.php` loads the 8 most recent notifications per page request. Each row links to `GET /notifications/{id}/read` which marks it read and redirects to `data.action_url`. A "Mark all read" form posts to `POST /notifications/read-all`.

---

## Middleware & Authorization

Spatie middleware aliases are registered in `bootstrap/app.php` (Laravel 12 has no `Http/Kernel.php`):

```php
$middleware->alias([
    'role'               => RoleMiddleware::class,
    'permission'         => PermissionMiddleware::class,
    'role_or_permission' => RoleOrPermissionMiddleware::class,
]);
```

Route-level protection uses `->middleware('role:nurse_doctor')` or `->middleware('permission:view_reports')`.

In Blade, use `@role` / `@endrole` for role checks and `@can` / `@endcan` for permission checks. Do **not** use `@permission` — it was removed in Spatie v6.

---

## Adding a New Role

1. Add the role name to `RolePermissionSeeder.php` with its permissions.
2. Add a case to `User::getRoleDisplayAttribute()` in `User.php`.
3. Create a dashboard view under `resources/views/<role-slug>/dashboard.blade.php`.
4. Add a case to `DashboardController::index()` routing to the new view.
5. Add sidebar links in `layouts/sidebar.blade.php` inside a `@role('new_role')` block.
6. Register the role as an allowed value in `RegisteredUserController` and `AdminController::storeUser()`.

---

## Running Seeders Individually

```bash
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=FacilitySeeder
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=NurseSeeder
```

Full reset:

```bash
php artisan migrate:fresh --seed
```

---

## Commit History

| Commit | Milestone |
|---|---|
| `f176839` | Extend models with relationships and transfer workflow migrations |
| `1861455` | Role-based controllers, routing, and all application views |
| `175a28d` | Seeders — roles, facilities, locations, nurse accounts |
| `51408d5` | Custom green UI theme, layouts, landing page, Tailwind CDN |
| `33040c1` | Transfer notifications (mail + database) and password toggle |
| `9b30bd1` | Fix Spatie middleware aliases and `@can` Blade directive |
