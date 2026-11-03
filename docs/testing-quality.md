# Zenith Framework — Testing and Quality Specification

**Status:** Proposed baseline

## 1. Quality gates

Every change must preserve a runnable framework and pass applicable automated checks. CI should run:
1. Composer manifest validation and clean dependency install;
2. formatting/style check;
3. static analysis;
4. import/namespace hygiene;
5. unit tests;
6. integration tests;
7. security regression tests;
8. package consumer/install tests;
9. API/contract compatibility checks;
10. representative performance smoke tests where relevant.

## 2. Test layers

- **Unit:** deterministic logic without external services.
- **Integration:** database drivers, cache stores, queues, filesystem and transport adapters.
- **Feature:** full application request/command lifecycle.
- **Contract:** package interfaces and adapter conformance.
- **Security:** exploit-focused negative tests.
- **Performance:** repeatable benchmarks for known workloads.
- **Compatibility:** supported PHP/database/dependency combinations.

## 3. Database test strategy

Run relational database suites against every officially supported driver/version. SQLite may be useful for fast tests but cannot prove MySQL/PostgreSQL compatibility. Use isolated databases/schema or transactions with explicit cleanup. Include concurrency tests for migrations, temporal writes, row locking and queue/outbox behavior where applicable.

## 4. Test isolation

No test should depend on global static user/guard/request state. Tests must restore container bindings, environment and filesystem state. Parallel tests must use unique identifiers and isolated resources.

## 5. Security test baseline

Include SQL injection across all dynamic query clauses, template XSS, CSRF boundaries, authorization bypass, session fixation, rate limiting, SSRF, upload traversal, credential redaction, unsafe deserialization and production exception leakage.

## 6. Package verification

Each independent Composer package must:
- install in a clean temporary consumer project;
- resolve declared dependencies without relying on root autoload mappings;
- run tests independently;
- verify public APIs and minimum PHP constraint;
- detect accidental dependency cycles.

## 7. Performance discipline

Benchmark realistic workloads with recorded hardware, PHP/opcache configuration, database version/schema, request payload and iteration methodology. Include warm/cold distinctions and variance. Avoid performance assertions based on a single run. Do not claim comparative framework superiority without equivalent conditions and published methodology.

## 8. Definition of done

A change is done only when implementation, tests, docs, config defaults and generated code agree; failure modes are described; compatibility impact is recorded; security implications are reviewed; and no placeholder implementation remains in production paths.
