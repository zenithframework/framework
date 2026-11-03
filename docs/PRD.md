# Zenith Framework — Product Requirements Document

**Status:** Proposed baseline, consolidated from project discussions  
**Product:** Zenith Framework  
**Repository:** https://github.com/zenithframework/framework  
**Runtime baseline:** PHP `^8.5` (required by the root Composer manifest)

## 1. Executive summary

Zenith is a modern, backend-first PHP application framework designed around predictable architecture, secure defaults, performance, developer productivity and Composer interoperability. It provides a coherent application runtime and a set of composable capabilities: HTTP routing, database access and ORM, authentication, validation, caching, queues, events, AI integrations, CLI tooling and an optional server-rendered interactive UI.

Zenith is one framework, not two competing application modes. Built-in server-rendered UI is an optional presentation capability. Applications using React, Vue, Next.js, Svelte, mobile clients or other frontends must be able to use the same backend through stable HTTP and API contracts.

This PRD defines intended behavior and acceptance criteria. It does not assert that the repository currently implements these capabilities.

## 2. Problem statement

PHP developers need a cohesive framework that provides a productive application experience without forcing a particular frontend, hiding important runtime behavior, coupling the application to a single database/provider, or requiring unsafe shortcuts. Zenith should make common tasks straightforward while retaining explicit control over security, database behavior, dependencies and production operations.

## 3. Product goals

1. Provide a single, understandable backend architecture for web applications, APIs and worker processes.
2. Make the framework Composer-first and interoperable with PSR standards and established PHP packages.
3. Offer secure defaults: parameterized SQL, safe output encoding, explicit authorization, validated input and production-safe error handling.
4. Provide a performant ORM and database layer with multiple SQL drivers and a separate document-database integration boundary.
5. Make server-rendered pages and interactive server actions optional, with no mandatory frontend framework for backend applications.
6. Support provider-neutral AI integrations through independently maintainable adapters.
7. Provide CLI generation, automated diagnostics, testing and documentation that keep the framework consistent.
8. Make package boundaries explicit so first-party modules can be tested, versioned and reused independently.

## 4. Non-goals

- Replacing PHP or Composer.
- Requiring a JavaScript framework for ordinary backend or server-rendered applications.
- Claiming zero JavaScript for interactive browser behavior; the framework may ship a small runtime.
- Making MongoDB behave like a relational SQL database.
- Enabling temporal history for every model by default.
- Automatically converting arbitrary raw SQL, dynamic identifiers or untrusted input into safe queries.
- Promising to outperform Laravel, Symfony or another framework without repeatable comparative benchmarks.
- Shipping every optional integration in the minimum installation.

## 5. Target users and use cases

| User | Needs |
|---|---|
| PHP application developer | Build web applications, APIs, authentication and data access quickly |
| Framework/package author | Develop, test and release isolated Composer packages |
| Full-stack developer | Build server-rendered screens or connect a separate frontend |
| Platform/operations engineer | Configure workers, queues, logs, health checks and deployments |
| Enterprise/team maintainer | Enforce predictable boundaries, tests, security and upgrade practices |

Representative use cases include CRUD applications, JSON APIs, authenticated applications, background processing, realtime features, AI-assisted products, admin interfaces, and applications that must retain an audit/history trail.

## 6. Product principles

- **Explicit over magical:** conventions should be discoverable; implicit behavior must be documented.
- **Secure by default:** unsafe behavior requires an explicit and auditable escape hatch.
- **Backend-first:** no frontend dependency leaks into the core HTTP, database or domain layers.
- **Composable:** optional capabilities and drivers are installed only when needed.
- **Standards-first:** PSR interfaces and Composer are preferred over proprietary replacements where practical.
- **Incremental evolution:** preserve existing behavior and migrate in small, tested steps.
- **Observable:** errors and runtime health must be diagnosable without exposing secrets to users.
- **Honest performance:** benchmark workloads and publish methodology, not unsupported claims.

## 7. Scope and functional requirements

### 7.1 Runtime and application lifecycle
- The framework MUST expose one application lifecycle for HTTP requests, CLI commands and worker execution.
- `boot/` MUST own bootstrap and composition-root responsibilities.
- Services MUST be registered and resolved through an explicit dependency-injection/container boundary.
- Long-lived workers MUST not leak request-specific mutable state between requests or jobs.
- Startup, request handling, termination and graceful shutdown MUST have testable lifecycle hooks.

### 7.2 HTTP, routing and middleware
- The framework MUST provide request/response abstractions, route matching, route parameters, middleware and exception handling.
- Route definitions MUST be separable into `routes/Web.php`, `routes/Api.php`, `routes/Auth.php` and `routes/Ai.php`.
- Default route groups SHOULD use prefixes `/`, `/api`, `/auth` and `/ai` respectively, with documented override support.
- Web and API middleware defaults MUST be distinct where their security models differ.
- API contracts MUST remain usable by external web and mobile clients.

### 7.3 Database and ORM
- SQL access MUST use prepared statements for values.
- Dynamic identifiers, operators and ordering expressions MUST use allowlists or structured expression objects.
- The ORM SHOULD provide models, query building, collections, relations, scopes, eager loading, pagination, factories, seeders and migration integration.
- Public APIs SHOULD use explicit resources/DTOs and bounded pagination rather than returning arbitrary unbounded model collections.
- SQL drivers MUST be adapters behind stable connection contracts.
- MongoDB/document persistence MUST be a separate adapter with document-native semantics.
- Temporal history MUST be optional, configurable and separately documented.

### 7.4 Security and identity
- The framework MUST provide secure password hashing, session handling, authentication guards, authorization policies and request validation.
- CSRF protection MUST apply to state-changing browser requests authenticated by cookies/sessions.
- Bearer-token APIs MUST not receive an indiscriminate browser CSRF requirement; they need appropriate token and authorization controls.
- Templates MUST escape untrusted output by default.
- Production errors MUST not reveal stack traces, secrets, SQL or raw exception messages.

### 7.5 UI
- Server-rendered pages, layouts and components MUST be optional.
- The UI layer SHOULD support server-side actions with minimal client runtime and predictable state updates.
- Actions MUST enforce CSRF, authentication, authorization, input validation and output escaping.
- Tailwind and shadcn-style component conventions SHOULD be supported without requiring them in the framework core.
- External frontend starter kits MAY be maintained separately.

### 7.6 AI
- AI integration MUST expose a provider-neutral SDK and provider adapters.
- Transport, provider payload mapping, response normalization and streaming MUST have separate responsibilities.
- API keys MUST be loaded from secure configuration and never logged or returned to clients.
- Timeouts, bounded retries, cancellation where supported and error normalization MUST be defined.

### 7.7 Async processing
- Queue, event, scheduler and broadcasting features MUST use contracts and adapters.
- Jobs SHOULD support retries, backoff, idempotency, timeouts and dead-letter handling.
- Reliable publication from database transactions SHOULD use a transactional outbox pattern where required.

### 7.8 CLI and tooling
- The CLI MUST expose consistent commands for project inspection, serving, testing and code generation.
- Generators MUST follow canonical directory and namespace rules.
- A code-quality command SHOULD detect missing, unused, duplicate and incorrectly ordered imports, plus avoidable fully qualified class names.
- Closures MUST remain allowed where appropriate; tooling must not enforce a blanket closure ban.
- PSR-12 compliance MUST be checked through the Composer `lint:style` script.

## 8. Quality attributes

| Attribute | Requirement |
|---|---|
| Security | Threat-modelled defaults, automated regression tests and no secret leakage |
| Performance | Baseline benchmarks for routing, container, ORM and representative requests |
| Reliability | Deterministic migrations, worker retries and graceful failure |
| Maintainability | Package boundaries, clear ownership, static analysis and coding standards |
| Portability | PHP `^8.5` runtime requirement and documented database-driver matrix |
| Accessibility | Built-in UI examples use semantic HTML and keyboard-accessible controls |
| Compatibility | Semver policy and documented upgrade/deprecation process |

## 9. Success metrics

Release gates should measure: test suite pass rate; static-analysis baseline; PSR-12 style check pass rate; security test coverage; supported driver integration matrix; time to create a basic page/API/resource; package installability; migration safety; request and query benchmark baselines; documentation completeness; and absence of known critical/high security defects.

No numeric performance target is accepted until a reproducible baseline is recorded on defined hardware, PHP version, database and workload.

## 10. Release strategy

1. **Foundation:** bootstrap, container, configuration, support primitives, exception handling, HTTP request/response and route dispatch.
2. **Application essentials:** validation, database connections, query builder, migrations, ORM foundations, sessions and authentication.
3. **Developer experience:** CLI, generators, testing utilities, docs and examples.
4. **Production capabilities:** cache, queues, events, observability, security hardening and worker lifecycle.
5. **Optional capabilities:** interactive UI, AI providers, broadcasting, temporal data and additional drivers.

Each phase must preserve a runnable framework and include upgrade notes.

## 11. Risks and mitigations

- **Over-modularization:** start with a small set of real package boundaries; do not split every class into a package.
- **Duplicate source trees:** each class has exactly one canonical source location.
- **API instability:** version public contracts and add compatibility tests before releasing packages.
- **ORM complexity:** separate SQL query execution, model hydration and document persistence.
- **History growth:** configurable retention, partitioning/archive strategies and observability.
- **UI security:** treat browser actions as untrusted HTTP requests, not privileged internal calls.
- **Scope expansion:** ship a stable foundation before optional subsystems.

## 12. Acceptance criteria

The initial product baseline is accepted when:
- all specification documents are reviewed and decisions marked;
- a fresh application can bootstrap with Composer autoloading;
- one HTTP route and one CLI command can run through the same application configuration;
- database values are bound and unsafe identifiers are rejected;
- tests prove production error redaction and default output escaping;
- first-party package boundaries and dependency direction are documented;
- external clients can consume the API without installing the UI runtime;
- migration, security, package and upgrade guides exist;
- `composer validate` and `composer lint:style` pass in CI;
- benchmarks and compatibility claims include reproducible evidence.
