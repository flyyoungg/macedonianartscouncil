<?php

namespace simply_static_pro;

use Simply_Static;

/**
 * Class which handles GitHub commits.
 */
class Bunny_Deploy_Task extends Simply_Static\Task {
	/**
	 * The task name.
	 *
	 * @var string
	 */
	protected static $task_name = 'bunny_deploy';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$options = Simply_Static\Options::instance();

		$this->options    = $options;
		$this->temp_dir   = $options->get_archive_dir();
		$this->start_time = $options->get( 'archive_start_time' );
	}

	/**
	 * Perform action to run on commit task.
	 *
	 * @return bool
	 */
	public function perform() {
		// Setup BunnyCDN client.
		$bunny_updater = Bunny_Updater::get_instance();

		// Sub directory?
		$options  = get_option( 'simply-static' );
		$cdn_path = apply_filters( 'ssp_cdn_path', '' );

		if ( ! empty( $options['cdn-directory'] ) ) {
			$cdn_path = $options['cdn-directory'] . '/';
		}

		$message = __( 'Starts to transfer of pages/files to CDN', 'simply-static-pro' );
		$this->save_status_message( $message );

		// Upload directory.
		$iterator = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $this->temp_dir, \RecursiveDirectoryIterator::SKIP_DOTS ) );
		$counter  = 0;

		foreach ( $iterator as $file_name => $file_object ) {
			if ( ! realpath( $file_name ) ) {
				continue;
			}

			$file_path     = realpath( $file_name );
			$relative_path = str_replace( $this->temp_dir, $cdn_path, $file_path );

			$bunny_updater->upload_file( $file_path, $relative_path );

			$counter++;
		}

		$message = sprintf( __( 'Pushed %d pages/files to CDN', 'simply-static-pro' ), $counter );
		$this->save_status_message( $message );

		// Maybe add 404.
		if ( ! empty( $options['cdn-404'] ) && realpath( $this->temp_dir . untrailingslashit( $options['cdn-404'] ) . DIRECTORY_SEPARATOR . 'index.html' ) ) {

			// Rename and copy file.
			$src_error_file = $this->temp_dir . untrailingslashit( $options['cdn-404'] ) . DIRECTORY_SEPARATOR . 'index.html';
			$dst_error_file = $this->temp_dir . 'bunnycdn_errors/404.html';

			mkdir( dirname( $dst_error_file ), 0777, true );
			copy( $src_error_file, $dst_error_file );

			// Upload 404 template file.
			$error_file_path     = realpath( $this->temp_dir . 'bunnycdn_errors/404.html' );
			$error_relative_path = str_replace( $this->temp_dir, '', $error_file_path );

			if ( $error_file_path ) {
				$bunny_updater->upload_file( $error_file_path, $error_relative_path );
			}
		}

		// Clear Pull zone cache.
		$bunny_updater->purge_cache();
		return true;
	}
}
