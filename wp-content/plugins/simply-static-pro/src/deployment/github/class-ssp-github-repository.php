<?php

namespace simply_static_pro;

/**
 * Class to handle Github repositories.
 */
class Github_Repository {
	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Contains new Github client.
	 *
	 * @var object
	 */
	public $client;

	/**
	 * Contains the user name.
	 *
	 * @var string
	 */
	public $user;

	/**
	 * Contains the repository name.
	 *
	 * @var string
	 */
	public $repository;

	/**
	 * Contains the branch name.
	 *
	 * @var string
	 */
	public $branch;

	/**
	 * Contains the visibility of the repository.
	 *
	 * @var string
	 */
	public $visibility;

	/**
	 * Returns instance of FILR_Folder.
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
	 * Constructor for repository class.
	 */
	public function __construct() {
		$options = get_option( 'simply-static' );

		if ( ! empty( $options['github-personal-access-token'] ) ) {
			$client  = new \Github\Client();

			// Authenticate.
			$client->authenticate( $options['github-personal-access-token'], $client::AUTH_ACCESS_TOKEN );

			// Setup data.
			$this->client     = $client;
			$this->user       = $options['github-user'];
			$this->repository = $options['github-repository'];
			$this->branch     = $options['github-branch'];
			$this->visibility = $options['github-repository-visibility'];
		}
	}

	/**
	 * Add the repository with Ajax.
	 */
	public function add_repository() {
		// check nonce.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ssp-add-repository' ) ) {
			$response = array( 'message' => 'Security check failed.', 'error' => true );
			print wp_json_encode( $response );
			exit;
		}

		try {
			$this->client->api( 'repo' )->create( $this->repository, __( 'This repository was created with Simply Static Pro', 'simply-static-pro' ), '', true );
			$response = array( 'message' => __( 'Repository was successfully created.', 'simply-static-pro' ) );

			// We should update the options before going further.
			$options = get_option( 'simply-static' );

			$options['github-repository-created'] = 'yes';
			update_option( 'simply-static', $options );

			// Now we can exit Ajax.
			print wp_json_encode( $response );
			exit;
		} catch ( \Github\Exception\ValidationFailedException $e ) {
			$response = array( 'message' => $e->getMessage(), 'error' => true );
			print wp_json_encode( $response );
			exit;
		} catch ( \Github\Exception\RuntimeException $e ) {
			$response = array( 'message' => $e->getMessage(), 'error' => true );
			print wp_json_encode( $response );
			exit;
		} catch ( \Github\Exception\ApiLimitExceedException $e ) {
			$response = array( 'message' => $e->getMessage(), 'error' => true );
			print wp_json_encode( $response );
			exit;
		}
	}

	/**
	 * Delete the repository with Ajax.
	 */
	public function delete_repository() {
		// check nonce.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ssp-delete-repository' ) ) {
			$response = array( 'message' => 'Security check failed.', 'error' => true );
			print wp_json_encode( $response );
			exit;
		}

		// Try to delete repository.
		try {
			$this->client->api( 'repo' )->remove( $this->user, $this->repository );
			$response = array( 'message' => __( 'Repository was successfully deleted.', 'simply-static-pro' ) );

			// We should reset the options before going further.
			$options = get_option( 'simply-static' );

			$options['github-repository']            = '';
			$options['github-repository-created']    = '';
			$options['github-repository-visibility'] = 'public';

			update_option( 'simply-static', $options );

			// Now we can exit Ajax.
			print wp_json_encode( $response );
			exit;
		} catch ( \Github\Exception\ValidationFailedException $e ) {
			$response = array( 'message' => $e->getMessage(), 'error' => true );
			print wp_json_encode( $response );
			exit;
		} catch ( \Github\Exception\RuntimeException $e ) {
			$response = array( 'message' => $e->getMessage(), 'error' => true );
			print wp_json_encode( $response );
			exit;
		} catch ( \Github\Exception\ApiLimitExceedException $e ) {
			$response = array( 'message' => $e->getMessage(), 'error' => true );
			print wp_json_encode( $response );
			exit;
		}
	}

	/**
	 * Change visibility of a repository.
	 *
	 * @return string
	 */
	public function change_visibility() {
		if ( 'private' === $this->visibility ) {
			try {
				$this->client->api( 'repo' )->update( $this->user, $this->repository, array( 'private' => true ) );
				return true;
			} catch ( \Github\Exception\ValidationFailedException $e ) {
				return $e->getMessage();
			} catch ( \Github\Exception\RuntimeException $e ) {
				return $e->getMessage();
			} catch ( \Github\Exception\ApiLimitExceedException $e ) {
				return $e->getMessage();
			}
		} else {
			try {
				$this->client->api( 'repo' )->update( $this->user, $this->repository, array( 'private' => false ) );
				return true;
			} catch ( \Github\Exception\ValidationFailedException $e ) {
				return $e->getMessage();
			} catch ( \Github\Exception\RuntimeException $e ) {
				return $e->getMessage();
			} catch ( \Github\Exception\ApiLimitExceedException $e ) {
				return $e->getMessage();
			}
		}
	}
}
