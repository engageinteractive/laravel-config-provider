<h1 align="center">Laravel Config Provider</h1>

<p align="center">
<a href="https://travis-ci.org/engageinteractive/laravel-config-provider"><img src="https://travis-ci.org/engageinteractive/laravel-config-provider.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/engageinteractive/laravel-config-provider"><img src="https://poser.pugx.org/engageinteractive/laravel-config-provider/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/engageinteractive/laravel-config-provider"><img src="https://poser.pugx.org/engageinteractive/laravel-config-provider/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/engageinteractive/laravel-config-provider"><img src="https://poser.pugx.org/engageinteractive/laravel-config-provider/license.svg" alt="License"></a>
</p>

To avoid filename collisions between Laravel config files, use this package to allow your end users to change which file is accessed in your package.

## Installation

```sh
composer require engageinteractive/laravel-config-provider
```

Now, in your package, create a new `ConfigProvider`:

```php
namespace Example\Package;

use EngageInteractive\LaravelConfigProvider\ConfigProvider as BaseConfigProvider;

class ConfigProvider extends BaseConfigProvider
{
    /**
     * Key to use when retrieving config values. Override this if you require `Example\Package` to
     * a different file for its configuration.
     *
     * @var string
     */
    protected $configKey = 'example-package';
}
```

Then, rather than using the `Config` facade, or the `config()` function in Laravel, your package should use Laravel's service container to get access to a `ConfigProvider`:

```php
namespace Example\Package;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Prepare the App for your package.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../publishes/config/example-package.php' => config_path('example-package.php'),
        ], 'config');

        // Previous you could have done `config('example-package.enabled')`
        if (app(ConfigProvider::class)->get('enabled')) {
            // Do your thing!
        }
    }
}
```

Doing so will then allow end users of your package to change which file your package uses in by providing an alternative in their `AppServiceProvider`.

## Config File Customisation

_*[Use this section to explain to your end users how to customise which file is used for your package. Don't forget to rename `example-package.php` to yours and delete this paragraph as well!]*_

By default the package uses the `config/example-package.php` file to define all the configuration settings. However, the package uses [Laravel Config Provider](https://github.com/engageinteractive/laravel-config-provider) to allow you change to which file is used. To do so bind your own instance of `ConfigProvider` in your `AppServiceProvider`. This is useful in cases where `config/example-package.php` is already in use within your project for example.

First create your own provider:

```php
namespace App\Config;

use Example\Package\ConfigProvider;

class ExamplePackageConfigProvider extends ConfigProvider
{
    /**
     * Key to use when retrieving config values.
     *
     * @var string
     */
    protected $configKey = 'different-example-package';
}
```

Then, add the provider to your bindings on startup.

```php
class AppServiceProvider extends ServiceProvider
{
...

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        \Example\Package\ConfigProvider::class => \App\Config\FrontendConfigProvider::class,
    ];

...
}
```

The package uses `ConfigProvider` via the Laravel Service Container exclusively, so when we request it yours will be created instead.

## Laravel Compatibility

Works on Laravel 5.5, 5.6 and 5.7.

## License

Laravel Config Provider is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
