<?php

namespace simply_static_pro;

/**
 * Class to handle meta for builds.
 */
class Build_Meta {
	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Returns instance of Build_Meta.
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
	 * Constructor for Build_Meta.
	 */
	public function __construct() {
		add_action( 'ssp-build_add_form_fields', array( $this, 'add_build_fields' ) );
		add_action( 'ssp-build_edit_form_fields', array( $this, 'edit_build_fields' ), 10, 2 );
		add_action( 'created_ssp-build', array( $this, 'save_build_fields' ) );
		add_action( 'edited_ssp-build', array( $this, 'save_build_fields' ) );
		add_filter( 'manage_edit-ssp-build_columns', array( $this, 'modify_build_columns' ) );
		add_filter( 'manage_ssp-build_custom_column', array( $this, 'modify_build_columns_content' ), 10, 3 );
	}

	/**
	 * Add builds meta fields to new build.
	 *
	 * @param object $taxonomy current taxonomy.
	 * @return void
	 */
	public function add_build_fields( $taxonomy ) {
		$options = get_option( 'simply-static' );
		?>
		<div class="form-field">
			<label for="additional-urls"><?php esc_html_e( 'URLs to export', 'simply-static-pro' ); ?></label>
			<textarea rows="5" cols="10" name="additional-urls" id="additional-urls"></textarea>
			<p><?php esc_html_e( 'Add URLs you want to export here (one per line). Simply Static will crawl each of these and run a static export only for these URLs.', 'simply-static-pro' ); ?></p>
		</div>
		<div class="form-field">
			<label for="additional-files"><?php esc_html_e( 'Additional Files and Directories', 'simply-static-pro' ); ?></label>
			<textarea rows="5" cols="10" name="additional-files" id="additional-files"></textarea>
			<p><?php esc_html_e( 'Add additional files or directories. Add the paths to those files or directories here (one per line).', 'simply-static-pro' ); ?></p>
			<p><?php echo sprintf( esc_html__( 'Example: %s', 'simply-static-pro' ), '<code>' . esc_html( trailingslashit( WP_CONTENT_DIR ) ) . esc_html__( 'my-file.pdf', 'simply-static-pro' ) ) . '</code>'; ?></p>
		</div>
		<?php
	}


	/**
	 * Add meta to edit build.
	 *
	 * @param object $build current build.
	 * @param object $taxonomy current taxonomy.
	 * @return void
	 */
	public function edit_build_fields( $build, $taxonomy ) {
		$additional_urls  = get_term_meta( $build->term_id, 'additional-urls', true );
		$additional_files = get_term_meta( $build->term_id, 'additional-files', true );

		$options = get_option( 'simply-static' );
		?>
		<tr class="form-field">
			<th>
				<label for="additional-urls"><?php esc_html_e( 'URLs to export', 'simply-static-pro' ); ?></label>
			</th>
			<td>
				<textarea rows="5" cols="10" name="additional-urls" id="additional-urls"><?php echo $additional_urls; ?></textarea>
				<p><?php esc_html_e( 'Add URLs you want to export here (one per line). Simply Static will crawl each of these and run a static export only for these URLs.', 'simply-static-pro' ); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th>
				<label for="additional-files"><?php esc_html_e( 'Additional Files and Directories', 'simply-static-pro' ); ?></label>
			</th>
			<td>
				<textarea rows="5" cols="10" name="additional-files" id="additional-files"><?php echo $additional_files; ?></textarea>
				<p><?php esc_html_e( 'Add additional files or directories. Add the paths to those files or directories here (one per line).', 'simply-static-pro' ); ?></p>
				<p><?php echo sprintf( esc_html__( 'Example: %s', 'simply-static-pro' ), '<code>' . esc_html( trailingslashit( WP_CONTENT_DIR ) ) . esc_html__( 'my-file.pdf', 'simply-static-pro' ) ) . '</code>'; ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Update build meta field.
	 *
	 * @param  int $build_id current build id.
	 * @return void
	 */
	public function save_build_fields( $build_id ) {
		update_term_meta( $build_id, 'additional-urls', $_POST[ 'additional-urls' ] );
		update_term_meta( $build_id, 'additional-files', $_POST[ 'additional-files' ] );
	}

	/**
	 * Add shortcode to columns for filr-lists.
	 *
	 * @param array $columns new columns to add.
	 * @return array
	 */
	public function modify_build_columns( $columns ) {
		$columns = array(
			'cb'       => '<input type="checkbox" />',
			'name'     => __( 'Name', 'simply-static-pro' ),
			'generate' => __( 'Generate', 'simply-static-pro' ),
		);

		return $columns;
	}
	/**
	 * Add content to shortcode column.
	 *
	 * @param string $value current value.
	 * @param string $name name of column.
	 * @param int    $term_id current id.
	 * @return string
	 */
	public function modify_build_columns_content( $value, $name, $term_id ) {
		switch ( $name ) {
			case 'generate':
				return '<a href="#" class="generate-build button button-primary" data-term-id="' . $term_id . '">Generate</a>';
				break;
		}
	}
}
