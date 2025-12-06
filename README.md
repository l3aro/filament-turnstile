# Filament Turnstile

[![Latest Version on Packagist](https://img.shields.io/packagist/v/l3aro/filament-turnstile.svg?style=flat-square)](https://packagist.org/packages/l3aro/filament-turnstile)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/l3aro/filament-turnstile/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/l3aro/filament-turnstile/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/l3aro/filament-turnstile/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/l3aro/filament-turnstile/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/l3aro/filament-turnstile.svg?style=flat-square)](https://packagist.org/packages/l3aro/filament-turnstile)

**Filament Turnstile** is a lightweight plugin that seamlessly integrates Cloudflare Turnstile with your Filament application.

## Installation

Install the package via Composer:

```bash
composer require l3aro/filament-turnstile
```

Publish the configuration file with:

```bash
php artisan vendor:publish --tag="filament-turnstile-config"
```

Optionally publish the views with:

```bash
php artisan vendor:publish --tag="filament-turnstile-views"
```

The published configuration file contains:

```php
return [
    'key' => env('TURNSTILE_SITE_KEY'),
    'secret' => env('TURNSTILE_SECRET_KEY'),
    'reset_event' => env('TURNSTILE_RESET_EVENT', 'reset-captcha'),
];
```

## Customizing the Reset Event

You can customize the name of the reset event **globally** by modifying the `TURNSTILE_RESET_EVENT` in your `.env` file:

```env
TURNSTILE_RESET_EVENT=your-custom-event-name
```

Or by directly modifying the `reset_event` value in the configuration file. This allows you to use a custom event name that better fits your application's event naming convention or to avoid conflicts with other JavaScript events.

## Turnstile Keys
To use **Cloudflare Turnstile**, obtain your `SiteKey` and `SecretKey` from the Cloudflare dashboard.

Refer to the [documentation](https://developers.cloudflare.com/turnstile/get-started/#get-a-sitekey-and-secret-key) for step-by-step instructions.

After you generate the keys, add them to your `.env` file in the following format:

```env
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA
```

For testing, you may use the [dummy site and secret keys](https://developers.cloudflare.com/turnstile/reference/testing/) provided by Cloudflare.

## Usage

Using the plugin is straightforward. In your form, add the following component:

```php
use l3aro\FilamentTurnstile\Enums\TurnstileSize;
use l3aro\FilamentTurnstile\Enums\TurnstileTheme;
use l3aro\FilamentTurnstile\Forms\Turnstile;


Turnstile::make('captcha')
    ->theme(TurnstileTheme::Auto)
    ->size(TurnstileSize::Normal)
    ->language('en-US')
    ->resetEvent('reset-captcha')
```

See the [supported languages](https://developers.cloudflare.com/turnstile/reference/supported-languages/) list for available locale codes.

The `Turnstile` field exposes additional options. Review the [Cloudflare configuration guide](https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/#configurations) for full details.

## Turnstile Events

The package provides events you can use to control the captcha in different scenarios.

### Reset event

The reset event (default: `reset-captcha`) resets the captcha challenge. It is helpful when you need to:

- **Clear the challenge after a successful submission** so the next visitor receives a fresh captcha.
- **Reset the challenge after validation errors** to avoid reusing a solved captcha when the form fails.

### Dispatching the reset event

There are two primary ways to dispatch `reset-captcha`:

1. **Via `onValidationError`:** Filament automatically calls `onValidationError` when validation fails. Dispatch the event there to refresh the captcha.

    ```php
    use l3aro\FilamentTurnstile\Facades\FilamentTurnstileFacade;

    protected function onValidationError(ValidationException $exception): void
    {
        $this->dispatch(FilamentTurnstileFacade::getResetEventName());

        // Perform additional actions as needed (e.g., display error messages)
    }
    ```

2. **Manually:** Dispatch the event whenever your logic requires a reset.

    ```php
    $this->dispatch(FilamentTurnstileFacade::getResetEventName());
    ```

### Resetting the login captcha

To automatically reset the captcha after a failed login attempt, use the `throwFailureValidationException` method in your login form's Livewire component:

```php
protected function authenticate(): void
{
    // Perform authentication logic
    // ...

    if (! Auth::attempt($this->data)) {
        $this->throwFailureValidationException(
            [
                'email' => 'Invalid email or password.',
            ]
        );
    }

    // Redirect to success page or perform other actions
}
```

Throwing a validation exception triggers `onValidationError`, which dispatches `reset-captcha` and refreshes the captcha for the next attempt.

## Real-life example

To implement the **Turnstile** captcha on the `Login` page in Filament, follow these steps:

Create a new `App/Filament/Pages/Auth/Login.php` class:

```php

namespace App\Filament\Pages\Auth;

use l3aro\FilamentTurnstile\Forms\Turnstile;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as AuthLogin;

class Login extends AuthLogin
{
    /**
     * @return array<int|string, string|Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        Turnstile::make('captcha'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    // if you want to reset the captcha in case of validation error
    protected function throwFailureValidationException(): never
    {
        $this->dispatch(FilamentTurnstileFacade::getResetEventName());

        parent::throwFailureValidationException();
    }
}
```

Then override the `login()` method in your `PanelProvider` (for example, `AdminPanelProvider`):

```php
namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use Filament\Panel;
use Filament\PanelProvider;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class); // override the login page class.
            ...
    }
}
```
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [l3aro](https://github.com/l3aro)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
