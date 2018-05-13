[![Author](https://img.shields.io/badge/author-Daniel%20M.%20Hendricks-lightgrey.svg?colorB=9900cc )](https://www.danhendricks.com)
[![Latest Release](https://img.shields.io/github/release/cloudverve/wp-aws-sdk.svg)](https://github.com/cloudverve/wp-aws-sdk/releases)
[![GitHub License](https://img.shields.io/github/license/cloudverve/wp-aws-sdk.svg)](https://raw.githubusercontent.com/cloudverve/wp-aws-sdk/master/LICENSE)
[![Twitter](https://img.shields.io/twitter/url/https/github.com/cloudverve/wp-aws-sdk.svg?style=social)](https://twitter.com/danielhendricks)

# Amazon Web Services (SDK) WordPress Plugin

A fork of the Amazon Web Services plugin by Delicious Brains. Loads the Amazon Web Services (AWS) [PHP SDK v3](https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/welcome.html) libraries and manages access keys.

**Status:** In Development

## Description

This plugin allows the user to define AWS access keys and allows other plugins to hook into it and use the AWS SDK that's included.

### Intent

The intent of this plugin is to provide a common install of the Amazon Web Services (AWS) SDK for plugins to share.

- To avoid "DLL hell" and reduce redundancy
- To allow a single place to update the SDK, rather than having to update it for each plugin/theme.

### Requirements

* WordPress 4.7 or higher
* PHP version 5.5 or greater
* PHP cURL library 7.16.2 or greater
* cURL compiled with OpenSSL and zlib
* `curl_multi_exec` enabled

### Compatibility

This plugin has not yet been tested for compatibility with other plugins. Use at your own risk and test first.

## Installation

When this project is stable, a distribution ZIP file will be created. Until then, you can download the source ZIP
and install it in WordPress > Plugins > Add New > Upload Plugin.

### Automatic Updates

When a release is made, automatic updates will be supported via [GitHub Updater](https://github.com/afragen/github-updater). This will be improved as time allows.

## Bundling with Plugins & Themes

You can choose to require this plugin as a dependency in any manner you like. An easy way is to use [TGM Plugin Activation](http://tgmpluginactivation.com/) (TGMPA). Example [configuration](http://tgmpluginactivation.com/configuration/):

```php
$plugins = array(
   array(
      'name'               => 'Amazon Web Services (SDK)', // The plugin name.
      'slug'               => 'amazon-web-services', // The plugin slug (typically the folder name).
      'source'             => 'https://github.com/cloudverve/wp-aws-sdk/archive/master.zip', // The plugin source.
      'required'           => true, // If false, the plugin is only 'recommended' instead of required.
      'version'            => '3.5', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
      'force_activation'   => true // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch or dependent plugin is disabled.
   )
);
```

## Screenshots

### 1. Settings Page
![Settings screen](https://raw.githubusercontent.com/cloudverve/wp-aws-sdk/master/assets/screenshot-1.png)

## Changelog

### master

* New: Added [Delicious Brains](https://github.com/deliciousbrains/wp-amazon-web-services) credit to plugin meta links
* New: Added proxy support, if defined by `WP_PROXY_HOST` and `WP_PROXY_PORT` constants ([5b1f4fb](https://github.com/ad34/wp-amazon-web-services/commit/5b1f4fbe92144688d3921b9bcb6825141ebc8e5b))
* New: Added support for [GitHub Updater](https://github.com/afragen/github-updater)
* New: Added translate and zip npm scripts
* Change: PHP 5.5 or higher as [required](https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/getting-started_requirements.html) by v3 SDK
* Change: Renamed plugin/slug, bumped version to match SDK
* Change: Renamed `wp-config.php` constants to `AWS_ACCESS_KEY_ID` and `AWS_SECRET_ACCESS_KEY`
* Improvement: Load AWS PHP SDK 3.5
