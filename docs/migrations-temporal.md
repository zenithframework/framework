# Zenith Framework — Migrations and Temporal Data Specification

**Status:** Proposed baseline

## 1. Migration goals

Migrations provide a versioned, repeatable and auditable way to evolve database schemas. Temporal data is an optional ORM capability that records historical model state. They are related operationally but are separate features.

## 2. Migration engine

The migration system MUST:
- track applied migrations with stable identifiers and checksums/metadata;
- prevent duplicate application and concurrent migration races;
- expose status, apply, rollback and fresh/reset commands with clear safety warnings;
- run transactionally when the database and operation support it;
- record duration, result and failure details;
- support environment/connection selection explicitly;
- fail closed when migration state is inconsistent.

Rollback is best-effort where DDL is non-transactional or destructive. A migration must document irreversible steps.

## 3. Production schema evolution

Prefer expand-and-contract:
1. add compatible columns/tables/indexes;
2. deploy code that can read/write old and new forms;
3. backfill in bounded batches;
4. switch reads/writes;
5. verify metrics and data;
6. remove obsolete schema in a later release.

Avoid long table locks and unbounded data rewrites during deployment. Support online/concurrent index operations where the driver allows them. Do not assume all DDL is transactional.

## 4. Temporal history model

Temporal history is optional and configured per model/table. It records versions over time for audit, reconstruction or comparison. A reasonable initial design uses:
- current table for active state;
- history table for prior versions;
- stable entity key;
- validity start/end timestamps or equivalent version metadata;
- operation metadata for create/update/delete as required.

An update creates a historical version according to the documented validity model. A delete must preserve the pre-delete state when temporal tracking is enabled. Inserts generally create the current version; whether an initial history row is written must be an explicit policy.

Avoid storing history for no-op updates. Define timestamp precision, timezone (UTC), transaction ordering, soft-delete interaction and concurrent-write semantics.

## 5. Public API direction

Proposed API concepts:
- `history()` to inspect versions;
- `asOf($timestamp)` to query the state at a point in time;
- `historyFor($id)` or equivalent to retrieve a specific entity's versions.

Exact names are not final until a working API prototype and query semantics are reviewed. The API must not ambiguously mix current rows and history rows.

A model may opt in through configuration/metadata, e.g. a temporal attribute or model setting. The retention and archive policy must be configurable per model/table and environment. Temporal history must be off by default unless explicitly enabled.

## 6. Schema evolution for history tables

Temporal tables create migration overhead because current and historical schemas must remain interpretable over time. Requirements:
- migrations that affect tracked models must define corresponding history-table behavior;
- column additions need defaults/backfill rules for existing history;
- renames must preserve old values and metadata or use a staged compatibility window;
- dropped columns must not erase required audit data unintentionally;
- type changes need a conversion policy for historical rows;
- migration tests must cover both current and history tables;
- schema version metadata SHOULD let readers interpret historic records created under earlier schemas.

Never assume a history table can simply be altered in lockstep without considering older records.

## 7. Row growth and storage management

Every tracked update may add a history row, so total history volume grows with write frequency and retention period. Provide:
- per-model/table retention policies;
- scheduled pruning in bounded batches;
- partitioning by time for supported databases;
- archive/export to lower-cost storage;
- compression/archival formats and restore verification;
- indexes tuned to entity key and time-range query patterns;
- metrics for history rows, table size, write amplification and cleanup progress.

Permanent retention is not a universal default. Retention must be configurable and aligned with compliance/legal obligations. Never delete history silently; provide dry-run counts and auditable cleanup logs. Archival must verify checksums and support restore testing.

## 8. Operational safeguards

- Keep history writes in the same transaction as the model update where supported.
- Define behavior when the database lacks transactions/temporal support.
- Make cleanup idempotent and resumable.
- Avoid a single unbounded delete that locks large tables.
- Define backup/restore coverage for both current and history tables.
- Expose retention backlog and storage growth through diagnostics.

## 9. Acceptance criteria

Test tracked update/delete behavior, no-op updates, concurrency ordering, point-in-time reads, timestamp boundaries, schema change compatibility, failed migration rollback, interrupted cleanup resume, partition/archive restore, and retention policy enforcement.
