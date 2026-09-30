# 🚗 Touche pas au klaxon

**CEF Homework** — Enterprise web application in **PHP 8.1+** to manage car sharing:
users publish trips between agencies, admins manage agencies and trips from a dashboard.

## 🖥️ Tech Stack

**Backend**

- **PHP 8.1+** — Server-side language (8.4 locally)
- **Composer** — Dependency management + PSR-4 autoloading
- **izniburak/router 3.1** — HTTP routing
- **Symfony HttpFoundation** — `Request` / `Response` (transitive dependency)
- **PHPUnit 13** — Unit testing, PDO is mocked (no MySQL required)
- **PHPStan 2** — Static analysis at level 8

**Frontend**

- **Sass** — CSS preprocessor
- **Bootstrap 5.3** — Responsive UI (imported from `node_modules`)

## 🚀 Setup

### 1. NPM dependencies

```bash
npm install
npm run build
```

`npm run build` compiles the Sass entry point into `public/styles/` and copies
`bootstrap.bundle.min.js` into `public/js/`. Both are generated artifacts, so
they are git-ignored. While developing, use the watcher instead:

```bash
npm run sass:watch
```

> [!NOTE]
> Sass compiles with `--load-path=node_modules` (see the npm scripts): Bootstrap
> is imported from `node_modules`, the build fails without this load path.

### 2. Composer dependencies

```bash
composer install
```

### 3. Database

No migration tool: create the base and load the seed, in this order.

```bash
mysql -u root < Core/schema.sql                        # creates the 3 tables
mysql -u root touche_pas_au_klaxon < Core/data.sql     # data.sql has no USE
```

`Core/data.sql` is re-runnable (`INSERT IGNORE`): **12 agencies**, **21 users**,
**7 trips**.

### 4. Start the app

The connection settings are read from **environment variables** (`Core/Config::get()`
calls `getenv()`). No dotenv loader is installed, so `.env.example` is only a
reference: copy it to `.env` and nothing changes. Pass the variables by hand on
the command line, they are only set for that run:

```bash
DB_HOST=[YOUR_DB_HOST] DB_PORT=[YOUR_DB_PORT] DB_NAME=[YOUR_DB_NAME] DB_USER=[YOUR_DB_USER] DB_PASS=[YOUR_DB_PASS] composer dev
```

Omit them and the app silently falls back to `localhost:3306`, database
`touche_pas_au_klaxon`, user `root`, empty password.

The app runs on [http://localhost:8080](http://localhost:8080).

## ▶️ Usage

### Test accounts

Seed passwords follow a simple convention (`[3 first letters of the first name,
capitalized][3 first letters of the last name, capitalized]@test`):

| Email | Password | Role |
| --- | --- | --- |
| `alexandre.martin@email.fr` | `AleMar@test` | `user` |
| `admin@email.fr` | `JohDoe@test` | `admin` |

Every other user in `Core/data.sql` follows the same rule (Alexandre Martin →
`AleMar@test`, John Doe → `JohDoe@test`).

> [!WARNING]
> These are **demo credentials**, committed on purpose so the project runs out
> of the box. Never reuse them elsewhere, never run `Core/data.sql` against a
> real database.

### Routes

All destructive actions are **POST** (never `GET`), every POST form carries a
**CSRF token**, and each write redirects back with a one-shot flash message
(PRG pattern).

| Method | Route | Access | Controller action |
| --- | --- | --- | --- |
| GET | `/` | public (users) / redirect to `/admin` | `HomeController::index` |
| GET | `/login` | public | `LoginController::index` |
| POST | `/login` | public | `LoginController::login` |
| GET | `/logout` | logged in | `LoginController::logout` |
| GET | `/trips/new` | logged in | `TripController::createTripForm` |
| POST | `/trips/new` | logged in | `TripController::createTrip` |
| GET | `/trips/update/:id` | owner | `TripController::updateTripForm` |
| POST | `/trips/update/:id` | owner | `TripController::updateTrip` |
| POST | `/trips/delete/:id` | owner | `TripController::deleteTrip` |
| GET | `/admin` | admin | `AdminController::index` |
| POST | `/admin/agencies/new` | admin | `AdminController::createAgency` |
| POST | `/admin/agencies/update` | admin | `AdminController::updateAgency` |
| POST | `/admin/agencies/delete/:id` | admin | `AdminController::deleteAgency` |
| POST | `/admin/trips/delete/:id` | admin | `AdminController::deleteTrip` |

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

Controllers guard each action with `AbstractController::isLoggedIn()` then
`hasRole()`, and the role is injected in every template as `$userRole`, so a
view never reads `$_SESSION` by itself.

## 🧪 Tests

The unit suite mocks `PDO`, so it runs without a MySQL server.

```bash
composer test                      # whole suite
./vendor/bin/phpunit tests/TripModelTest.php
./vendor/bin/phpunit --filter testSaveTripSendsAnInsertToPdoWithTheTripValues
```

| Test | Covers |
| --- | --- |
| `AbstractModelTest` | `save()` forwards the SQL and its params to PDO |
| `TripModelTest` | `saveTrip()` / `updateTripById()` / `deleteTripById()` build the right statements, and `PDOException` propagates |
| `AgencyModelTest` | `getAllAgencies()` / `saveAgency()` / `deleteAgencyById()` build the right statements |
| `CsrfTest` | session token generation and `verify()` |
| `FlashTest` | one-shot message storage |

## 🔍 Code Quality

### Before every push

```bash
composer lint && composer phpstan && composer test
```

| Gate | Command | What it checks |
| --- | --- | --- |
| Syntax | `composer lint` | every PHP file parses (`php -l`, one file at a time) |
| Static analysis | `composer phpstan` | level **8** on `App`, `Core`, `Router`, `public`, `templates` and `tests` |
| Tests | `composer test` | the unit test suite (PDO mocked, no MySQL needed) |

> [!TIP]
> `composer lint` is a one-liner over `php -l`; run it on a single file while
> writing with `php -l path/to/File.php`.

### Conventions

| Convention | Where |
| --- | --- |
| Controllers stay thin: read the request, validate, ask a model, return a `Response` | `App/Controller` |
| Validation lives in dedicated services, away from the controllers | `App/Service` |
| CSRF verification and session flash messages are dedicated helpers | `App/Security` |
| Views only display what a controller gives them, all values escaped with `htmlspecialchars()` | `templates` |
| No SQL outside models, always prepared statements | `App/Model`, `Core` |
| Roles are enums, never raw strings | `App/Model/User/UserRole.php` |
| One `return` per branch, braces always present (PSR-12) | everywhere |
| Access control: `isLoggedIn()` then `hasRole()`, checked at the top of every action | `App/Controller` |
| User-facing messages in French, code and comments in English | everywhere |

## 🔑 License

<div align="center">MIT License — Copyright © 2026 Loïck CHERIMONT.</div>
