# WP Asteriski Calendar
Contributors: Maks Turtiainen, Roosa Virta
Tags: block
Tested up to: 6.1
Stable tag: 0.1.0
License: MIT
WP Asteriski Calendar is a WordPress plugin that displays upcoming events from a Google Calendar.

© Asteriski ry

## Requirements

- WordPress 5.8 or later
- PHP 7.4 or later
- credentials.json with valid credentials for Google Calendar.

## Installation

1. Download and extract the plugin files.
2. Upload the `wp-asteriski-calendar` folder to the `/wp-content/plugins/` directory or install the plugin from the WordPress plugin repository.
3. Run `composer install` on root of the plugin
4. Activate the plugin through the 'Plugins' screen in WordPress.
5. Use the `[asteriski-next-events]` shortcode in a post, page or widget to display the upcoming events.

HOX. `credentials.json` must be in credentials directory. Ask it from Maks.
## Usage

### Shortcode

Use the `[asteriski-next-events]` shortcode to display the upcoming events in a post or a page.

### Widget

You can add the widget to a widget area by going to Appearance > Widgets and dragging the Asteriski's upcoming events widget to the desired widget area.

### Settings

There are two settings for the widget:
* Title: The title of the widget
* Amount: The number of upcoming events to display

## Credits

This plugin was created by Maks Turtiainen for Asteriski ry.