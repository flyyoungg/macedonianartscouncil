<?php

namespace simply_static_pro;

use Simply_Static;

/**
 * Class to handle settings for fuse.
 */
class Helper {
	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Returns instance of Search_Settings.
	 *
	 * @return object
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor for Search_Settings.
	 */
	public function __construct() {
		add_action( 'ss_finished_fetching_pages', array( $this, 'add_configs' ), 99 );
		add_action( 'wp_head', array( $this, 'add_config_meta_tag' ) );
		add_action( 'init', array( $this, 'clean_head' ) );
		add_action( 'admin_footer', array( $this, 'dev_mode_warning' ) );
		add_action( 'wp_footer', array( $this, 'insert_post_id' ) );
	}

	/**
	 * Maybe show dev mode waring.
	 *
	 * @return void
	 */
	public function dev_mode_warning() {
		$screen = get_current_screen();

		if ( 'simply-static' === $screen->parent_base ) {
			?>
			<?php if ( defined( 'SSP_DEV_MODE' ) && true === SSP_DEV_MODE ) : ?>
			<script>
			jQuery(document).ready(function( $ ) {
				$('.actions').append('<span style="float:left;width:100%;padding: 5px 0;color:red;"><?php echo esc_html_e( "Warning, you are currently running in development mode. Make sure to remove define( 'SSP_DEV_MODE', true ); from your wp-config.php before you run a new static export.", 'simply-static-pro' ); ?></span>');
			});
			</script>
			<?php endif; ?>
			<?php
		}
	}

	/**
	 * Add config URL path as meta tag.
	 *
	 * @return void
	 */
	public function add_config_meta_tag() {
		$options    = get_option( 'simply-static' );
		$static_url = '';
		$origin_url = untrailingslashit( get_bloginfo( 'url' ) );

		if ( ! empty( $options['static-search-url'] ) ) {
			$static_url = untrailingslashit( $options['static-search-url'] );
		} elseif ( ! empty( $options['static-url'] ) ) {
			$static_url = untrailingslashit( $options['static-url'] );
		}

		$upload_dir = wp_upload_dir();
		$config_url = $upload_dir['baseurl'] . '/simply-static/configs/';

		if ( ! empty( $options['relative_path'] ) ) {
			$config_url = str_replace( $origin_url, $origin_url . $options['relative_path'], $config_url );
		}

		?>
		<meta name="ssp-url" content="<?php echo esc_url( $static_url ); ?>">
		<meta name="ssp-origin-url" content="<?php echo base64_encode( esc_url( $origin_url ) ); ?>">

		<?php if ( defined( 'SSP_DEV_MODE' ) && true === SSP_DEV_MODE ) : ?>
		<meta name="ssp-config-url" content="<?php echo esc_url( $config_url ); ?>">
		<?php else : ?>
			<meta name="ssp-config-url" content="<?php echo esc_url( str_replace( $origin_url, $static_url, $config_url ) ); ?>">
		<?php endif; ?>

		<?php if ( isset( $options['use-comments'] ) && 'no' !== $options['use-comments'] ) : ?>
			<meta name="ssp-comment-redirect-url" content="<?php echo esc_url( $options['comment-redirect'] ); ?>">
		<?php endif; ?>
		<?php
	}

	/**
	 * Add post id to each page.
	 *
	 * @return void
	 */
	public function insert_post_id() {
		?>
		<span class="ssp-id" style="display:none"><?php echo esc_html( get_the_id() ); ?></span>
		<?php
	}

	/**
	 * Add configs to static export.
	 *
	 * @return void
	 */
	public function add_configs() {
		$options    = Simply_Static\Options::instance();
		$origin_url = untrailingslashit( get_bloginfo( 'url' ) );

		// Get directories.
		$temp_dir   = $options->get_archive_dir();
		$upload_dir = wp_upload_dir();
		$config_dir = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'simply-static' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR;

		// Setup config URL.
		$config_url = str_replace( $origin_url, '', $upload_dir['baseurl'] . '/simply-static/configs/' );

		if ( 'local' === $options->get( 'delivery_method' ) ) {
			$copy = $this->copy_directory( $config_dir, $options->get( 'local_dir' ) . $config_url );
		} else {
			$copy = $this->copy_directory( $config_dir, $temp_dir . $config_url );
		}
	}

	/**
	 * Copy an entire directory.
	 *
	 * @param string $source the soruce path.
	 * @param string $target the target path.
	 * @return void
	 */
	public function copy_directory( $source, $target ) {
		if ( is_dir( $source ) ) {
			wp_mkdir_p( $target );
			$d = dir( $source );
			while ( FALSE !== ( $entry = $d->read() ) ) {
				if ( $entry == '.' || $entry == '..' ) {
					continue;
				}
				$Entry = $source . '/' . $entry;
				if ( is_dir( $Entry ) ) {
					$this->copy_directory( $Entry, $target . '/' . $entry );
					continue;
				}
				copy( $Entry, $target . '/' . $entry );
			}
			$d->close();
		} else {
			copy( $source, $target );
		}
	}

	/**
	 * Cleans up the head area of WordPress.
	 *
	 * @return void
	 */
	public function clean_head() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	}
}
