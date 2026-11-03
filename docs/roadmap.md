# Zenith Framework — Roadmap and Acceptance Plan

**Status:** Proposed sequencing

This roadmap is a dependency-ordered plan, not a promise of delivery dates. Keep each phase independently testable and releasable.

## Phase 0 — Repository and specification baseline

- Review the target repository and determine whether it is a fresh framework repository or an application skeleton.
- Confirm supported PHP versions, namespace roots, executable name, licensing and package release policy.
- Accept/revise these specification files and add ADRs for unresolved public API decisions.
- Establish CI, formatting, static analysis, security scanning and contribution rules.

**Exit criteria:** documented repository strategy; clean Composer install; CI executes on the supported PHP matrix.

## Phase 1 — Runtime foundation

- Composer autoload and package boundaries.
- Support primitives and contracts.
- Container, provider registration and bootstrap lifecycle in `boot/`.
- Configuration and environment loading.
- Centralized exception handling and safe logging.
- HTTP request/response and routing foundation.
- Minimal CLI with help, test, lint and serve.

**Exit criteria:** fresh app boots; one route and one command run; production exceptions are redacted; worker/request state is scoped.

## Phase 2 — Application essentials

- Middleware pipeline and validation/DTO boundary.
- Database connections and SQL query builder.
- Migrations and schema builder.
- ORM models, hydration, relations and pagination.
- Sessions, authentication and authorization.
- View rendering and optional UI foundation.

**Exit criteria:** CRUD and API examples pass integration/security tests across the declared driver matrix.

## Phase 3 — Developer experience and packaging

- Code generators.
- Route/config/view caches.
- Package discovery and consumer install tests.
- Documentation and upgrade guides.
- Import hygiene and static analysis tooling.

**Exit criteria:** independently installable first-party packages work outside the monorepo.

## Phase 4 — Production capabilities

- Cache stores.
- Queues, retries, workers and outbox.
- Events/listeners and scheduler.
- Observability, health checks and graceful shutdown.
- Upload hardening and security headers.

**Exit criteria:** operational tests cover crash/retry behavior, state isolation and telemetry.

## Phase 5 — Optional capabilities

- AI SDK and provider adapters.
- Temporal ORM history/retention/archive.
- Broadcasting and realtime adapters.
- More database/cache/queue/filesystem providers.
- Frontend starter kits maintained separately.

**Exit criteria:** optional packages do not inflate the minimal install; adapter contract suites pass.

## Cross-phase release gates

- No unresolved critical/high security defects.
- No untested dynamic SQL surface.
- Public APIs are documented and semver policy applied.
- Supported PHP/database matrix is tested.
- Performance data includes reproducible methodology.
- Migrations and upgrade paths are documented.
- Example applications use the public APIs, not internal implementation shortcuts.

## Open decisions to resolve before stable release

1. Final PHP minimum and support window.
2. Final namespace root and package names.
3. Final CLI executable (`zen` vs `zenith`) and command grammar.
4. Whether first-party `core/`, `system/` and `support/` remain directly autoloaded roots or are later extracted to packages.
5. Initial official SQL driver/version matrix.
6. Exact ORM public API and relation coverage.
7. Temporal validity model, initial version semantics and default retention.
8. UI action protocol, state signing and DOM update strategy.
9. AI provider set and normalized capability scope.
10. Stable API versioning and error response schema.
