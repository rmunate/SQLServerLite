---
title: Release Notes
editLink: true
outline: deep
---

# Release Notes

## [3.0.0]

### Added

- Support for Laravel 13 on PHP 8.3 and later, while retaining Laravel 8 through 12.

### Fixed

- Restored PHP 7.4 compatibility and removed PHP 8.2+ deprecation warnings for row properties and chunk callbacks.
- `value()` works without `Collection::value()`, which is not available on Laravel 8.
- `chunk()` preserves collection keys consistently across Laravel versions and runs the optional callback once per chunk. `SQLServerResponse` supports empty collections and inherited collection factories.
- Missing or malformed Laravel connection settings raise a `SQLServerException` that names the connection and configuration file.

## [2.0.0]

### Added

- **Singleton**: Starting from version 2.0, it is guaranteed to work with the singleton architecture, ensuring a single instance of a persistent connection.

- **Exception Handling**: The way exceptions are thrown has been rewritten for improved readability and clarity.

- **Params**: The usage of params has been refined through a fluent method.

### Changed

- **Final Methods**: The final methods for returning values have been completely redefined.

### Removed

- **Wrappers**: Wrappers for pre-existing package methods have been removed, and no support will be provided for previous versions.