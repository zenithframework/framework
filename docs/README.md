# Zenith Framework — Product & Engineering Specifications

This directory is the documentation source of truth for the planned Zenith Framework.

## Status and interpretation

These documents consolidate requirements and design decisions from project discussions. They are **specifications, not claims that features already exist**. Each feature must be validated against implementation and tests before being marked complete.

- **Normative terms:** MUST, MUST NOT, SHOULD, MAY are used as requirements language.
- **PHP baseline:** PHP `^8.5` is required by the root Composer manifest. This supports compatible PHP 8.x releases from 8.5 onward; PHP 9 remains unsupported until verified.
- **Coding standard:** PSR-12 is enforced by PHP_CodeSniffer using the root `phpcs.xml` ruleset and Composer scripts `lint:style` / `fix:style`.
- **Architecture direction:** backend-first, Composer-first, modular framework; optional server-rendered interactive UI; stable API support for external clients.
- **Compatibility principle:** retain existing working behavior during implementation; use incremental migrations rather than a destructive rewrite.

## Specification index

| File | Scope |
|---|---|
| [Product requirements](PRD.md) | Vision, users, goals, non-goals, success criteria, release strategy |
| [Architecture](architecture.md) | System boundaries, dependency rules, application lifecycle |
| [Directory structure](directory-structure.md) | Ownership of app, boot, core, system, support, packages and vendor |
| [Package and Composer model](package-management.md) | Monorepo workspace, package manifests, path repositories and installed dependencies |
| [PHP standards and typing](php-standards.md) | PHP 8.5+, strict typing, namespaces, imports, PSR-12 and style tooling |
| [HTTP and routing](http-routing.md) | Requests, responses, middleware, route files and API contracts |
| [Database and ORM](database-orm.md) | Connections, query builder, models, relations, security and pagination |
| [Migrations and temporal data](migrations-temporal.md) | Schema evolution, history tables, retention, archive and partition strategy |
| [Security](security.md) | Secure defaults, threat model and operational controls |
| [UI and server interactions](ui.md) | Pages, components, layouts, actions, rendering and frontend interoperability |
| [Authentication and authorization](authentication.md) | Guards, sessions, policies and password handling |
| [AI SDK](ai.md) | Provider-neutral API, adapters, transport, streaming and safety |
| [Queues, events and broadcasting](queues-events.md) | Asynchronous work, delivery guarantees and realtime adapters |
| [CLI and developer experience](cli.md) | Commands, generators, diagnostics and project workflows |
| [Testing and quality](testing-quality.md) | Unit, integration, security, performance and compatibility gates |
| [Operations and observability](operations.md) | Logging, errors, cache, runtime, health and graceful shutdown |
| [Roadmap and acceptance](roadmap.md) | Sequencing, dependencies and definition of done |

## Decision register

1. The core repository is a backend-only foundation. ORM, migration tooling, AI, UI, queue backends, WebSocket servers and provider integrations are separate optional Composer packages/repositories unless a later ADR changes this boundary.
2. Composer requires PHP `^8.5`; PHP 9 is not declared supported until compatibility is verified.
3. PSR-12 is enforced through the committed PHP_CodeSniffer ruleset and Composer scripts.

1. Zenith is one backend-first framework architecture. Built-in UI is optional; external web, mobile and desktop clients consume stable HTTP/API contracts.
2. Keep `boot/` as the application bootstrap/composition root. Do not rename it to `Kernel/`.
3. Separate framework features (`core/`), runtime/infrastructure (`system/`), and reusable low-level utilities (`support/`) by responsibility. Do not create duplicate implementations across these directories.
4. Use `packages/` as the first-party package source workspace. Use `vendor/` exclusively for Composer-installed dependencies. Do not hand-edit `vendor/`.
5. Framework packages must be real Composer packages with their own manifest, PSR-4 mapping, dependency declarations and tests; a folder alone does not make a package independent.
6. Keep strict typing in PHP files with `declare(strict_types=1);`. PHP does not support enabling strict typing at namespace or runtime scope.
7. Prefer explicit imports at the top of PHP files. Do not ban closures globally; use them where they are appropriate, including route groups and migrations.
8. Preserve the route-file convention `routes/Web.php`, `routes/Api.php`, `routes/Auth.php`, and `routes/Ai.php`, with clearly defined route prefixes and middleware.
9. SQL values MUST use bound parameters. Dynamic identifiers and SQL fragments require explicit allowlists or structured query APIs.
10. Temporal history is an optional ORM capability with explicit query APIs, configurable retention and operational controls. It must not silently make every model temporal.
11. No performance or security superiority claims against other frameworks without reproducible benchmarks and evidence.

## Status labels

Use `Proposed`, `Accepted`, `Implemented`, and `Deprecated` in follow-up decision records. A decision becomes `Implemented` only when code, tests and documentation agree.
