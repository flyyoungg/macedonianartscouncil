<?php

namespace simply_static_pro;

use voku\Httpful\Client;

/**
 * Class to handle settings for fuse.
 */
class Multilingual {
	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Returns instance of Multilingual.
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
	 * Constructor for Multilingual.
	 */
	public function __construct() {
		add_action( 'ss_match_tags', array( $this, 'find_translated_pages' ) );
		add_filter( 'ss_get_options', array( $this, 'get_multilingual_options' ) );
	}

	/**
	 * Add translations from meta tags.
	 *
	 * @param  array $match_tags list of matching tags for extraction.
	 * @return array
	 */
	public function find_translated_pages( $match_tags ) {
		$match_tags['link'] = array( 'href' );
		return $match_tags;
	}

	/**
	 * Return options in selected language with WPML.
	 *
	 * @param  array $options array of options.
	 * @return array
	 */
	public function get_multilingual_options( $options ) {
		do_action( 'wpml_multilingual_options', 'simply-static' );

		$options = get_option( 'simply-static' );
		return $options;
	}

	/**
	 * Get related translations of a page.
	 *
	 * @param  int $single_id single post id.
	 * @return array
	 */
	public static function get_related_translations( $single_id ) {
		$options              = get_option( 'simply-static' );
		$related_translations = array();

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

		foreach ( $dom->find( 'link' ) as $link ) {
			if ( $link->hasAttribute( 'hreflang' ) ) {
				if ( get_permalink( $single_id ) == $link->getAttribute( 'href' ) && 'x-default' !== $link->getAttribute( 'hreflang' ) ) {
					$related_translations[] = $link->getAttribute( 'href' );
				}
			}
		}

		return $related_translations;
	}
}
