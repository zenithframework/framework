# Directory Structure

```text
framework/
├── bin/
├── bootstrap/
├── config/
├── docs/
├── src/Zenith/
│   ├── Application.php
│   ├── Container/
│   ├── Contracts/
│   ├── Database/
│   ├── Events/
│   ├── Exceptions/
│   ├── Http/
│   ├── Middleware/
│   ├── Routing/
│   └── Support/
├── composer.json
├── README.md
└── .gitignore
```

- `src/Zenith/`: canonical framework source.
- `bootstrap/`: minimal runtime bootstrap.
- `config/`: configuration files only when core requires defaults.
- `bin/`: minimal command entry point.
- `docs/`: architecture and contribution specifications.

There is deliberately no root `app/`, `packages/`, `vendor/`, `tests/`, `resources/`, or frontend directory in this core repository. Composer creates `vendor/` during installation; application skeletons and optional packages live elsewhere.
