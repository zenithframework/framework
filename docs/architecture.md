# Zenith Framework — Architecture Specification

**Status:** Proposed baseline

## 1. Architecture shape

Zenith is a backend-first, layered and Composer-first PHP framework. HTTP applications, API applications, CLI commands and queue workers share a common application lifecycle and dependency container. Presentation is an adapter: server-rendered UI is optional and external clients remain first-class.

## 2. Logical layers

### Application (`app/`)
User-owned code: controllers/handlers, domain services, models, jobs, listeners, pages and application-specific providers. Framework packages MUST NOT depend on a concrete application class.

### Bootstrap (`boot/`)
Composition root and lifecycle orchestration. It loads configuration, registers providers, builds the container, initializes the application and handles lifecycle transitions. Keep this name; do not rename it to `Kernel/`.

### Core (`core/`)
Framework behavior and feature integration: application abstractions, container integration, routing, HTTP, database/ORM integration, auth, validation, view integration, queue and event abstractions. Core owns framework semantics, not operating-system details.

### System (`system/`)
Runtime and infrastructure implementations: environment loading, filesystem, process/server integration, logging backend, diagnostics, monitoring, performance instrumentation, resilience and graceful shutdown.

### Support (`support/`)
Low-level reusable primitives: array/string helpers, collections, reflection utilities, date/time helpers and small traits. Support SHOULD have no dependency on Core or Application. Avoid moving domain features into Support merely because they are reused.

### Packages (`packages/`)
Development workspace for first-party modules intended to be independently Composer-installable, tested and versioned. A package is a real package only when it owns a `composer.json`, PSR-4 mapping, explicit dependencies, tests and a public API. The existence of a directory does not create package independence.

### Vendor (`vendor/`)
Composer-generated installed dependencies and autoloader. MUST NOT be hand-edited or treated as first-party source.

## 3. Canonical source ownership

A class MUST have one canonical source location. Do not duplicate classes under both `core/` and `packages/`, or mirror source into `vendor/`.

Use this rule:
- Keep tightly integrated runtime composition in the framework's `core/`, `system/` and `support/` layers.
- Put a subsystem in `packages/<package>/` when it has a meaningful independent release/install boundary.
- When extracting an existing subsystem into a package, move it and update autoload/dependencies; do not copy a second implementation.
- The root application may require local packages through Composer path repositories during monorepo development.

The exact extraction boundary must be recorded in an architecture decision record before a subsystem is split.

## 4. Dependency direction

Preferred dependency direction:

```text
Application ──> Framework public APIs / package contracts
Boot ─────────> Core contracts + System implementations + providers
Core ─────────> Contracts + Support
System ───────> Contracts + Support / PHP extensions
Support ──────> PHP standard library (ideally)
Packages ─────> Declared package dependencies only
```

- Support MUST NOT depend on Core, System or Application.
- System SHOULD NOT depend on application code.
- Core SHOULD depend on infrastructure through interfaces/contracts where substitution is valuable.
- Application code SHOULD depend on public abstractions rather than internal implementation classes.
- Circular package dependencies are prohibited.
- Dependency injection and provider registration belong at composition boundaries, not inside arbitrary domain objects.

This is a target dependency policy, not a claim that every current class already satisfies it.

## 5. Application lifecycle

1. Entry point loads Composer autoload.
2. Bootstrap creates configuration/environment context.
3. Container and service providers are registered.
4. Framework runtime services are initialized.
5. The selected entrypoint runs: HTTP request, console command or worker loop.
6. Request/job-scoped services are created and disposed at the correct scope.
7. Exceptions pass through a centralized handler and are logged safely.
8. Termination hooks flush telemetry and perform graceful shutdown.

Long-lived workers MUST use request/job scopes for mutable state. Static global user, request or connection state is prohibited for request-specific data.

## 6. Composition and extensibility

- Use contracts for replaceable databases, cache stores, queue transports, AI providers, filesystem and mail transports.
- Use service providers or explicit registration maps to bind implementations.
- Optional package discovery MUST be deterministic and auditable.
- Configuration must identify active drivers; do not silently select an insecure or unintended production driver.
- Framework internals should not require facade usage; facades MAY be a convenience layer over container-managed services.

## 7. Error handling

A centralized exception pipeline MUST:
- convert known exceptions to appropriate HTTP/CLI responses;
- preserve correlation/request IDs;
- log detailed diagnostics server-side with sensitive values redacted;
- return generic production responses;
- preserve debug detail only when explicitly enabled in a safe development environment;
- avoid swallowing failures or converting all errors into success-shaped values.

## 8. Concurrency and state

- Avoid mutable static properties for current user, guard, request, transaction or tenant.
- Scope state to request, job, command or application lifecycle.
- Document connection pooling and transaction ownership.
- Ensure concurrent jobs cannot accidentally share mutable per-job context.

## 9. Testing boundaries

Each package tests its public API independently and includes integration tests for its adapters. Core tests use fake contracts where appropriate. System adapters receive integration tests against real services where feasible. End-to-end tests cover bootstrap and a representative HTTP request and CLI command.

## 10. Architecture decision records

Changes to public package boundaries, namespace roots, lifecycle, route conventions, database semantics, serialization formats or security defaults require an ADR with context, decision, alternatives, consequences and migration plan.
