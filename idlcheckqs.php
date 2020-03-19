<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tahir.codes/
 * @since             1.0.0
 * @package           Idlcheckqs
 *
 * @wordpress-plugin
 * Plugin Name:       Checkout Custom Question
 * Plugin URI:        https://tahir.codes/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Tahir Iqbal
 * Author URI:        https://tahir.codes/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       idlcheckqs
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'IDLCHECKQS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-idlcheckqs-activator.php
 */
function activate_idlcheckqs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-idlcheckqs-activator.php';
	Idlcheckqs_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-idlcheckqs-deactivator.php
 */
function deactivate_idlcheckqs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-idlcheckqs-deactivator.php';
	Idlcheckqs_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_idlcheckqs' );
register_deactivation_hook( __FILE__, 'deactivate_idlcheckqs' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-idlcheckqs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_idlcheckqs() {

	$plugin = new Idlcheckqs();
	$plugin->run();

}
run_idlcheckqs();
