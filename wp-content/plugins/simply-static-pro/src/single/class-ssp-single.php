<?php

namespace simply_static_pro;

/**
 * Simply Static.
 */
use Simply_Static;

/**
 * HTTP Client for extracting elements from dom.
 */
use voku\Httpful\Client;

/**
 * Class to handle settings for single.
 */
class Single {
	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Returns instance of Single.
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
	 * Constructor for Single.
	 */
	public function __construct() {
		add_filter( 'ss_static_pages', array( $this, 'filter_static_pages' ), 10, 2 );
		add_filter( 'ss_remaining_pages', array( $this, 'filter_remaining_pages' ), 10, 2 );
		add_filter( 'ss_total_pages', array( $this, 'filter_total_pages' ) );
		add_filter( 'ss_total_pages_log', array( $this, 'filter_total_pages_log' ) );
		add_action( 'wp_ajax_apply_single', array( $this, 'apply_single' ) );
		add_action( 'wp_ajax_delete_single', array( $this, 'delete_single' ) );
		add_action( 'ss_after_cleanup', array( $this, 'clear_single' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ) );
		add_action( 'ssp_comment_added', array( $this, 'export_single' ) );
	}

	/**
	 * Enqueue scripts in WordPress.
	 *
	 * @return void
	 */
	public function add_admin_scripts() {
		wp_enqueue_script( 'ssp-single-admin', SIMPLY_STATIC_PRO_URL . '/assets/ssp-single-admin.js', array( 'jquery' ), '1.1.1', true );

		wp_localize_script(
			'ssp-single-admin',
			'ssp_single_ajax',
			array(
				'ajax_url'     => admin_url() . 'admin-ajax.php',
				'single_nonce' => wp_create_nonce( 'ssp-single' ),
				'redirect_url' => admin_url() . 'admin.php?page=simply-static',
			)
		);
	}

	/**
	 * Generate single for static export.
	 *
	 * @return void
	 */
	public function apply_single() {
		// check nonce.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ssp-single' ) ) {
			$response = array( 'message' => 'Security check failed.' );
			print wp_json_encode( $response );
			exit;
		}

		$single_id       = intval( $_POST['single_id'] );
		$additional_urls = apply_filters( 'ssp_single_export_additional_urls', array_merge( $this->get_related_urls( $single_id ), $this->get_related_attachements( $single_id ), Multilingual::get_related_translations( $single_id ) ) );

		// Update option for using a single post.
		update_option( 'simply-static-use-single', $single_id );

		// Clear records before run the export.
		Simply_Static\Page::query()->delete_all();

		// Add URls for static export.
		$this->add_url( $single_id );
		$this->add_additional_urls( $additional_urls, $single_id );

		do_action( 'ssp_before_run_single' );

		// Start static export.
		$ss = Simply_Static\Plugin::instance();
		$ss->run_static_export();

		// Exit now.
		$response = array( 'success' => true );
		print wp_json_encode( $response );
		exit;
	}

	/**
	 * Get related URls to include in single export.
	 *
	 * @param  int $single_id single post id.
	 * @return array
	 */
	public function get_related_urls( $single_id ) {
		$related_urls = array();

		// Get category URLs.
		$categories = get_the_terms( $single_id, 'category' );

		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$related_urls[] = get_term_link( $category );
			}
		}

		// Get tag URLs.
		$tags = get_the_terms( $single_id, 'post_tag' );

		if ( ! empty( $tags ) ) {
			foreach ( $tags as $tag ) {
				$related_urls[] = get_term_link( $tag );
			}
		}

		// Add blogpage.
		$blog_id        = get_option( 'page_for_posts' );
		$related_urls[] = get_permalink( $blog_id );

		// Add frontpage.
		$front_id       = get_option( 'page_on_front' );
		$related_urls[] = get_permalink( $front_id );

		// Get archive URL.
		$post_type      = get_post_type( $single_id );
		$related_urls[] = get_post_type_archive_link( $post_type );

		return $related_urls;
	}

	/**
	 * Get related URls to include in single export.
	 *
	 * @param  int $single_id single post id.
	 * @return array
	 */
	public function get_related_attachements( $single_id ) {
		$options       = get_option( 'simply-static' );
		$related_files = array();

		// Get all images from that post.

		// Basic Auth enabled?
		if ( isset( $options['http_basic_auth_digest'] ) && ! empty( $options['http_basic_auth_digest'] ) ) {
			$auth_params = explode( ':', base64_decode( $options['http_basic_auth_digest'] ) );
			$response    = \Httpful\Client::get_request( get_permalink( $single_id ) )
			->expectsHtml()
			->disableStrictSSL()
			->withBasicAuth( $auth_params[0], $auth_params[1] )
			->send();

		} else {
			$response = \Httpful\Client::get_request( get_permalink( $single_id ) )
			->expectsHtml()
			->disableStrictSSL()
			->send();
		}

		$dom = $response->getRawBody();

		foreach ( $dom->find( 'img' ) as $img ) {
			$related_files[] = $img->src;
			$related_files[] = $img->srcset;
		}
		return $related_files;
	}

	/**
	 * Add single URL.
	 *
	 * @param  int $single_id current single id.
	 * @return void
	 */
	public function add_url( $single_id ) {
		// Add URL.
		$url = get_permalink( $single_id );

		if ( Simply_Static\Util::is_local_url( $url ) ) {
			Simply_Static\Util::debug_log( 'Adding additional URL to queue: ' . $url );
			$static_page = Simply_Static\Page::query()->find_or_initialize_by( 'url', $url );
			$static_page->set_status_message( __( "Additional URL", 'simply-static' ) );
			$static_page->post_id     = $single_id;
			$static_page->found_on_id = 0;
			$static_page->save();
		}
	}

	/**
	 * Ensure the user-specified Additional URLs are in the DB.
	 *
	 * @param  array $additional_urls array of additional urls.
	 * @return void
	 */
	public function add_additional_urls( $additional_urls, $single_id ) {
		foreach ( $additional_urls as $url ) {
			if ( Simply_Static\Util::is_local_url( $url ) ) {
				Simply_Static\Util::debug_log( 'Adding additional URL to queue: ' . $url );
				$static_page = Simply_Static\Page::query()->find_or_initialize_by( 'url', $url );
				$static_page->set_status_message( __( "Additional URL", 'simply-static' ) );
				$static_page->found_on_id = $single_id;
				$static_page->post_id     = $single_id;
				$static_page->save();
			}
		}
	}

	/**
	 * Update related URLs for a single post.
	 *
	 * @param  int $single_id post id.
	 * @return void
	 */
	public function update_related_urls( $single_id ) {
		// set post to draft to exclude it from related URLs.
		wp_update_post( array( 'ID' => $single_id, 'post_status' => 'draft' ) );

		$related_urls = array_merge( $this->get_related_urls( $single_id ), Multilingual::get_related_translations( $single_id ) );

		// Update option for using a single post.
		update_option( 'simply-static-use-single', $single_id );

		// Clear records before run the export.
		Simply_Static\Page::query()->delete_all();

		// Add URls for static export.
		$this->add_additional_urls( $related_urls, $single_id );

		// Start static export.
		$ss = Simply_Static\Plugin::instance();
		$ss->run_static_export();
	}

	/**
	 * Clear selected single after export.
	 *
	 * @return void
	 */
	public function clear_single() {
		delete_option( 'simply-static-use-single' );
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
		$use_single = get_option( 'simply-static-use-single' );

		if ( empty( $use_single ) ) {
			return $results;
		}

		$post_id = intval( $use_single );

		return Simply_Static\Page::query()
		->where( 'last_checked_at < ? AND post_id = ?', $archive_start_time, $post_id )
		->limit( $batch_size )
		->find();
	}

	/**
	 * Filter remaining pages.
	 *
	 * @param  array $results results from database.
	 * @param  array $archive_start_time timestamp.
	 * @return int|array
	 */
	public function filter_remaining_pages( $results, $archive_start_time ) {
		$use_single = get_option( 'simply-static-use-single' );

		if ( empty( $use_single ) ) {
			return $results;
		}

		$post_id = intval( $use_single );

		return Simply_Static\Page::query()
		->where( 'last_checked_at < ? AND post_id = ?', $archive_start_time, $post_id )
		->count();
	}


	/**
	 * Filter total pages.
	 *
	 * @param array $results results from the database.
	 *
	 * @return int|mixed|null
	 * @throws \Exception
	 */
	public function filter_total_pages( $results ) {
		$use_single = get_option( 'simply-static-use-single' );

		if ( empty( $use_single ) ) {
			return $results;
		}

		$post_id = intval( $use_single );

		return Simply_Static\Page::query()
		->where( 'post_id = ?', $post_id )
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
		$use_single   = get_option( 'simply-static-use-single' );

		if ( empty( $use_single ) ) {
			return $results;
		}

		$post_id = intval( $use_single );

		return Simply_Static\Page::query()
			->where( 'post_id = ?', $post_id )
			->limit( $per_page )
			->offset( $offset )
			->order( 'http_status_code' )
			->find();
	}

	/**
	 * Export single if comment was added.
	 *
	 * @param  int $post_id given post id.
	 * @return void
	 */
	public function export_single( $post_id ) {
		// Update option for using single.
		update_option( 'simply-static-use-single', $post_id );

		// Clear records before run the export.
		Simply_Static\Page::query()->delete_all();

		// Add URLs.
		$this->add_url( $post_id );

		do_action( 'ssp_before_run_single' );

		// Start static export.
		$ss = Simply_Static\Plugin::instance();
		$ss->run_static_export();
	}


	/**
	 * Delete file.
	 *
	 * @return void
	 */
	public function delete_single() {
		// check nonce.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ssp-single' ) ) {
			$response = array( 'message' => 'Security check failed.' );
			print wp_json_encode( $response );
			exit;
		}

		$single_id = intval( $_POST['single_id'] );
		$options   = get_option( 'simply-static' );
		$url       = get_permalink( $single_id );

		// Delete search results.
		if ( ! empty( $options['use-search'] ) && 'no' !== $options['use-search'] && 'algolia' === $options['search-type'] ) {
			if ( isset( $options['algolia-app-id'] ) && ! empty( $options['algolia-app-id'] ) && isset( $options['algolia-admin-api-key'] ) && ! empty( $options['algolia-admin-api-key'] ) ) {
				$client = \Algolia\AlgoliaSearch\SearchClient::create( $options['algolia-app-id'], $options['algolia-admin-api-key'] );
				$index  = $client->initIndex( $options['algolia-index'] );

				// Now we can delete the search result.
				$index->deleteObject( $single_id );
			}
		}

		// Check delivery method.
		$delivery_method = $options['delivery_method'];
		$origin_url      = untrailingslashit( get_bloginfo( 'url' ) );

		switch ( $delivery_method ) {
			case 'local':
				$relative_path = $options['local_dir'];

				// Build the path to delete.
				$path = untrailingslashit( $relative_path ) . str_replace( $origin_url, '', $url );

				// Delete direcory of file.
				if ( is_dir( $path ) ) {
					global $wp_filesystem;

					// Initialize the WP filesystem.
					if ( empty( $wp_filesystem ) ) {
						require_once( ABSPATH . '/wp-admin/includes/file.php' );
						WP_Filesystem();
					}
					$wp_filesystem->rmdir( $path, true );
				} else {
					// Delete directory.
					if ( file_exists( $path ) ) {
						wp_delete_file( $path, true );
					}
				}
				break;
			case 'github':
				// Build the path to delete.
				$path   = str_replace( $origin_url, '', $url );
				$path   = substr( $path, 1 );
				$github = Github_File::get_instance();

				// If path contains a . it's a file otherwise it's a directory.
				$is_file = strpos( $path, '.' );

				if ( $is_file !== false ) {
					$github->delete_file( $path, __( 'Deleted file on path', 'simply-static-pro' ) );
				} else {
					// We need to enhance the path with index.html.
					$index_path = $path . 'index.html';
					$github->delete_file( $index_path, __( 'Deleted file on path', 'simply-static-pro' ) );

					// We may also need to remove the RSS Feed.
					$feed_path = $path . 'feed/index.xml';
					$github->delete_file( $feed_path, __( 'Deleted file on path', 'simply-static-pro' ) );
				}
				break;
			case 'cdn':
				$sub_directory = $options['cdn-directory'];

				// Subdirectory or not?
				if ( ! empty( $sub_directory ) ) {
					$path = untrailingslashit( $sub_directory ) . str_replace( $origin_url, '', $url );
				} else {
					$path = str_replace( $origin_url, '', $url );
				}

				// Delete the file path.
				$bunny   = Bunny_Updater::get_instance();
				$deleted = $bunny->delete_file( $path );

				if ( ! $deleted ) {
					$response = array(
						'success' => false,
						'error'   => esc_html__( 'The file could not be deleted. Please check your access key in Simply Static -> Settings -> Deployment', 'simply-static-pro' ),
					);

					print wp_json_encode( $response );
					exit;
				}

				break;
			case 'simply-cdn':
				if ( class_exists('\sch\Api' ) ) {
					$simply_cdn = \sch\Simply_CDN::get_instance();

					// Subdirectory or not?
					$sub_directory = $simply_cdn->data->cdn->sub_directory;

					if ( ! empty( $sub_directory ) ) {
						$path = untrailingslashit( $sub_directory ) . str_replace( $origin_url, '', $url );
					} else {
						$path = str_replace( $origin_url, '', $url );
					}

					$deleted = $simply_cdn->delete_file( $path );

					if ( ! $deleted ) {
						$response = array(
							'success' => false,
							'error'   => esc_html__( 'The file could not be deleted. Please check your access key in Simply Static -> Settings -> Deployment', 'simply-static-pro' ),
						);

						print wp_json_encode( $response );
						exit;
					}
				}
				break;
		}

		// Run static single export to update blog/homepage and cat/tag pages.
		$this->update_related_urls( $single_id );

		// Exit now.
		$response = array( 'success' => true );
		print wp_json_encode( $response );
		exit;
	}
}
