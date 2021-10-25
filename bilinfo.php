<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://podi.dk
 * @since             1.0.0
 * @package           bilinfo
 *
 * @wordpress-plugin
 * Plugin Name:       Bilinfo
 * Plugin URI:        http://podi.dk/bilinfo
 * Description:       Plugin til at hente data ind i Wordpress fra Bilinfo.
 * Version:           1.0.0
 * Author:            Podi
 * Author URI:        https://podi.dk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bilinfo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('BILINFO_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bilinfo-activator.php
 */
function activate_bilinfo()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-bilinfo-activator.php';
	bilinfo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bilinfo-deactivator.php
 */
function deactivate_bilinfo()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-bilinfo-deactivator.php';
	bilinfo_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_bilinfo');
register_deactivation_hook(__FILE__, 'deactivate_bilinfo');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-bilinfo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bilinfo()
{

	$plugin = new bilinfo();
	$plugin->run();
}
run_bilinfo();
