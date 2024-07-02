[![Banner of Sylius Menu plugin](docs/images/banner.jpg)](https://monsieurbiz.com/agence-web-experte-sylius)

<h1 align="center">Sylius Menu Plugin</h1>

[![Menu Plugin license](https://img.shields.io/github/license/monsieurbiz/SyliusMenuPlugin?public)](https://github.com/monsieurbiz/SyliusMenuPlugin/blob/master/LICENSE.txt)
[![Tests Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusMenuPlugin/tests.yaml?branch=master&logo=github)](https://github.com/monsieurbiz/SyliusMenuPlugin/actions?query=workflow%3ATests)
[![Recipe Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusMenuPlugin/recipe.yaml?branch=master&label=recipes&logo=github)](https://github.com/monsieurbiz/SyliusMenuPlugin/actions?query=workflow%3ASecurity)
[![Security Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusMenuPlugin/security.yaml?branch=master&label=security&logo=github)](https://github.com/monsieurbiz/SyliusMenuPlugin/actions?query=workflow%3ASecurity)

This plugins allows you to manage your menus.

## Compatibility

| Sylius Version | PHP Version |
|---|---|
| 1.11 | 8.0 - 8.1 |
| 1.12 | 8.1 - 8.2 |
| 1.13 | 8.1 - 8.2 |

## Installation

If you want to use our recipes, you can configure your composer.json by running:

```bash
composer config --no-plugins --json extra.symfony.endpoint '["https://api.github.com/repos/monsieurbiz/symfony-recipes/contents/index.json?ref=flex/master","flex://defaults"]'
```

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

twig:
    form_themes: ['@MonsieurBizSyliusMenuPlugin/Admin/Browser/Form/_theme.html.twig']
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

## Add URL Provider

The URLs selector allows you to select a URL from a list of URLs.
It provides URLs for :
- Taxons
- Products

You can add your own custom Provider by creating a class which implements the `MonsieurBiz\SyliusMenuPlugin\Provider\UrlProviderInterface` interface.

https://github.com/monsieurbiz/SyliusMenuPlugin/assets/11380627/98edde9e-8a25-46be-bc1c-7a2b06d15ead

## Menu example

### Admin form index

![Admin form view](screenshots/menu_admin.jpg)

### Menu front view

The front view is exactly the same as the default one.

## Customize front view

A menu can look very differently depending on where it should be displayed so most of the time you will need to create your own macro for the menu items.
A good place to start is the template of the main menu here: ```src/Resources/views/Layout/Header/_menu.html.twig``` where we define a macro for the menu items, and we use them directly in the template.

To get the first items of a menu you can call our custom twig function ```menu_first_level('main')``` where `main` is the code of the menu we want to retrieve.

## Contributing

You can open an issue or a Pull Request if you want! 😘  
Thank you!
