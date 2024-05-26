# Olmec Low Stock Notification Plugin

The Olmec Low Stock Notification Plugin is a WordPress plugin that notifies store managers when the stock levels of products in a specific category fall below a defined threshold. This helps in ensuring that store managers can take timely action to replenish the stock and avoid out-of-stock situations.

## Features

- Adds a settings tab in WooCommerce for configuring low stock notifications.
- Allows enabling/disabling email notifications for low stock alerts.
- Displays a metabox on the WordPress dashboard with stock information.
- Automatically processes notifications for low stock levels and logs actions.

## Installation

1. Download the plugin and unzip it.
2. Upload the `olmec-low-stock` directory to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Configure the plugin settings under WooCommerce > Settings > Low stock by category.

## Settings

The plugin provides several settings to customize its behavior:

- **Show metabox on dashboard**: Display a metabox on the WordPress dashboard showing stock levels by category.
- **Send email notification to store manager**: Enable or disable email notifications when stock levels are low.

## Usage

### Adding a Settings Tab

The plugin adds a custom settings tab in WooCommerce for managing low stock notifications.

```php
public function addSettingsTab($settingsTab)
{
    $settingsTab['olmec_lowstock_options'] = __('Low stock by category', OLMEC_LOW_STOCK_TEXT_DOMAIN);
    return $settingsTab;
}
```
## Tests
### Running tests
Unit tests are provided for the plugin using Mockery and PestPHP. To run the tests, use the following commands:

```sh
composer install
composer run tests
```

## Code patterns
Always try to encapsulate and separate concerns. If a piece of code displays markup, move it to a template file and render it using the available tools. Keep casing consistent with PSR directives. Hooks are how we load our app inside WordPress, acting as an API we interact with. Therefore, we leverage hooks in a loader class, which is responsible for booting our app. Side effects (such as sending an email) should be queued and processed in a non-blocking way. Not following this pattern can lead to severe consequences during periods of heavy load. Indexation is better than nesting loops. Always find a way to pull data and store it in an indexed manner. For instance, we do not need to collect all categories and then loop through all products. This should be done only once, when the plugin is activated. After that, we leverage hooks to manipulate a simple array that we can query by index.

## For developers and WordPress plugin reviewers
Developers who wish to contribute are encouraged to fork this repo and submit a pull request. **The sections below provide clarity on this plugin's development flow.**

### 1. Stable plugin generation
- **Automated Stable Version Generation**: This repository features a GitHub workflow that auto-generates a stable branch based on the latest main branch. Whenever code is merged into the main branch, the stable version is updated accordingly.

- **Manual Stable Version Generation**: Alternatively, you can clone this repo, run `composer install`, and then `composer run generate:stable`. This will produce a stable version derived from your current working branch. It will be saved as a zip file in the directory above.

### 2. WordPress code reviewers
Please note that this repo serves as the development version of what we intend to offer to WordPress users. The end-users receive a stable, compiled version without any development-related files or folders. **Given this, it's advisable to review both this development repository, and the stable version.**

## License

This WordPress plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the [GNU General Public License](http://www.gnu.org/licenses/gpl-2.0.html) for more details.