<?php

namespace simply_static_pro;

use Simply_Static;
use voku\helper\HtmlDomParser;
use voku\Httpful\Client;


/**
 * Class to handle settings for deployment.
 */
class Search_Algolia {
	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Contains new Algolia client.
	 *
	 * @var object
	 */
	private $client;

	/**
	 * Contains new Index client.
	 *
	 * @var object
	 */
	private $index;

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

		if ( ! empty( $options['use-search'] ) && 'no' !== $options['use-search'] && 'algolia' == $options['search-type'] ) {
			if ( isset( $options['algolia-app-id'] ) && ! empty( $options['algolia-app-id'] ) && isset( $options['algolia-admin-api-key'] ) && ! empty( $options['algolia-admin-api-key'] ) ) {
				$client = \Algolia\AlgoliaSearch\SearchClient::create( $options['algolia-app-id'], $options['algolia-admin-api-key'] );

				$this->client = $client;
				$this->index  = $client->initIndex( $options['algolia-index'] );

				add_action( 'wp_enqueue_scripts', array( $this, 'add_search_scripts' ) );
				add_action( 'ss_after_setup_task', array( $this, 'clear_index' ) );
				add_action( 'ss_after_setup_static_page', array( $this, 'push_to_index' ) );
				add_action( 'ss_finished_fetching_pages', array( $this, 'add_config' ) );
			}
		}
	}

	/**
	 * Clear Algolia index on full static export to prevent duplicates.
	 *
	 * @return void
	 */
	public function clear_index() {
		$use_single = get_option( 'simply-static-use-single' );
		$use_build  = get_option( 'simply-static-use-build' );

		if ( empty( $use_build ) && empty( $use_single ) ) {
			$this->index->clearObjects();
		}
	}

	/**
	 * Enqueue scripts for Algolia Instant Search.
	 *
	 * @return void
	 */
	public function add_search_scripts() {
		$options = get_option( 'simply-static' );

		if ( isset( $options['use-search'] ) && ! empty( $options['use-search'] ) && isset( $options['search-type'] ) && 'algolia' == $options['search-type'] ) {

			wp_enqueue_script( 'ssp-algolia', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js', array(), null, true );
			wp_enqueue_script( 'ssp-algolia-autocomplete', 'https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js', array(), null, true );

			if ( defined( 'SSP_DEV_MODE' ) && true === SSP_DEV_MODE ) {
				wp_enqueue_script( 'ssp-algolia-script-dev', SIMPLY_STATIC_PRO_URL . '/assets/dev/ssp-search-algolia-dev.js', array( 'ssp-algolia-autocomplete', 'ssp-algolia' ), '1.1.1', true );
			} else {
				wp_enqueue_script( 'ssp-algolia-script', SIMPLY_STATIC_PRO_URL . '/assets/ssp-search-algolia.js', array( 'ssp-algolia-autocomplete', 'ssp-algolia' ), '1.1.1', true );
			}

			wp_enqueue_style( 'ssp-search-algolia', SIMPLY_STATIC_PRO_URL . '/assets/ssp-search-algolia.css', array(), '1.1.1', 'all' );
		}
	}

	/**
	 * Push static pages to Algolia.
	 *
	 * @param  object $static_page static page object after crawling.
	 * @return object
	 */
	public function push_to_index( $static_page ) {
		$options = get_option( 'simply-static' );

		// Check if search is active.
		if ( ! isset( $options['use-search'] ) || 'no' === $options['use-search'] || 'algolia' !== $options['search-type'] ) {
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

					// Add data to Algolia.
					try {
						// Create a new index item.
						$this->index->saveObject( $index_item );
					} catch ( Exception $e ) {
						Simply_Static\Util::debug_log( __( 'There was an connection error with Algolia. Please check your settings.', 'simply-static-pro' ) );
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
	 * Setup the index file and add it to Simply Static options.
	 *
	 * @return string
	 */
	public function add_config() {
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
		$config_file = $config_dir . $domain . '-algolia.json';

		// Delete old index.
		if ( file_exists( $config_file ) ) {
			wp_delete_file( $config_file, true );
		}

		// Check if directory exists.
		if ( ! is_dir( $config_dir ) ) {
			wp_mkdir_p( $config_dir );
		}

		// Save Algolia settings to config file.
		$algolia_config = array(
			'app_id'      => $options['algolia-app-id'],
			'api_key'     => $options['algolia-search-api-key'],
			'index'       => $options['algolia-index'],
			'selector'    => $options['algolia-selector'],
			'use_excerpt' => apply_filters( 'ssp_algolia_use_excerpt', true ),
		);

		$wp_filesystem->put_contents( $config_file, json_encode( $algolia_config ) );

		return $config_file;
	}
}
