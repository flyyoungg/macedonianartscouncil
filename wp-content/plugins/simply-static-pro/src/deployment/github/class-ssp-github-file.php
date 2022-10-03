<?php

namespace simply_static_pro;

/**
 * Class to handle Github repositories.
 */
class Github_File {
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
	private $client;

	/**
	 * Contains the user name.
	 *
	 * @var string
	 */
	private $user;

	/**
	 * Contains the name of the repository.
	 *
	 * @var string
	 */
	private $repository;

	/**
	 * Contains the name of the branch.
	 *
	 * @var string
	 */
	private $branch;

	/**
	 * Contains the committer with name and email as array.
	 *
	 * @var array
	 */
	private $committer;

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
	 * Constructor for file class.
	 */
	public function __construct() {
		$options = get_option( 'simply-static' );
		$client  = new \Github\Client();

		// Authenticate.
		$client->authenticate( $options['github-personal-access-token'], $client::AUTH_ACCESS_TOKEN );

		$this->client     = $client;
		$this->user       = $options['github-user'];
		$this->repository = $options['github-repository'];
		$this->branch     = $options['github-branch'];
		$this->committer  = array( 'name' => $options['github-user'], 'email' => $options['github-email'] );
	}

	/**
	 * Add file to repository.
	 *
	 * @param string $filename file name for the file to create.
	 * @param string $file_content file content as string (file_get_contents()).
	 * @param string $commit_message a commit message.
	 * @return bool
	 */
	public function add_file( $filename, $file_content, $commit_message ) {
		try {
			$this->client->api( 'repo' )->contents()->create( $this->user, $this->repository, $filename, $file_content, $commit_message, $this->branch, $this->committer );
			return true;
		} catch ( \Github\Exception\ValidationFailedException $e ) {
			return $e->getMessage();
		} catch ( \Github\Exception\RuntimeException $e ) {
			return $e->getMessage();
		} catch ( \Github\Exception\ApiLimitExceedException $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Update a file in a repository.
	 *
	 * @param string $filename file name for the file to create.
	 * @param string $file_content file content as string (file_get_contents()).
	 * @param string $commit_message a commit message.
	 * @return bool
	 */
	public function update_file( $filename, $file_content, $commit_message ) {
		$old_file = $this->get_file( $filename );

		if ( ! empty( $old_file['sha'] ) ) {
			try {
				$this->client->api( 'repo' )->contents()->update( $this->user, $this->repository, $filename, $file_content, $commit_message, $old_file['sha'], $this->branch, $this->committer );
				return true;
			} catch ( \Github\Exception\ValidationFailedException $e ) {
				return $e->getMessage();
			} catch ( \Github\Exception\RuntimeException $e ) {
				return $e->getMessage();
			} catch ( \Github\Exception\ApiLimitExceedException $e ) {
				return $e->getMessage();
			}
		}
		return false;
	}
	/**
	 * Delete a file in a repository.
	 *
	 * @param string $filename file name for the file to create.
	 * @param string $commit_message a commit message.
	 * @return bool
	 */
	public function delete_file( $filename, $commit_message ) {
		$old_file = $this->get_file( $filename );

		if ( ! empty( $old_file['sha'] ) ) {
			try {
				$this->client->api( 'repo' )->contents()->rm( $this->user, $this->repository, $filename, $commit_message, $old_file['sha'], $this->branch, $this->committer );
			} catch ( \Github\Exception\ValidationFailedException $e ) {
				return $e->getMessage();
			} catch ( \Github\Exception\RuntimeException $e ) {
				return $e->getMessage();
			} catch ( \Github\Exception\ApiLimitExceedException $e ) {
				return $e->getMessage();
			}
		}
		return false;
	}

	/**
	 * Get a file from a repository.
	 *
	 * @param string $filename file name for the file to create.
	 * @return array|bool
	 */
	public function get_file( $filename ) {
		try {
			return $this->client->api( 'repo' )->contents()->show( $this->user, $this->repository, $filename, $this->branch );
		} catch ( \Github\Exception\ValidationFailedException $e ) {
			return $e->getMessage();
		} catch ( \Github\Exception\RuntimeException $e ) {
			return $e->getMessage();
		} catch ( \Github\Exception\ApiLimitExceedException $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Get reference branch.
	 *
	 * @return array
	 */
	public function get_reference() {
		try {
			return $this->client->api( 'gitData' )->references()->show( $this->user, $this->repository, 'heads/' . $this->branch );
		} catch ( \Github\Exception\ValidationFailedException $e ) {
			return $e->getMessage();
		} catch ( \Github\Exception\RuntimeException $e ) {
			return $e->getMessage();
		} catch ( \Github\Exception\ApiLimitExceedException $e ) {
			return $e->getMessage();
		}
	}
}
