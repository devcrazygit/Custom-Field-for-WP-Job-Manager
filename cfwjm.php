<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://devcrazy.com
 * @since             1.0.0
 * @package           Cfwjm
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Field for WP Job Manager
 * Plugin URI:        https://devcrazy.com/cfwjm
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Devcrazy
 * Author URI:        https://devcrazy.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cfwjm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
session_start();			
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CFWJM_VERSION', '1.0.0' );

define('CFWJM_LIB_PATH', __DIR__ . '\\lib\\');
define('CFWJM_INCLUDE_PATH', __DIR__ . "\\includes\\");
define('CFWJM_ADMIN_PATH', __DIR__ . "\\admin\\");


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cfwjm-activator.php
 */
function activate_cfwjm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cfwjm-activator.php';
	Cfwjm_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cfwjm-deactivator.php
 */
function deactivate_cfwjm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cfwjm-deactivator.php';
	Cfwjm_Deactivator::deactivate();
}

/**
 * The code that runs during plugin updated.
 * This action is documented in includes/class-cfwjm-activator.php
 */
function update_cfwjm(){
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cfwjm-activator.php';
	Cfwjm_Activator::update();
}


register_activation_hook( __FILE__, 'activate_cfwjm' );
register_deactivation_hook( __FILE__, 'deactivate_cfwjm' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cfwjm.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cfwjm() {

	$plugin = new Cfwjm();
	$plugin->run();

}
run_cfwjm();
