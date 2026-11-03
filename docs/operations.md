# Zenith Framework — Operations and Observability Specification

**Status:** Proposed baseline

## 1. Runtime environments

Support explicit local/development, test, staging and production configuration. Environment selection must be explicit and safe. Production must not enable debug output by accident. Configuration may be cached for deployment, but cache generation and invalidation must be deterministic.

## 2. Logging

- Use structured log records with severity, timestamp, event name and correlation ID.
- Redact credentials, tokens, cookies, AI keys, sensitive headers and protected payload fields.
- Provide replaceable logging adapters; Monolog integration is a reasonable adapter option.
- Define log rotation/retention outside the framework process where possible.
- Avoid logging entire request bodies by default.

## 3. Error reporting

Centralize exception mapping and report unexpected failures to configured observability backends. Production responses must be generic. Error reports need correlation IDs and safe context, not raw secrets.

## 4. Metrics and tracing

Expose optional instrumentation for request duration, route, status, database query count/duration, cache hit/miss, queue depth/lag, job retries, AI provider latency/tokens and migration duration. Metrics labels must avoid unbounded cardinality and sensitive user identifiers.

Distributed tracing should use standards-compatible context propagation where feasible. Keep instrumentation optional and low overhead when disabled.

## 5. Health checks

Provide liveness/readiness concepts. Liveness should indicate process health without depending on every external service. Readiness may check required dependencies with bounded timeouts. Do not expose detailed internal diagnostics publicly.

## 6. Cache

Define cache store contracts, TTL semantics, serialization policy, namespace/versioning and invalidation. Do not cache sensitive authenticated data without identity-aware keys and appropriate isolation. Avoid unsafe object deserialization from untrusted stores.

## 7. Worker and shutdown

Long-lived workers need memory/restart limits, job timeouts, graceful shutdown, signal handling and state reset between jobs. Connections and buffers must be flushed/closed. A worker must not retain the previous job's authenticated principal or request data.

## 8. Deployment and migrations

Use immutable builds where possible, locked Composer dependencies and explicit migration steps. Document maintenance/zero-downtime strategy. Use expand-and-contract migrations for schema compatibility. Rollback plans must account for application and schema compatibility.

## 9. Acceptance tests

Test production debug suppression, secret redaction, correlation ID propagation, readiness timeout, cache isolation, worker state reset, graceful shutdown and telemetry behavior when the backend is unavailable.
