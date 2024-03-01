<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Better Plugin Skeleton</h1>

<p align="center">Skeleton for starting Sylius plugins.</p>

## Init

```
git clone git@github.com:hubertinio/SyliusExamplePlugin.git SyliusUnicornPlugin
```

## What to rename?

Let's assume that the new plugin is Vendor\SyliusUnicornPlugin

- update name in the composer.json into `vendor\vendor-sylius-unicorn-plugin`
- find and replace `Hubertinio\\\\SyliusExamplePlugin` into `Vendor\\\\SyliusUnicornPlugin`
- find and replace `Hubertinio\SyliusExamplePlugin` into `Vendor\SyliusUnicornPlugin`
- find and replace with case-sensitive `HubertinioSyliusExamplePlugin` into `VendorSyliusUnicornPlugin`
- find and replace with case-sensitive `hubertiniosyliusexampleplugin` into `vendorsyliusunicornplugin`
- find and replace `hubertinio_sylius_example` into `vendor_sylius_unicorn`
- refactor class and file name `src/HubertinioSyliusExamplePlugin.php` into `VendorSyliusUnicornPlugin.php` 
- refactor class and file name `src/DependencyInjection/HubertinioSyliusExampleExtension.php` into `src/DependencyInjection/VendorSyliusUnicornExtension.php`


## Composer install

Set private repository.

```
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:vendor/SyliusUnicornPlugin.git"
        }
    ],
```

Install.

```
composer require vendor/sylius-unicorn-plugin:1.12.x-dev
```


## Register plugin

Insert into `tests/Application/config/bundles.php` array that line:

```
Hubertinio\SyliusUnicornPlugin\HubertinioSyliusUnicornPlugin::class => ['all' => true],
```

## How to add routing?

Insert into `tests/Application/config/routes.yaml` this content:

```
vendor_sylius_unicorn_plugin:
    resource: "@VendorSyliusUnicornPlugin/config/routing.yml"
```

## Documentation

For a comprehensive guide on Sylius Plugins development please go to Sylius documentation,
there you will find the <a href="https://docs.sylius.com/en/latest/plugin-development-guide/index.html">Plugin Development Guide</a>, that is full of examples.

## Quickstart Installation

### Docker

1. Execute `make build-containers` and `make start-containers`

2. Initialize plugin `make install`

3. See your browser `open localhost`

## Usage

### Running plugin tests

  - PHPUnit

    ```bash
    vendor/bin/phpunit
    ```

  - PHPSpec

    ```bash
    vendor/bin/phpspec run
    ```

  - Behat (non-JS scenarios)

    ```bash
    vendor/bin/behat --strict --tags="~@javascript"
    ```

  - Behat (JS scenarios)
 
    1. [Install Symfony CLI command](https://symfony.com/download).
 
    2. Start Headless Chrome:
    
      ```bash
      google-chrome-stable --enable-automation --disable-background-networking --no-default-browser-check --no-first-run --disable-popup-blocking --disable-default-apps --allow-insecure-localhost --disable-translate --disable-extensions --no-sandbox --enable-features=Metal --headless --remote-debugging-port=9222 --window-size=2880,1800 --proxy-server='direct://' --proxy-bypass-list='*' http://127.0.0.1
      ```
    
    3. Install SSL certificates (only once needed) and run test application's webserver on `127.0.0.1:8080`:
    
      ```bash
      symfony server:ca:install
      APP_ENV=test symfony server:start --port=8080 --dir=tests/Application/public --daemon
      ```
    
    4. Run Behat:
    
      ```bash
      vendor/bin/behat --strict --tags="@javascript"
      ```
    
  - Static Analysis
  
    - Psalm
    
      ```bash
      vendor/bin/psalm
      ```
      
    - PHPStan
    
      ```bash
      vendor/bin/phpstan analyse -c phpstan.neon -l max src/  
      ```

  - Coding Standard
  
    ```bash
    vendor/bin/ecs check
    ```
