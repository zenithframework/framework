# Zenith Framework — PHP Standards and Typing Specification

**Status:** Proposed baseline

## 1. PHP baseline

The current project direction specifies PHP `^8.5`. Before publishing releases, confirm the minimum version is available in supported deployment environments and that CI runs the declared minimum plus current supported versions.

## 2. Strict typing

PHP strict typing is a **file-level** declaration:

```php
<?php

declare(strict_types=1);

namespace App\\Services;

final class AuthService
{
    public function authenticate(string $email, string $password): bool
    {
        // ...
        return false;
    }
}
```

PHP does not provide a namespace-level or runtime switch equivalent to `declare(strict_types=1)`. A namespace such as `Zenith\\Strict` cannot turn strict typing on for every class within it. A namespace cannot be used as a substitute for this language feature.

Requirements:
- Framework-owned PHP files MUST use `declare(strict_types=1);` unless an explicit compatibility exception is documented.
- Public methods MUST declare useful parameter and return types.
- Use DTOs/value objects for important boundaries rather than unstructured arrays.
- Validate external input at the boundary; type declarations alone do not validate HTTP payloads.
- Use static analysis and tests to catch type inconsistencies.
- Avoid weakening signatures merely to accommodate malformed input.

## 3. Namespaces and imports

- Follow PSR-4 and PSR-12.
- Use stable namespace roots such as `ZenithFramework\\...` for framework code and `App\\...` for application code.
- Prefer imports at the top of the file over inline fully qualified class names.
- Use fully qualified names only when they improve clarity, resolve a naming collision or are required by the context.
- A quality tool SHOULD identify missing, unused, duplicate and incorrectly ordered imports.
- Import ordering MUST be deterministic and documented.

## 4. Closures and callables

Do not ban closures globally. Closures are appropriate for route groups, migrations, callbacks, collection operations and concise configuration. Prefer named classes when logic is complex, reused, stateful, needs constructor dependencies or deserves isolated testing.

## 5. Object-oriented design

- Prefer composition and small explicit contracts over deep inheritance.
- Use interfaces for boundaries that require substitution or stable package contracts, not automatically for every class.
- Use final classes when extension is not part of the public API.
- Keep entities/value objects distinct from transport DTOs and persistence records where semantics differ.
- Do not hide I/O or network calls in constructors.
- Avoid mutable static state for request-scoped identity, database connections or runtime context.

## 6. Coding conventions

- PSR-12 formatting.
- Typed properties and return types.
- Explicit visibility.
- Predictable exception types.
- No suppressed errors as a substitute for handling.
- No production TODO placeholders or pseudocode.
- No secret values in source or fixtures.
- Avoid over-generalized helper classes.

## 7. Quality tooling

Recommended quality pipeline:
1. `composer validate`;
2. formatter/style check;
3. static analysis at an agreed level;
4. import and namespace diagnostics;
5. unit and integration tests;
6. security tests;
7. API compatibility checks;
8. package installation test.

The framework may expose these through `php zen lint` and `php zen test`; exact command syntax must be consistent with the CLI specification.

## 8. Static analysis and generics

Use PHPDoc generics where PHP's native type system cannot express collection, builder or relation type parameters. PHPDoc MUST match runtime behavior. Prefer explicit generic annotations for public collection/query APIs and test them with static-analysis fixtures.
