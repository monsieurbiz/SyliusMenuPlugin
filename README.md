<p align="center">
    <a href="https://monsieurbiz.com" target="_blank">
        <img src="https://monsieurbiz.com/logo.png" width="250px" alt="Monsieur Biz logo" />
    </a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="https://monsieurbiz.com/agence-web-experte-sylius" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" width="200px" alt="Sylius logo" />
    </a>
    <br/>
    <img src="https://monsieurbiz.com/assets/images/sylius_badge_extension-artisan.png" width="100" alt="Monsieur Biz is a Sylius Extension Artisan partner">
</p>

<h1 align="center">Menu Plugin</h1>

[![Menu Plugin license](https://img.shields.io/github/license/monsieurbiz/SyliusMenuPlugin?public)](https://github.com/monsieurbiz/SyliusMenuPlugin/blob/master/LICENSE.txt)
[![Tests Status](https://img.shields.io/github/workflow/status/monsieurbiz/SyliusMenuPlugin/Tests?logo=github)](https://github.com/monsieurbiz/SyliusMenuPlugin/actions?query=workflow%3ATests)
[![Security Status](https://img.shields.io/github/workflow/status/monsieurbiz/SyliusMenuPlugin/Security?label=security&logo=github)](https://github.com/monsieurbiz/SyliusMenuPlugin/actions?query=workflow%3ASecurity)

This plugins allows you to manage your menus.

## Installation

```bash
composer require monsieurbiz/sylius-menu-plugin
```

Change your `config/bundles.php` file to add the line for the plugin:

```php
<?php

return [
    //..
    MonsieurBiz\SyliusMenuPlugin\MonsieurBizSyliusMenuPlugin::class => ['all' => true],
];
```

Then create the config file in `config/packages/monsieurbiz_sylius_menu_plugin.yaml`:

```yaml
imports:
    - { resource: "@MonsieurBizSyliusMenuPlugin/Resources/config/config.yaml" }
```
Finally import the routes in `config/routes/monsieurbiz_sylius_menu_plugin.yaml`:

```yaml
monsieurbiz_menu_admin:
    resource: "@MonsieurBizSyliusMenuPlugin/Resources/config/routes/admin.yaml"
    prefix: /%sylius_admin.path_name%
```

Then run it:

```php
bin/console doctrine:migrations:migrate
```

## Customize your menu

If you want to customize your menu, like adding an image, do so by overriding the MenuItem entity (more info about [overriding entities in the Sylius documentation](https://docs.sylius.com/en/1.9/customization/model.html)).

## Menu example

### Admin form index

![Admin form view](screenshots/menu_admin.jpg)

### Menu front view

The front view is exactly the same as the default one.

## Contributing

You can open an issue or a Pull Request if you want! 😘  
Thank you!
