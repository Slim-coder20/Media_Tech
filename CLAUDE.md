# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Laravel Boost

This project has Laravel Boost installed. **Read `AGENTS.md` before making changes** — it contains detailed, tool-specific guidelines (search-docs usage, Artisan conventions, Pint formatting, Pest testing conventions, PHP style rules) that apply on top of everything below and are not repeated here.

## Commands

```sh
composer setup   # composer install, .env, key:generate, migrate, npm install, npm run build (first-time setup)
composer dev      # runs the dev server, queue listener, log viewer (pail) and Vite concurrently
npm run dev       # Vite dev server only
npm run build     # Vite production build

php artisan test --compact           # full test suite
php artisan test --filter=testName   # single test
vendor/bin/pest path/to/Test.php     # run a specific Pest file directly
vendor/bin/pest --filter=testName

vendor/bin/pint --dirty --format agent   # format only files changed since last commit (run after any PHP edit)
```

- DB is SQLite in both dev (`database/database.sqlite`) and tests (`:memory:`, configured in `phpunit.xml`) — no external DB service needed.
- Tests use Pest (`tests/Feature`, `tests/Unit`), with `pestphp/pest-plugin-laravel`.

## Architecture

This app has **two separate front-end stacks that must not be mixed**:

1. **Public/auth pages** — `resources/views/auth/*`, `resources/views/layouts/{app,guest}.blade.php`, `resources/views/components/*`. This is the standard Laravel Breeze scaffolding: Tailwind CSS (built via Vite, `resources/css`, `tailwind.config.js`) + Alpine.js. Use Tailwind utility classes here.
2. **Admin dashboard ("back")** — `resources/views/back/*`. This is a separate, self-contained Bootstrap admin template whose CSS/JS/fonts/images are static assets served from `public/back_auth/assets` (jQuery, Bootstrap 4/5, FontAwesome, Morris charts, slimscroll) — **not** compiled through Vite/Tailwind. Pages here use Bootstrap conventions (`row`, `col-md-*`, `form-group`, `btn btn-primary`), not Tailwind utilities.
   - All admin pages extend `back.app` (`resources/views/back/app.blade.php`), which includes `back.partials.header`, `back.partials.sidebar`, `back.partials.styles`, `back.partials.scripts`, and exposes two yields: `@yield('dashboard-header')` and `@yield('dashboard-content')`.
   - New admin views should follow the pattern in `resources/views/back/category/*.blade.php`.
   - UI text in the admin dashboard is in French; keep new admin-facing strings consistent with that.

### Admin CRUD module pattern

`app/Http/Controllers/Category/CategoryController.php` + `app/Http/Requests/Category/{Store,Update}CategoryRequest.php` + `app/Models/Category.php` is the reference pattern for admin resources: controllers and form requests are namespaced per-resource (`Category\`), not flat under `Http/Controllers`/`Http/Requests`. Follow this structure (dedicated sub-namespace per resource) when adding new admin CRUD features rather than flattening everything into the top-level `Controllers`/`Requests` directories.

- `App\Models\Category` uses `spatie/laravel-sluggable` (`HasSlug`) to auto-generate `slug` from `name` on save — follow this pattern for any other sluggable model instead of hand-rolling slug logic.
- Admin routes are currently declared ad hoc in `routes/web.php` (not via `Route::resource`) — e.g. `GET /categories`, `GET /create`, `POST /categories`. Match this existing style when adding routes for a resource unless asked to refactor to resourceful routing.
- Auth routes (login/register/password/email verification) live in `routes/auth.php` and are `require`d from `routes/web.php`.

### Other notes

- `App\Models\User` uses PHP attributes (`#[Fillable(...)]`, `#[Hidden(...)]`) instead of the classic `protected $fillable` / `protected $hidden` properties — follow this attribute-based style on `User`, but note `Category` still uses the classic `protected $fillable` array; match whichever style the model you're editing already uses.
- Profile image upload logic lives in `app/Http/Controllers/ProfileController.php`.
