<?php

namespace simply_static_pro;

use Simply_Static;

/**
 * Class to handle settings for deployment.
 */
class Deployment_Settings {
	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Returns instance of Deployment_Settings.
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
	 * Constructor for Deployment_Settings.
	 */
	public function __construct() {
		add_action( 'simply_static_settings_view_tab', array( $this, 'output_settings_tab' ), 10 );
		add_action( 'simply_static_settings_view_form', array( $this, 'output_settings_form' ), 10 );
		add_filter( 'simply_static_options', array( $this, 'add_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ) );
		add_filter( 'simplystatic.archive_creation_job.task_list', array( $this, 'modify_task_list' ), 20, 2 );
		add_filter( 'simply_static_class_name', array( $this, 'check_class_name' ), 10, 2 );
		add_action( 'simply_static_delivery_methods', array( $this, 'add_delivery_method' ) );
		add_action( 'simply_static_delivery_method_description', array( $this, 'add_delivery_method_description' ) );

		// Add ajax handler to delete repository.
		$repository = Github_Repository::get_instance();

		add_action( 'wp_ajax_delete_repository', array( $repository, 'delete_repository' ) );
		add_action( 'wp_ajax_nopriv_delete_repository', array( $repository, 'delete_repository' ) );
		add_action( 'wp_ajax_add_repository', array( $repository, 'add_repository' ) );
		add_action( 'wp_ajax_nopriv_add_repository', array( $repository, 'add_repository' ) );

		// Modify commit message.
		add_filter( 'ssp_github_commit_message', array( $this, 'maybe_modify_commit_message' ) );
	}

	/**
	 * Output a new settings tab in Simply Static Settings.
	 *
	 * @return void
	 */
	public function output_settings_tab() {
		?>
		<a class='nav-tab' id='deployment-tab' href='#tab-deployment'><?php echo esc_html( 'Deployment' ); ?></a>
		<?php
	}

	/**
	 * Add delivery method to Simply Static settings.
	 *
	 * @return void
	 */
	public function add_delivery_method() {
		$options = get_option( 'simply-static' );
		?>
		<option value='github' <?php Simply_Static\Util::selected_if( 'github' === $options['delivery_method'] ); ?>><?php esc_html_e( 'Github', 'simply-static-pro' ); ?></option>
		<option value='cdn' <?php Simply_Static\Util::selected_if( 'cdn' === $options['delivery_method'] ); ?>><?php esc_html_e( 'CDN', 'simply-static-pro' ); ?></option>
		<?php
	}

	/**
	 * Add delivery method to Simply Static settings.
	 *
	 * @return void
	 */
	public function add_delivery_method_description() {
		?>
		<tr class="delivery-method github-deploy" style="display:none">
			<th></th>
			<td>
				<p><?php esc_html_e( 'When using Github please make sure you are using relative URLs and that you have configured the necessary settings in Simply Static -> Settings -> Deployment', 'simply-static-pro' ); ?></p>
			</td>
		</tr>
		<tr class="delivery-method cdn-deploy" style="display:none">
			<th></th>
			<td>
				<p><?php esc_html_e( 'When using CDN please make sure you are using relative URLs and that you have configured the necessary settings in Simply Static -> Settings -> Deployment', 'simply-static-pro' ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Output content for new settings tab in Simply Static Settings.
	 *
	 * @return void
	 */
	public function output_settings_form() {
		$options    = get_option( 'simply-static' );
		$repository = Github_Repository::get_instance();

		// buffer output.
		ob_start();
		include( SIMPLY_STATIC_PRO_PATH . '/src/deployment/views/deployment.php' );
		$settings = ob_get_contents();
		ob_end_clean();

		// Replacing placeholders with values from options.
		if ( ! empty( $options['cdn-api-key'] ) ) {
			$settings = str_replace( '[CDN_API_KEY]', $options['cdn-api-key'], $settings );
		} else {
			$settings = str_replace( '[CDN_API_KEY]', '', $settings );
		}

		if ( ! empty( $options['cdn-storage-host'] ) ) {
			$settings = str_replace( '[CDN_STORAGE_HOST]', $options['cdn-storage-host'], $settings );
		} else {
			$settings = str_replace( '[CDN_STORAGE_HOST]', 'storage.bunnycdn.com', $settings );
		}

		if ( ! empty( $options['cdn-access-key'] ) ) {
			$settings = str_replace( '[CDN_ACCESS_KEY]', $options['cdn-access-key'], $settings );
		} else {
			$settings = str_replace( '[CDN_ACCESS_KEY]', '', $settings );
		}

		if ( ! empty( $options['cdn-pull-zone'] ) ) {
			$settings = str_replace( '[CDN_PULL_ZONE]', $options['cdn-pull-zone'], $settings );
			$settings = str_replace( '[CDN_CNAME]', '<a target="_blank" href="https://' . $options['cdn-pull-zone'] . '.b-cdn.net' . '">' . $options['cdn-pull-zone'] . '.b-cdn.net</a>', $settings );
		} else {
			$settings = str_replace( '[CDN_PULL_ZONE]', '', $settings );
			$settings = str_replace( '[CDN_CNAME]', __( 'There is currently no Pull zone connected.', 'simply-static-pro' ), $settings );
		}

		if ( ! empty( $options['cdn-storage-zone'] ) ) {
			$settings = str_replace( '[CDN_STORAGE_ZONE]', $options['cdn-storage-zone'], $settings );
		} else {
			$settings = str_replace( '[CDN_STORAGE_ZONE]', '', $settings );
		}

		if ( ! empty( $options['cdn-directory'] ) ) {
			$settings = str_replace( '[CDN_DIRECTORY]', $options['cdn-directory'], $settings );
		} else {
			$settings = str_replace( '[CDN_DIRECTORY]', '', $settings );
		}

		if ( ! empty( $options['cdn-404'] ) ) {
			$settings = str_replace( '[CDN_404]', $options['cdn-404'], $settings );
		} else {
			$settings = str_replace( '[CDN_404]', '', $settings );
		}

		if ( ! empty( $options['github-user'] ) ) {
			$settings = str_replace( '[GITHUB_USER]', $options['github-user'], $settings );
		} else {
			$settings = str_replace( '[GITHUB_USER]', '', $settings );
		}

		if ( ! empty( $options['github-email'] ) ) {
			$settings = str_replace( '[GITHUB_EMAIL]', $options['github-email'], $settings );
		} else {
			$settings = str_replace( '[GITHUB_EMAIL]', '', $settings );
		}

		if ( ! empty( $options['github-personal-access-token'] ) ) {
			$settings = str_replace( '[GITHUB_TOKEN]', $options['github-personal-access-token'], $settings );
		} else {
			$settings = str_replace( '[GITHUB_TOKEN]', '', $settings );
		}

		if ( ! empty( $options['github-repository'] ) ) {
			$settings = str_replace( '[GITHUB_REPOSITORY]', $options['github-repository'], $settings );
		} else {
			$settings = str_replace( '[GITHUB_REPOSITORY]', '', $settings );
		}

		if ( ! empty( $options['github-repository-visibility'] ) ) {
			if ( 'public' === $options['github-repository-visibility'] ) {
				$select_options = '<option selected value="public">public</option><option value="private">private</option>';
			} else {
				$select_options = '<option selected value="private">private</option><option value="public">public</option>';
			}
			$settings = str_replace( '[GITHUB_VISIBILITY]', $select_options, $settings );
		} else {
			$select_options = '<option value="public">public</option><option value="private">private</option>';
			$settings = str_replace( '[GITHUB_VISIBILITY]', $select_options, $settings );
		}

		if ( ! empty( $options['github-branch'] ) ) {
			$settings = str_replace( '[GITHUB_BRANCH]', $options['github-branch'], $settings );
		} else {
			$settings = str_replace( '[GITHUB_BRANCH]', 'main', $settings );
		}

		if ( ! empty( $options['github-webhook-url'] ) ) {
			$settings = str_replace( '[GITHUB_WEBHOOK]', $options['github-webhook-url'], $settings );
		} else {
			$settings = str_replace( '[GITHUB_WEBHOOK]', '', $settings );
		}

		if ( ! empty( $options['github-repository-created'] ) ) {
			if ( 'yes' === $options['github-repository-created'] ) {
				// Make it private based on settings.
				$repository->change_visibility();
				$settings = str_replace( '[GITHUB_LINK]', '<a target="_blank" href="https://github.com/' . $options['github-user'] . '/' . $options['github-repository'] . '">github.com/' . $options['github-user'] . '/' . $options['github-repository'] . '</a>', $settings );

				// Show delete.
				$settings = str_replace( '[GITHUB_DELETE]', '<a class="button button-secondary" name="delete" id="github-delete">' . esc_html__( 'Delete Repository', 'simply-static-pro' ) . '</a>', $settings );
				$settings = str_replace( '[GITHUB_ADD]', '', $settings );
			} else {
				if ( ! empty( $options['github-user'] ) && ! empty( $options['github-personal-access-token'] ) ) {
					$settings = str_replace( '[GITHUB_ADD]', '<a class="button button-primary" name="add" id="github-add">' . esc_html__( 'Add Repository', 'simply-static-pro' ) . '</a>', $settings );
				} else {
					$settings = str_replace( '[GITHUB_ADD]', '', $settings );
				}

				$settings = str_replace( '[GITHUB_LINK]', __( 'There is currently no repository.', 'simply-static-pro' ), $settings );
				$settings = str_replace( '[GITHUB_DELETE]', '', $settings );
			}
		} else {
			if ( ! empty( $options['github-user'] ) && ! empty( $options['github-personal-access-token'] ) ) {
				$settings = str_replace( '[GITHUB_ADD]', '<a class="button button-primary" name="add" id="github-add">' . esc_html__( 'Add Repository', 'simply-static-pro' ) . '</a>', $settings );
			} else {
				$settings = str_replace( '[GITHUB_ADD]', '', $settings );
			}

			$settings = str_replace( '[GITHUB_LINK]', __( 'There is currently no repository.', 'simply-static-pro' ), $settings );
			$settings = str_replace( '[GITHUB_DELETE]', '', $settings );
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

		$options['cdn-api-key']                  = $ss->fetch_post_value( 'cdn-api-key' );
		$options['cdn-storage-host']             = $ss->fetch_post_value( 'cdn-storage-host' );
		$options['cdn-access-key']               = $ss->fetch_post_value( 'cdn-access-key' );
		$options['cdn-pull-zone']                = $ss->fetch_post_value( 'cdn-pull-zone' );
		$options['cdn-storage-zone']             = $ss->fetch_post_value( 'cdn-storage-zone' );
		$options['cdn-directory']                = $ss->fetch_post_value( 'cdn-directory' );
		$options['cdn-404']                      = $ss->fetch_post_value( 'cdn-404' );
		$options['github-user']                  = $ss->fetch_post_value( 'github-user' );
		$options['github-email']                 = $ss->fetch_post_value( 'github-email' );
		$options['github-personal-access-token'] = $ss->fetch_post_value( 'github-personal-access-token' );
		$options['github-repository']            = $ss->fetch_post_value( 'github-repository' );
		$options['github-repository-visibility'] = $ss->fetch_post_value( 'github-repository-visibility' );
		$options['github-branch']                = $ss->fetch_post_value( 'github-branch' );
		$options['github-webhook-url']           = $ss->fetch_post_value( 'github-webhook-url' );

		return $options;
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @return void
	 */
	public function add_admin_scripts() {
		wp_enqueue_script( 'ssp-deployment-admin', SIMPLY_STATIC_PRO_URL . '/assets/ssp-deployment-admin.js', array( 'jquery' ), '1.1.1', true );

		wp_localize_script(
			'ssp-deployment-admin',
			'sspd_ajax',
			array(
				'ajax_url'     => admin_url() . 'admin-ajax.php',
				'delete_nonce' => wp_create_nonce( 'ssp-delete-repository' ),
				'add_nonce'    => wp_create_nonce( 'ssp-add-repository' ),
			)
		);
	}

	/**
	 * Add tasks to Simply Static task list.
	 *
	 * @param array  $task_list current task list.
	 * @param string $delivery_method current delivery method.
	 * @return array
	 */
	public function modify_task_list( $task_list, $delivery_method ) {
		if ( 'github' === $delivery_method ) {
			$task_list = array( 'setup', 'fetch_urls', 'github_commit', 'wrapup' );
			return $task_list;
		}

		if ( 'cdn' === $delivery_method ) {
			$task_list = array( 'setup', 'fetch_urls', 'bunny_deploy', 'wrapup' );
			return $task_list;
		}
		return $task_list;
	}

	/**
	 * Modify task class name in Simply Static.
	 *
	 * @param string $class_name current class name.
	 * @param string $task_name current task name.
	 * @return string
	 */
	public function check_class_name( $class_name, $task_name ) {
		if ( 'github_commit' === $task_name ) {
			return 'simply_static_pro\\' . ucwords( $task_name ) . '_Task';
		}

		if ( 'bunny_deploy' === $task_name ) {
			return 'simply_static_pro\\' . ucwords( $task_name ) . '_Task';
		}
		return $class_name;
	}

	/**
	 * Modify commit message to prevent auto deploys.
	 *
	 * @param  string $message given commit message.
	 * @return string
	 */
	public function maybe_modify_commit_message( $message ) {
		$options = get_option( 'simply-static' );

		if ( ! empty( $options['github-webhook-url'] ) && false !== strpos( $options['github-webhook-url'], 'netlify' ) ) {
			return '[skip netlify]' . $message;
		}

		return $message;
	}
}
