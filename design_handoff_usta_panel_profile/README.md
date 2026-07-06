# Handoff: Usta Panel — Profile Screen Redesign

## Overview
Redesigned admin panel shell (sidebar + topbar) plus a full Profile settings page: profile info editing, avatar upload, password change with strength meter, and account deletion with a confirmation modal. Includes a working dark/light theme toggle and a RU/TK language switcher.

## About the Design Files
The file in this bundle (`Usta Panel.dc.html`) is a **design reference built in HTML** — a working prototype demonstrating the intended visual design, layout, states, and interaction behavior. It is not production code to copy verbatim. Recreate this design in the target codebase's existing stack (React, Vue, etc.) using its established component patterns, state management, and API/data layer. If no frontend stack exists yet, pick the framework best suited to the project.

## Fidelity
**High-fidelity.** Colors, spacing, typography, and copy below are final values taken directly from the prototype — implement pixel-for-pixel.

## Screens / Views

### Shell (Sidebar + Topbar) — present on every screen
**Purpose**: Primary navigation + global controls (theme, language, current user).

**Layout**
- Root: horizontal flex, full viewport height/width.
- Sidebar: fixed width 248px, sticky, full height, flex column: logo row → nav list (flex:1) → footer (user + logout), border-right 1px.
- Main column: flex:1, flex column: topbar (64px, sticky, border-bottom) → scrollable content area.

**Sidebar components**
- Logo: 34×34px rounded-10px box, gradient `linear-gradient(135deg,#8b7cf8,#6c5ce7)`, white ship/rocket-style icon SVG, box-shadow `0 4px 12px rgba(124,108,246,.35)`. Wordmark "Usta Panel", 15.5px/800 weight.
- Nav items (6): Dashboard, Servers, Portfolio (has chevron, expandable — chevron rotates 180° when open), Messages, My Learning, Profile. Each: 17px icon + label, 10px 12px padding, 10px border-radius. Active state: text/icon in accent `#7c6cf6`, background `rgba(124,108,246,.16)` dark / `.10` light, font-weight 600. Inactive: secondary text color, weight 500, transparent bg.
- Footer: 36px circular avatar (2px accent border @ 40% opacity), name (12.5px/600) + email (11px, muted) truncating with ellipsis, 30×30px logout icon button (bordered, transparent bg).

**Topbar components**
- Page title left (16px/700).
- Right cluster, 14px gap: theme toggle button (34×34px, rounded-9px, bordered) showing sun/moon icon depending on theme; language switch (segmented control, TK/RU pill buttons, active pill filled accent color `#7c6cf6` w/ white text, 11.5px/700 uppercase-style labels); 1px vertical divider; 34px avatar + user name (13px/600).

## Profile Page

**Layout**: Content scroll area, centered container max-width 1080px, **CSS grid** `repeat(auto-fit, minmax(360px,1fr))` with 22px gap — cards reflow onto one row on wide screens and stack on narrow ones. Danger-zone card spans full width (`grid-column: 1/-1`) always.

### Card 1 — Profile Info
- Card: rounded-16px, 1px border, padding 26px/28px/24px, subtle shadow, background swaps between dark `#1b2135` and light `#ffffff`.
- Title (15.5px/700) + subtitle (12.5px, secondary color).
- Avatar upload: 64px circular image with 2px accent border, 2px padding; clicking anywhere on it (or the "Choose photo" button) opens a hidden file input; on select, swaps preview to `URL.createObjectURL(file)`. Hover state should reveal a dark overlay with a camera icon (overlay currently opacity:0 by default — wire up hover in production, e.g. `.avatar-upload:hover .overlay { opacity: 1 }`).
- "Choose photo" button: secondary style (input-bg background, bordered), plus hint text below ("PNG, JPG до 5MB." / "PNG, JPG, 5MB çenli.").
- Name field, Email field: standard labeled text inputs, 9px radius, bordered, input background differs from card background.
- Footer row: right-aligned; on Save, shows a green checkmark + "Сохранено"/"Ýatda saklandy" tag for 2s, primary accent button "Сохранить"/"Ýatda sakla".

### Card 2 — Change Password
- Same card chrome/title pattern.
- Current password field with eye-icon toggle (button positioned absolute inside input, right:10px) to reveal/hide.
- New password field, same eye toggle. Below it: a **strength meter** — 4px rounded bar, fill width/color driven by password length: <6 chars → 30% red `#ef4444` ("Слабый"/"Gowşak"); <10 chars → 65% amber `#f4b93d` ("Средний"/"Orta"); ≥10 chars → 100% green `#22c55e` ("Надёжный"/"Ynamdar"). Only shown once user has typed something.
- Confirm password field, same eye toggle; if it doesn't match "new password" once non-empty, border turns red `#ef4444` and an inline error message appears below ("Пароли не совпадают"/"Parollar gabat gelmeýär").
- Save button is a no-op (does not submit) if new/confirm mismatch. Shows same "saved" tag pattern as Card 1 and clears all 3 fields on success.

### Card 3 — Danger Zone (Delete Account)
- Full-width card, red-tinted background (`rgba(239,68,68,.06)` dark / `.04` light), red 25%-opacity border.
- Title in red `#ef4444` (15.5px/700), explanatory subtitle (max-width 440px).
- "Delete account" button: solid red, white text, shadow `0 4px 12px rgba(239,68,68,.3)`.
- Opens a confirmation modal (see below) rather than deleting immediately.

## Delete Confirmation Modal
- Full-screen backdrop `rgba(10,12,20,.55)` + `backdrop-filter: blur(3px)`; click outside closes it (click on card itself must `stopPropagation`).
- Card: 380px wide, same card background, 16px radius, 26px padding, elevated shadow `0 20px 60px rgba(0,0,0,.4)`.
- 42px warning-triangle icon in a red-tinted rounded square.
- Title (15.5px/700), body copy (12.5px, secondary, 1.6 line-height).
- A required checkbox: "I understand this is irreversible" — the destructive confirm button is disabled/dimmed (40% opacity bg) until checked, then becomes full-opacity red and clickable.
- Two actions: Cancel (bordered, transparent) and "Delete forever" (destructive, disabled until checkbox is checked).

## Interactions & Behavior
- **Theme toggle**: single boolean flips entire color palette (see Design Tokens) with a 0.25s transition on background/border/color properties. Persist choice (e.g. localStorage) in production.
- **Language toggle**: TK/RU segmented control swaps all copy instantly (see full copy dict in Design Tokens/Assets section of source file). Persist choice in production.
- **Nav "Portfolio" item**: acts as an expandable section — chevron rotates 180° when expanded (submenu content not included in this prototype; add child items as needed).
- **Avatar upload**: click avatar or button → native file picker → object URL preview. In production, upload to backend and use returned URL; revoke the object URL after upload to avoid memory leaks.
- **Save actions**: optimistic — show inline "Saved" confirmation + a global toast at bottom-center (dark pill, fades in via `translateY(8px)→0` over 0.2s, auto-dismisses after ~2.2s). Wire to real API calls with loading/error states in production (not present in this hifi mock — add a spinner/disabled state on the button while a real request is in flight, and an error toast/inline message on failure).
- **Password visibility toggles**: 3 independent eye icons, each flips its own field between `type="password"` / `type="text"`.
- **Delete flow**: button → modal → checkbox required → confirm → (here) just closes modal + shows toast. Wire to a real destructive API call with a loading state on the confirm button.
- **Responsive**: profile cards grid reflows via `auto-fit`/`minmax(360px,1fr)` — 1 column under ~760px effective width, up to 2-3 columns on wide screens; danger-zone card always spans full row.

## State Management
- `theme`: 'dark' | 'light'
- `lang`: 'ru' | 'tk'
- `active` (current nav key), `portfolioOpen` (bool)
- `profileName`, `profileEmail`, `avatarSrc`
- `infoSaved` (bool, transient success flag)
- `curPw`, `newPw`, `confirmPw` + 3 independent `*PwType` visibility flags
- `pwSaved` (bool, transient)
- `deleteModalOpen`, `deleteConfirmed` (bool)
- `toastMsg` (string, transient, auto-clears)

Derived/computed (not stored, recompute from state):
- Password strength label/percent/color from `newPw.length`
- Confirm-password mismatch (`confirmPw` non-empty && `newPw !== confirmPw`)

## Design Tokens

### Colors
| Token | Dark | Light |
|---|---|---|
| Page background | `#0f1320` | `#f3f4f8` |
| Sidebar/topbar background | `#151a2b` | `#ffffff` |
| Card background | `#1b2135` | `#ffffff` |
| Border | `rgba(255,255,255,.07)` | `rgba(15,19,32,.08)` |
| Text primary | `#f1f2f6` | `#171a24` |
| Text secondary | `rgba(241,242,246,.55)` | `rgba(23,26,36,.55)` |
| Text muted | `rgba(241,242,246,.38)` | `rgba(23,26,36,.4)` |
| Input background | `#242b42` | `#f3f4f8` |
| Input border | `rgba(255,255,255,.09)` | `rgba(15,19,32,.1)` |
| Accent (primary/brand) | `#7c6cf6` | `#7c6cf6` |
| Accent-soft (active nav bg) | `rgba(124,108,246,.16)` | `rgba(124,108,246,.10)` |
| Destructive / error | `#ef4444` | `#ef4444` |
| Warning (password: medium) | `#f4b93d` | `#f4b93d` |
| Success | `#22c55e` | `#22c55e` |
| Theme toggle icon | `#f4c95d` (moon, dark) | `#6c5ce7` (sun, light) |

### Typography
- Font family: **Manrope** (Google Fonts), weights 400/500/600/700/800. Fallback: system-ui, sans-serif.
- Page title: 16px / 700
- Card title: 15.5px / 700
- Card subtitle/body: 12.5px / 400, 1.5 line-height
- Nav label: 13.5px, 500 (600 active)
- Labels: 12.5px / 600
- Input text: 13.5px / 400
- Buttons: 13px / 700
- Logo wordmark: 15.5px / 800, -0.01em letter-spacing

### Spacing / Radius
- Card radius: 16px; inputs/buttons: 9px; small icon buttons: 7–10px; avatar/circles: 50%
- Card padding: 26px 28px 24px
- Grid gap between cards: 22px
- Sidebar width: 248px; Topbar height: 64px

### Shadows
- Card (dark): `0 1px 0 rgba(255,255,255,.02)`; (light): `0 1px 2px rgba(15,19,32,.04)`
- Primary button: `0 4px 12px rgba(124,108,246,.3)`
- Destructive button: `0 4px 12px rgba(239,68,68,.3)`
- Modal card: `0 20px 60px rgba(0,0,0,.4)`

## Assets
- Avatar placeholder image: `https://i.pravatar.cc/120?img=13` (placeholder only — wire to real user avatar URL in production).
- All icons are inline SVGs (stroke-based, 24×24 viewBox, currentColor) — hand-drawn in the prototype, listed in full in the source file. Replace with your codebase's icon set (e.g. Lucide/Feather) matching the same stroke style (2px stroke, rounded caps/joins) if preferred, or export the SVGs from the prototype directly.
- Full RU/TK copy dictionary is defined as a `COPY` object at the top of the component's logic — copy it verbatim into your i18n layer.

## Files
- `Usta Panel.dc.html` — the full interactive prototype (open directly in a browser; template markup + a small logic/state class inside one file).
