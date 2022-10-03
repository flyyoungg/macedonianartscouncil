<?php
/**
 * Widget: Banner (Gutenberg support)
 *
 * @package ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


// Gutenberg Block
//------------------------------------------------------

// Add scripts and styles for the editor
if ( ! function_exists( 'trx_addons_gutenberg_sc_banner_editor_assets' ) ) {
	add_action( 'enqueue_block_editor_assets', 'trx_addons_gutenberg_sc_banner_editor_assets' );
	function trx_addons_gutenberg_sc_banner_editor_assets() {
		if ( trx_addons_exists_gutenberg() && trx_addons_get_setting( 'allow_gutenberg_blocks' ) ) {
			// Scripts
			wp_enqueue_script(
				'trx-addons-gutenberg-editor-block-banner',
				trx_addons_get_file_url( TRX_ADDONS_PLUGIN_WIDGETS . 'banner/gutenberg/banner.gutenberg-editor.js' ),
				array( 'wp-blocks', 'wp-editor', 'wp-i18n', 'wp-element', 'trx_addons-admin', 'trx_addons-utils', 'trx_addons-gutenberg-blocks' ),
				filemtime( trx_addons_get_file_dir( TRX_ADDONS_PLUGIN_WIDGETS . 'banner/gutenberg/banner.gutenberg-editor.js' ) ),
				true
			);
		}
	}
}

// Block register
if ( ! function_exists( 'trx_addons_sc_banner_add_in_gutenberg' ) ) {
	add_action( 'init', 'trx_addons_sc_banner_add_in_gutenberg' );
	function trx_addons_sc_banner_add_in_gutenberg() {
		if ( trx_addons_exists_gutenberg() && trx_addons_get_setting( 'allow_gutenberg_blocks' ) ) {
			register_block_type(
				'trx-addons/banner', array(
					'attributes'      => array_merge(
						array(
							'title'     => array(
								'type'    => 'string',
								'default' => '',
							),
							'image'     => array(
								'type'    => 'number',
								'default' => 0,
							),
							'image_url' => array(
								'type'    => 'string',
								'default' => '',
							),
							'link'      => array(
								'type'    => 'string',
								'default' => '',
							),
							'code'      => array(
								'type'    => 'string',
								'default' => '',
							),
							'fullwidth' => array(
								'type'    => 'boolean',
								'default' => false,
							),
						),
						trx_addons_gutenberg_get_param_id()
					),
					'render_callback' => 'trx_addons_gutenberg_sc_banner_render_block',
				)
			);
		}
	}
}

// Block render
if ( ! function_exists( 'trx_addons_gutenberg_sc_banner_render_block' ) ) {
	function trx_addons_gutenberg_sc_banner_render_block( $attributes = array() ) {
		$attributes['fullwidth'] = $attributes['fullwidth'] ? 'on' : 'off';
		return trx_addons_sc_widget_banner( $attributes );
	}
}
