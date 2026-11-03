# Contributing

## Requirements

- PHP 8.5 or later in the PHP 8.x series.
- Composer 2.
- All PHP files must declare `strict_types=1`.

The root Composer manifest requires `php: ^8.5`. This means PHP 8.5 and compatible later PHP 8.x releases; PHP 9 is not declared supported until it is verified.

## Coding standards

- Follow PSR-4 autoloading.
- Follow PSR-12 formatting.
- Use explicit types and imports.
- Keep the core backend-only and dependency-light.
- Add contracts before coupling the core to optional implementations.
- Do not add ORM, AI, UI, testing framework, frontend, or provider-specific integrations here.
- Document public API changes and maintain backwards compatibility where practical.

## Local checks

Install development dependencies and run the manifest validation and style check before submitting a change:

```bash
composer install
composer validate
composer lint:style
```

To automatically fix supported PSR-12 formatting issues:

```bash
composer fix:style
```

PHP_CodeSniffer is a development-only dependency. The PSR-12 ruleset is defined in the repository root `phpcs.xml`. Review auto-fixes before committing them.
