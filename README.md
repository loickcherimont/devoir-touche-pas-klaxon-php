# 🚗 Touche pas au klaxon

**CEF Homework** — Enterprise web application made with **PHP** to manage car sharing.

## 🖥️ Tech Stack

**Backend:**

- **PHP 8** — Server-side language
- **Composer** — Dependency management & autoloading (`Loick\DevoirTouchePasKlaxonPhp`)
- **izniburak/router 3.1** — Routing/PHP router ([github.com/izniburak/router](https://github.com/izniburak/router))
- **PHPUnit** — Unit testing ([phpunit.de](https://phpunit.de))
- **PHPStan** — Static analysis ([phpstan.org](https://phpstan.org))
- **phpDocumentor** — API documentation ([phpdoc.org](https://www.phpdoc.org))
- **DocBlocks** — Code documentation

**Frontend:**

- **Sass** — CSS preprocessor ([sass-lang.com/](https://sass-lang.com/))
- **Bootstrap 5.3** — Responsive UI framework ([getbootstrap.com](https://getbootstrap.com))


## 🚀 Setup

### 1. NPM dependencies

```bash
npm install
npm run build
```

`npm run build` compiles the Sass entry point into `public/styles/index.css` and
copies `bootstrap.bundle.min.js` into `public/js/`, the only directories the PHP
dev server exposes. Both are generated artifacts, so they are git-ignored.

While developing, use the watcher instead — it recompiles the stylesheet on
every change, and refresh the browser to pick them up:

```bash
npm run sass:watch
```

> [!NOTE]
> Bootstrap's JavaScript bundle is required by every interactive component
> (modals, dropdowns, collapse...). Without it, `data-bs-*` attributes are
> simply ignored and those components never open.

### 2. Composer dependencies

```bash
composer install
```

Configuration is read from environment variables. Copy the provided template and
adjust it to your local MySQL setup:

```bash
cp .env.example .env
```

Run the app:

```bash
composer dev
```

Your app is now running on [http://localhost:8080](http://localhost:8080).

## ▶️ Usage

### Test accounts

The `Core/data.sql` seed file ships **20 users** whose passwords follow a simple
convention (see the comment at the top of the `users` inserts):

```
[3 first letters of the first name, capitalized][3 first letters of the last name, capitalized]@test
```

Example for the first user, **Alexandre Martin** → `AleMar@test`.

| Email | Password |
| --- | --- |
| `alexandre.martin@email.fr` | `AleMar@test` |
| `sophie.dubois@email.fr` | `SopDub@test` |
| `julien.bernard@email.fr` | `JulBer@test` |

Any other user in `Core/data.sql` follows the same rule.

## 🧪 Tests

Unit tests cover the **database write operations** (the assignment requirement).
They mock `PDO`, so the suite runs without a MySQL server.

```bash
# Run the whole suite
composer test
```

| Test | Covers |
| --- | --- |
| `AbstractModelTest` | `save()` forwards the SQL and its params to PDO |
| `TripModelTest` | `saveTrip()` turns a `TripDataDTO` into a correct INSERT |
| `TripModelTest` | `updateTripById()` turns a `TripDataDTO` into a correct UPDATE |

## 🔍 Code Quality

Run PHPStan (static analysis) to **qualify the code before every push or
production deploy**: it checks the code without executing it, at level **8**,
and covers `App`, `Core`, `Router`, `public`, `templates` and `tests`.

```bash
# Static analysis (level 8)
composer phpstan
```

> [!IMPORTANT]  
> The next parts of this project are currently **in development** and will be added soon.

## 🔑 License

<div align="center">Copyright © 2026 | Loick CHERIMONT | All Rights Reserved.</div>
