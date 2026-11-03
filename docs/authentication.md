# Zenith Framework — Authentication and Authorization Specification

**Status:** Proposed baseline

## 1. Responsibilities

Authentication establishes identity. Authorization decides what an authenticated or anonymous principal may do. Keep these concerns separate while providing an integrated developer experience.

## 2. Architecture

- **User/principal contract:** identity and credential-related access.
- **Guard:** determines how identity is resolved for a request/context.
- **Provider:** retrieves/validates the principal.
- **Session/token adapter:** persists authentication state.
- **Policy/gate:** evaluates permission for a resource/action.
- **Middleware:** enforces authentication/authorization at route boundaries.

Exact class names are subject to implementation review. Interfaces should exist at meaningful extension boundaries.

## 3. State isolation

Do not use process-wide static mutable user or guard properties. Resolve current principal from request/job context. This is required for long-running workers, concurrent execution and test isolation.

## 4. Sessions

- Use secure cookie attributes and session rotation after login/privilege elevation.
- Define idle and absolute expiration.
- Support invalidation/logout and revocation.
- Store server-side session data through a replaceable session store.
- Protect against fixation and session replay.
- Never serialize live service objects into sessions.

## 5. Passwords and recovery

Use PHP's password hashing APIs, verify with `password_verify`, and rehash when policy changes. Password reset tokens must be random, hashed at rest, single use and expiring. Never log passwords, reset tokens or full credential payloads.

## 6. Guards and API tokens

Provide clearly separated session/cookie and bearer-token flows. Bearer tokens need storage-safe hashing, expiration/revocation, scopes or abilities, audience/context restrictions and rate limits. Do not assume an API request is browser-session authenticated.

## 7. Authorization

- Policies/gates must be checked on every sensitive operation.
- Deny by default where no policy exists for protected resources.
- Ownership checks must be server-derived.
- UI visibility checks are not authorization; the server must enforce the same rule.
- Background jobs must carry the minimum necessary actor/context and reauthorize or verify authorization assumptions at execution time where appropriate.

## 8. Route and endpoint behavior

Use the `routes/Auth.php` grouping for authentication flows if enabled, with explicit route middleware. Public login/register/reset endpoints must have throttling, input validation and abuse controls. Error responses should avoid account enumeration where appropriate.

## 9. Acceptance tests

Test password hashing/rehash, session rotation/fixation, logout/revocation, expiry, guard isolation across sequential requests in one worker, bearer scopes, policy denial, ownership checks, brute-force throttling and UI server-action authorization.
