---
name: handoff-portfolio
description: Pointer to the design handoff bundle (Portfolio.html + README.md) used as source-of-truth for the public portfolio
metadata:
  type: reference
---

Дизайн-пакет публичного портфолио: `Portfolio.html` (React-прототип) и `README.md` верхнего уровня в репозитории — это спецификация. Токены, шрифты (Sora + DM Sans с Google Fonts), цвета (OKLCH), все компоненты, i18n-ключи, анимации и брейкпоинты — описаны там.

**Когда применять:** при работе над публичной страницей `/` (Pages/Public/Portfolio.vue) или её компонентами в `resources/js/Components/Portfolio/*`. Все размеры, шрифты, анимации сверять по README.md, а интерактив — по Portfolio.html.

Не отдавать Portfolio.html в продакшен — это референс, а не код.
