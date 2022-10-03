<?php

namespace simply_static_pro;

use Simply_Static;

/**
 * Class which handles GitHub commits.
 */
class Github_Commit_Task extends Simply_Static\Task {
	/**
	 * The task name.
	 *
	 * @var string
	 */
	protected static $task_name = 'github_commit';

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
		$github_file = Github_File::get_instance();
		$batch_size  = apply_filters( 'simply_static_commit_files_batch_size', 50 );

		$static_pages = Simply_Static\Page::query()
			->where( 'last_transferred_at < ? OR last_transferred_at IS NULL', $this->start_time )
			->limit( $batch_size )
			->find();

		$pages_remaining = Simply_Static\Page::query()
			->where( 'last_transferred_at < ? OR last_transferred_at IS NULL', $this->start_time )
			->count();

		$total_pages     = Simply_Static\Page::query()->count();
		$pages_processed = $total_pages - $pages_remaining;

		Simply_Static\Util::debug_log( 'Total files: ' . $total_pages . '; Files remaining: ' . $pages_remaining );

		while ( $static_page = array_shift( $static_pages ) ) {
			// Check if it's only a directory.
			$path_info = Simply_Static\Util::url_path_info( $static_page->file_path );
			$file_path = apply_filters( 'ssp_github_file_path', $static_page->file_path );

			if ( '' !== $path_info['extension'] && '' !== $path_info['filename'] ) {
				$content = file_get_contents( $this->temp_dir . $static_page->file_path, true );

				Simply_Static\Util::debug_log( 'Try to commit file: ' . $file_path );

				// Try update.
				$commit_message = apply_filters( 'ssp_github_commit_message', 'Updated ' . $this->options->get( 'archive_name' ) );

				$file_updated = $github_file->update_file( $file_path, $content, $commit_message );

				if ( ! $file_updated ) {
					// Create file.
					$commit_message = apply_filters( 'ssp_github_commit_message', 'Added ' . $this->options->get( 'archive_name' ) );
					$file_created   = $github_file->add_file( $file_path, $content, $commit_message );
					Simply_Static\Util::debug_log( 'File was missing created: ' . $file_path );
				}
			}

			$static_page->last_transferred_at = Simply_Static\Util::formatted_datetime();
			$static_page->save();
			continue;
		}

		$message = sprintf( __( 'Comitted / Updated %d of %d pages/files', 'simply-static-pro' ), $pages_processed, $total_pages );
		$this->save_status_message( $message );

		// if we haven't processed any additional pages, we're done.
		if ( $pages_remaining == 0 ) {
			$this->notify_webhook();
		}

		return $pages_remaining == 0;
	}

	/**
	 * Notify external Webhook after Simply Static finished static export.
	 *
	 * @return void
	 */
	public function notify_webhook() {
		$options = get_option( 'simply-static' );

		if ( empty( $options['github-webhook-url'] ) ) {
			return;
		}

		$webhook_args = apply_filters( 'ssp_webhook_args', array() );

		wp_remote_post( esc_url( $options['github-webhook-url'] ), $webhook_args );
	}
}
