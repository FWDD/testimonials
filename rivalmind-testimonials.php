<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rivalmind.com
 * @since             1.0.0
 * @package           rivalmind_testimonials
 *
 * @wordpress-plugin
 * Plugin Name:       RivalMind Testimonials
 * Plugin URI:        http://rivalmind.com
 * Description:       Adds a custom post type for client Testimonials to your WordPress website.
 * Version:           1.0.0
 * Author:            RivalMind
 * Author URI:        https://rivalmind.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rivalmind-testimonials
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( !function_exists( 'add_action' ) ) {
	echo 'Sorry, this is a plugin that won\'t do anything if called directly.';
	exit;
}

// Version constants
define("RMT_VERSION", "1.0.0");

// Directory constant
define("RMT_DIR", dirname(__FILE__));

/**
 * Include the At a Glance class
 */
if ( ! class_exists( 'Gamajo_Dashboard_Glancer' ) ) {
	require_once( RMT_DIR . '/includes/class-gamajo-dashboard-glancer.php' );
}

/**
 * Include the plugin functions file
 */
require_once(RMT_DIR . '/includes/functions.php');

/**
 * This function runs during plugin activation.
 */
function activate_rivalmind_testimonials() {
	//Initialize any options
	RivalMind_init_options();
	//See if we need to update
	RivalMind_testimonials_update();
	//Register the custom post type.
	RivalMind_create_testimonials_post_type();
	//Flush the rewrite rules
	flush_rewrite_rules();
}

/**
 * This function runs during plugin deactivation.
 */
function deactivate_rivalmind_testimonials() {

}

register_activation_hook( __FILE__, 'activate_rivalmind_testimonials' );
register_deactivation_hook( __FILE__, 'deactivate_rivalmind_testimonials' );


add_action('init', 'RivalMind_create_testimonials_post_type', 0);

require_once(RMT_DIR . '/includes/admin.php');

require_once(RMT_DIR . '/includes/testimonial-widget.php');
