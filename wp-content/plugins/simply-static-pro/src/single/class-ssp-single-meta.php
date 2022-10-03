<?php

namespace simply_static_pro;

/**
 * Class to handle meta for single.
 */
class Single_Meta {
	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Returns instance of Single_Meta.
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
	 * Constructor for Single_Meta.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'add_toolbar_action' ) );
	}


	/**
	 * Add static export action to Block editor toolbar.
	 *
	 * @return void
	 */
	public function add_toolbar_action() {
		wp_enqueue_script( 'ssp-toolbar', SIMPLY_STATIC_PRO_URL . '/assets/ssp-toolbar.js', array(), '1.0', true );
		wp_localize_script(
			'ssp-toolbar',
			'sspt',
			array(
				'button_label'         => __( 'Generate Static', 'simply-static-pro' ),
				'publish_button_label' => __( 'Publish', 'simply-static-pro' ),
			)
		);
	}


	/**
	 * Adds the meta box container.
	 *
	 * @param array $post_type array of post types.
	 * @return void
	 */
	public function add_metaboxes( $post_type ) {
		$post_types = get_post_types( array( 'public' => true, 'exclude_from_search' => false ), 'names' );

		add_meta_box( 'single-export', __( 'Simply Static', 'simply-static-pro' ), array( $this, 'render_simply_static' ), apply_filters( 'ssh_single_export_post_types', $post_types ), 'side', 'high' );
	}

	/**
	 * Add static export button.
	 *
	 * @param  object $post current post object.
	 * @return void
	 */
	public function render_simply_static( $post ) {
		$current_screen = get_current_screen();
		?>
		<?php if ( 'publish' === $post->post_status || method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) : ?>
		<p id="export-file-container">
			<a href="#" id="generate-single" class="button button-primary" data-id="<?php echo esc_html( $post->ID ); ?>"><?php esc_html_e( 'Generate static', 'simply-static-pro' ); ?></a>
			<span class="spinner"></span>
			<br>
			<small><?php esc_html_e( 'Use this to generate a static version of the current page you are editing.', 'simply-static-pro' ); ?></small>
		</p>
		<p id="delete-file-container">
			<a href="#" id="delete-single" class="button button-secondary" data-id="<?php echo esc_html( $post->ID ); ?>"><?php esc_html_e( 'Delete static', 'simply-static-pro' ); ?></a>
			<span class="spinner"></span>
			<br>
			<small><?php esc_html_e( 'Use this to delete the static version of the current page you are editing.', 'simply-static-pro' ); ?></small>
		</p>
		<?php else : ?>
		<p>
			<small><?php esc_html_e( 'You have to publish your post before you can create a static version of it.', 'simply-static-pro' ); ?></small>
		</p>
		<?php endif; ?>
		<style>
		.spinner {
			float: none;
			margin: 0;
			top: -2px;
			position: relative;
		}

		.edit-post-header__toolbar .spinner {
		float: none;
		margin: 0;
			margin-left: 0px;
		top: 5px;
		position: relative;
		margin-left: 3px;
		}
		</style>
		<?php
	}
}
