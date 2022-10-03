<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Name:       Simply Static Pro
 * Plugin URI:        https://patrickposner.dev
 * Description:       Enhances Simply Static with GitHub Integration, Forms, Comments and more.
 * Version:           1.2.1
 * Author:            Patrick Posner
 * Author URI:        https://patrickposner.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simply-static-pro
 * Domain Path:       /languages
 */

define( 'SIMPLY_STATIC_PRO_PATH', plugin_dir_path( __FILE__ ) );
define( 'SIMPLY_STATIC_PRO_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

// localize.
$textdomain_dir = plugin_basename( dirname( __FILE__ ) ) . '/languages';
load_plugin_textdomain( 'simply-static-pro', false, $textdomain_dir );

// load Freemius.
require_once( SIMPLY_STATIC_PRO_PATH . 'inc/setup.php' );

// Bootmanager for Agy plugin.
if ( ! function_exists( 'ssp_run_plugin' ) ) {

	// autoload files.
	if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
		require __DIR__ . '/vendor/autoload.php';
	}

	add_action( 'plugins_loaded', 'ssp_run_plugin' );

	/**
	 * Run plugin
	 *
	 * @return void
	 */
	function ssp_run_plugin() {
		if ( function_exists( 'simply_static_run_plugin' ) ) {
			if ( ssp_fs()->is_plan_or_trial__premium_only( 'pro' ) ) {
				// We need the task class from Simply Static to integrate our job.
				require_once SIMPLY_STATIC_PATH . 'src/tasks/class-ss-task.php';
				require_once SIMPLY_STATIC_PATH . 'src/tasks/class-ss-fetch-urls-task.php';
				require_once SIMPLY_STATIC_PATH . 'src/class-ss-plugin.php';
				require_once SIMPLY_STATIC_PATH . 'src/class-ss-util.php';

				// Helper.
				require_once SIMPLY_STATIC_PRO_PATH . 'src/class-ssp-helper.php';
				simply_static_pro\Helper::get_instance();

				// Deployment.
				require_once SIMPLY_STATIC_PRO_PATH . 'src/deployment/class-ssp-deployment-settings.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/deployment/github/class-ssp-github-repository.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/deployment/github/class-ssp-github-file.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/deployment/github/class-ssp-github-commit-task.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/deployment/bunny-cdn/class-ssp-bunny-updater.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/deployment/bunny-cdn/class-ssp-bunny-deploy-task.php';

				simply_static_pro\Deployment_Settings::get_instance();

				// Builds.
				require_once SIMPLY_STATIC_PRO_PATH . 'src/build/class-ssp-build-settings.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/build/class-ssp-build-meta.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/build/class-ssp-build.php';

				simply_static_pro\Build_Settings::get_instance();
				simply_static_pro\Build_Meta::get_instance();
				simply_static_pro\Build::get_instance();

				// Single.
				require_once SIMPLY_STATIC_PRO_PATH . 'src/single/class-ssp-single-meta.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/single/class-ssp-single.php';

				simply_static_pro\Single_Meta::get_instance();
				simply_static_pro\Single::get_instance();

				// Forms.
				require_once SIMPLY_STATIC_PRO_PATH . 'src/form/class-ssp-form-settings.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/form/class-ssp-form-meta.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/form/class-ssp-form-webhook.php';

				simply_static_pro\Form_Settings::get_instance();
				simply_static_pro\Form_Meta::get_instance();
				simply_static_pro\Form_Webhook::get_instance();

				// Comments.
				require_once SIMPLY_STATIC_PRO_PATH . 'src/comment/class-ssp-comment.php';
				simply_static_pro\Comment::get_instance();

				// Cors.
				require_once SIMPLY_STATIC_PRO_PATH . 'src/cors/class-ssp-cors.php';

				simply_static_pro\CORS::get_instance();

				// Search.
				require_once SIMPLY_STATIC_PRO_PATH . 'src/search/class-ssp-search-settings.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/search/class-ssp-search-algolia.php';
				require_once SIMPLY_STATIC_PRO_PATH . 'src/search/class-ssp-search-fuse.php';

				simply_static_pro\Search_Settings::get_instance();
				simply_static_pro\Search_Algolia::get_instance();
				simply_static_pro\Search_Fuse::get_instance();

				// Multilingual.
				require_once SIMPLY_STATIC_PRO_PATH . 'src/multilingual/class-ssp-multilingual.php';
				simply_static_pro\Multilingual::get_instance();

				add_action( 'admin_enqueue_scripts', 'ssp_add_admin_styles' );
				add_filter( 'simply_static_info_links', 'ssp_add_info_links' );
			}
		} else {
			add_action( 'admin_notices', 'ssp_show_requirements' );
		}
	}
}

/**
 * Show conditional message for requirements.
 *
 * @return void
 */
function ssp_show_requirements() {
	$message = sprintf( __( 'The free version of Simply Static is required to use Simply Static Pro. You can get it %s.', 'simply-static-pro' ), '<a target="_blank" href="https://wordpress.org/plugins/simply-static/">here</a>' );
	echo wp_kses_post('<div class="notice notice-error"><p>' .  $message . '</p></div>' );
}

/**
 * Enqueue admin scripts.
 */
function ssp_add_admin_styles() {
	wp_enqueue_style( 'ssp-admin', SIMPLY_STATIC_PRO_URL . '/assets/ssp-admin.css', false, '1.1' );
}

/**
 * Add information links in admin header.
 *
 * @param  string $info_text given info text.
 * @return string
 */
function ssp_add_info_links( $info_text ) {
	ob_start();
	?>
	<a href="https://patrickposner.dev/docs/simply-static" target="_blank">Documentation</a>
	<a href="https://patrickposner.dev/support/" target="_blank">Support</a>
    <a href="https://simplycdn.io" target="_blank">Simply CDN</a>
	<?php
	$info_text = ob_get_clean();
	return $info_text;
}
