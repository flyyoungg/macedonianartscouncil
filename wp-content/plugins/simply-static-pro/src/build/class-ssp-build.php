<?php

namespace simply_static_pro;

use Simply_Static;

/**
 * Class to handle settings for builds.
 */
class Build {
	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Returns instance of Build.
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
	 * Constructor for Build.
	 */
	public function __construct() {
		add_filter( 'ss_static_pages', array( $this, 'filter_static_pages' ), 10, 2 );
		add_filter( 'ss_remaining_pages', array( $this, 'filter_remaining_pages' ), 10, 2 );
		add_filter( 'ss_total_pages', array( $this, 'filter_total_pages' ) );
		add_filter( 'ss_total_pages_log', array( $this, 'filter_total_pages_log' ) );
		add_action( 'wp_ajax_apply_build', array( $this, 'apply_build' ) );
		add_action( 'wp_ajax_nopriv_apply_build', array( $this, 'apply_build' ) );
		add_filter( 'ss_local_dir', array( $this, 'filter_output_directory' ) );
		add_action( 'ss_after_cleanup', array( $this, 'clear_build' ) );
	}

	/**
	 * Generate build for static export.
	 *
	 * @return void
	 */
	public function apply_build() {
		// check nonce.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ssp-run-build' ) ) {
			$response = array( 'message' => 'Security check failed.' );
			print wp_json_encode( $response );
			exit;
		}

		$build_id = esc_html( $_POST['term_id'] );

		// Update option for using a build.
		update_option( 'simply-static-use-build', $build_id );

		// Clear records before run the export.
		Simply_Static\Page::query()->delete_all();

		// Add URLs.
		self::add_urls( $build_id );
		self::add_files( $build_id );

		do_action( 'ssp_before_run_build' );

		// Start static export.
		$ss = Simply_Static\Plugin::instance();
		$ss->run_static_export();

		// Exit now.
		$response = array( 'success' => true );
		print wp_json_encode( $response );
		exit;
	}



	/**
	 * Filter additional urls
	 *
	 * @param  int $build_id current build id.
	 * @return void
	 */
	public static function add_urls( $build_id ) {
		$urls = array_unique( Simply_Static\Util::string_to_array( get_term_meta( $build_id, 'additional-urls', true ) ) );

		// Get all posts attached to that term.
		$post_types = get_post_types( array( 'public' => true ) );

		$args = array(
			'post_type'   => $post_types,
			'numberposts' => -1,
			'fields'      => 'ids',
			'tax_query'   => array(
				array(
					'taxonomy' => 'ssp-build',
					'field'    => 'term_id',
					'terms'    => $build_id
				)
			)
		);

		$posts = get_posts( $args );

		foreach ( $posts as $post_id ) {
			$urls[] = get_permalink( $post_id );
		}

		foreach ( $urls as $url ) {
			if ( Simply_Static\Util::is_local_url( $url ) ) {
				Simply_Static\Util::debug_log( 'Adding additional URL to queue: ' . $url );
				$static_page = Simply_Static\Page::query()->find_or_initialize_by( 'url', $url );
				$static_page->set_status_message( __( "Additional URL", 'simply-static' ) );
				$static_page->build_id    = $build_id;
				$static_page->found_on_id = 0;
				$static_page->save();
			}
		}
	}


	/**
	 * Convert Additional Files/Directories to URLs and add them to the database.
	 *
	 * @param  int $build_id current build id.
	 * @return void
	 */
	public static function add_files( $build_id ) {
		$additional_files = array_unique( Simply_Static\Util::string_to_array( get_term_meta( $build_id, 'additional-files', true ) ) );

		// Convert additional files to URLs and add to queue
		foreach ( $additional_files as $item ) {
			if ( file_exists( $item ) ) {
				if ( is_file( $item ) ) {
					$url = self::convert_path_to_url( $item );

					Simply_Static\Util::debug_log( "File " . $item . ' exists; adding to queue as: ' . $url );

					$static_page = Simply_Static\Page::query()->find_or_create_by( 'url', $url );
					$static_page->set_status_message( __( "Additional File", 'simply-static' ) );
					$static_page->build_id    = $build_id;
					$static_page->found_on_id = 0;
					$static_page->save();
				} else {
					Simply_Static\Util::debug_log( "Adding files from directory: " . $item );
					$iterator = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $item, \RecursiveDirectoryIterator::SKIP_DOTS ) );

					foreach ( $iterator as $file_name => $file_object ) {
						$url = self::convert_path_to_url( $file_name );

						Simply_Static\Util::debug_log( "Adding file " . $file_name . ' to queue as: ' . $url );

						$static_page = Simply_Static\Page::query()->find_or_initialize_by( 'url', $url );
						$static_page->set_status_message( __( "Additional Dir", 'simply-static' ) );
						$static_page->build_id    = $build_id;
						$static_page->found_on_id = 0;
						$static_page->save();
					}
				}
			} else {
				Simply_Static\Util::debug_log( "File doesn't exist: " . $item );
			}
		}
	}

	/**
	 * Convert a directory path into a valid WordPress URL
	 *
	 * @param  string $path The path to a directory or a file.
	 * @return string       The WordPress URL for the given path.
	 */
	public static function convert_path_to_url( $path ) {
		$url = $path;
		if ( stripos( $path, WP_PLUGIN_DIR ) === 0 ) {
			$url = str_replace( WP_PLUGIN_DIR, WP_PLUGIN_URL, $path );
		} elseif ( stripos( $path, WP_CONTENT_DIR ) === 0 ) {
			$url = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $path );
		} elseif ( stripos( $path, get_home_path() ) === 0 ) {
			$url = str_replace( untrailingslashit( get_home_path() ), Util::origin_url(), $path );
		}
		return $url;
	}

	/**
	 * Clear selected build after export.
	 *
	 * @return void
	 */
	public function clear_build() {
		delete_option( 'simply-static-use-build' );
	}

	/**
	 * Filter the local output directory.
	 *
	 * @param  string $local_directory local dir as string.
	 * @return string
	 */
	public function filter_output_directory( $local_directory ) {
		$use_build = get_option( 'simply-static-use-build' );

		if ( empty( $use_build ) ) {
			return $local_directory;
		}

		$build_id  = intval( $use_build );
		$directory = get_term_meta( $build_id, 'export-directory', true );

		if ( ! empty( $directory ) ) {
			return $directory;
		}

		return $local_directory;
	}

	/**
	 * Filter static pages.
	 *
	 * @param  array $results results from database.
	 * @param  array $archive_start_time timestamp.
	 * @return array
	 */
	public function filter_static_pages( $results, $archive_start_time ) {
		$batch_size = apply_filters( 'simply_static_fetch_urls_batch_size', 10 );
		$use_build  = get_option( 'simply-static-use-build' );

		if ( empty( $use_build ) ) {
			return $results;
		}

		$build_id = intval( $use_build );

		return Simply_Static\Page::query()
		->where( 'last_checked_at < ? AND build_id = ?', $archive_start_time, $build_id )
		->limit( $batch_size )
		->find();
	}

	/**
	 * Filter remaining pages.
	 *
	 * @param  array $results results from database.
	 * @param  array $archive_start_time timestamp.
	 * @return array
	 */
	public function filter_remaining_pages( $results, $archive_start_time ) {
		$use_build = get_option( 'simply-static-use-build' );

		if ( empty( $use_build ) ) {
			return $results;
		}

		$build_id = intval( $use_build );

		return Simply_Static\Page::query()
		->where( 'last_checked_at < ? AND build_id = ?', $archive_start_time, $build_id )
		->count();
	}

	/**
	 * Filter total pages.
	 *
	 * @param  array $results results from database.
	 * @return array
	 */
	public function filter_total_pages( $results ) {
		$use_build = get_option( 'simply-static-use-build' );

		if ( empty( $use_build ) ) {
			return $results;
		}

		$build_id = intval( $use_build );

		return Simply_Static\Page::query()
		->where( 'build_id = ?', $build_id )
		->count();
	}

	/**
	 * Filter total pages for log.
	 *
	 * @param  array $results results from database.
	 * @return array
	 */
	public function filter_total_pages_log( $results ) {
		$per_page     = $_POST['per_page'];
		$current_page = $_POST['page'];
		$offset       = ( intval( $current_page ) - 1 ) * intval( $per_page );
		$use_build    = get_option( 'simply-static-use-build' );

		if ( empty( $use_build ) ) {
			return $results;
		}

		$build_id = intval( $use_build );

		return Simply_Static\Page::query()
			->where( 'build_id = ?', $build_id )
			->limit( $per_page )
			->offset( $offset )
			->order( 'http_status_code' )
			->find();
	}
}
