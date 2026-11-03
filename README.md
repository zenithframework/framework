# Zenith Framework

Zenith is a backend-first PHP framework. This repository contains only the essential backend runtime.

## Scope

The core owns application lifecycle, dependency-container contracts, HTTP request/response abstractions, routing and middleware contracts, database connection contracts, authentication/session/cache contracts, validation primitives, event dispatching, exceptions, and shared utilities.

Optional systems are intentionally excluded: ORM, migration tooling, database vendor drivers, AI SDK, UI/view engine, frontend assets, testing framework, WebSocket server, queue backends, and provider-specific SDKs. They will be developed in separate repositories and integrated as optional Composer packages.

## Requirements

- PHP 8.5 or later in the PHP 8.x series (Composer constraint: `^8.5`)
- Composer 2

PHP 9 is not included in the supported constraint until compatibility is verified.

## Install

```bash
composer require zenithframework/framework
```

## Development

Install development tools and dependencies:

```bash
composer install
composer validate
composer dump-autoload
```

Check PSR-12 formatting:

```bash
composer lint:style
```

Automatically fix supported PSR-12 violations:

```bash
composer fix:style
```

All PHP source files must declare `strict_types=1`. PSR-12 is enforced by PHP_CodeSniffer using the repository's `phpcs.xml` ruleset. See `docs/` for architecture and contribution standards.
