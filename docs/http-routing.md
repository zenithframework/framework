# Zenith Framework — HTTP and Routing Specification

**Status:** Proposed baseline

## 1. Goals

Provide deterministic HTTP request handling, a composable routing system, clear middleware execution and stable APIs for external clients. Backend behavior MUST NOT depend on the optional UI package.

## 2. Route files and prefixes

Maintain these route entrypoints:

| File | Default prefix | Intended use |
|---|---|---|
| `routes/Web.php` | `/` | Browser/web routes |
| `routes/Api.php` | `/api` | JSON/API routes |
| `routes/Auth.php` | `/auth` | Authentication endpoints and flows |
| `routes/Ai.php` | `/ai` | AI application endpoints, if enabled |

Route files are organizational units, not security boundaries. Each route must declare or inherit the correct middleware. Prefixes, names and middleware must be configurable without accidental double-prefixing.

## 3. Request/response model

- Normalize method, URI, headers, query parameters, cookies and body at the HTTP boundary.
- Distinguish query parameters, form data, JSON payload and uploaded files.
- Provide typed accessors and explicit validation.
- Response objects define status, headers, cookies and body.
- JSON serialization must use a defined error behavior and content type.
- Large payload handling must be bounded and configurable.
- Do not trust proxy headers unless trusted-proxy configuration permits them.

## 4. Routing

- Support HTTP methods, named routes, route parameters, route groups and middleware.
- Route matching must be deterministic and reject ambiguous definitions at registration/compile time where possible.
- Route caches must be rebuildable and invalidated safely.
- URL generation must account for configured base paths and trusted host/scheme information.
- Method override, if supported, must be explicit and limited to accepted sources.
- Route parameter constraints must be validated before controller invocation.

## 5. Middleware

Middleware has a documented request/response pipeline order. Framework and app middleware must be composable. Middleware registration should use class references/aliases rather than scattered inline class-name strings.

Security-sensitive middleware includes:
- trusted host/proxy handling;
- request size limits;
- authentication and authorization;
- CSRF for cookie-authenticated browser mutations;
- rate limits;
- content negotiation;
- security headers;
- request correlation.

Middleware must not silently bypass execution due to exceptions or malformed configuration.

## 6. Controller and handler invocation

- Controllers/handlers receive explicit request and route context.
- Dependency injection occurs through the container.
- Avoid global request state and static user identity.
- Return supported response types or values covered by a documented response factory.
- Do not automatically serialize arbitrary ORM models for public APIs without resource/DTO policy.

## 7. API contract

- JSON error shape and status mapping must be documented and versionable.
- API responses must not expose exception messages, SQL, file paths or secrets.
- Pagination metadata, validation errors, timestamps and null semantics must be consistent.
- OpenAPI generation SHOULD be supported from explicit route/DTO metadata.
- API authentication model must be distinguished from cookie/session web authentication.
- External clients must not need Zenith's built-in UI runtime.

## 8. Exception behavior

Known validation/authentication/not-found/rate-limit failures map to appropriate responses. Unknown failures return a generic production error and correlation ID. Detailed diagnostics are logged server-side with redaction. Development debug responses must be disabled by default in production.

## 9. Acceptance tests

Test route methods/parameters/names/groups, prefix composition, middleware order, route cache consistency, malformed bodies, request size limits, validation mapping, JSON error contract, trusted proxy handling, CSRF boundaries, API bearer flows and production exception redaction.
