# Architecture

## Purpose

This repository is the minimal backend runtime of Zenith Framework. It owns only the primitives required to construct and handle a backend application.

## Dependency rules

- Core PHP source is namespaced under `Zenith\\` and loaded through Composer PSR-4.
- Low-level contracts must not depend on optional integrations.
- Core internals depend on interfaces at integration boundaries.
- Application-specific code belongs to consuming applications.
- A class has one canonical implementation and location.

## Out of scope

ORM, migration tooling, database vendor drivers, AI SDKs, UI/view rendering, frontend assets, testing framework, WebSocket server, queue backends, and provider-specific SDKs are separate packages/repositories.

## Extension policy

Optional packages may depend on this core and implement its contracts. The core must not require those packages to boot. Add a subsystem only when it is essential to the backend lifecycle and has a stable API.
