<?php

namespace simply_static_pro;

use Simply_Static;
use voku\helper\HtmlDomParser;
use voku\Httpful\Client;


/**
 * Class to handle settings for fuse.
 */
class Search_Fuse {
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
		$options = get_option( 'simply-static' );

		add_shortcode( 'ssp-search', array( $this, 'render_shortcode' ) );
		add_action( 'ss_after_setup_task', array( $this, 'remove_index_file' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_fuse_scripts' ) );
		add_action( 'ss_after_setup_static_page', array( $this, 'update_index_file' ) );

		if ( ! empty( $options['use-search'] ) && 'fuse' == $options['search-type'] ) {
			add_action( 'ss_finished_fetching_pages', array( $this, 'finish_index_file' ) );
		}
	}

	/**
	 * Render search box shortcode.
	 *
	 * @return string
	 */
	public function render_shortcode() {
		$options = get_option( 'simply-static' );

		if ( isset( $options['use-search'] ) && ! empty( $options['use-search'] ) && isset( $options['search-type'] ) && 'fuse' == $options['search-type'] ) {
			ob_start();
			?>
			<div class="ssp-search">
				<form class="search-form">
					<div class="form-row">
					<div class="search-input-container">
						<input class="search-input fuse-search" name="search-input" placeholder="<?php esc_html_e( 'Search', 'simply-static-pro' ); ?>" autocomplete="off" data-noresult="<?php esc_html_e( 'No results found.', 'simply-static-pro' ); ?>">
						<div class="search-auto-complete"></div>
					</div>
					<button type="submit" class="search-submit">
						<svg viewBox="0 0 24 24">
						<path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
						</svg>
					</button>
					</div>
				</form>
				<div class="result"></div>
			</div>
			<?php
			return ob_get_clean();
		}
	}

	/**
	 * Enqueue scripts for shortcode.
	 *
	 * @return void
	 */
	public function add_fuse_scripts() {
		$options    = get_option( 'simply-static' );
		$load_local = apply_filters( 'ssp_include_local', false );

		if ( isset( $options['use-search'] ) && 'no' !== $options['use-search'] && ! empty( $options['use-search'] ) && isset( $options['search-type'] ) && 'fuse' == $options['search-type'] ) {
			wp_enqueue_style( 'ssp-search', SIMPLY_STATIC_PRO_URL . '/assets/ssp-search.css', array(), '1.1.1', 'all' );

			if ( $load_local ) {
				wp_enqueue_script( 'ssp-fuse', 'https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.js', array(), '1.1.1', true );
			} else {
				wp_enqueue_script( 'ssp-fuse', SIMPLY_STATIC_PRO_URL . '/assets/fuse.js', array(), '1.1.1', true );
			}

			if ( defined( 'SSP_DEV_MODE' ) && true === SSP_DEV_MODE ) {
				wp_enqueue_script( 'ssp-search-dev', SIMPLY_STATIC_PRO_URL . '/assets/dev/ssp-search-dev.js', array( 'ssp-fuse' ), '1.1.1', true );
			} else {
				wp_enqueue_script( 'ssp-search', SIMPLY_STATIC_PRO_URL . '/assets/ssp-search.js', array( 'ssp-fuse' ), '1.1.1', true );
			}
		}
	}

	/**
	 * Remove old index file on full static export.
	 *
	 * @return void
	 */
	public function remove_index_file() {
		global $wp_filesystem;

		// Check if it's a full static export.
		$use_single = get_option( 'simply-static-use-single' );
		$use_build  = get_option( 'simply-static-use-build' );

		if ( isset( $use_build ) && ! empty( $use_build ) || isset( $use_single ) && ! empty( $use_single ) ) {
			return;
		}

		// Initialize the WP filesystem.
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		$options = get_option( 'simply-static' );

		$static_url = '';
		$origin_url = untrailingslashit( get_bloginfo( 'url' ) );

		if ( ! empty( $options['static-search-url'] ) ) {
			$static_url = untrailingslashit( $options['static-search-url'] );
		} elseif ( ! empty( $options['static-url'] ) ) {
			$static_url = untrailingslashit( $options['static-url'] );
		}

		$domain      = wp_parse_url( $static_url );
		$domain      = str_replace( '.', '-', $domain['host'] );
		$upload_dir  = wp_upload_dir();
		$config_dir  = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'simply-static' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR;
		$config_file = $config_dir . $domain . '-index.json';

		// Delete old index.
		if ( file_exists( $config_file ) ) {
			wp_delete_file( $config_file, true );
		}
	}

	/**
	 * Setup the index file and add it to Simply Static options.
	 *
	 * @return string
	 */
	public function get_config() {
		global $wp_filesystem;

		// Initialize the WP filesystem.
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		$options = get_option( 'simply-static' );

		$static_url = '';
		$origin_url = untrailingslashit( get_bloginfo( 'url' ) );

		if ( ! empty( $options['static-search-url'] ) ) {
			$static_url = untrailingslashit( $options['static-search-url'] );
		} elseif ( ! empty( $options['static-url'] ) ) {
			$static_url = untrailingslashit( $options['static-url'] );
		}

		$domain      = wp_parse_url( $static_url );
		$domain      = str_replace( '.', '-', $domain['host'] );
		$upload_dir  = wp_upload_dir();
		$config_dir  = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'simply-static' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR;
		$config_file = $config_dir . $domain . '-index.json';

		if ( file_exists( $config_file ) ) {
			return $config_file;
		}

		// Check if directory exists.
		if ( ! is_dir( $config_dir ) ) {
			wp_mkdir_p( $config_dir );
		}

		$wp_filesystem->put_contents( $config_file, '[' );

		return $config_file;
	}

	/**
	 * Push static pages to Algolia.
	 *
	 * @param  object $static_page static page object after crawling.
	 * @return object
	 */
	public function update_index_file( $static_page ) {
		$options = get_option( 'simply-static' );

		// Check if search is active.
		if ( ! isset( $options['use-search'] ) || 'no' === $options['use-search'] || 'fuse' !== $options['search-type'] ) {
			return $static_page;
		}

		// Exclude from search index.
		$excludables = $options['search-excludable'];

		if ( ! is_array( $excludables ) ) {
			$excludables = array();
		}

		// Remove files, feeds, comments and author archives from index.
		$excludables = apply_filters( 'ssp_excluded_by_default', array_merge( $excludables, array( 'feed', 'comments', 'author', '.jpg', '.png', '.pdf', '.xml', '.gif', '.mp4', '.xsl' ) ) );

		if ( ! empty( $excludables ) ) {
			foreach ( $excludables as $excludable ) {
				$in_url = strpos( $static_page->url, $excludable );

				if ( $in_url !== false ) {
					return $static_page;
				}
			}
		}

		// Check if it's a full static export.
		$use_single = get_option( 'simply-static-use-single' );
		$use_build  = get_option( 'simply-static-use-build' );

		if ( isset( $use_build ) && ! empty( $use_build ) || isset( $use_single ) && ! empty( $use_single ) ) {
			return $static_page;
		}

		if ( 200 == $static_page->http_status_code ) {
			// Basic Auth enabled?
			if ( isset( $options['http_basic_auth_digest'] ) && ! empty( $options['http_basic_auth_digest'] ) ) {
				$auth_params = explode( ':', base64_decode( $options['http_basic_auth_digest'] ) );
				$response    = \Httpful\Client::get_request( $static_page->url )
				->expectsHtml()
				->disableStrictSSL()
				->withBasicAuth( $auth_params[0], $auth_params[1] )
				->send();

			} else {
				$response = \Httpful\Client::get_request( $static_page->url )
				->expectsHtml()
				->disableStrictSSL()
				->send();
			}

			$dom = $response->getRawBody();

			if ( is_null( $dom ) ) {
				return $static_page;
			}

			// Get elements from settings.
			$title   = 'title';
			$body    = 'body';
			$excerpt = '.entry-content';

			if ( isset( $options['search-index-title'] ) && ! empty( $options['search-index-title'] ) ) {
				$title = $options['search-index-title'];
			}

			if ( isset( $options['search-index-content'] ) && ! empty( $options['search-index-content'] ) ) {
				$body = $options['search-index-content'];
			}

			if ( isset( $options['search-index-excerpt'] ) && ! empty( $options['search-index-excerpt'] ) ) {
				$excerpt = $options['search-index-excerpt'];
			}

			// Filter dom for creating index entries.
			$title   = $dom->find( $title, 0 )->innertext;
			$body    = wp_strip_all_tags( $dom->find( $body, 0 )->innertext );
			$excerpt = wp_strip_all_tags( $dom->find( $excerpt, 0 )->innertext );
			$post_id = wp_strip_all_tags( $dom->find( '.ssp-id', 0 )->innertext );

			// Multilingual.
			$language = '';

			foreach ( $dom->find( 'link' ) as $link ) {
				if ( $link->hasAttribute( 'hreflang' ) ) {
					if ( $static_page->url == $link->getAttribute( 'href' ) && 'x-default' !== $link->getAttribute( 'hreflang' ) ) {
						$language = $link->getAttribute( 'hreflang' );
					}
				}
			}

			// Strip whitespace.
			$body = preg_replace( '/\s+/', '', $body );

			if ( '' !== $title && '' !== $post_id ) {
				// Maybe replace URL.
				$static_url = '';
				$origin_url = untrailingslashit( get_bloginfo( 'url' ) );

				if ( ! empty( $options['static-search-url'] ) ) {
					$static_url = untrailingslashit( $options['static-search-url'] );
				} elseif ( ! empty( $options['static-url'] ) ) {
					$static_url = untrailingslashit( $options['static-url'] );
				}

				// Additional Path set?
				if ( ! empty( $options['relative_path'] ) ) {
					$static_url = $static_url . $options['relative_path'];
				}

				if ( ! empty( $static_url ) ) {
					// Build search entry.
					$index_item = apply_filters(
						'ssp_search_index_item',
						array(
							'objectID' => $post_id,
							'title'    => wp_strip_all_tags( $title ),
							'content'  => $body,
							'excerpt'  => wp_trim_words( $excerpt, '20', '..' ),
							'url'      => str_replace( $origin_url, $static_url, $static_page->url ),
						),
						$dom
					);

					if ( '' !== $language ) {
						$index_item['language'] = $language;
					}

					// Write to index file.
					$config_file = $this->get_config();

					if ( file_exists( $config_file ) ) {
						if ( ! empty( $title ) && ! empty( $body ) ) {
							$file = fopen( $config_file, 'a' );
							$data = wp_json_encode( $index_item );

							fwrite( $file, $data . ',' );
							fclose( $file );
						}
					} else {
						Simply_Static\Util::debug_log( __( 'Could not append the result to the search index.', 'simply-static-pro' ) . ': ' . $static_page->url );
					}

					Simply_Static\Util::debug_log( __( 'Added the following URL to search index', 'simply-static-pro' ) . ': ' . $static_page->url );
				} else {
					Simply_Static\Util::debug_log( __( 'You need to add the static URL in your search settings before you can create an index.', 'simply-static-pro' ) );
				}
			}
		}

		return $static_page;
	}

	/**
	 * Finish index file to result in valid JSON.
	 *
	 * @return void
	 */
	public function finish_index_file() {
		// Write to index file.
		$config_file = $this->get_config();

		if ( file_exists( $config_file ) ) {
			$file    = fopen( $config_file, 'a' );
			$content = substr( file_get_contents( $config_file ), 0, -1 );
			$content = $content . ']]';

			fwrite( $file, $content );
			fclose( $file );
		}
	}
}
