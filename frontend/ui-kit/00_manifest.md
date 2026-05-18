# OVC Design System v3.0 — Technical Manifest

## 1. ROLE & CONTEXT

**OVC Design System v3.0** is an interactive brandbook and UI kit with a **hybrid interface** that combines:
- **Terminal/CLI aesthetic** (for developers/tech mode)
- **Corporate Dashboard** (for managers/management mode)

The system supports **dual-mode operation** where UI elements can be shown/hidden based on the active mode (Tech vs Management). This allows the same codebase to serve both technical and business stakeholders.

**Key Features:**
- Dark/Light theme toggle
- Tech/Management mode switching
- Web Components (OvcHeader, OvcFooter)
- Interactive dashboards with KPI cards and bar charts
- Expandable sections with scroll-spy navigation

---

## 2. FILE STRUCTURE

```
frontend/ui-kit/
├── 00_manifest.md          # This technical instruction file
├── css/
│   ├── 01_tokens.css       # Design tokens: CSS variables, theme overrides
│   ├── 02_base.css         # Base styles: reset, typography, responsive breakpoints
│   └── 03_components.css  # All UI components: cards, buttons, dashboards, charts
├── js/
│   ├── 04_components.js    # Web Components: OvcHeader, OvcFooter classes
│   └── 05_interactions.js  # UI logic: mode toggle, theme toggle, expansions, copyCode
├── docs/
│   ├── 06_snippets.html    # HTML component examples (reference)
│   └── 08_ai_prompts.md   # AI prompts for code generation
└── index.html              # Entry point: links all CSS/JS, contains demo content
```

### File Responsibilities:

| File | Responsibility |
|------|-----------------|
| `01_tokens.css` | CSS Custom Properties (`:root`), `.theme-light` overrides, `.management-mode` variable overrides |
| `02_base.css` | CSS Reset (`*`), `body` styles, typography (`h1-h3`, `p`, `.page-title`, `.section-title`), media queries |
| `03_components.css` | All UI components: `.site-header`, `.card`, `.btn`, `.kpi-grid`, `.bar-chart`, `.article`, `.ovc-trigger`, etc. |
| `04_components.js` | `class OvcHeader extends HTMLElement` and `class OvcFooter extends HTMLElement` with `customElements.define()` |
| `05_interactions.js` | `expansions` array, `updateExpansion()`, `copyCode()`, `DOMContentLoaded` event listener with all initialization logic |
| `index.html` | Entry point with `<head>` (CSS links), `<body>` (demo content: header, nav, main sections, footer), and JS script tags |

---

## 3. UI LOGIC (CRITICAL FOR AI)

### 3.1 Mode-Specific Classes

The system uses two special classes to show/hide elements based on the active mode:

| Class | Behavior | Visible In |
|-------|----------|------------|
| `.cli-only` | Elements with this class are **hidden in Management mode** | Tech Mode only |
| `.mgmt-only` | Elements with this class are **hidden in Tech mode** | Management Mode only |

**CSS Implementation:**
```css
/* In 03_components.css */
.management-mode .cli-only {
  display: none !important;
}

body:not(.management-mode) .mgmt-only {
  display: none !important;
}
```

### 3.2 Mode Switching Mechanism

The mode is controlled by adding/removing the `.management-mode` class on the `<body>` element:

**Tech Mode (Default):**
```html
<body>
  <!-- .cli-only elements VISIBLE -->
  <!-- .mgmt-only elements HIDDEN -->
</body>
```

**Management Mode:**
```html
<body class="management-mode">
  <!-- .cli-only elements HIDDEN -->
  <!-- .mgmt-only elements VISIBLE -->
</body>
```

**JavaScript Toggle (in `05_interactions.js`):**
```javascript
const modeToggle = document.getElementById('mode-toggle');
modeToggle.addEventListener('click', () => {
  document.body.classList.toggle('management-mode');
  const isManagement = document.body.classList.contains('management-mode');
  modeToggle.textContent = isManagement ? 'Роль: Менеджер' : 'Роль: Разработчик';
});
```

### 3.3 Theme Toggle

Dark/Light theme is controlled via `.theme-light` class on `<body>`:

```javascript
const themeToggle = document.getElementById('theme-toggle');
themeToggle.addEventListener('click', () => {
  document.body.classList.toggle('theme-light');
});
```

---

## 4. DESIGN TOKENS

### 4.1 Core Color Palette

| Variable | Value (Dark) | Value (Light) | Usage |
|----------|--------------|---------------|-------|
| `--c-main` | `#2a629a` | `#2a629a` | Primary brand color, links, headings |
| `--c-rag` | `#c29922` | `#c29922` | RAG status (Red-Amber-Green), warnings |
| `--c-toc` | `#5a9c42` | `#5a9c42` | TOC section colors, success states |
| `--c-ai` | `#b02a2a` | `#b02a2a` | AI-related elements, alerts |
| `--c-bim` | `#178a99` | `#178a99` | BIM-related elements |
| `--c-fun` | `#4a2180` | `#4a2180` | Fun/accent elements |
| `--c-tg` | `#24a1de` | `#24a1de` | Telegram/social links |

### 4.2 Background & Text

| Variable | Value (Dark) | Value (Light) | Usage |
|----------|--------------|---------------|-------|
| `--bg-main` | `#181b1f` | `#f0ede8` | Main background |
| `--bg-secondary` | `#22262c` | `#e8e4de` | Secondary background (cards in dark) |
| `--bg-tertiary` | `#14171a` | `#ddd9d3` | Tertiary background |
| `--text-main` | `#dde1e7` | `#1a1d21` | Main text color |
| `--text-muted` | `#8a9099` | `#5a5f68` | Muted/secondary text |
| `--text-dim` | `#8b949e` | `#9a9fa8` | Dimmed text (system logs, etc.) |
| `--border` | `#2e333b` | `#ccc8c2` | Border color |
| `--border-sub` | `#232830` | `#d8d4ce` | Subtle border |

### 4.3 Spacing & Border Radius

| Variable | Value | Usage |
|----------|-------|-------|
| `--r-sm` | `6px` (Tech) / `12px` (Mgmt) | Small border radius |
| `--r-md` | `10px` (Tech) / `16px` (Mgmt) | Medium border radius |
| `--r-lg` | `16px` (Tech) / `20px` (Mgmt) | Large border radius |
| `--r-xl` | `24px` (Tech) / `28px` (Mgmt) | Extra large border radius |

### 4.4 Shadows

| Variable | Value (Dark) | Value (Light) |
|----------|--------------|---------------|
| `--sh-sm` | `0 2px 8px rgba(0,0,0,.28)` | `0 4px 12px rgba(0,0,0,.12)` |
| `--sh-md` | `0 6px 20px rgba(0,0,0,.35)` | `0 8px 24px rgba(0,0,0,.15)` |
| `--sh-lg` | `0 16px 48px rgba(0,0,0,.45)` | `0 20px 60px rgba(0,0,0,.18)` |

---

## 5. DASHBOARD SPEC

### 5.1 KPI Card Grid

**Structure:**
```html
<section class="dashboard-section">
  <h2 class="section-title">Section Title</h2>
  <div class="kpi-grid">
    <div class="card kpi-card">
      <div class="kpi-header">
        <span class="kpi-icon">📊</span>
        <span class="kpi-label">Metric Name</span>
      </div>
      <div class="kpi-value">1234</div>
      <div class="kpi-change positive">+12%</div>
    </div>
    <!-- More KPI cards... -->
  </div>
</section>
```

**CSS Classes:**
- `.kpi-grid` - CSS Grid container (responsive: 1-4 columns)
- `.kpi-card` - Individual card with background, border, padding
- `.kpi-header` - Flex container for icon + label
- `.kpi-icon` - Emoji/icon (24px)
- `.kpi-label` - Metric label (--text-muted color)
- `.kpi-value` - Large numeric value (--c-main color, 2rem font-size)
- `.kpi-change` - Change indicator (`.positive` = green, `.negative` = red)

**Adding New KPI Cards:**
1. Copy an existing `.kpi-card` block
2. Update `.kpi-icon`, `.kpi-label`, `.kpi-value`, `.kpi-change`
3. Use `.positive` or `.negative` class for the change indicator
4. The grid will automatically adjust (CSS Grid with `repeat(auto-fit, minmax(200px, 1fr))`)

### 5.2 Bar Charts

**Structure:**
```html
<div class="bar-chart">
  <h3 class="bar-chart__title">Chart Title</h3>
  <div class="bar-chart__container">
    <div class="bar-item">
      <span class="bar-label">Label 1</span>
      <div class="bar-track">
        <div class="bar-fill" style="--bar-value: 75%; background: var(--c-main);">
          <span class="bar-value">75%</span>
        </div>
      </div>
    </div>
    <!-- More bar items... -->
  </div>
</div>
```

**CSS Classes:**
- `.bar-chart` - Container with background, padding, border-radius
- `.bar-chart__title` - Chart title (--c-main color)
- `.bar-chart__container` - Flex column for bar items
- `.bar-item` - Individual bar row (label + track)
- `.bar-label` - Text label (--text-main)
- `.bar-track` - Background track (--bg-secondary)
- `.bar-fill` - Filled portion with `--bar-value` custom property for width
- `.bar-value` - Numeric value displayed on the bar

**Adding New Bar Items:**
1. Copy an existing `.bar-item` block
2. Update `.bar-label` text
3. Set `--bar-value` inline style (e.g., `style="--bar-value: 85%;"`)
4. Set `background` inline style or use CSS variables (`--c-main`, `--c-rag`, `--c-bim`, etc.)
5. Update `.bar-value` text

**Color Coding:**
- Use `var(--c-main)` for primary metrics
- Use `var(--c-rag)` for warning/attention metrics
- Use `var(--c-bim)` for BIM-related metrics
- Use `var(--c-ai)` for AI-related metrics

---

## 6. ARCHITECTURE RULES

1. **NO OPTIMIZATION:** Do NOT refactor, rename classes, delete or modify CSS variables and JS logic. Only physical separation (Cut & Paste).
2. **NO TRUNCATION:** All code must be transferred 100%. No comments like `// rest of the code here`.
3. **CONSISTENCY:** When adding new elements, follow the existing patterns in `03_components.css` and `index.html`.

---

## 7. QUICK REFERENCE

**Key CSS Variables File:** `css/01_tokens.css` (82 lines)
**Key JS Logic File:** `js/05_interactions.js` (177 lines)
**Component Examples:** `docs/06_snippets.html`
**AI Prompts:** `docs/08_ai_prompts.md`

**Repository:** https://github.com/chernetchenko-dev/go-cms
**UI Kit Path:** `frontend/ui-kit/`