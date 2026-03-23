# Stasis Vite Extension

Stasis Vite Extension is an adapter that integrates [Vite](https://vite.dev/) frontend build tool with [Stasis](https://github.com/stasis-php/stasis).

## Installation
Install the latest version with [Composer](https://getcomposer.org/):
```shell
composer require stasis/ext-vite
```

## Configuration

> [!NOTE]
> The configuration examples below show only the relevant configuration, omitting unrelated parts.
> For a full list of available configuration options refer to [Stasis](https://github.com/stasis-php/stasis) package documentation.

### Plain Usage (Without Twig)
Register extension in your `stasis.php` configuration file:

```php
<?php // file: stasis.php

declare(strict_types=1);

use Stasis\Config\ConfigInterface;
use Stasis\Extension\Vite\StasisViteExtension;
use Stasis\Router\Route\Route;
use Stasis\Router\Router;

return new class implements ConfigInterface
{
    private readonly StasisViteExtension $vite;
    
    public function __construct() 
    {
        $this->vite = StasisViteExtension::create(
            assetsSourcePath: __DIR__ . '/dist_assets/assets',
            manifestPath: __DIR__ . '/dist_assets/manifest.json',
            assetsRoutePath: '/assets',
        );
    }
    
    public function extensions(): iterable
    {
        return [$this->vite];
    }

    public function routes(): iterable
    {
        return [
            new Route('/', $this->renderHome(...), 'home'),
        ];
    }

    private function renderHome(Router $router, array $parameters): string
    {
        return <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                {$this->vite->assetMapper->import('src/main.js')}
            </head>
            <body>
                <img src="{$this->vite->assetMapper->path('src/logo.svg')}"></img>
            </body>
            </html>
            HTML;
    }
};
```

### Usage with Twig Extension
If you're using Twig, register the extension and enable Twig integration:

```php
<?php // file: stasis.php

declare(strict_types=1);

use Stasis\Config\ConfigInterface;
use Stasis\Extension\Vite\StasisViteExtension;
use Stasis\Router\Route\Route;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return new class implements ConfigInterface
{
    private readonly Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader();
        $loader->addPath(__DIR__ . '/templates');
        $this->twig = new Environment($loader);
    }

    public function extensions(): iterable
    {
        return [
            StasisViteExtension::create(
                assetsSourcePath: __DIR__ . '/dist_assets/assets',
                manifestPath: __DIR__ . '/dist_assets/manifest.json',
                assetsRoutePath: '/assets',
            )->withTwig($this->twig),
        ];
    }

    public function routes(): iterable
    {
        return [
            new Route('/', fn() => $this->twig->render('home.html.twig'), 'home'),
        ];
    }
};
```

In your Twig templates, the next functions are available:
 - `asset_import` - to import your entry point assets;
 - `asset_path` - to get the path to a specific asset.

```twig
{# file: home.html.twig #}
<!DOCTYPE html>
<html>
<head>
    {{ asset_import('src/main.js') }}
</head>
<body>
    <img src="{{ asset_path('src/logo.svg') }}">
</body>
</html>
```

> [!NOTE]
> Asset paths should be relative to your Vite project root, as they appear in the manifest file.

## Credits
[Volodymyr Stelmakh](https://github.com/vstelmakh)  
Licensed under the MIT License. See [LICENSE](LICENSE) for more information.  
