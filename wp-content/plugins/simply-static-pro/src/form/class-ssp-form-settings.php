<?php

namespace simply_static_pro;

use Simply_Static;

/**
 * Class to handle admin for forms.
 */
class Form_Settings {
	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Returns instance of Form_Settings.
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
	 * Constructor for Form_Settings.
	 */
	public function __construct() {
		add_action( 'simply_static_settings_view_tab', array( $this, 'output_settings_tab' ), 30 );
		add_action( 'simply_static_settings_view_form', array( $this, 'output_settings_form' ), 30 );
		add_filter( 'simply_static_options', array( $this, 'add_options' ) );

		$options = get_option( 'simply-static' );

		if ( 'yes' === $options['use-forms'] ) {
			add_action( 'init', array( $this, 'add_forms_post_type' ) );
			add_action( 'save_post_ssp-form', array( $this, 'update_config' ), 10, 3 );
			add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ) );
			add_filter( 'simply_static_class_name', array( $this, 'check_class_name' ), 30, 2 );
			add_filter( 'parent_file', array( $this, 'show_parent_menu' ) );

			// Add compatibilites for form plugins.
			add_filter( 'gform_form_args', array( $this, 'disable_gravity_forms_ajax' ), 10, 1 );
			add_action( 'wp_ajax_create_form_config', array( $this, 'create_form_config' ) );
		}
	}

	/**
	 * Highlight parent menu when editing ssp form post.
	 *
	 * @param string $parent given parent.
	 * @return string
	 */
	public function show_parent_menu( $parent = '' ) {
		global $pagenow, $typenow, $taxnow;

		// If we're editing the form settings, we must be within the SS menu, so highlight that.
		if ( ( $pagenow === 'post.php' ) && ( $typenow === 'ssp-form' ) ) {
			$parent = 'simply-static';
		}
		return $parent;
	}

	/**
	 * Output a new settings tab in Simply Static Settings.
	 *
	 * @return void
	 */
	public function output_settings_tab() {
		?>
		<a class='nav-tab' id='form-tab' href='#tab-form'><?php esc_html_e( 'Forms', 'simply-static-pro' ); ?></a>
		<?php
	}

	/**
	 * Output content for new settings tab in Simply Static Settings.
	 *
	 * @return void
	 */
	public function output_settings_form() {
		$options = get_option( 'simply-static' );

		// buffer output.
		ob_start();
		include( SIMPLY_STATIC_PRO_PATH . '/src/form/views/form.php' );
		$settings = ob_get_contents();
		ob_end_clean();

		// Cors settings.
		if ( ! empty( $options['fix-cors'] ) ) {
			if ( 'allowed_http_origins' === $options['fix-cors'] ) {
				$select_options = '<option selected value="allowed_http_origins">allowed_http_origins</option><option value="wp_headers">wp_headers</option>';
			} else {
				$select_options = '<option selected value="wp_headers">wp_headers</option><option value="allowed_http_origins">allowed_http_origins</option>';
			}
			$settings = str_replace( '[FIX_CORS]', $select_options, $settings );
		} else {
			$select_options = '<option value="allowed_http_origins">allowed_http_origins</option><option value="wp_headers">wp_headers</option>';
			$settings = str_replace( '[FIX_CORS]', $select_options, $settings );
		}

		if ( ! empty( $options['static-url'] ) ) {
			$settings = str_replace( '[STATIC_URL]', $options['static-url'], $settings );
		} else {
			// Check if Search URL is set.
			if ( ! empty( $options['static-search-url'] ) ) {
				$settings = str_replace( '[STATIC_URL]', $options['static-search-url'], $settings );
			} else {
				$settings = str_replace( '[STATIC_URL]', '', $settings );
			}
		}

		// Comments settings.
		if ( ! empty( $options['use-comments'] ) ) {
			if ( 'no' === $options['use-comments'] ) {
				$select_options = '<option selected value="no">' . esc_html__('no', 'simply-static-pro' ) . '</option><option value="yes">' . esc_html__('yes', 'simply-static-pro' ) . '</option>';
			} else {
				$select_options = '<option selected value="yes">' . esc_html__('yes', 'simply-static-pro' ) . '</option><option value="no">' . esc_html__('no', 'simply-static-pro' ) . '</option>';
			}
			$settings = str_replace( '[USE_COMMENTS]', $select_options, $settings );
		} else {
			$select_options = '<option value="no">' . esc_html__('no', 'simply-static-pro' ) . '</option><option value="yes">' . esc_html__('yes', 'simply-static-pro' ) . '</option>';
			$settings = str_replace( '[USE_COMMENTS]', $select_options, $settings );
		}

		if ( ! empty( $options['comment-redirect'] ) ) {
			$settings = str_replace( '[COMMENT_REDIRECT]', $options['comment-redirect'], $settings );
		} else {
			$settings = str_replace( '[COMMENT_REDIRECT]', '', $settings );
		}

		// Forms settings.
		if ( ! empty( $options['use-forms'] ) ) {
			if ( 'no' === $options['use-forms'] ) {
				$select_options = '<option selected value="no">' . esc_html__('no', 'simply-static-pro' ) . '</option><option value="yes">' . esc_html__('yes', 'simply-static-pro' ) . '</option>';
			} else {
				$select_options = '<option selected value="yes">' . esc_html__('yes', 'simply-static-pro' ) . '</option><option value="no">' . esc_html__('no', 'simply-static-pro' ) . '</option>';
			}
			$settings = str_replace( '[USE_FORMS]', $select_options, $settings );
		} else {
			$select_options = '<option value="no">' . esc_html__('no', 'simply-static-pro' ) . '</option><option value="yes">' . esc_html__('yes', 'simply-static-pro' ) . '</option>';
			$settings       = str_replace( '[USE_FORMS]', $select_options, $settings );
		}

		if ( ! empty( $options['static-search-url'] ) ) {
			$settings = str_replace( '[CREATE_FORM_CONFIG]', '<a class="button button-secondary" name="create-form-config" id="create-form-config">' . esc_html__( 'Create form config', 'simply-static-pro' ) . '</a>', $settings );
		} else {
			$settings = str_replace( '[CREATE_FORM_CONFIG]', '', $settings );
		}

		echo $settings;
	}

	/**
	 * Filter the Simply Static options and add pro options.
	 *
	 * @param array $options array of options.
	 * @return array
	 */
	public function add_options( $options ) {
		$ss = Simply_Static\Plugin::instance();

		$options['fix-cors']         = $ss->fetch_post_value( 'fix-cors' );
		$options['static-url']       = $ss->fetch_post_value( 'static-url' );
		$options['use-forms']        = $ss->fetch_post_value( 'use-forms' );
		$options['use-comments']     = $ss->fetch_post_value( 'use-comments' );
		$options['comment-redirect'] = $ss->fetch_post_value( 'comment-redirect' );

		if ( 'yes' === $ss->fetch_post_value( 'use-comments' ) ) {
			// Modify default WordPress comments.
			$require_registration = get_option( 'comment_registration' );
			$require_name_mail    = get_option( 'require_name_email' );

			if ( 1 == $require_registration ) {
				update_option( 'comment_registration', 0 );
			}

			if ( 0 == $require_name_mail ) {
				update_option( 'comment_registration', 1 );
			}
		}

		return $options;
	}

	/**
	 * Disable ajax in Gravity Forms form.
	 *
	 * @param  array $args array of arguments.
	 * @return array
	 */
	public function disable_gravity_forms_ajax( $args ) {
		$args['ajax'] = false;
		return $args;
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function add_admin_scripts() {
		wp_enqueue_script( 'ssp-form-admin', SIMPLY_STATIC_PRO_URL . '/assets/ssp-form-admin.js', array( 'jquery' ), '1.1.1', true );
		wp_enqueue_style( 'ssp-form-admin', SIMPLY_STATIC_PRO_URL . '/assets/ssp-form-admin.css', false, '1.1.1' );

		wp_localize_script(
			'ssp-form-admin',
			'form_config',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ssp-form-config' ),
			)
		);
	}

	/**
	 * Add submenu page for builds taxonomy.
	 *
	 * @return void
	 */
	public function add_submenu_page() {
		add_submenu_page(
			'simply-static',
			__( 'Forms', 'simply-static-pro' ),
			__( 'Forms', 'simply-static-pro' ),
			'edit_posts',
			'edit.php?post_type=ssp-form',
			false
		);
	}

	/**
	 * Create forms custom post type.
	 *
	 * @see register_post_type() for registering custom post types.
	 */
	public function add_forms_post_type() {
		$labels = array(
			'name'                  => _x( 'Forms', 'Post type general name', 'simply-static-pro' ),
			'singular_name'         => _x( 'Form', 'Post type singular name', 'simply-static-pro' ),
			'menu_name'             => _x( 'Forms', 'Admin Menu text', 'simply-static-pro' ),
			'name_admin_bar'        => _x( 'Form', 'Add New on Toolbar', 'simply-static-pro' ),
			'add_new'               => __( 'Add New', 'simply-static-pro' ),
			'add_new_item'          => __( 'Add New Form', 'simply-static-pro' ),
			'new_item'              => __( 'New Form', 'simply-static-pro' ),
			'edit_item'             => __( 'Edit Form', 'simply-static-pro' ),
			'view_item'             => __( 'View Form', 'simply-static-pro' ),
			'all_items'             => __( 'All Forms', 'simply-static-pro' ),
			'search_items'          => __( 'Search Forms', 'simply-static-pro' ),
			'parent_item_colon'     => __( 'Parent Forms:', 'simply-static-pro' ),
			'not_found'             => __( 'No forms found.', 'simply-static-pro' ),
			'not_found_in_trash'    => __( 'No forms found in Trash.', 'simply-static-pro' ),
			'featured_image'        => _x( 'Form Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'simply-static-pro' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'simply-static-pro' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'simply-static-pro' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'simply-static-pro' ),
			'archives'              => _x( 'Form archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'simply-static-pro' ),
			'insert_into_item'      => _x( 'Insert into form', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'simply-static-pro' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this form', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'simply-static-pro' ),
			'filter_items_list'     => _x( 'Filter forms list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'simply-static-pro' ),
			'items_list_navigation' => _x( 'Forms list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'simply-static-pro' ),
			'items_list'            => _x( 'Forms list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'simply-static-pro' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'ssp-form', $args );
	}


	/**
	 * Modify task class name in Simply Static.
	 *
	 * @param string $class_name current class name.
	 * @param string $task_name current task name.
	 * @return string
	 */
	public function check_class_name( $class_name, $task_name ) {
		if ( 'form_config' === $task_name ) {
			return 'simply_static_pro\\' . ucwords( $task_name ) . '_Task';
		}
		return $class_name;
	}

	/**
	 * Create form config file with ajax.
	 *
	 * @return void
	 */
	public function create_form_config() {
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'ssp-form-config' ) ) {
			die();
		}

		if ( ! current_user_can( 'administrator' ) ) {
			die();
		}

		$config_path = $this->create_config_file();

		if ( file_exists( $config_path ) ) {
			$response = array( 'success' => true, 'message' => __( 'Created form config file', 'simply-static-pro' ) );
		} else {
			$response = array( 'success' => true, 'message' => __( 'There was a problem creating the config file.', 'simply-static-pro' ) );
		}

		print wp_json_encode( $response );
		exit;
	}

	/**
	 * Update form config if ssp-form post is saved.
	 *
	 * @param  int    $post_id given post id.
	 * @param  object $post given post object.
	 * @param  bool   $update updated or not.
	 * @return void
	 */
	public function update_config( $post_id, $post, $update ) {
		$this->create_config_file();
	}

	/**
	 * Create JSON file for forms config.
	 *
	 * @return string;
	 */
	public function create_config_file() {
		global $wp_filesystem;

		$options    = get_option( 'simply-static' );
		$static_url = '';

		if ( ! empty( $options['static-search-url'] ) ) {
			$static_url = untrailingslashit( $options['static-search-url'] );
		} elseif ( ! empty( $options['static-url'] ) ) {
			$static_url = untrailingslashit( $options['static-url'] );
		}

		if ( empty( $static_url ) ) {
			Simply_Static\Util::debug_log( __( 'You need to add the static URL in your CORS settings before you can create a form configuration.', 'simply-static-pro' ) );
			return true;
		}

		$domain      = wp_parse_url( $static_url );
		$domain      = str_replace( '.', '-', $domain['host'] );
		$upload_dir  = wp_upload_dir();
		$config_dir  = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'simply-static' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR;
		$config_file = $config_dir . $domain . '-forms.json';

		// Delete old index.
		if ( file_exists( $config_file ) ) {
			wp_delete_file( $config_file, true );
		}

		// Get static form configurations.
		$args      = array( 'numberposts' => -1, 'post_type' => 'ssp-form', 'fields' => 'ids' );
		$ssp_forms = get_posts( $args );
		$forms     = array();

		if ( ! empty( $ssp_forms ) ) {
			foreach ( $ssp_forms as $form_id ) {
				$form               = new \stdClass();
				$form->id           = get_post_meta( $form_id, 'form_id', true );
				$form->tool         = get_post_meta( $form_id, 'tool', true );
				$form->endpoint     = get_post_meta( $form_id, 'endpoint', true );
				$form->redirect_url = get_post_meta( $form_id, 'redirect_url', true );
				$forms[]            = $form;
			}
		}

		// Now create the json file.
		$json = wp_json_encode( $forms );

		// Initialize the WP filesystem.
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		// Check if directory exists.
		if ( ! is_dir( $config_dir ) ) {
			wp_mkdir_p( $config_dir );
		}

		$wp_filesystem->put_contents( $config_file, $json );

		return $config_file;
	}
}
