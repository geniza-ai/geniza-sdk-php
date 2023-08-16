Geniza PHP SKD
=========================

[![Php_version](https://img.shields.io/packagist/php-v/geniza-ai/geniza-sdk-php?logo=php&logoColor=ffffff)](https://packagist.org/packages/datadistillr/drill-sdk-php)
[![Package version](https://img.shields.io/packagist/v/geniza-ai/geniza-sdk-php?include_prereleases&logo=packagist&logoColor=ffffff)](https://packagist.org/packages/datadistillr/drill-sdk-php)
[![License](https://img.shields.io/packagist/l/geniza-ai/geniza-sdk-php?logo=MIT&logoColor=ffffff)](LICENSE)


This SDK is the best and easiest way to connect to the Geniza.ai API.

Installation
------------

Install using composer:

```
composer require geniza-ai/geniza-sdk-php
```

Usage
-----

```php
use Geniza\Geniza;

new Geniza($key, $secretKey);
```
## Framework Integrations

### CodeIgniter 4

Add the key and secret key to a config class.
```php
<?php
namespace Config;
use CodeIgniter\Config\BaseConfig;

class Geniza extends BaseConfig {
	public string $key = '';
	public string $secretKey = '';
}
```
Note: These values should be set and overridden by your environment variables.

Add the following to your `\Config\Services.php` file:
```php
	public static function geniza(bool $getShared = true) {
		if($getShared) {
			return static::getSharedInstance('geniza');
		}

		return new \Geniza\Geniza(
			config('Geniza')->key,
			config('Geniza')->secretKey
		);
	}
```

You can then instantiate your Geniza handle by simply calling the service method:
```php
	$geniza = \Config\Services::geniza(true);
```

### Laravel

This is an example service provider which registers a shared Optimus instance for your entire application:

```php
<?php

namespace App\Providers;

use Geniza\Geniza;
use Illuminate\Support\ServiceProvider;

class GenizaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Geniza::class, function ($app) {
            $key = 'abcd1234';
            $secretKey = 'efgh5678';
            return new Geniza($key, $secretKey);
        });
    }
}
```

Once you have created the service provider, add it to the providers array in your `config/app.php` configuration file:

```
App\Providers\GenizaServiceProvider::class,
```

More information: https://laravel.com/docs/5.3/container#resolving
