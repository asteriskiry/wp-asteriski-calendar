<?php
/**
 * Plugin Name: WP Asteriski Calendar
 * Description: Google kalenterista "Tulevat tapahtumat"
 * Plugin URI: https://asteriski.fi
 * Author: Maks Turtiainen, Asteriski ry
 * Version: 1.5
 * Author URI: https://github.com/asteriskiry
 * License: MIT
 * Text Domain: asteriski-next-events
 **/

defined('ABSPATH') || exit;

require_once dirname(__FILE__) . '/vendor/autoload.php';
include_once dirname(__FILE__). '/shortcode.php';
include_once dirname(__FILE__). '/widget.php';

add_action('plugins_loaded', 'asteriski_next_events_text_domain');

/**
 * Localize the plugin.
 */
function asteriski_next_events_text_domain(): void
{
    load_plugin_textdomain('asteriski-next-events', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

/**
 * Load CSS
 */
function calendar_enqueue_css(): void
{
    wp_register_style('asteriski-calendar-css', plugins_url('css/main.css', __FILE__));
    
    wp_enqueue_style('asteriski-calendar-css');
}

add_action('wp_enqueue_scripts', 'calendar_enqueue_css');