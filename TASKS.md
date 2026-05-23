# TASKS

## Выполнено

### Инфраструктура
- [x] Docker Compose с 7 сервисами: app, nginx, redis, horizon, scheduler, reverb, certbot
- [x] Multi-stage Dockerfile (Composer → npm build → PHP 8.3-fpm-alpine)
- [x] SSL через Let's Encrypt + Certbot авто-обновление
- [x] Nginx на портах 8080/8443 с runtime DNS resolver для Reverb
- [x] deploy.sh — один образ собирается, остальные переиспользуют

### Backend
- [x] Laravel 11 + Inertia 2 + Vue 3 — базовая архитектура
- [x] Repository Pattern (7 репозиториев + интерфейсы, бинд в AppServiceProvider)
- [x] Action Pattern (19 action-классов по модулям)
- [x] Form Requests для всей валидации (18 классов)
- [x] Trait WithNotification — notifySuccess() / notifyError() во всех контроллерах
- [x] 5 Enum-классов для статусов и категорий
- [x] Миграции всех 9 таблиц
- [x] Мультиязычность контента: JSON-поля (tk/ru/en) в Project и Experience
- [x] Observers: ProjectObserver (удаление файлов), ProjectImageObserver (диспатч Job)
- [x] Events: ServerCameOnline, ServerWentOffline + Listeners (логирование)
- [x] Horizon + Redis для мониторинга очередей
- [x] Reverb WebSocket сервер настроен

### Модули
- [x] **Аутентификация** — полный Breeze: логин, пароль, сброс, подтверждение email
- [x] **Серверный мониторинг** — CRUD серверов, CheckServerJob (CPU/RAM/disk/uptime), метрики, статусы
- [x] **Портфолио** — CRUD проектов, загрузка изображений, конвертация в WebP через Job, мультиязычные описания
- [x] **Приватные проекты** — пароль-защита с разблокировкой
- [x] **Навыки и опыт** — Skills (frontend/backend/tools), Experiences с локализацией
- [x] **Настройки портфолио** — акцент-цвет, тема, пароль для private
- [x] **CV** — загрузка/удаление PDF
- [x] **Трекер обучения** — LearningTopic с категориями (it/ai/language), статусами и прогрессом
- [x] **Контактные сообщения** — форма на публичном портфолио, авто-ответы через queue, дашборд сообщений
- [x] **Публичное портфолио** — отображение всего портфолио + разблокировка приватных проектов

### Frontend
- [x] Vue 3 + Inertia 2 + Pinia + vue-i18n (ru/tk)
- [x] Tailwind CSS v3
- [x] AdminLayout, AuthenticatedLayout, GuestLayout
- [x] 14 компонентов для публичного портфолио (PfHero, PfProjects, PfContact, PfSkills и др.)
- [x] Базовые UI компоненты: TextInput, Modal, Dropdown, Buttons и т.д.
- [x] Все страницы дашборда (серверы, портфолио, обучение, сообщения, настройки)
- [x] Страница публичного портфолио (Portfolio.vue)

### Тесты
- [x] Feature тесты: аутентификация, профиль, серверы, обучение, публичное портфолио (unlock)
- [x] 14 тестовых файлов

---

## В процессе / Требует проверки

- [ ] Реальная интеграция WebSocket на фронтенде (Laravel Echo подключён в package.json, нужно проверить что события транслируются в UI)
- [ ] Тесты для: Portfolio CRUD, ContactMessage, PortfolioInfo, CV загрузка

---

## Планируется / Идеи

- [ ] API v1 (routes/api/v1.php + App\Http\Controllers\Api\V1\) — в архитектуре прописано, но не создано
- [ ] Уведомления об оффлайне серверов (email) через Event → Listener → Mailable (события есть, мейлы — нет)
- [ ] Тесты для ProcessProjectImageJob и CheckServerJob
- [ ] Метрики серверов в виде графиков на дашборде
- [ ] Пагинация для сообщений и проектов
