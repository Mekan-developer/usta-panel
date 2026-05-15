# Usta Panel

Personal developer portfolio & admin panel — built with **Laravel 11**, **Inertia.js v2**, **Vue 3**, and **Tailwind CSS v3**.

## Features

### Public Portfolio
- Responsive single-page portfolio (hero, skills, experience, projects)
- Optional password-protected private mode
- Contact form with rate limiting

### Admin Dashboard
- **Server monitoring** — track server uptime/status, manual or scheduled health checks, online/offline events with logging
- **Portfolio management** — create/edit projects with image galleries (chunked upload, background processing)
- **Portfolio info** — manage hero section, skills (categorized), and work experience
- **Learning tracker** — track learning topics by category and status
- **CV management** — upload and manage downloadable CV
- **Contact inbox** — view and delete incoming contact messages
- **Portfolio settings** — privacy password, public visibility

### Technical Highlights
- Repository Pattern (all queries behind interfaces bound in `AppServiceProvider`)
- Action classes for every write operation
- Form Requests for all validation (`Store*Request` / `Update*Request`)
- Events + Listeners for server status changes (no direct email in services)
- Queue jobs for server checks and image processing
- Model Observers for project/image lifecycle hooks
- Localization in **Turkmen (tk)** and **Russian (ru)** via `vue-i18n` + Pinia locale store

## Tech Stack

| Layer | Package | Version |
|-------|---------|---------|
| Backend | Laravel | 11 |
| Auth | Laravel Sanctum + Breeze | 4 / 2 |
| SPA bridge | Inertia.js | 2 |
| Frontend | Vue 3 + `<script setup>` | 3 |
| State | Pinia | 3 |
| Styling | Tailwind CSS | 3 |
| Routing (client) | Ziggy | 2 |
| i18n | vue-i18n | 11 |
| Build | Vite | 5 |
| Testing | PHPUnit | 10 |
| Static analysis | Larastan / PHPStan | - |
| Code style | Laravel Pint | 1 |

## Requirements

- PHP 8.3+
- Composer
- Node.js 20+ / npm
- SQLite (default) **or** MySQL / PostgreSQL
- Redis (for queues & caching in production)

## Installation

```bash
# 1. Clone
git clone <repo-url> usta-panel
cd usta-panel

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Database
php artisan migrate --seed

# 6. Build frontend
npm run build

# 7. Start dev server
composer run dev
# or separately:
php artisan serve
npm run dev
```

## Environment Variables

Key variables to configure in `.env`:

```dotenv
APP_NAME="Usta Panel"
APP_URL=http://localhost

# Database (SQLite by default)
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=usta_panel

# Queue (use Redis in production)
QUEUE_CONNECTION=database

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="your@gmail.com"

# Redis (for Horizon in production)
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

## Project Structure

```
app/
├── Actions/          # Single-purpose operation classes
│   ├── Portfolio/
│   ├── Profile/
│   └── Server/
├── Contracts/
│   └── Repositories/ # Repository interfaces
├── Enums/            # ProjectType, ServerStatus, SkillCategory, etc.
├── Events/           # ServerWentOffline, ServerCameOnline
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── Dashboard/
│   │   └── PublicPortfolioController.php
│   ├── Middleware/   # SetLocale, HandleInertiaRequests
│   ├── Requests/     # StoreXxxRequest / UpdateXxxRequest per entity
│   └── Traits/       # WithNotification
├── Jobs/             # CheckServerJob, ProcessProjectImageJob
├── Listeners/
├── Mail/
├── Models/
├── Observers/
├── Repositories/     # Concrete implementations
└── Services/

resources/js/
├── Pages/
│   ├── Auth/
│   ├── Dashboard/
│   │   ├── Portfolio/
│   │   ├── Servers/
│   │   └── Learning/
│   └── Public/
├── Layouts/
├── Components/
└── stores/           # Pinia stores (locale, notifications, etc.)

lang/
├── ru/               # Russian translations
└── tk/               # Turkmen translations
```

## Running Tests

```bash
# All tests
php artisan test --compact

# Single file
php artisan test --compact tests/Feature/ExampleTest.php

# Filter by name
php artisan test --compact --filter=testName
```

## Code Quality

```bash
# Fix code style
vendor/bin/pint --dirty --format agent

# Static analysis
vendor/bin/phpstan analyse
```

## Queue Worker

```bash
# Development
php artisan queue:work

# Production (via Horizon)
php artisan horizon
```

## Localization

The application supports **Turkmen (tk)** and **Russian (ru)**. Translation keys live in `lang/tk/` and `lang/ru/`. The active locale is managed via Pinia and persisted in `localStorage`.

## License

MIT
