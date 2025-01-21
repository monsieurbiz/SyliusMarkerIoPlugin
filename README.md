<p align="center">
    <a href="https://monsieurbiz.com" target="_blank">
        <img src="https://monsieurbiz.com/logo.png" width="250px" alt="Monsieur Biz logo" />
    </a>
    <a href="https://monsieurbiz.com/agence-web-experte-sylius" target="_blank">
        <img src="https://monsieurbiz.com/sylius_logo.png" width="200px" alt="Sylius logo" />
    </a>
    <br/>
    <img src="https://monsieurbiz.com/assets/images/sylius_badge_extension-artisan.png" width="100" alt="Monsieur Biz is a Sylius Extension Artisan partner">
</p>

<h1 align="center">Marker.io's plugin for Sylius</h1>

[![Plugin license](https://img.shields.io/github/license/monsieurbiz/SyliusMarkerioPlugin?public)](https://github.com/monsieurbiz/SyliusMarkerioPlugin/blob/master/LICENSE) [![Flex Recipe](https://github.com/monsieurbiz/SyliusMarkerioPlugin/actions/workflows/recipe.yaml/badge.svg)](https://github.com/monsieurbiz/SyliusMarkerioPlugin/actions/workflows/recipe.yaml) [![Security](https://github.com/monsieurbiz/SyliusMarkerioPlugin/actions/workflows/security.yaml/badge.svg)](https://github.com/monsieurbiz/SyliusMarkerioPlugin/actions/workflows/security.yaml) [![Tests](https://github.com/monsieurbiz/SyliusMarkerioPlugin/actions/workflows/tests.yaml/badge.svg)](https://github.com/monsieurbiz/SyliusMarkerioPlugin/actions/workflows/tests.yaml)

This plugin is a Sylius integration for [Marker.io](https://marker.io).

It gives the capability to integrate the extension if you have a Project ID.  
In the same time, if the script is loaded, we've added some metadata to the configuration sent to Marker.io.

## Compatibility

| Sylius Version | PHP Version     |
|----------------|-----------------|
| 1.12           | 8.1 - 8.2 - 8.3 |
| 1.13           | 8.1 - 8.2 - 8.3 |
| 1.14           | 8.1 - 8.2 - 8.3 |

## Installation

If you want to use our recipes, you can configure your composer.json by running:

```bash
composer config --no-plugins --json extra.symfony.endpoint '["https://api.github.com/repos/monsieurbiz/symfony-recipes/contents/index.json?ref=flex/master","flex://defaults"]'
```

```
composer require monsieurbiz/sylius-markerio-plugin
```

You may need to install our recipes first:

```
composer config --no-plugins --json extra.symfony.endpoint '["https://api.github.com/repos/monsieurbiz/symfony-recipes/contents/index.json?ref=flex/master","flex://defaults"]'
```

## Update the metadata

You can create your own event listener and then update the data sent to the plugin using the event itself.

See [EventListener/MarkerioCustomDataListener.php](https://github.com/monsieurbiz/SyliusMarkerioPlugin/blob/master/src/EventListener/MarkerioCustomDataListener.php) as an example.

## License

This plugin is under the MIT license.
Please see the [LICENSE](LICENSE) file for more information.
