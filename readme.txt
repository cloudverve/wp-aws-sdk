=== Amazon Web Services ===
Contributors: hendridm, bradt, deliciousbrains
Tags: amazon, amazon web services, sdk
Requires at least: 4.6
Tested up to: 4.9.8
Stable tag: 3.67.18
License: GPLv3

Loads the Amazon Web Services (AWS) PHP SDK v3 libraries and manages access keys.

== Description ==

This plugin allows the user to define AWS access keys and allows other plugins to hook into it and use the AWS SDK that's included.

It is a fork of the Amazon Web Services plugin by Delicious Brains.

= Requirements =

* PHP version 5.5 or greater
* PHP cURL library 7.16.2 or greater
* cURL compiled with OpenSSL and zlib
* curl_multi_exec enabled

== Installation ==

1. Use WordPress' built-in installer
2. A new AWS menu will appear in the side menu

== Screenshots ==

1. Settings screen

== Changelog ==

= 3.56.3 - 2018-05-18 =

* New: Added [Delicious Brains](https://github.com/deliciousbrains/wp-amazon-web-services) credit to plugin meta links
* New: Added proxy support, if defined by `WP_PROXY_HOST` and `WP_PROXY_PORT` constants ([5b1f4fb](https://github.com/ad34/wp-amazon-web-services/commit/5b1f4fbe92144688d3921b9bcb6825141ebc8e5b))
* New: Added support for [GitHub Updater](https://github.com/afragen/github-updater)
* New: Added translate and zip npm scripts
* New: Added support for retrieving `AWS_ACCESS_KEY_ID` and `AWS_SECRET_ACCESS_KEY` from environmental variables
* Change: PHP 5.5 or higher as [required](https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/getting-started_requirements.html) by v3 SDK
* Change: Renamed plugin/slug, bumped version to match SDK
* Change: Renamed `wp-config.php` constants to `AWS_ACCESS_KEY_ID` and `AWS_SECRET_ACCESS_KEY`
* Change: Moved AWS admin menu to be submenu of Settings
* Improvement: Load AWS PHP SDK 3.5

= 1.0.5 - 2018-02-20 =
* Change: Plugins page removed as this plugin is no longer required by [WP Offload S3](https://deliciousbrains.com/wp-offload-s3/?utm_campaign=WP%2BOffload%2BS3&utm_source=wordpress.org&utm_medium=free%2Bplugin%2Blisting&utm_content=AWS) or [WP Offload S3 Lite](http://wordpress.org/plugins/amazon-s3-and-cloudfront/)
* Bug fix: Update checking broken for version 1.5 or less of WP Offload S3
* Bug fix: `WP_Offload_S3_Autoloader` class included in plugin
* Bug fix: License in composer.json fails Packagist validation

= 1.0.4 - 2017-11-20 =
* Improvement: Compatibility with WordPress 4.9
* Improvement: Compatibility with WP Offload S3 1.5.1
* Bug fix: Reveal access keys form option shown when keys partially defined
* Bug fix: WP_Error being passed to AWS methods
* Bug fix: "More info" links can be broken across two lines

= 1.0.3 - 2017-06-19 =
* Improvement: Compatibility with WP Offload S3 1.5

= 1.0.2 - 2017-03-13 =
* New: AWS SDK updated to 2.8.31
* New: London and Montreal regions added

= 1.0.1 - 2016-12-13 =
* New: Mumbai and Seoul regions added

= 1.0 - 2016-09-29 =
* Improvement: Compatibility with WP Offload S3 Lite 1.1
* Improvement: Compatibility with WP Offload S3 1.2

= 0.3.7 - 2016-09-01 =
* Improvement: No longer delete plugin data on uninstall. Manual removal possible, as per this [doc](https://deliciousbrains.com/wp-offload-s3/doc/uninstall/?utm_campaign=changelogs&utm_source=wordpress.org&utm_medium=free%2Bplugin%2Blisting&utm_content=AWS).

= 0.3.6 - 2016-05-30 =
* Improvement: Now checks that the `curl_multi_exec` function is available.

= 0.3.5 - 2016-03-07 =
* Improvement: Support for `DBI_` prefixed constants to avoid conflicts with other plugins
* Improvement: Redesign of the Addons page
* Improvement: Compatibility with WP Offload S3 Lite 1.0
* Improvement: Compatibility with WP Offload S3 1.1

= 0.3.4 - 2015-11-02 =
* Improvement: Compatibility with WP Offload S3 Pro 1.0.3

= 0.3.3 - 2015-10-26 =
* Improvement: Updated Amazon SDK to version 2.8.18
* Improvement: Fix inconsistent notice widths on _Access Keys_ screen
* New: WP Offload S3 Pro addons (Enable Media Replace, Meta Slider, WPML) added to the _Addons_ screen

= 0.3.2 - 2015-08-26 =
* New: WP Offload S3 Pro upgrade and addons added to the _Addons_ screen

= 0.3.1 - 2015-07-29 =
* Bug fix: Style inconsistencies on the _Addons_ screen

= 0.3 - 2015-07-08 =
* New: Support for [IAM Roles on Amazon EC2](https://deliciousbrains.com/wp-offload-s3/doc/iam-roles/?utm_campaign=changelogs&utm_source=wordpress.org&utm_medium=free%2Bplugin%2Blisting&utm_content=AWS) using the `AWS_USE_EC2_IAM_ROLE` constant
* New: Redesigned _Access Keys_ and _Addons_ screens
* Improvement: _Settings_ menu item renamed to _Access Keys_
* Improvement: _Access Keys_ link added to plugin row on _Plugins_ screen
* Improvement: Activate addons directly from within _Addons_ screen
* Improvement: [Quick Start Guide](https://deliciousbrains.com/wp-offload-s3/doc/quick-start-guide/?utm_campaign=changelogs&utm_source=wordpress.org&utm_medium=free%2Bplugin%2Blisting&utm_content=AWS) documentation

= 0.2.2 - 2015-01-19 =
* Bug Fix: Reverting AWS client config of region and signature

= 0.2.1 - 2015-01-10 =
* New: AWS SDK updated to 2.7.13
* New: Translation ready
* Improvement: Code cleanup to WordPress coding standards
* Improvement: Settings notice UI aligned with WordPress style
* Bug: Error if migrating keys over from old Amazon S3 and CloudFront plugin settings

= 0.2 - 2014-12-04 =
* New: AWS SDK updated to 2.6.16
* New: Set the region for the AWS client by defining `AWS_REGION` in your wp-config.php
* New: Composer file for Packagist support
* Improvement: Base plugin class performance of installed version
* Improvement: Base plugin class accessor for various properties
* Improvement: Addon plugin modal now responsive
* Improvement: Better menu icon
* Improvement: Code formatting to WordPress standards

= 0.1 - 2013-09-20 =
* First release
