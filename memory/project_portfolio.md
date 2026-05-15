---
name: project-portfolio
description: Public portfolio page being built on / based on a handoff (Portfolio.html + README.md) — replaces Welcome
metadata:
  type: project
---

2026-05-12: Реализуется публичная страница портфолио на `/` (заменит `Welcome.vue`).

Решения, согласованные с Mekan'ом:
- Языки портфолио: tk + ru + **en** (en добавляется, хотя у проекта стандарт tk+ru — портфолио международное).
- Данные проектов: из БД через Inertia props (модель `Project` уже есть, контроллер `PortfolioController` и админка тоже).
- Приватные проекты: один глобальный пароль в таблице `settings` (ключ `portfolio.private_password`, hashed). Юзер вводит → разблокируются ВСЕ приватные. localStorage хранит только факт разблокировки на сессии.
- Hero данные (имя, роль, био, email, телефон, локация, соцсети) + skills + timeline → редактируются в админке.
- Структура админки `/dashboard/portfolio` разворачивается в подменю: **Settings** (пароль/accent/тема), **Portfolio Info** (hero+contacts+socials+skills+timeline), **Projects** (существующий CRUD).
- Skills и Timeline — отдельные таблицы с CRUD.
- Phase 1 в первую очередь: бэкенд + публичная страница; админка-подменю — потом.

**Why:** это персональное портфолио Mekan'а, всё контентом управляется им через админку, чтобы не лазить в код.

**How to apply:** Любая новая фича портфолио должна быть data-driven (БД + админка), а не хардкод. Все строки переводятся через i18n. CSS — токены OKLCH из README.md handoff.

См. [[handoff-portfolio]] для дизайн-токенов и спецификации секций.
