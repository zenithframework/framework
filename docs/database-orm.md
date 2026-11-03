# Zenith Framework — Database and ORM Specification

**Status:** Proposed baseline

## 1. Scope

The data layer provides SQL connections, query execution, schema migrations, ORM models, relations and supporting utilities. Document persistence is a separate capability and must not be forced into SQL abstractions.

## 2. Layer boundaries

Suggested responsibilities:
- **Connection layer:** PDO/driver construction, configuration, lifecycle and transaction primitives.
- **Query layer:** structured query representation, parameter binding, grammar/driver compilation and execution.
- **Schema layer:** schema operations through driver-aware schema builders.
- **ORM layer:** model identity, attributes, hydration, persistence, relations, scopes and lifecycle events.
- **Resource/serialization layer:** explicit external representation and field visibility.
- **Document adapter:** separate document-native repository/query APIs (e.g. MongoDB).

Avoid a single static global PDO connection. Connections should be container-managed, configurable by name and safe for the selected process model.

## 3. SQL security requirements

- Bind every user-supplied value through prepared statements.
- Never interpolate values directly into SQL.
- Table/column names, operators, sort directions, join identifiers and raw fragments cannot be bound as values; validate them against metadata/allowlists or build them with structured expression objects.
- Reject unknown sort directions/operators; do not silently replace unsafe input with a default.
- Raw SQL APIs must be clearly marked as trusted-code escape hatches and must accept bindings separately.
- Query builder APIs must distinguish identifiers, expressions and values.
- Add tests for injection attempts in filters, joins, group/order clauses, having and pagination.

## 4. Query API behavior

The API should support safe filtering, projection, joins, aggregates, ordering, grouping, scopes and pagination. Query execution should be explicit and predictable. A `get()` call returns a collection; `first()`/find methods return one optional model; `exists()` avoids unnecessary hydration; aggregates should be executed in SQL.

No unbounded implicit fetch should be used for public endpoints. `Model::all()` may be a legitimate explicitly requested full fetch, but applications must understand its resource cost. The framework must not silently return a truncated subset while claiming to return all rows. Provide explicit pagination/chunk/cursor/streaming APIs.

## 5. Model requirements

- Explicit table/primary-key configuration where conventions are insufficient.
- Safe mass assignment with explicit fillable/guarded rules.
- Attribute casts and serialization rules.
- Clear create/update/delete semantics.
- Model hydration must not invoke unintended writes.
- Lifecycle hooks/events must have deterministic order.
- Avoid global mutable query builders on the model class.
- Model instance state must not leak between requests in long-lived processes.

## 6. Relations

Support a defined initial set such as belongs-to, has-one, has-many and many-to-many. Relation queries must remain composable. Eager loading must prevent N+1 query patterns and support nested relations where defined. Pivot updates and cascading behavior must be explicit. Relation metadata should be testable without issuing unexpected queries.

## 7. Pagination and collections

Provide offset pagination and cursor/keyset pagination where stable ordering keys exist. Cursor pagination must reject missing/ambiguous order definitions. Collections must have documented lazy/eager behavior, key semantics and generic types. Large result sets should have chunk/stream options with clear connection and cursor lifecycle rules.

## 8. Multiple SQL drivers

Driver adapters must define connection, grammar, schema differences, transaction capability and exception normalization. Support matrix (initial candidates) should be explicit: SQLite for tests/local use, MySQL/MariaDB and PostgreSQL for production. Do not claim full compatibility until each driver has integration tests across supported versions.

## 9. MongoDB/document persistence

MongoDB is not just another SQL driver. It uses document-native filters, updates, indexes, aggregation and transaction semantics. Implement it as a separate persistence adapter/package with its own repository/query contracts. Shared concepts may be abstracted only where semantics are genuinely common.

## 10. Performance and observability

- Avoid duplicate metadata discovery and redundant hydration.
- Provide query timing, query count and slow-query logging with bindings redacted.
- Index recommendations belong in docs/diagnostics, not silent schema mutation.
- Connection lifecycle and transaction boundaries must be observable.
- Cache only with explicit invalidation/consistency semantics.

## 11. Acceptance tests

Test SQL injection on every dynamic query surface, hydration and fillable behavior, relation correctness, eager-loading query counts, pagination boundaries, transaction rollback, driver-specific schema operations, long-running worker state isolation and document adapter semantics separately.
