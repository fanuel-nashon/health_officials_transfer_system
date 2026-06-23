# Nurses & Doctors Transfer Management System

A web-based transfer management platform for the Government Health Sector of Tanzania. The system digitises the process of submitting, reviewing, and approving transfer requests for nurses and doctors across government health facilities, replacing paper-based workflows with a transparent multi-level approval chain.

---

## Features

- **Online transfer applications** — nurses and doctors submit requests from any device
- **4-tier approval workflow** — Facility → District → Region → Ministry
- **Role-based access control** — each user sees and can do only what their role permits
- **Real-time status tracking** — applicants follow their request at every stage
- **Email & in-app notifications** — alerts on submission, progression, and final decisions
- **Admin user management** — create and manage system users with role assignment
- **Reports & analytics** — workforce distribution statistics per level
- **Custom green-themed UI** — fully branded health sector interface (no default Laravel pages)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Database | PostgreSQL |
| Authentication | Laravel Breeze |
| Authorization | Spatie Laravel Permission v6 |
| Frontend | Tailwind CSS (Play CDN), Alpine.js (CDN) |
| Notifications | Laravel Notifications — `database` + `mail` channels |

---

## Roles & Permissions

| Role | Key Permissions |
|---|---|
| `admin` | Manage users, facilities, locations; view all transfers and reports |
| `nurse_doctor` | Submit transfers, view own transfers |
| `facility_admin` | Review transfers at facility level, view reports |
| `district_officer` | Review transfers at district level, view reports |
| `region_officer` | Review transfers at region level, view reports |
| `ministry_official` | Final approval/rejection, view all transfers and reports |

---

## Quick Start

### Requirements

- PHP 8.2+
- PostgreSQL
- Composer

### Installation

```bash
git clone <repo-url>
cd health_officials_transfer_system

composer install

cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=health_transfer
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

Run migrations and seed:

```bash
php artisan migrate
php artisan db:seed
```

Start the development server:

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000`.

> No build step needed — Tailwind and Alpine.js are loaded from CDN.

---

## Default Credentials

| Role | Email | Password |
|---|---|---|
| System Administrator | admin@health.go.tz | Admin@1234 |
| Sample Nurse (×20) | e.g. amina.rashidi@health.go.tz | Nurse@1234 |

To create users for other roles (facility admin, district officer, etc.) log in as admin and go to **Users → New User**, or register directly via `/register`.

---

## Seeded Data

Running `php artisan db:seed` creates:

- **6 roles** with scoped permissions
- **36 health facilities** across **22 locations** in 13 Tanzanian regions
- **1 admin account**
- **20 nurse/doctor accounts** with Swahili names assigned to random facilities

---

## Mail Notifications

To enable email alerts, configure your SMTP settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@health.go.tz
MAIL_FROM_NAME="Transfer Management System"
```

Emails are sent on:
- Transfer submission (to facility admins)
- Transfer reviewed/forwarded (to the applicant and next-level reviewers)
- Final approval or rejection (to the applicant)

---

## License

This project is developed for the Government Health Sector of Tanzania as part of an academic/government systems initiative.
