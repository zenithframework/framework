# Zenith Framework — Security Specification

**Status:** Proposed baseline

## 1. Security principles

Security is a default architectural property, not an optional afterthought. Threats include malicious HTTP inputs, SQL injection, cross-site scripting, CSRF, broken authorization, credential theft, SSRF, unsafe uploads, resource exhaustion, dependency compromise and secret leakage.

## 2. Input and output

- Validate external input at every trust boundary using explicit schemas/DTOs.
- Enforce request/body/file size limits.
- Encode output for its destination context; server-rendered templates escape untrusted values by default.
- Raw/unescaped output requires an explicit trusted-code decision.
- Do not trust client-supplied IDs, role names, tenant IDs or ownership claims.
- Normalize and validate redirects and URL schemes.

## 3. Database

- Bind all SQL values.
- Allowlist identifiers, operators, sort directions and raw fragments.
- Use least-privilege database credentials.
- Avoid returning arbitrary model columns in public API responses.
- Redact credentials, personal data and secrets from query logs.
- Test every query-builder clause that accepts dynamic input.

## 4. CSRF, sessions and cookies

- Apply CSRF protection to state-changing browser requests authenticated by cookies/sessions.
- Use secure, HttpOnly and appropriate SameSite cookie attributes; require Secure under HTTPS.
- Rotate session identifiers on authentication/privilege change.
- Protect logout and sensitive browser actions appropriately.
- Do not require browser CSRF tokens indiscriminately for stateless bearer-token APIs; protect those APIs with token validation, scopes and rate limits.

## 5. Authentication and authorization

- Use password_hash/password_verify with supported secure algorithms and rehash policy.
- Never store plaintext passwords or log credentials.
- Separate authentication (identity) from authorization (permission).
- Enforce authorization on every sensitive action, including server UI actions and background jobs.
- Use policies/gates or equivalent explicit authorization services.
- Apply brute-force protections and secure account recovery flows.
- Use constant-time comparisons where secrets/tokens are compared.

## 6. HTTP hardening

- Configure explicit trusted hosts and trusted proxies.
- Define security headers and CSP strategy.
- Configure CORS explicitly; wildcard origins must not be combined with credentials.
- Rate-limit authentication, expensive endpoints and AI proxy endpoints.
- Reject invalid content types and oversized payloads.
- Avoid leaking framework/runtime versions unnecessarily.
- Set safe cache controls for authenticated/private responses.

## 7. SSRF and outbound network access

AI providers, webhooks, file imports and URL fetchers can become SSRF vectors. Outbound HTTP features must:
- validate scheme and destination;
- block loopback, private, link-local and metadata ranges unless explicitly allowed;
- revalidate resolved IPs and redirects;
- restrict ports and protocols;
- enforce connection/read timeouts and response size;
- prevent DNS rebinding where feasible;
- avoid forwarding internal credentials across redirects.

## 8. File uploads

- Enforce size and extension/MIME policy.
- Inspect actual content rather than trusting filename or client MIME.
- Generate server-side storage names.
- Store private files outside the public document root.
- Prevent path traversal and executable uploads.
- Scan files when risk/requirements justify it.
- Serve downloads with safe content disposition and authorization.

## 9. Secrets and cryptography

- Keep secrets in environment/secret management, not committed config or logs.
- Provide key rotation guidance and versioned encrypted payloads.
- Use authenticated encryption APIs and secure random generation.
- Avoid custom cryptographic algorithms.
- Ensure TLS certificate verification remains enabled by default.
- Do not expose AI/API keys to browser bundles.

## 10. Error handling and audit

Production responses MUST be generic and include a correlation ID. Detailed exceptions belong in access-controlled logs with redaction. Security-sensitive events (login failures, permission changes, key changes, retention deletes) SHOULD be auditable without recording raw secrets.

## 11. Security testing

Require tests for SQL injection, XSS escaping, CSRF rejection/acceptance boundaries, authorization bypass, session fixation, rate limiting, SSRF redirects/private addresses, upload traversal, secret redaction, CORS and production stack-trace leakage. Run dependency vulnerability checks and keep supported dependencies patched.

## 12. Secure defaults and escape hatches

A security bypass must be explicit, narrowly scoped, documented and testable. No global insecure mode should be enabled by default. Developers must understand when raw SQL, raw HTML, disabled CSRF or unrestricted outbound requests are used.
