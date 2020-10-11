<?php

namespace Difficult13\EasyAjaxPagination;

use Difficult13\EasyAjaxPagination\Includes\EasyAjaxPagination;
use Difficult13\EasyAjaxPagination\Includes\EasyAjaxPaginationDeactivator;
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Ajax Pagination
 * Plugin URI:        https://github.com/Difficult13/easy-ajax-pagination
 * Description:       A lightweight and useful plugin that provides an easy way to add infinite scrolling or ajax pagination to your site.
 * Version:           1.0.0
 * Author:            Ivan Barinov
 * Author URI:        https://github.com/Difficult13
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-ajax-pagination
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

esc_html__('A lightweight and useful plugin that provides an easy way to add infinite scrolling or ajax pagination to your site.', 'easy-ajax-pagination');

/**
 * Currently plugin version.
 */
define( 'EAP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin deactivation.
 */
function deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-ajax-pagination-deactivator.php';
    EasyAjaxPaginationDeactivator::deactivate();
}

register_deactivation_hook(  __FILE__, __NAMESPACE__ . '\\deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-easy-ajax-pagination.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run() {
	$plugin = new EasyAjaxPagination();
	$plugin->run();
}
run();
