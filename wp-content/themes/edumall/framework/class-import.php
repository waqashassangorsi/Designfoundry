<?php
defined( 'ABSPATH' ) || exit;

/**
 * Initial OneClick import for this theme
 */
if ( ! class_exists( 'Edumall_Import' ) ) {
	class Edumall_Import {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'insight_core_import_demos', array( $this, 'import_demos' ) );
			add_filter( 'insight_core_import_generate_thumb', array( $this, 'generate_thumbnail' ) );
		}

		public function import_demos() {
			$import_img_url = EDUMALL_THEME_URI . '/assets/import';

			return array(
				'main'    => array(
					'screenshot' => EDUMALL_THEME_URI . '/screenshot.jpg',
					'name'       => esc_html__( 'Main', 'edumall' ),
					'url'        => 'https://api.thememove.com/import/edumall/edumall-insightcore-main-1.0.0.zip',
				),
			);
		}

		/**
		 * Generate thumbnail while importing
		 */
		function generate_thumbnail() {
			return false;
		}
	}

	Edumall_Import::instance()->initialize();
}
