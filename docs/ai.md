# Zenith Framework — AI SDK Specification

**Status:** Proposed baseline; optional packages

## 1. Purpose

Provide a provider-neutral PHP API for text generation, structured output, embeddings and streaming where supported. Do not make the core framework depend on one AI vendor.

## 2. Boundaries

Separate:
1. public SDK/request objects;
2. provider contracts and capability declarations;
3. provider adapters mapping normalized requests to provider-specific payloads;
4. HTTP transport, retry and timeout policy;
5. response normalization and streaming event parsing;
6. application-level orchestration/tools (optional layer).

Provider-specific fields must not leak into the common API unless represented as explicit provider options.

## 3. Provider adapters

Each provider is an independently testable adapter. Capabilities must be declared rather than assumed. Normalize model identifiers, usage metadata, finish reasons, structured output and provider errors. Unsupported capabilities should fail explicitly.

## 4. Transport and reliability

- Use TLS certificate verification.
- Apply connect/read/overall timeouts.
- Bound response sizes.
- Retry only eligible transient failures, with bounded exponential backoff and jitter.
- Respect provider rate limits and retry-after metadata.
- Avoid retrying non-idempotent tool/action calls without an idempotency strategy.
- Support cancellation/stream interruption where transport permits.
- Redact authorization headers and sensitive prompt metadata from logs.

## 5. Credentials and configuration

Keys are loaded from environment/secret stores and injected into provider adapters. Never expose keys to browser code or API responses. Support multiple named provider configurations without global mutable current-provider state.

## 6. Streaming

Define a normalized event stream (e.g. start, text delta, structured data, usage, completed, error). Provider parsers must handle fragmented UTF-8, partial frames, heartbeat/comments, malformed events and abrupt disconnects. Streaming and non-streaming results must have consistent normalized semantics.

## 7. Safety and data handling

Document what prompt/content data is sent to each provider, retention assumptions and configurable redaction. Prevent SSRF in custom endpoint configuration and tool URL fetching. Treat model output as untrusted input; validate structured outputs and authorize tool calls independently.

## 8. Testing

Use deterministic fake transports and provider fixtures. Integration tests against live providers are opt-in and must not run in ordinary CI using real secrets. Test malformed responses, timeouts, retry budgets, redaction, streaming fragmentation and capability mismatch.
