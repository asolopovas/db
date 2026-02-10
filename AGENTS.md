# AGENTS.md

Guidance for agentic coding tools working in this repository.

## 1) Repository profile

- Backend: Laravel 11 on PHP 8.2+.
- Frontend: Vue 3 + TypeScript + Vuex 4 + Vue Router 4.
- Build system: Vite + `laravel-vite-plugin`.
- Styling: Tailwind + SCSS.
- Frontend testing: Vitest + Vue Test Utils + jsdom.
- Backend testing: PHPUnit with in-memory SQLite.
- Package managers present: Bun, npm, Composer.
- Key roots: `app/`, `routes/`, `resources/app/`, `tests/`.

## 2) Cursor/Copilot rule check

- `.cursor/rules/`: not found.
- `.cursorrules`: not found.
- `.github/copilot-instructions.md`: not found.
- Extra local guidance exists in `CLAUDE.md` files (root + subdirectories).

## 3) Setup

Run from `/home/andrius/www/db.3oak.test`.

```bash
composer install
bun install
cp .env.example .env
php artisan key:generate
```

## 4) Build, lint, and test commands

### 4.1 Dev and build

- Dev frontend: `bun run dev`
- Dev backend: `php artisan serve`
- Build assets: `bun run prod`
- Alt build: `vite build`

### 4.2 Frontend tests (Vitest)

- Run all: `bun run test` or `vitest`
- UI mode: `bun run test:ui` or `vitest --ui`
- One-shot run: `bun run test:run` or `vitest run`
- Single test file: `vitest run tests/ts/ChartStats.test.ts`
- Single test by name: `vitest run tests/ts/ChartStats.test.ts -t "renders the component correctly"`
- Name-only filter: `vitest -t "aggregates chart data correctly"`

### 4.3 Backend tests (PHPUnit)

- Run all: `phpunit` or `npm run phpunit`
- Watch mode: `npm run phpunit-watch` or `phpunit-watcher watch`
- Single test file: `phpunit tests/Unit/OrderTest.php`
- Single test method (short filter): `phpunit --filter order_totals_are_correct tests/Unit/OrderTest.php`
- Single test method (fully qualified): `phpunit --filter "Tests\\Unit\\OrderTest::order_totals_are_correct"`

### 4.4 Lint/format status

- No canonical root lint script is defined in `package.json`.
- ESLint and Prettier are installed, but no committed root ESLint/Prettier config was found.
- No committed root Pint/PHPCS/PHPStan config was found.
- Default behavior: make targeted edits and match style in touched files.
- Best-effort optional checks: `npx eslint .`, `npx prettier --check "resources/**/*.{ts,vue,scss}"`.

## 5) Code style guidelines

When conventions conflict, follow the style already present in the file you edit.

### 5.1 Imports and module boundaries

- TS/Vue aliases in active use: `@`, `@app`, `@components`, `@lib`, `@store`, `@root`, `@icons`.
- Keep import groups stable: framework/vendor imports first, local imports next.
- Use `import type` for type-only imports when practical.
- In PHP, keep `use` statements under namespace; grouped `use` braces are used in places.
- Reuse existing abstractions (`apiFetch`, Vuex modules, Laravel traits/transformers) before new wrappers.

### 5.2 Formatting

- 4-space indentation is standard in PHP and most TS/Vue script blocks.
- Preserve the local quote style (`'` and `"` are both used in repo).
- Keep multiline arrays/objects expanded when surrounding code does.
- Keep trailing commas where local style already uses them.
- Avoid unrelated reformatting/churn in large legacy files.

### 5.3 TypeScript and Vue

- Prefer `<script setup lang="ts">` where SFCs already use it.
- Add explicit types for exported APIs and complex computed/ref values.
- Existing code contains `any`; narrow types incrementally when touching those areas.
- Preserve Vuex patterns (`store.dispatch`, `store.commit`, module structure in `store/modules`).
- Respect alias/path configuration in `tsconfig.app.json` and `vite.config.ts`.

### 5.4 PHP and Laravel

- Follow resource-controller routing patterns used in `routes/api.php`.
- Prefer Eloquent relationships/scopes/traits over raw SQL unless necessary.
- Keep API response shapes compatible with existing endpoints (`type`, `message`, `item`, pagination).
- Use typed properties/return types where the file already follows typed style.
- Keep business logic in existing layers (Helpers, Traits, Transformers, Observers).

### 5.5 Naming conventions

- PHP classes: PascalCase (example: `OrdersController`).
- PHP methods/properties: camelCase.
- DB columns and many payload keys: snake_case.
- Vue component files: mostly PascalCase; some base components are kebab-case.
- TS filenames vary by folder; mirror local naming pattern.
- Vuex mutation/action IDs commonly use verb-style names (example: `setOrderStats`).

### 5.6 Error handling

- Frontend HTTP should generally go through `resources/app/lib/apiFetch.ts`.
- `apiFetch` throws structured errors: `{ status, message, data }`.
- Use `try/catch` in UI actions and keep notification behavior consistent with existing UX.
- In backend handlers, prefer explicit validation and predictable HTTP status codes.
- Do not silently swallow exceptions.

### 5.7 Testing expectations

- Backend edits: run related PHPUnit tests at minimum.
- Frontend edits: run related Vitest file(s) at minimum.
- Iterate with single-test commands first, then run broader suites before handoff.
- Preserve test assumptions (SQLite memory backend tests, jsdom/mocks frontend tests).

## 6) Workflow guidance for agents

- Read nearest `CLAUDE.md` in the area being changed (`app/`, `resources/app/`, `tests/`, `routes/`).
- Keep patches focused; avoid unrelated edits.
- Preserve architecture direction: Laravel controllers/transformers backend, Vuex modules frontend.
- When introducing a new pattern, apply it incrementally and explain rationale in handoff.

## 7) Quick single-test examples

- Frontend: `vitest run tests/ts/ChartStats.test.ts -t "renders the component correctly"`
- Backend: `phpunit --filter order_totals_are_correct tests/Unit/OrderTest.php`

## 8) Practical defaults

- Prefer Bun for frontend scripts unless npm is explicitly requested.
- Start with targeted tests, then run broader suites before handoff.
- Keep API contract compatibility when editing controllers/transformers.
- Avoid introducing new tooling/config unless the task requires it.
- If a command fails due to missing config, report it and proceed with targeted verification.
- Preserve user-authored changes in a dirty worktree; do not revert unrelated edits.
- Never run destructive git operations unless explicitly requested.
