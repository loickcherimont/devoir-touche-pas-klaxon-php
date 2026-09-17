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
npm run sass:watch
```

### 2. Composer dependencies

```bash
composer install
```

Run the app:

```bash
composer dev
```

Your app is now running on [http://localhost:8080](http://localhost:8080).

## 🔍 Code Quality

Run PHPStan (static analysis) to **qualify the code before every push or
production deploy**: it checks the code without executing it. Default level is
**5**; use level **8** for a serious check, especially before going to
production.

```bash
# Default analysis (level 5)
composer phpstan

# Serious check before production (level 8)
composer phpstan:strict
```

> [!IMPORTANT]  
> The next parts of this project are currently **in development** and will be added soon.

## 🔑 License

<div align="center">Copyright © 2026 | Loick CHERIMONT | All Rights Reserved.</div>
