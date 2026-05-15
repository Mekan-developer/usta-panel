# Handoff: Developer Portfolio — Laravel + Inertia + Vue

## Overview

A full-featured, multilingual personal portfolio website with:
- Hero, Skills, Career Timeline, Projects, and Contact sections
- Three languages: English / Russian / Uzbek
- Dark / Light theme toggle
- Private projects protected by password (with admin panel to manage)
- Accent colour theming (4 presets)

---

## About the Design Files

`Portfolio.html` in this bundle is a **design reference built in HTML + React**.
It is **not** production code — it's a pixel-accurate prototype showing the intended
look, behaviour, and content structure.

**Your task:** recreate every screen and interaction shown in `Portfolio.html` inside
the existing **Laravel + Inertia + Vue** codebase, using its established patterns,
directory conventions, and any existing design system. Do not ship the HTML file directly.

**Fidelity: HIGH — pixel-perfect.**
Colours, typography, spacing, shadows, border radii, hover states, and animations
should all match the prototype precisely.

---

## Target Stack

| Layer       | Technology                       |
|-------------|----------------------------------|
| Backend     | Laravel (routing, auth, API)     |
| Bridge      | Inertia.js                       |
| Frontend    | Vue 3 (Composition API)          |
| Styling     | Tailwind CSS **or** plain CSS custom-properties (see tokens) |
| i18n        | `vue-i18n` v9                    |
| State       | `ref` / `reactive` (no Vuex needed) |
| Persistence | `localStorage` for theme/lang/unlocked projects |

---

## Recommended File Structure

```
resources/
  js/
    Pages/
      Portfolio.vue          ← Inertia page (root, assembles all sections)
    Components/
      Portfolio/
        NavBar.vue
        HeroSection.vue
        SkillsSection.vue
        TimelineSection.vue
        ProjectsSection.vue
        ContactSection.vue
        PasswordModal.vue
        AdminPanel.vue
        TweaksPanel.vue
    composables/
      useTheme.js            ← dark/light toggle logic
      usePortfolioPass.js    ← password check & admin logic
    locales/
      en.json
      ru.json
      uz.json
  css/
    portfolio.css            ← CSS custom property tokens
routes/
  web.php                   ← single route  GET /  → Portfolio
```

---

## Design Tokens

Paste these CSS custom properties into `resources/css/portfolio.css`.
Theme switching is done by toggling `data-theme="light"` on `<html>`.

```css
/* === DARK THEME (default) === */
:root {
  --bg:   #0b0d14;
  --bg2:  #111420;
  --bg3:  #181c2b;
  --bd:   rgba(255,255,255,0.07);
  --tx:   #dde1f0;
  --tx2:  #7b7f9a;
  --acc:  oklch(65% 0.22 265);   /* indigo — default accent */
  --acc2: oklch(65% 0.22 185);   /* teal complement */
  --r:    14px;
  --fh:   'Sora', sans-serif;
  --fb:   'DM Sans', sans-serif;
  --sh:   0 8px 40px rgba(0,0,0,0.4);
  --sh2:  0 2px 14px rgba(0,0,0,0.25);
}

/* === LIGHT THEME === */
[data-theme="light"] {
  --bg:  #f0ede8;
  --bg2: #ffffff;
  --bg3: #e6e2db;
  --bd:  rgba(0,0,0,0.07);
  --tx:  #1a1c28;
  --tx2: #6b6d82;
  --sh:  0 8px 40px rgba(0,0,0,0.08);
  --sh2: 0 2px 14px rgba(0,0,0,0.05);
}
```

**Google Fonts** — add to `<head>`:
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
```

### Accent colour presets
Swap `--acc` / `--acc2` to change the accent. Four presets:

| Name    | `--acc`                  | `--acc2`                 |
|---------|--------------------------|--------------------------|
| indigo  | `oklch(65% 0.22 265)`    | `oklch(65% 0.22 185)`    |
| violet  | `oklch(65% 0.22 305)`    | `oklch(65% 0.22 245)`    |
| teal    | `oklch(65% 0.22 195)`    | `oklch(65% 0.22 255)`    |
| amber   | `oklch(72% 0.22 72)`     | `oklch(65% 0.22 38)`     |

---

## Screens / Views

### 1. NavBar — `NavBar.vue`

**Layout:** fixed top bar, full width, `height: 64px`, `z-index: 100`.
On scroll > 20px: add `backdrop-filter: blur(20px)`, `background: color-mix(in oklch, var(--bg) 88%, transparent)`, `border-bottom: 1px solid var(--bd)`, subtle shadow.

**Left:** logo `alex.dev` — Sora 800, 1.15rem. The `.` is coloured `var(--acc)`.

**Centre:** nav links list — `font-size: 0.875rem`, `font-weight: 500`, `color: var(--tx2)`. Hover → `color: var(--tx)`. Links: `#skills`, `#timeline`, `#projects`, `#contact`. Hidden on mobile (< 920px).

**Right (flex, gap 9px):**

1. Language toggle — 3 buttons (EN / RU / UZ) grouped in a `background: var(--bg3); border-radius: 8px; padding: 3px` pill container. Each button: `padding: 4px 9px`, `font-size: 0.71rem`, `font-weight: 700`. Active button: `background: var(--acc); color: #fff; border-radius: 5px`.

2. Theme icon button — 34×34px, `border-radius: 8px`, `background: var(--bg3)`, `border: 1px solid var(--bd)`. Shows sun SVG in dark mode, moon SVG in light mode. Hover: `border-color: var(--acc); color: var(--acc)`.

**i18n keys needed:** `nav.skills`, `nav.timeline`, `nav.projects`, `nav.contact`

---

### 2. Hero — `HeroSection.vue`

**Layout:** `min-height: 100vh`, flex-center, top padding `64px` (nav height).
Two-column grid: `grid-template-columns: 1fr 360px; gap: 60px; align-items: center`.
On mobile (< 920px): single column, text centred, photo on top.

**Background FX (CSS only, no images):**
- Dot grid: `background-image: radial-gradient(circle, var(--bd) 1px, transparent 1px); background-size: 30px 30px`
- Radial glow top-right: `position: absolute; width: 520px; height: 520px; background: radial-gradient(circle, oklch(65% 0.22 265 / 0.07) 0%, transparent 65%)`

**Left column (top → bottom):**

1. **"Available" badge** — `display: inline-flex; gap: 8px; border: 1px solid var(--bd); background: var(--bg2); padding: 6px 14px; border-radius: 100px; font-size: 0.78rem; font-weight: 500; color: var(--tx2)`. Inside: a 7×7px green dot (`background: var(--acc2)`) that pulses with CSS animation `opacity 1→0.45→1, scale 1→0.82→1` over 2.2s ease-in-out.

2. **Name heading** — `font-size: clamp(2.8rem, 5.5vw, 4.1rem); font-weight: 800; letter-spacing: -0.035em; line-height: 1.07`. Line 1: greeting text (e.g. "Hi, I'm"). Line 2: name with **gradient text**: `background: linear-gradient(135deg, var(--acc), var(--acc2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent`.

3. **Role/subtitle** — Sora, 1.05rem, `color: var(--tx2)`, margin-bottom 16px.

4. **Bio paragraph** — 0.96rem, `color: var(--tx2)`, `line-height: 1.75`, max-width 490px.

5. **CTA buttons** (flex, gap 12px):
   - Primary: `background: var(--acc); color: #fff; padding: 13px 26px; border-radius: 10px; font-size: 0.9rem; font-weight: 600`. Hover: `opacity: 0.88; transform: translateY(-1px)`.
   - Outline: `border: 1px solid var(--bd); padding: 13px 26px; border-radius: 10px`. Hover: `border-color: var(--acc); color: var(--acc)`.

6. **Social links** (flex, gap 10px) — 40×40px rounded-10px buttons, `background: var(--bg3); border: 1px solid var(--bd); color: var(--tx2)`. Hover: `border-color: var(--acc); color: var(--acc); transform: translateY(-2px)`. Icons: GitHub, LinkedIn, Twitter, Email (SVG).

**Right column — Photo placeholder:**
- `width: 290px; height: 290px; border-radius: 50%; border: 1.5px solid var(--acc); padding: 5px`
- Inner circle: `background: var(--bg3)` with a centred SVG silhouette (head circle + body ellipse at low opacity) and monospace label `[ profile photo ]`
- Radial glow behind photo: absolute, same size, `background: radial-gradient(circle, oklch(65% 0.22 265 / 0.15) 0%, transparent 70%)`

**Entrance animations:** all children fade up (`opacity: 0 → 1; transform: translateY(16px) → 0`) over 0.55s ease, with staggered delays (0, 0.08s, 0.16s, 0.24s, 0.32s).

**i18n keys:** `hero.badge`, `hero.hi`, `hero.name`, `hero.role`, `hero.bio`, `hero.cta1`, `hero.cta2`

---

### 3. Skills — `SkillsSection.vue`

**Layout:** `background: var(--bg2); padding: 96px 0`.

**Section header:** small uppercase label `var(--acc)` above large bold title.

**Tab bar** (flex, gap 7px, flex-wrap):
Three tabs — Frontend / Backend / Tools & DevOps.
Inactive: `background: var(--bg3); border: 1px solid var(--bd); padding: 9px 20px; border-radius: 100px; color: var(--tx2)`.
Active: `background: var(--acc); border-color: var(--acc); color: #fff`.

**Skills grid** — `grid-template-columns: repeat(auto-fill, minmax(152px, 1fr)); gap: 13px`.

**Skill card** — `background: var(--bg); border: 1px solid var(--bd); border-radius: 14px; padding: 18px`. Hover: `border-color: var(--acc); transform: translateY(-3px)`.

Inside each card (top → bottom):
1. **Abbreviation badge** — 40×40px, `border-radius: 10px; background: oklch(65% 0.22 265 / 0.1); display: flex; align-items: center; justify-content: center; font-size: 0.68rem; font-weight: 800; color: var(--acc); font-family: var(--fh)`. Text: 2-char abbreviation (Re, TS, Nx, Vu, Tw, Fx / No, Py, PG, Mg, Dj, Rd / Gt, Dk, Fg, AW, Lx, CI).
2. **Skill name** — `font-weight: 600; font-size: 0.88rem; margin-bottom: 10px`.
3. **Progress bar** — container: `height: 4px; background: var(--bg3); border-radius: 2px; overflow: hidden`. Fill: `background: linear-gradient(90deg, var(--acc), var(--acc2)); transition: width 1.1s cubic-bezier(0.4,0,0.2,1)`. Width animates from 0% → skill% when section enters viewport (IntersectionObserver, threshold 0.15). Re-trigger animation when switching tabs.
4. **Percentage** — `font-size: 0.72rem; color: var(--tx2); text-align: right; margin-top: 4px`.

**Skill data:**

Frontend: React 92%, TypeScript 88%, Next.js 85%, Vue.js 78%, Tailwind CSS 93%, Framer Motion 80%
Backend: Node.js 88%, Python 84%, PostgreSQL 86%, MongoDB 80%, Django 75%, Redis 73%
Tools: Git/GitHub 95%, Docker 82%, Figma 87%, AWS 72%, Linux 85%, CI/CD 78%

**i18n keys:** `skills.label`, `skills.title`, `skills.tabs[0..2]`

---

### 4. Career Timeline — `TimelineSection.vue`

**Layout:** `padding: 96px 0; background: var(--bg)`.

**Timeline container** — `position: relative`. Central line: `::before` pseudo, `position: absolute; left: 50%; width: 1.5px; background: linear-gradient(180deg, transparent 0%, var(--acc) 8%, var(--acc) 92%, transparent); height: 100%`.

**Row structure** — CSS grid: `grid-template-columns: 1fr 48px 1fr; gap: 0 16px; margin-bottom: 40px`.
- Col 1: left card (even items only) — right-aligned
- Col 2: dot centred at top
- Col 3: right card (odd items only)

**Dot** — 13×13px circle, `background: var(--acc); border: 2px solid var(--bg); box-shadow: 0 0 0 4px oklch(65% 0.22 265 / 0.2)`.

**Card** — `background: var(--bg2); border: 1px solid var(--bd); border-radius: 14px; padding: 20px 22px`. Hover: `border-color: var(--acc)`. Contains:
- Company — `font-size: 0.71rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--acc)`.
- Role — Sora, `font-weight: 700; font-size: 0.97rem`.
- Duration — small clock SVG + text, `font-size: 0.78rem; color: var(--tx2)`.
- Description — `font-size: 0.85rem; color: var(--tx2); line-height: 1.65`.

**Mobile (< 920px):** hide central line, move line to left: `left: 21px`. Grid becomes `42px 1fr`. Left column hidden. Show right column for ALL items (duplicate card into right col with `v-show` based on breakpoint, or use a `.show-mob` class).

**Timeline data (all translatable):**

| Company       | Duration       | Role (EN)                     |
|---------------|----------------|-------------------------------|
| TechCorp Inc. | 2023 – Present | Senior Full-Stack Developer   |
| StartupX      | 2021 – 2023    | Full-Stack Developer          |
| DesignStudio  | 2019 – 2021    | Frontend Developer            |
| Freelance     | 2017 – 2019    | Web Developer                 |

**i18n keys:** `timeline.label`, `timeline.title`, and each job's `role` and `desc` in en/ru/uz.

---

### 5. Projects — `ProjectsSection.vue`

**Layout:** `background: var(--bg2); padding: 96px 0`. Grid: `grid-template-columns: repeat(auto-fill, minmax(308px, 1fr)); gap: 20px`.

**Project card** — `background: var(--bg); border: 1px solid var(--bd); border-radius: 14px; overflow: hidden`. Hover: `border-color: var(--acc); transform: translateY(-4px); box-shadow: var(--sh)`.

**Thumbnail area** (height 164px):
- Background: `background-color: var(--bg3)` + diagonal stripe pattern:
  `background-image: repeating-linear-gradient(45deg, transparent, transparent 12px, var(--bd) 12px, var(--bd) 13px)`
- **Public projects:** centred monospace label `font-size: 0.68rem; color: var(--tx2)` describing what goes there.
- **Private (locked):** full-cover overlay with the same striped bg, a lock SVG icon, label "Password Protected" (`var(--tx2)`), sub-label "Click to unlock" (`var(--acc)`). Cursor pointer. Hover: slight accent tint `oklch(65% 0.22 265 / 0.06)`.

**Card body** (padding 18px 20px):
1. **Badge pill** — `font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.09em; padding: 3px 9px; border-radius: 100px`.
   - Public: `background: oklch(65% 0.22 185 / 0.12); color: var(--acc2)`
   - Private: `background: oklch(65% 0.22 265 / 0.12); color: var(--acc)`
2. **Project title** — Sora, `font-weight: 700; font-size: 1rem`.
3. **Description** — `font-size: 0.84rem; color: var(--tx2); line-height: 1.65`.
4. **Tech stack pills** — flex wrap, gap 5px. Each pill: `font-size: 0.69rem; font-weight: 600; padding: 2px 9px; background: var(--bg3); border-radius: 6px; color: var(--tx2)`.
5. **"View Project →" link** — `font-size: 0.84rem; font-weight: 600; color: var(--acc)`. Only shown when project is unlocked.

**Projects data:**

| ID | Type    | Title                   | Tech stack                            |
|----|---------|-------------------------|---------------------------------------|
| p1 | public  | OpenDash Analytics      | React, D3.js, Node.js, PostgreSQL     |
| p2 | public  | UX Component Kit        | React, TypeScript, Storybook          |
| p3 | private | Enterprise CRM System   | Next.js, Django, PostgreSQL, Redis    |
| p4 | public  | AI Content Generator    | React, Python, OpenAI API             |
| p5 | private | FinTech Payment Gateway | Node.js, MongoDB, Stripe, Docker      |
| p6 | public  | DevPortfolio Template   | Next.js, Tailwind, Framer Motion      |

**Password logic:**
- Unlocked project IDs stored in `localStorage('pf_unlocked')` as JSON array.
- On click of locked overlay → open `PasswordModal.vue`.
- Password checked against (in priority order):
  1. `localStorage.getItem('proj_pass_' + projectId)`
  2. `localStorage.getItem('portfolio_global_pass')`
  3. Fallback hardcoded: `'demo1234'`
- On success: add ID to unlocked array, close modal, show project normally.

**i18n keys:** `projects.label`, `projects.title`, `projects.pub`, `projects.priv`, `projects.locked`, `projects.unlock`, `projects.view`

---

### 6. Password Modal — `PasswordModal.vue`

**Trigger:** clicking locked project overlay.
**Backdrop:** `position: fixed; inset: 0; background: rgba(0,0,0,0.62); backdrop-filter: blur(8px); z-index: 300`. Click backdrop → close.
**Modal box:** `background: var(--bg2); border: 1px solid var(--bd); border-radius: 20px; padding: 32px; max-width: 380px; width: 92%; box-shadow: var(--sh)`. Entrance: `opacity 0→1 + translateY(12px)→0` over 0.22s.
**Close button:** top-right, 28×28px, `background: var(--bg3); border-radius: 7px`.
**Content:** lock emoji (2rem), title, subtitle, password `<input type="password">`, submit button (full width, `btn-p` style).
**Error state:** red text `#f87171` under input on wrong password.
**Props:** `project` (id + title), emits `unlock(projectId)` and `close`.

---

### 7. Contact — `ContactSection.vue`

**Layout:** `padding: 96px 0; background: var(--bg)`. Two-column grid: `1fr 1.1fr; gap: 60px`. Single column on mobile.

**Left — info side:**
- Sub-heading (Sora, 1.35rem, 700) + description paragraph.
- Contact items list (flex column, gap 13px):
  - Each item: 38×38px icon box (`background: var(--bg3); border: 1px solid var(--bd); border-radius: 9px; color: var(--acc)`) + value text + small label.
  - Items: email `hello@alexjohnson.dev`, phone `+1 (555) 123-4567`, location `San Francisco, CA`.
- Social links (same style as hero).

**Right — contact form:**
Fields: Name, Email, Message (textarea, min-height 112px). All use class `fi`:
`background: var(--bg3); border: 1px solid var(--bd); border-radius: 10px; padding: 12px 15px; width: 100%`. Focus: `border-color: var(--acc)`.
Labels: `font-size: 0.77rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: var(--tx2)`.
Submit button (full width, primary style). On success: button text changes to "✓ Sent!" for 3.5s then resets.

**Vue implementation note:** handle form submit with `@submit.prevent`. For real email delivery wire up to a Laravel `POST /contact` route + `Mail::to(...)`. The prototype just shows a success flash.

**i18n keys:** `contact.label`, `contact.title`, `contact.desc`, `contact.nf`, `contact.ef`, `contact.mf`, `contact.send`, `contact.el`, `contact.pl`, `contact.ll`

---

### 8. Admin Panel — `AdminPanel.vue`

**Access:** small "Admin" button in the footer (flex, gap 4px, gear SVG + text). `opacity: 0.3`, hover `opacity: 0.85`. Font-size 0.72rem.

**Modal** (same backdrop/box style as PasswordModal, max-width 450px):
- Gear emoji header, title + subtitle.
- **Global password section:** shows masked password dots; "Change" button reveals an inline text input + Save button. On save: writes to `localStorage('portfolio_global_pass')`, shows "Saved!" confirmation for 2.5s.
- **Per-project passwords section:** lists all private projects by title. Same inline edit pattern per project, writes to `localStorage('proj_pass_' + id)`.
- Footer note: "Default password: `demo1234`".

**Vue implementation note:** each project row should be its own child component (`AdminProjectRow.vue`) so it can hold its own `editing` / `saved` reactive state without hooks-in-loop issues.

---

## i18n Setup — `vue-i18n`

Install: `npm install vue-i18n@9`

Register in `app.js`:
```js
import { createI18n } from 'vue-i18n'
import en from './locales/en.json'
import ru from './locales/ru.json'
import uz from './locales/uz.json'

const i18n = createI18n({
  locale: localStorage.getItem('pf_lang') || 'en',
  fallbackLocale: 'en',
  messages: { en, ru, uz }
})
```

On language change: `i18n.global.locale.value = lang; localStorage.setItem('pf_lang', lang)`.

**Full `en.json`:**
```json
{
  "nav": { "skills": "Skills", "timeline": "Timeline", "projects": "Projects", "contact": "Contact" },
  "hero": {
    "badge": "Available for work",
    "hi": "Hi, I'm", "name": "Alex Johnson",
    "role": "Full-Stack Developer & UI Designer",
    "bio": "I craft clean, performant digital experiences — from pixel-perfect interfaces to scalable backend systems. 5+ years building products people love.",
    "cta1": "View Projects", "cta2": "Contact Me"
  },
  "skills": { "label": "What I Know", "title": "Skills & Expertise", "tabs": ["Frontend","Backend","Tools & DevOps"] },
  "timeline": { "label": "Experience", "title": "Career Timeline" },
  "projects": {
    "label": "My Work", "title": "Projects",
    "pub": "Public", "priv": "Private",
    "locked": "Password Protected", "unlock": "Click to unlock", "view": "View Project →"
  },
  "contact": {
    "label": "Get In Touch", "title": "Contact Me",
    "desc": "I'm open to new opportunities. Whether you have a project in mind or just want to say hi — reach out.",
    "nf": "Your Name", "ef": "Your Email", "mf": "Message", "send": "Send Message",
    "el": "Email", "pl": "Phone", "ll": "Location"
  },
  "pass": { "title": "Private Project", "sub": "This project is password protected.", "ph": "Enter password", "btn": "Unlock", "err": "Incorrect password. Try again." },
  "admin": {
    "title": "Admin Panel", "sub": "Manage private project passwords",
    "gh": "Global Password", "gs": "Default password for all private projects",
    "ph": "Per-Project Passwords", "change": "Change", "save": "Save", "saved": "Saved!", "np": "New password"
  },
  "footer": { "copy": "© 2026 Alex Johnson. All rights reserved.", "admin": "Admin" }
}
```

*(ru.json and uz.json follow the same key structure — full translations are in Portfolio.html's LANGS constant.)*

---

## Interactions & Animations

| Interaction | Spec |
|---|---|
| Nav scroll | Add `.scrolled` class on `window.scrollY > 20` |
| Hero entrance | Staggered `fadeUp` keyframe: `opacity 0→1, translateY 16px→0`, 0.55s ease, delays 0 / 0.08 / 0.16 / 0.24 / 0.32s |
| Available badge dot | `@keyframes blink`: opacity+scale pulse, 2.2s ease-in-out infinite |
| Skill bars | Width 0→pct%, 1.1s `cubic-bezier(0.4,0,0.2,1)`. Triggered by IntersectionObserver (threshold 0.15). Reset & retrigger on tab change (50ms delay) |
| Card hover | `border-color: var(--acc); transform: translateY(-3px or -4px)`, 0.2s |
| Social links hover | `transform: translateY(-2px)`, 0.18s |
| Modal open | `opacity 0→1 + translateY 12px→0`, 0.22s ease |
| Button primary hover | `opacity: 0.88; transform: translateY(-1px)` |
| Theme switch | CSS custom-property swap on `<html data-theme>` + `transition: background 0.3s, color 0.3s` on body |

---

## Composables

### `useTheme.js`
```js
import { ref, watch } from 'vue'
export function useTheme() {
  const theme = ref(localStorage.getItem('pf_theme') || 'dark')
  watch(theme, (val) => {
    document.documentElement.setAttribute('data-theme', val)
    localStorage.setItem('pf_theme', val)
  }, { immediate: true })
  const toggle = () => { theme.value = theme.value === 'dark' ? 'light' : 'dark' }
  return { theme, toggle }
}
```

### `usePortfolioPass.js`
```js
import { ref } from 'vue'
export function usePortfolioPass() {
  const unlocked = ref(JSON.parse(localStorage.getItem('pf_unlocked') || '[]'))
  const check = (projectId, input) => {
    const p = localStorage.getItem('proj_pass_' + projectId)
              || localStorage.getItem('portfolio_global_pass')
              || 'demo1234'
    return input === p
  }
  const unlock = (id) => {
    unlocked.value = [...unlocked.value, id]
    localStorage.setItem('pf_unlocked', JSON.stringify(unlocked.value))
  }
  const isLocked = (proj) => proj.type === 'private' && !unlocked.value.includes(proj.id)
  return { unlocked, check, unlock, isLocked }
}
```

---

## Laravel Routes

```php
// routes/web.php
Route::get('/', fn() => Inertia::render('Portfolio'))->name('portfolio');

// Optional: contact form submission
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
```

---

## Responsive Breakpoints

| Breakpoint | Changes |
|---|---|
| `< 920px` | Hero: 1-col, text centred, photo on top. Nav links hidden. Timeline: single-col left-aligned. Contact: 1-col. |
| `< 600px` | All sections: padding 64px 0. Projects: 1-col. Skills: smaller min-width. |

---

## Assets

No external images are used. All placeholders are CSS/SVG-based:
- **Profile photo:** replaced by `<image-slot>` or `<img>` pointing to the real photo once available.
- **Project thumbnails:** replaced by real screenshots/mockups when available.
- **Icons:** inline SVG (GitHub, LinkedIn, Twitter, Mail, Phone, Pin, Lock, Gear, Clock, Sun, Moon). All SVGs are in the `Portfolio.html` prototype under the `Ico` constant — copy them as Vue `<template>` slots or a shared `Icon.vue` component.

---

## Files in this Bundle

| File | Purpose |
|---|---|
| `README.md` | This document — full implementation spec |
| `Portfolio.html` | Full working design reference prototype |

Open `Portfolio.html` in a browser to interact with the design.
Toggle the language switcher (EN/RU/UZ) and dark/light mode in the top-right nav.
Click any private project card to test the password modal — default password is `demo1234`.
Click the tiny "Admin" link in the footer to test the admin panel.
