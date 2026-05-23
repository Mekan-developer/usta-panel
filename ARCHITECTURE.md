# ARCHITECTURE

## Стек технологий

| Слой | Технология | Версия |
|------|-----------|--------|
| PHP | Laravel | 11 |
| SPA bridge | Inertia.js | v2 |
| Frontend framework | Vue 3 | 3.4 |
| CSS | Tailwind CSS | v3 |
| State management | Pinia | v3 |
| i18n | vue-i18n | v11 |
| Real-time | Laravel Reverb | v1 |
| WebSocket client | Laravel Echo | v2 |
| Queue monitoring | Laravel Horizon | v5 |
| Auth | Laravel Sanctum | v4 |
| Database | SQLite | — |
| Cache / Queue | Redis | — |
| HTTP server | Nginx | — |
| Runtime | PHP-FPM 8.3-alpine | — |

---

## Структура папок

```
app/
├── Actions/               # Атомарные операции (CQRS-like)
│   ├── Portfolio/         # 14 actions: CRUD проектов, изображений, навыков, опыта, настроек, CV
│   ├── Server/            # 3 actions: CRUD серверов
│   └── Profile/           # 2 actions: обновление профиля, удаление аккаунта
├── Contracts/
│   └── Repositories/      # 7 интерфейсов репозиториев
├── Enums/                 # ProjectType, ServerStatus, SkillCategory, LearningCategory, LearningStatus
├── Events/Server/         # ServerCameOnline, ServerWentOffline
├── Http/
│   ├── Controllers/
│   │   ├── Auth/          # 8 auth-контроллеров (Breeze)
│   │   └── Dashboard/     # 8 dashboard-контроллеров
│   ├── Middleware/        # HandleInertiaRequests, SetLocale
│   └── Requests/          # 18 Form Request классов
├── Jobs/                  # CheckServerJob, CheckAllServersJob, ProcessProjectImageJob
├── Listeners/Server/      # LogServerCameOnline, LogServerWentOffline
├── Mail/                  # Mailable-классы для email-уведомлений
├── Models/                # 9 Eloquent моделей
├── Observers/             # ProjectObserver, ProjectImageObserver
├── Providers/             # AppServiceProvider (DI), EventServiceProvider
├── Repositories/          # 7 реализаций репозиториев
├── Services/              # ContactMessageService, LearningTopicService
├── Support/               # PortfolioSettings
└── Traits/                # WithNotification

resources/js/
├── Components/            # UI-компоненты (TextInput, Modal, Buttons, Dropdown...)
│   └── Portfolio/         # PfHero, PfNavBar, PfProjects, PfSkills, PfContact...
├── Layouts/               # AdminLayout, AuthenticatedLayout, GuestLayout
├── Pages/
│   ├── Auth/              # 5 страниц (Login, ForgotPassword, ResetPassword, VerifyEmail, ConfirmPassword)
│   ├── Dashboard/         # Index, Portfolio/*, Servers/*, Learning, Messages, Profile, Settings, Cv
│   └── Public/            # Portfolio.vue
├── composables/           # Vue composables
├── stores/                # Pinia-сторы
└── i18n/                  # Конфиг vue-i18n

routes/
├── web.php                # Публичные + dashboard маршруты
├── auth.php               # Auth маршруты (Breeze)
└── channels.php           # Broadcast каналы

lang/
├── ru/                    # auth, resources, layout, profile, notifications, learning
└── tk/                    # auth, resources, layout, profile, notifications, learning
```

---

## Архитектурные паттерны

### Request → Response Flow

```
HTTP Request
  → FormRequest (валидация)
  → Controller (только HTTP: принять, передать, вернуть)
    → Action (одна операция, одна ответственность)
      → Repository (все запросы к БД — только здесь)
        → Eloquent Model
      → event() (если нужно уведомить систему)
  → Inertia::render() / redirect() с notify
```

### Dependency Injection

Репозитории биндятся через интерфейсы в `AppServiceProvider`:
```
Interface ProjectRepositoryInterface
  → реализация EloquentProjectRepository
```
Сервисы и контроллеры зависят от интерфейса, не от реализации.

### Событийная система

```
Job (CheckServerJob)
  → Event (ServerWentOffline / ServerCameOnline)
    → Listener (LogServer*)
      → [TODO: Mailable для уведомлений об офлайне]
```

### Уведомления в UI

```
Controller
  → WithNotification::notifySuccess() / notifyError()
    → session flash
      → HandleInertiaRequests → shared props
        → Vue компонент читает notification
```

### Локализация контента

**Backend (модели):** JSON-поля `title_translations`, `description_translations` вида:
```json
{"tk": "...", "ru": "...", "en": "..."}
```
Модели имеют методы `localizedTitle()`, `localizedDescription()`.

**Frontend (UI):** vue-i18n + файлы `lang/ru/`, `lang/tk/`.
Локаль хранится в сессии, применяется через `SetLocale` middleware.

---

## Модели и связи

```
User
  (пока без явных связей с другими моделями)

Project ──< ProjectImage
  - thumbnail: string (путь)
  - screenshots: array (JSON)
  - tech_stack: array (JSON)
  - title_translations / description_translations: JSON {tk, ru, en}
  - type: ProjectType enum
  - is_private: bool + пароль в settings

ProjectImage
  - is_processing: bool (ставится true при загрузке, false после Job)

Server ──< ServerMetric
  - token: encrypted
  - status: ServerStatus enum
  - last_metrics: array (cpu, ram, disk, uptime)

Skill
  - category: SkillCategory enum
  - percent: int (0-100)

Experience
  - role / description: JSON {tk, ru, en}

LearningTopic
  - category: LearningCategory enum
  - status: LearningStatus enum
  - period: string (YYYY-MM)
  - progress: int (0-100)

ContactMessage
  - is_read: bool

Setting
  - key / value: key-value store
  - Используется для: portfolio hero info, accent color, theme, private password
```

---

## Очереди

| Очередь | Job | Назначение |
|---------|-----|-----------|
| `servers` | CheckServerJob | HTTP-запрос к серверу, сбор метрик |
| `images` | ProcessProjectImageJob | Конвертация в WebP, масштабирование ≤1200px |
| `default` | CheckAllServersJob | Диспатч CheckServerJob для всех серверов |
| `default` | SendContactReply | Email авто-ответ отправителю |

Мониторинг: Laravel Horizon (`/horizon`).

---

## Docker-сервисы

```
nginx (8080/8443) → app (PHP-FPM:9000)
                  → reverb (WebSocket:8080)
redis ← horizon (queue worker)
redis ← scheduler (artisan schedule:work)
redis ← app (cache, sessions, queues)
certbot → SSL-сертификаты для nginx
```

---

## API

Публичного REST API нет. Весь обмен данными — через Inertia.js (server-side props).  
Структура `/api/v1/` в архитектуре запланирована, но не реализована.
