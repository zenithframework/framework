# Zenith Framework

Zenith is a backend-first PHP framework. This repository contains only the essential backend runtime.

## Scope

The core owns application lifecycle, dependency-container contracts, HTTP request/response abstractions, routing and middleware contracts, database connection contracts, authentication/session/cache contracts, validation primitives, event dispatching, exceptions, and shared utilities.

Optional systems are intentionally excluded: ORM, migration tooling, database vendor drivers, AI SDK, UI/view engine, frontend assets, testing framework, WebSocket server, queue backends, and provider-specific SDKs. They will be developed in separate repositories and integrated as optional Composer packages.

## Requirements

- PHP 8.2+
- Composer 2

## Install

```bash
composer require zenithframework/framework
```

## Development

```bash
composer validate
composer dump-autoload
```

All PHP files must declare `strict_types=1`. See `docs/` for architecture boundaries.
