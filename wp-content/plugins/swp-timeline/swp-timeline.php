<?php

/**
 * Timeline 
 *
 * @package     swp-timeline
 * @author      SolverWP
 * @copyright   2021 solverwp
 * @license     GPL-2.0-or-later
 *
 * Plugin Name: Timeline 
 * Plugin URI:  https://solverwp.com/
 * Description: TimelinePress is the WordPress Plugin that creates Beautiful Timeline Page of your WordPress Posts or With Elementor repeater field. Using this plugin user can create unlimited beautiful Vertical and horizontal  timelines
 * Version:     1.0.0
 * Author:      SolverWP
 * Author URI:  https://themeforest.net/user/solverwp/
 * Text Domain: swp-timeline
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 */


if (!defined('ABSPATH')) {
	die;
}


/*
 * Define Plugin Dir Path
 * @since 1.0.0
 * */
define('SWP_TIMELINE_ROOT_PATH', plugin_dir_path(__FILE__));
define('SWP_TIMELINE_ROOT_URL', plugin_dir_url(__FILE__));
define('SWP_TIMELINE_INC', SWP_TIMELINE_ROOT_PATH . '/inc');
define('SWP_TIMELINE_CSS', SWP_TIMELINE_ROOT_URL . 'assets/css');
define('SWP_TIMELINE_JS', SWP_TIMELINE_ROOT_URL . 'assets/js');
define('SWP_TIMELINE_IMG', SWP_TIMELINE_ROOT_URL . 'assets/img');
define('SWP_TIMELINE_ELEMENTOR', SWP_TIMELINE_ROOT_PATH . '/elementor');

/** Plugin version **/
define('SWP_TIMELINE_VERSION', '1.0.0');



/**
 * Load plugin textdomain.
 */
add_action('plugins_loaded', 'Swp_Timeline_textdomain');
if (!function_exists('Swp_Timeline_textdomain')) {

	function Swp_Timeline_textdomain()
	{
		load_plugin_textdomain('swp-timeline', false, plugin_basename(dirname(__FILE__)) . '/language');
	}
}


/*
 * require file
*/

if (file_exists(SWP_TIMELINE_INC . '/class-swp-timeline-init.php')) {
	require_once SWP_TIMELINE_INC . '/class-swp-timeline-init.php';
}
