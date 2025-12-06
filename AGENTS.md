# Agent Instructions for l3aro/filament-turnstile

## Commands
- **Test all**: `composer test` or `vendor/bin/pest`
- **Test single**: `vendor/bin/pest --filter="test name"` or `vendor/bin/pest tests/FileTest.php`
- **Lint/Static analysis**: `composer analyse` or `vendor/bin/phpstan analyse`
- **Format code**: `composer format` or `vendor/bin/pint`

## Code Style
- **PHP**: 8.2+ with strict typing and PSR-4 autoloading
- **Formatting**: Laravel Pint with "per" preset (4-space indentation, UTF-8, LF endings)
- **Static analysis**: PHPStan level 4
- **Testing**: Pest framework with descriptive test names
- **Naming**: PascalCase for classes, camelCase for methods/properties, SCREAMING_SNAKE_CASE for constants
- **Imports**: Group by type (classes, functions, constants) with blank lines between groups
- **Error handling**: Use exceptions for errors, validate inputs, return typed responses
- **Architecture**: Follow Laravel package conventions (facades, service providers, enums for constants)</content>
