# CHANGELOG

## [Unreleased] — текущее состояние

---

## 2026-05-20

### fix: nginx ports → 8080/8443
- Изменены порты nginx с дефолтных на 8080 (HTTP) и 8443 (HTTPS)

### fix: nginx runtime DNS resolver для Reverb upstream
- Добавлен `resolver` в nginx.conf для корректного проксирования Reverb по имени сервиса
- Добавлен `depends_on: reverb` в конфиг docker-compose

### fix: убран `--isolated` из migrate
- Таблица `cache_locks` не существует при первом запуске свежей БД — флаг убран

### fix: рефактор Dockerfile
- Виртуальные build-deps для pecl-расширений
- Composer берётся из официального образа `composer:latest`
- Добавлены `autoconf`, `g++`, `make` для компиляции pecl-расширений

### fix: единый образ для всех сервисов
- `deploy.sh` собирает образ один раз, все сервисы (horizon, scheduler, reverb) его переиспользуют
- Убрана дублирующая сборка образов

### fix: порядок установки зависимостей в Dockerfile
- Composer deps устанавливаются ДО npm build для корректной генерации Ziggy-маршрутов

### feat: установка Horizon и Reverb, оптимизация job-очередей
- Установлен и настроен Laravel Horizon v5
- Установлен и настроен Laravel Reverb v1
- Настроены отдельные очереди: `servers` (для CheckServerJob), `images` (для ProcessProjectImageJob)
- Добавлены сервисы horizon, scheduler, reverb в docker-compose

---

## 2026-05-19

### feat: контактная форма, Gmail, дашборд сообщений, confirmation emails
- Создана модель ContactMessage с миграцией
- Добавлено поле `locale` в contact_messages
- Реализован ContactMessageService (CRUD + email уведомления)
- Реализован PublicPortfolioController@sendMessage с throttle 5/min
- Создан дашборд Messages/Index.vue — просмотр и удаление сообщений
- Авто-ответ отправителю + уведомление администратору через Gmail SMTP + queue
- Форма PfContact.vue на публичном портфолио

---

## 2026-05-15

### feat: бутстрап всей архитектуры Laravel 11 portfolio panel
Полный стек одним коммитом:

**Backend:**
- Laravel 11 с Inertia 2 + Ziggy
- Repository Pattern: 7 репозиториев + интерфейсы + бинды в AppServiceProvider
- Action Pattern: 19 action-классов (Portfolio, Server, Profile)
- 18 Form Request классов
- 9 моделей: User, Project, ProjectImage, Server, ServerMetric, Skill, Experience, LearningTopic, Setting
- 5 Enum: ProjectType, ServerStatus, SkillCategory, LearningCategory, LearningStatus
- 2 Observer: ProjectObserver, ProjectImageObserver
- 2 Event: ServerCameOnline, ServerWentOffline
- 2 Listener: LogServerCameOnline, LogServerWentOffline
- 3 Job: CheckServerJob, CheckAllServersJob, ProcessProjectImageJob
- 2 Service: ContactMessageService, LearningTopicService
- Trait WithNotification
- 15 миграций
- Мультиязычные JSON-поля (tk/ru/en) в Project и Experience
- Аутентификация (Breeze): полный flow (логин, пароль, reset, email verify)
- Шифрование токенов серверов

**Frontend:**
- Vue 3 + Pinia + vue-i18n
- Tailwind CSS v3
- 3 Layout: Admin, Authenticated, Guest
- 31 страница + 24 компонента
- lang/ru и lang/tk (6 файлов каждый)

**Инфраструктура:**
- Docker Compose с nginx, redis, php-fpm, certbot
- .env.example с полной конфигурацией
- Timezone: Asia/Ashgabat

### первый коммит
- Инициализация репозитория

---

*Формат: `feat:` — новый функционал, `fix:` — баг-фикс, `refactor:` — без изменения логики*
