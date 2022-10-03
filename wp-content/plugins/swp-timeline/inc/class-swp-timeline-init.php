<?php

/**
 * @package timeline builder
 * @sicne 1.0.0
 * */

if (!class_exists('Swp_Timeline_Init')) {

	class Swp_Timeline_Init
	{

		//instance
		protected static $instance;

		public function __construct()
		{
			//plugin_assets
			add_action('wp_enqueue_scripts', array($this, 'plugin_assets'), 99);

			//load plugin dependency files
			$this->load_plugin_dependency_files();
		}



		/**
		 * plugin_assets()
		 * @since 1.0.0
		 * */
		public function plugin_assets()
		{
			$this->load_plugin_css();
			$this->load_plugin_js();
		}

		/*
		*ele blog default font
		*/
		public static function swp_timeline_fonts_url()
		{
			$fonts_url = '';
			$font_families = array();

			if ('off' !== esc_html_x('on', 'Google font: on or off', 'swp-timeline')) :
				$font_families[] = 'Inter:300,400,500,600,700&display=swap';
				$font_families[] = 'Rubik:400,400i,500,600,700,800display=swap';
				$query_args = array(
					'family'  => urlencode(implode('|', $font_families)),
					'display' => 'swap',
				);
				$fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
			endif;

			return esc_url_raw($fonts_url);
		}


		/**
		 * load plugin css
		 * @since 1.0.0
		 * */
		public function load_plugin_css()
		{

			wp_enqueue_style('swp-timeline-font', self::swp_timeline_fonts_url(), array(), SWP_TIMELINE_VERSION, 'all');
			wp_enqueue_style('swp-timeline-grid', SWP_TIMELINE_CSS . '/swp-grid.css', array(), SWP_TIMELINE_VERSION, 'all');
			wp_enqueue_style('magnific', SWP_TIMELINE_CSS . '/magnific.min.css', array(), SWP_TIMELINE_VERSION, 'all');
			wp_enqueue_style('owl', SWP_TIMELINE_CSS . '/owl.min.css', array(), SWP_TIMELINE_VERSION, 'all');
			wp_enqueue_style('fontawesome', SWP_TIMELINE_CSS . '/fontawesome.min.css', array(), SWP_TIMELINE_VERSION, 'all');
			wp_enqueue_style('swp-timeline-global', SWP_TIMELINE_CSS . '/swp-global.css', array(), SWP_TIMELINE_VERSION, 'all');
			wp_enqueue_style('swp-timeline-main', SWP_TIMELINE_CSS . '/swp-styles.css', array(), SWP_TIMELINE_VERSION, 'all');
		}

		/**
		 * load plugin js
		 * @since 1.0.0
		 * */
		public function load_plugin_js()
		{

			wp_enqueue_script('owl-carousel', SWP_TIMELINE_JS . '/owl.carousel.min.js', array('jquery'), SWP_TIMELINE_VERSION, true);
			wp_enqueue_script('imagesloaded', SWP_TIMELINE_JS . '/imagesloaded.pkgd.min.js', array('jquery'), SWP_TIMELINE_VERSION, true);
			wp_enqueue_script('magnific', SWP_TIMELINE_JS . '/magnific.min.js', array('jquery'), SWP_TIMELINE_VERSION, true);
			wp_enqueue_script('isotope', SWP_TIMELINE_JS . '/isotope.pkgd.min.js', array('jquery'), SWP_TIMELINE_VERSION, true);
			wp_enqueue_script('swp-timeline-main', SWP_TIMELINE_JS . '/swp-main.js', array('jquery'), SWP_TIMELINE_VERSION, true);
			wp_enqueue_script('swp-timeline-elementor-script', SWP_TIMELINE_JS . '/elementor-script.js', array('jquery'), SWP_TIMELINE_VERSION, true);
			wp_localize_script('swp-timeline-elementor-script', 'swp_timeline_localize', array('ajax_url' => admin_url('admin-ajax.php')));
		}


		/**
		 * load_plugin_dependency_files()
		 * @since 1.0.0
		 * */
		public function load_plugin_dependency_files()
		{


			$includes_files = array(
				array(
					'file-name' => 'swp-timeline-elementor-widgets-init',
					'file-path' => SWP_TIMELINE_ELEMENTOR
				),
				array(
					'file-name' => 'functions',
					'file-path' => SWP_TIMELINE_INC
				),
			);
			if (is_array($includes_files) && !empty($includes_files)) {
				foreach ($includes_files as $file) {
					if (file_exists($file['file-path'] . '/' . $file['file-name'] . '.php')) {
						require $file['file-path'] . '/' . $file['file-name'] . '.php';
					}
				}
			}
		}
	} //end class

	new Swp_Timeline_Init();
}