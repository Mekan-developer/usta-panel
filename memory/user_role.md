---
name: user-role
description: Mekan — Laravel senior dev coach client; primary language Russian, project is usta-panel (Laravel 11 + Inertia + Vue 3)
metadata:
  type: user
---

Mekan — разработчик, для которого Claude является «персональным senior Laravel coach» (см. CLAUDE.md). Общение на русском, кратко и по делу. Главный проект — usta-panel: личная админка/мониторинг серверов + портфолио. Стек: Laravel 11, Inertia v2, Vue 3 (Composition API + `<script setup>`), Pinia, Tailwind 3, PHPUnit, vue-i18n.

Использует строгую архитектуру (см. CLAUDE.md): Repository для всех запросов к БД, Service/Action для логики, Form Request для валидации, Enum для статусов, Observer для событий моделей, dark/light через Pinia + localStorage, локализация tk/ru минимум. Контроллеры тонкие. Уведомления через `WithNotification` trait + `useNotificationStore`.
