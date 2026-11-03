# Zenith Framework — Queues, Events, Scheduling and Broadcasting Specification

**Status:** Proposed baseline; optional capabilities

## 1. Goals

Support synchronous and asynchronous application work through stable contracts and replaceable transports. Keep business logic independent of Redis, database queue tables or a specific broadcasting vendor.

## 2. Queues and jobs

A job defines data and behavior; a queue transport owns delivery. Job payloads must be serializable, versionable and free of live service objects, closures, open connections or secrets.

Required behavior:
- retry limits and backoff;
- timeout and failure handling;
- idempotency guidance and optional deduplication;
- reservation/visibility timeout semantics;
- dead-letter/failure storage;
- explicit queue names/concurrency;
- graceful worker shutdown;
- job payload versioning and compatibility during deploys.

At-least-once delivery is the realistic default for most transports; handlers must tolerate duplicate execution. Do not promise exactly-once execution without a proven end-to-end protocol.

## 3. Transactions and outbox

When an event/job must be published only if a database transaction commits, use a transactional outbox or a documented after-commit mechanism with known failure windows. The outbox dispatcher must be idempotent, observable and safe to retry.

## 4. Events and listeners

Separate event definition, dispatch contract and listener execution. Support synchronous listeners where appropriate and queueable listeners where configured. Define exception propagation, ordering guarantees and whether dispatch occurs before/after transaction commit.

## 5. Scheduling

Scheduler definitions must have explicit timezone behavior, locking/overlap rules, missed-run policy and observability. A scheduler should dispatch work rather than perform long tasks inline by default.

## 6. Broadcasting/realtime

Expose transport-neutral broadcasting contracts. Provider adapters may target supported services (for example, Mercure, Pusher or WebSocket transports) as optional packages. Define auth/channel authorization, event payload schema, reconnect behavior and delivery expectations. Never assume a client subscription implies authorization.

## 7. Testing and operations

Provide fake/sync transports for tests. Test duplicate jobs, retry exhaustion, backoff, worker crash/recovery, graceful shutdown, outbox crash windows, event ordering, channel authorization and payload compatibility. Expose queue depth, oldest job age, failure rate, retry count and dispatch lag.
