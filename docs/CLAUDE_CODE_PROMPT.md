# Claude Code Prompt — Portfolio Laravel + Inertia + Vue

Скопируй и вставь этот промпт в Claude Code в своём проекте.

---

## ПРОМПТ ДЛЯ CLAUDE CODE

```
Реализуй дизайн портфолио в нашем стеке Laravel + Inertia + Vue 3.

В папке design_handoff_portfolio/ лежат два файла:
- Portfolio.html  — полностью рабочий прототип (открой в браузере чтобы увидеть результат)
- ANT Logo.html   — логотип проекта (открой чтобы увидеть все варианты)
- README.md       — полная техническая спецификация

СТЕК: Laravel (routing/API) + Inertia.js + Vue 3 Composition API + vue-i18n v9

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ЧТО НУЖНО РЕАЛИЗОВАТЬ (по порядку):
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1. РОУТ и INERTIA СТРАНИЦА
   - GET / → Inertia::render('Portfolio')
   - resources/js/Pages/Portfolio.vue — корневая страница

2. CSS ТОКЕНЫ (скопируй в resources/css/portfolio.css)
   :root {
     --bg: #0b0d14; --bg2: #111420; --bg3: #181c2b;
     --bd: rgba(255,255,255,0.07); --tx: #dde1f0; --tx2: #7b7f9a;
     --acc: oklch(65% 0.22 265); --acc2: oklch(65% 0.22 185);
     --r: 14px; --fh: 'Sora', sans-serif; --fb: 'DM Sans', sans-serif;
     --sh: 0 8px 40px rgba(0,0,0,0.4);
   }
   [data-theme="light"] {
     --bg: #f0ede8; --bg2: #ffffff; --bg3: #e6e2db;
     --bd: rgba(0,0,0,0.07); --tx: #1a1c28; --tx2: #6b6d82;
   }

3. ШРИФТЫ (добавь в <head>)
   Google Fonts: Sora (300,400,600,700,800) + DM Sans (400,500,600)

4. КОМПОНЕНТЫ (создай в resources/js/Components/Portfolio/)
   - NavBar.vue        ← фиксированный navbar, lang toggle, theme toggle
   - HeroSection.vue   ← hero с фото, кнопки, соц.ссылки
   - SkillsSection.vue ← табы Frontend/Backend/Tools, progress bars с анимацией
   - TimelineSection.vue ← вертикальный timeline карьеры
   - ProjectsSection.vue ← ГЛАВНОЕ (см. ниже детали)
   - ContactSection.vue  ← форма + контакты
   - PasswordModal.vue   ← модал для приватных проектов
   - ProjectModal.vue    ← полноэкранный просмотр проекта
   - AdminPanel.vue      ← управление паролями (доступ через footer)

5. СЕКЦИЯ PROJECTS — самая важная, реализуй точно:

   Каждая карточка проекта содержит:
   a) СВАЙПЕР ИЗОБРАЖЕНИЙ (ImageCarousel внутри карточки):
      - 3 "скриншота" на проект (пока placeholders, потом заменишь на реальные)
      - Стрелки ‹ › (появляются при hover)
      - Точки-индикаторы снизу
      - Touch/swipe на мобиле
      - Лейбл экрана в правом верхнем углу

   b) При клике на карточку → открывается ДЕТАЛЬНЫЙ МОДАЛ:
      - Большой показ скриншота (280-340px высота)
      - Стрелки навигации прямо на изображении
      - Стрип миниатюр снизу (3 thumbnail кнопки)
      - Название, описание, tech stack pills
      - Кнопка "Open Application ↗" (ссылка на проект)
      - Закрытие по ESC или клику на backdrop
      - Скролл body блокируется пока модал открыт

   c) ПРИВАТНЫЕ ПРОЕКТЫ:
      - Вместо скриншотов показывается замок 🔒
      - Клик → PasswordModal (поле пароля)
      - Пароль хранится в localStorage:
        * localStorage.getItem('proj_pass_' + projectId)
        * localStorage.getItem('portfolio_global_pass')
        * fallback: 'demo1234'
      - После правильного пароля → разблокируется в localStorage

6. i18n — три языка: EN / RU / UZ
   - Установи: npm install vue-i18n@9
   - Файлы: resources/js/locales/en.json, ru.json, uz.json
   - Переключатель языка в navbar (3 кнопки)
   - Текущий язык сохраняется в localStorage('pf_lang')

7. DARK/LIGHT ТЕМА
   - Переключатель в navbar (иконка солнца/луны)
   - Toggle data-theme="dark"|"light" на <html>
   - Сохранять в localStorage('pf_theme')

8. COMPOSABLES
   resources/js/composables/useTheme.js    ← dark/light логика
   resources/js/composables/usePortfolioPass.js ← логика паролей

9. ЛОГОТИП ANT
   - В navbar вместо текста использовать SVG логотип муравья
   - SVG код логотипа (white ant on indigo circle):
   
   <svg width="32" height="32" viewBox="0 0 108 108">
     <circle cx="54" cy="54" r="50" fill="#818cf8"/>
     <ellipse cx="54" cy="83" rx="13" ry="14.5" fill="white"/>
     <ellipse cx="54" cy="68" rx="4.5" ry="4" fill="white"/>
     <ellipse cx="54" cy="57" rx="9" ry="8.5" fill="white"/>
     <circle cx="54" cy="40" r="11" fill="white"/>
     <circle cx="49.5" cy="38" r="2.5" fill="#818cf8"/>
     <circle cx="58.5" cy="38" r="2.5" fill="#818cf8"/>
     <path d="M48,31 Q39,22 30,18" stroke="white" stroke-width="2.8" fill="none" stroke-linecap="round"/>
     <path d="M60,31 Q69,22 78,18" stroke="white" stroke-width="2.8" fill="none" stroke-linecap="round"/>
     <circle cx="30" cy="18" r="3.5" fill="white"/>
     <circle cx="78" cy="18" r="3.5" fill="white"/>
     <polyline points="45,54 33,47 22,44" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
     <polyline points="45,59 30,59 20,63" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
     <polyline points="46,64 33,72 23,78" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
     <polyline points="63,54 75,47 86,44" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
     <polyline points="63,59 78,59 88,63" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
     <polyline points="62,64 75,72 85,78" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
   </svg>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ДАННЫЕ ДЛЯ НАПОЛНЕНИЯ (замени на свои):
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

PROJECTS (6 проектов):
  p1: OpenDash Analytics    — public  — React, D3.js, Node.js, PostgreSQL
  p2: UX Component Kit      — public  — React, TypeScript, Storybook
  p3: Enterprise CRM System — private — Next.js, Django, PostgreSQL, Redis
  p4: AI Content Generator  — public  — React, Python, OpenAI API
  p5: FinTech Payment Gway  — private — Node.js, MongoDB, Stripe, Docker
  p6: DevPortfolio Template — public  — Next.js, Tailwind, Framer Motion

SKILLS (прогресс-бары):
  Frontend: React 92%, TypeScript 88%, Next.js 85%, Vue.js 78%, Tailwind 93%, Framer 80%
  Backend:  Node.js 88%, Python 84%, PostgreSQL 86%, MongoDB 80%, Django 75%, Redis 73%
  Tools:    Git 95%, Docker 82%, Figma 87%, AWS 72%, Linux 85%, CI/CD 78%

TIMELINE (карьера):
  2023–now  TechCorp Inc.  — Senior Full-Stack Developer
  2021–2023 StartupX       — Full-Stack Developer
  2019–2021 DesignStudio   — Frontend Developer
  2017–2019 Freelance      — Web Developer

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
АНИМАЦИИ (важно реализовать):
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

- Hero секция: fade-up анимация с задержкой 0/0.08/0.16/0.24/0.32s
- Skill bars: ширина 0→value% за 1.1s cubic-bezier(0.4,0,0.2,1), триггер по IntersectionObserver
- Navbar: backdrop-filter + border появляется при scrollY > 20px
- Карточки: border-color + translateY(-4px) при hover
- Модал: opacity 0→1 + translateY(12px)→0 за 0.28s
- Карусель: translateX transition 0.38s cubic-bezier(0.4,0,0.2,1)
- Анимация badge (зелёная точка): opacity + scale пульс 2.2s infinite

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
АДАПТИВНОСТЬ:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

< 920px: Hero в 1 колонку, nav links скрыты, timeline левый
< 600px: Projects в 1 колонку, padding уменьшен

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ДОПОЛНИТЕЛЬНО:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

- Полный рабочий прототип смотри в Portfolio.html
- Логотип (все варианты) смотри в ANT Logo.html
- Детальную спецификацию компонентов смотри в README.md
- Открой Portfolio.html в браузере — это точный визуальный референс

Реализуй точно как в прототипе. Начни с роута, CSS токенов и NavBar.
```

---

## КАК ИСПОЛЬЗОВАТЬ:

1. Скачай zip архив (кнопка "Portfolio Handoff" выше в чате)
2. Скопируй файлы в папку своего Laravel проекта
3. Открой Portfolio.html и ANT Logo.html в браузере — это визуальный референс
4. Вставь промпт выше в Claude Code
5. Claude Code прочитает README.md и файлы — и начнёт реализацию
