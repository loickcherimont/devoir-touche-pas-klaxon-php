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

### Administrator account

The seed also ships **one admin** account, the only one allowed to reach the
dashboard at `/admin`. It follows the exact same password convention:

| Email | Password | Role |
| --- | --- | --- |
| `admin@email.fr` | `JohDoe@test` | `admin` |

**John Doe** → `JohDoe@test`, exactly like **Alexandre Martin** → `AleMar@test`.

> [!WARNING]
> These are **demo / development credentials only**. They are committed on
> purpose so the project can be run and reviewed out of the box. Never reuse
> them anywhere else, and never run `Core/data.sql` against a real database.

### How the roles work

`role` is a `VARCHAR` in the database but a real type in the code: the
`App\Model\User\UserRole` enum lists the two possible values. Nothing else
writes `'admin'` or `'user'`:

```php
$role = UserRole::tryFrom((string) $user['role']); // null for an unknown value

if ($role === null) {
    // Refuse the login: the app never grants an access it cannot name.
}
```

Controllers test the role through `AbstractController::hasRole()`, and the
role is injected in every template as `$userRole`, so a view never reads
`$_SESSION` by itself.

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
| `TripModelTest` | `deleteTripById()` turns the trip id + its owner into a correct DELETE |

## 🔍 Code Quality

### Before every push

Three gates, **all green** before anything reaches the remote:

| Gate | Command | What it checks |
| --- | --- | --- |
| Syntax | `composer lint` | every PHP file parses (`php -l`, one file at a time) |
| Static analysis | `composer phpstan` | level **8** on `App`, `Core`, `Router`, `public`, `templates` and `tests` |
| Tests | `composer test` | the unit test suite (PDO is mocked, no MySQL needed) |

```bash
# All three, in order
composer lint && composer phpstan && composer test
```

PHPStan analyses the code **without executing it** at level **8**, which is
the strictest level that still does not require a full type system. A clean
run means every class, method signature and property type is documented and
consistent.

> [!TIP]
> `composer lint` is a one-liner over `php -l`; run it on a single file while
> writing with `php -l path/to/File.php`.

### Coding conventions observed in this project

| Convention | Where |
| --- | --- |
| Controllers stay thin: read the request, ask a model, return a `Response` | `App/Controller` |
| Views only display what a controller gives them, all values escaped with `htmlspecialchars()` | `templates` |
| No SQL outside models, always prepared statements | `App/Model`, `Core` |
| Roles and states are enums, never raw strings | `App/Model/User/UserRole.php` |
| One `return` per branch, braces always present (PSR-12) | everywhere |
| Access control goes through `AbstractController::hasRole()`, checked at the top of every action | `App/Controller` |

> [!IMPORTANT]  
> The next parts of this project are currently **in development** and will be added soon.

## 🔑 License

<div align="center">Copyright © 2026 | Loick CHERIMONT | All Rights Reserved.</div>
