[![Author](https://img.shields.io/badge/author-Daniel%20M.%20Hendricks-lightgrey.svg?colorB=9900cc )](https://www.danhendricks.com)
[![Latest Release](https://img.shields.io/github/release/cloudverve/wp-aws-sdk.svg)](https://github.com/cloudverve/wp-aws-sdk/releases)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/danielhendricks)
[![GitHub License](https://img.shields.io/github/license/cloudverve/wp-aws-sdk.svg)](https://raw.githubusercontent.com/cloudverve/wp-aws-sdk/master/LICENSE)
[![Twitter](https://img.shields.io/twitter/url/https/github.com/cloudverve/wp-aws-sdk.svg?style=social)](https://twitter.com/danielhendricks)

# Amazon Web Services (SDK) WordPress Plugin

A fork of the Amazon Web Services plugin by Delicious Brains. Loads the Amazon Web Services (AWS) PHP SDK v3 libraries and manages access keys.

**Status:** In Development

## Description

This plugin allows the user to define AWS access keys and allows other plugins to hook into it and use the AWS SDK that's included.

### Requirements

* WordPress 4.7 or higher
* PHP version 5.5 or greater
* PHP cURL library 7.16.2 or greater
* cURL compiled with OpenSSL and zlib
* `curl_multi_exec` enabled

### Compatibility

This plugin has not yet been tested for compatibility with other plugins. Use at your own risk and test first.

## Installation

When this project is stable, a distribution ZIP file will be created. Until then, you must clone the repo. Change directory to your WordPress plugins directory and perform the following:

```bash
git clone https://github.com/cloudverve/wp-aws-sdk.git
cd wp-aws-sdk
composer install
```

## Screenshots

### 1. Settings Page
![Settings screen](https://raw.githubusercontent.com/cloudverve/wp-aws-sdk/assets/screenshot-1.png)

## Changelog

### master

* New: Added [Delicious Brains](https://github.com/deliciousbrains/wp-amazon-web-services) credit to plugin meta links
* Change: PHP 5.5 or higher [required](https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/getting-started_requirements.html) by v3 SDK
* Change: Renamed plugin/slug, bumped version to match SDK
* Change: Renamed `wp-config.php` constants to `AWS_ACCESS_KEY_ID` and `AWS_SECRET_ACCESS_KEY`
* Improvement: Load AWS PHP SDK 3.5
