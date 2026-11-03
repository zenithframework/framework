# Zenith Framework — Directory Structure Specification

**Status:** Proposed baseline

## 1. Recommended repository layout

```text
framework/
├── app/                         # Application-specific code in skeleton projects
│   ├── Http/
│   ├── Models/
│   ├── Services/
│   ├── Events/
│   ├── Listeners/
│   ├── Jobs/
│   ├── Providers/
│   └── UI/
│       ├── Components/
│       ├── Layouts/
│       └── Pages/
├── boot/                        # Bootstrap and composition root
├── core/                        # Framework feature/runtime integration
├── system/                      # Infrastructure and process runtime
├── support/                     # Reusable low-level primitives
├── packages/                    # First-party independent package workspace
│   ├── contracts/
│   ├── container/
│   ├── database/
│   ├── orm/
│   ├── routing/
│   ├── http/
│   ├── auth/
│   ├── cache/
│   ├── queue/
│   ├── events/
│   ├── ai/
│   └── ui/
├── config/
├── routes/
│   ├── Web.php
│   ├── Api.php
│   ├── Auth.php
│   └── Ai.php
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── resources/
│   └── views/
├── public/
├── storage/
├── tests/
├── docs/
├── vendor/                      # Composer-managed; ignored by Git
├── composer.json
├── composer.lock                 # Commit for applications; library policy may differ
└── zenith                       # CLI entrypoint
```

This is a target layout. Keep existing names and paths working while migrating; do not move directories solely for aesthetic consistency.

## 2. Directory responsibilities

| Directory | Owns | Must not own |
|---|---|---|
| `app/` | Application domain and user code | Framework internals |
| `boot/` | Bootstrap, provider loading, composition root | Business/domain behavior |
| `core/` | Framework feature semantics and integration | OS/process adapters or generic helper dumping ground |
| `system/` | Runtime, infrastructure adapters and diagnostics | Application-specific business logic |
| `support/` | General reusable primitives | ORM, auth or other feature subsystems |
| `packages/` | Independently installable first-party packages | Duplicate copies of core/system/support classes |
| `vendor/` | Composer-installed packages | Hand-maintained source |
| `config/` | Configuration files/defaults | Secrets committed to source control |
| `routes/` | Route declarations and grouping | Controller implementation |
| `database/` | App migration/factory/seeder artifacts | ORM engine internals |
| `resources/` | Views and source assets | Publicly served secrets |
| `public/` | Web document root and compiled public assets | Private source/configuration |
| `storage/` | Runtime-generated files/logs/cache/sessions | Version-controlled application source |
| `tests/` | Unit/integration/feature tests | Production classes |

## 3. Core vs System vs Support

### Core
Use for framework features and their integration: HTTP abstractions, routing, container integration, ORM semantics, auth abstractions, validation, views, queue/event contracts and exception pipeline.

### System
Use for runtime and infrastructure: environment/config loading mechanisms, process management, server integration, filesystem drivers, logging backends, health/diagnostic services, performance instrumentation and shutdown handling.

### Support
Use for generic primitives: arrays, strings, collections, reflection, date/time, helper functions and small reusable traits. Keep it deliberately small and dependency-light.

A class is placed by responsibility and dependency direction, not by how frequently it is reused.

## 4. Package workspace vs vendor

- `packages/` contains first-party package source during framework development.
- Each independently releasable package has a package-level `composer.json`, PSR-4 namespace, dependencies, tests, docs and version policy.
- Composer path repositories may symlink local packages into `vendor/` during development.
- `vendor/` contains installed package copies/links and Composer-generated autoload metadata.
- Never create a second manual namespace map for packages if Composer already owns it.
- The installed runtime MUST behave the same whether packages are sourced from local path repositories or a published Composer repository.

## 5. Migration policy

Directory moves are breaking changes when they alter public namespaces, include paths or config conventions. Every move requires:
1. inventory of current references;
2. an explicit autoload migration;
3. compatibility shims where justified;
4. updated generators and docs;
5. tests for old/new behavior during the deprecation window;
6. a removal release and upgrade guide.

## 6. Naming conventions

- PHP namespaces: vendor/project convention, e.g. `ZenithFramework\...`.
- Application namespaces: `App\...`.
- Route filenames retain the capitalized names shown above unless a repository-wide ADR changes them.
- Package names use a stable vendor prefix such as `zenith/<package>`.
- Avoid vague folders such as `Misc/`, `Common/` or an unlimited `Helpers/` dumping ground.
