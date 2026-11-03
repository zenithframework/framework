# Zenith Framework — UI and Server Interaction Specification

**Status:** Proposed baseline; optional subsystem

## 1. Product position

Zenith provides one backend framework. Its server-rendered UI is an optional presentation package, not a separate backend architecture. Applications may use Zenith UI, a conventional template layer, or external frontend applications through the same backend services and APIs.

## 2. Application structure

Suggested application folders:

```text
app/UI/
├── Components/
├── Layouts/
└── Pages/
resources/views/
├── layouts/
├── pages/
└── components/
```

Base abstractions may include `Component`, `Page` and `Layout`, but inheritance should remain shallow and composition should be preferred. Keep view templates separate from PHP classes when this improves clarity.

## 3. Rendering

- Server rendering is the default initial response for the built-in UI.
- Templates escape untrusted output by default.
- Raw HTML is an explicit trusted boundary.
- Layout composition, components, slots/props and page metadata must have documented semantics.
- Rendering must not trigger unintended database writes.
- Cache keys must include relevant identity/locale/tenant context where output varies by them.

## 4. Interactive server actions

The UI MAY provide a Livewire-like server-action model so developers can write PHP components and avoid manually writing JavaScript for ordinary interactions. This still requires a small client runtime to serialize events/state, send requests and update the DOM; do not claim zero JavaScript implementation.

Conceptual syntax:

```html
<button action="increment()">Increment</button>
```

This is illustrative, not a finalized template syntax. The actual compiler/runtime contract must be specified before implementation.

Every action request is an untrusted HTTP request and MUST:
- identify the component/action through a registered allowlist;
- validate payload shape, size and types;
- enforce CSRF for cookie-authenticated browser sessions;
- re-check authentication and authorization on the server;
- prevent arbitrary PHP method invocation;
- prevent mass assignment of protected component/model state;
- use a component identity/signature or equivalent tamper protection;
- apply rate limits where appropriate;
- return a minimal, documented response;
- re-render only the necessary component/region;
- handle stale state and concurrent updates deterministically.

Never trust client-side hidden fields or serialized state as authoritative.

## 5. State and lifecycle

Define which values are public/serializable, private/server-only, computed or transient. Lifecycle hooks must have deterministic invocation order. Persisted client state must be validated and versioned. Avoid serializing service objects, closures, credentials, database connections or arbitrary model internals.

## 6. Runtime and assets

- Ship a small runtime only when the UI package is installed/used.
- Do not load it for pure API applications.
- Runtime must support CSRF, request cancellation/stale response handling, keyboard accessibility and secure HTML updates.
- Do not evaluate arbitrary returned JavaScript.
- Provide CSP-compatible behavior where possible.

## 7. Styling and component ecosystem

Support Tailwind CSS as an optional workflow. Provide compatible component conventions that can be styled in a shadcn-like manner, without making shadcn or Tailwind mandatory runtime dependencies. Keep styles and JS optional and avoid shipping unused assets.

## 8. External frontend compatibility

Backend routes, authentication contracts, validation errors, resources and OpenAPI schemas must be independently consumable by React, Vue, Next.js, Svelte, mobile and custom clients. Starter kits MAY live in separate packages/repositories and MUST not couple framework core to a particular frontend.

## 9. Accessibility and UX

Generated UI examples MUST use semantic controls, labels, focus management, keyboard access, useful validation announcements and reduced-motion support where applicable. Server action errors must be surfaced without losing user-entered values unnecessarily.

## 10. Acceptance criteria

Test autoescaping, raw HTML boundary, server action allowlisting, CSRF and authorization, payload tampering, stale updates, request ordering, keyboard operation, component rendering and absence of UI assets in an API-only installation.
