<?php

/**
 * FWDD Testimonials
 *
 * @since             1.0.0
 * @package           fwdd_testimonials
 *
 * @wordpress-plugin
 * Plugin Name:       FWDD Testimonials
 * Plugin URI:        https://github.com/FWDD/testimonials
 * Description:       Adds a custom post type for client Testimonials to your WordPress website.
 * Version:           1.0.0
 * Author:            FWDD
 * Author URI:        https://freelance-web-designer-developer.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fwdd-testimonials
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( !function_exists( 'add_action' ) ) {
	echo 'Sorry, this is a plugin that won\'t do anything if called directly.';
	exit;
}

// Version constants
define("FWDDT_VERSION", "1.0.0");

// Directory constant
define("FWDDT_DIR", dirname(__FILE__));

/**
 * Include the At a Glance class
 */
if ( ! class_exists( 'Gamajo_Dashboard_Glancer' ) ) {
	require_once( FWDDT_DIR . '/includes/class-gamajo-dashboard-glancer.php' );
}

/**
 * Include the plugin functions file
 */
require_once(FWDDT_DIR . '/includes/functions.php');

/**
 * This function runs during plugin activation.
 */
function activate_fwdd_testimonials() {
	//Initialize any options
	fwdd_init_options();
	//See if we need to update
	fwdd_testimonials_update();
	//Register the custom post type.
	fwdd_create_testimonials_post_type();
	//Flush the rewrite rules
	flush_rewrite_rules();
}

/**
 * This function runs during plugin deactivation.
 */
function deactivate_fwdd_testimonials() {

}

register_activation_hook( __FILE__, 'activate_fwdd_testimonials' );
register_deactivation_hook( __FILE__, 'deactivate_fwdd_testimonials' );


add_action('init', 'fwdd_create_testimonials_post_type', 0);

require_once(FWDDT_DIR . '/includes/admin.php');

require_once(FWDDT_DIR . '/includes/testimonial-widget.php');
