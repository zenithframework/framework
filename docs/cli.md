# Zenith Framework — CLI and Developer Experience Specification

**Status:** Proposed baseline

## 1. Goals

Provide one discoverable CLI entrypoint for project commands, code generation, diagnostics, local development and operational tasks. CLI syntax must be consistent, documented and testable.

## 2. Entry point

Use the repository's documented `zenith` or `zen` executable consistently. Do not support contradictory syntaxes without an explicit compatibility alias. Recommended command shape:

```bash
php zen make:controller UserController
php zen make:model User
php zen make:page Dashboard
php zen make:component Alert
php zen lint
php zen test
php zen serve
```

The exact executable name is a release decision; the grammar above illustrates the preferred `make:<artifact>` pattern.

## 3. Command requirements

- Show help and examples for each command.
- Use stable exit codes for success, usage error, validation failure and runtime failure.
- Never print secrets.
- Support non-interactive/CI execution.
- Validate target paths and avoid overwriting existing user files by default.
- Offer an explicit force/overwrite option only when safe and documented.
- Keep output deterministic for automation.

## 4. Generators

Generators must:
- derive namespaces from Composer PSR-4 configuration;
- follow the canonical directory structure;
- emit strict types and imports;
- include useful types and safe defaults;
- never generate production TODO placeholders or insecure examples;
- detect path/class collisions;
- be covered by snapshot or integration tests.

Potential generators: controller, request DTO, model, migration, factory, seeder, middleware, policy, event, listener, job, page, component, layout and provider.

## 5. Quality and inspection commands

Provide commands for formatting/linting, static analysis, import hygiene, route listing, config inspection, package validation, migration status, cache status and environment diagnostics. Sensitive configuration values must be masked.

The import checker should identify missing, unused, duplicate and incorrectly ordered imports and avoidable inline FQCN usage. It must not enforce a blanket prohibition on closures.

## 6. Development and production safety

Local server commands are for development, not a production deployment recommendation. Destructive commands (database reset, migration rollback, cache flush, key rotation) require clear target/environment display and explicit safeguards. Production confirmation should be impossible to bypass accidentally in automation without an explicit flag/policy.

## 7. Acceptance tests

Test help text, command exit codes, generator namespace/path mapping, no-overwrite behavior, invalid arguments, CI non-interactive mode, secrets masking and destructive-command guards.
