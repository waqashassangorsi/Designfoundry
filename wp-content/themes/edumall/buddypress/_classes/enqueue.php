<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_BP_Enqueue' ) ) {
	class Edumall_BP_Enqueue extends Edumall_BP {

		private static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ], 99 );
		}

		public function frontend_scripts() {
			wp_register_script( 'edumall-buddypress-script', EDUMALL_BP_ASSETS_URI . '/js/frontend.js', null, EDUMALL_THEME_VERSION );

			wp_enqueue_script( 'edumall-buddypress-script' );

			if ( bp_is_user() || bp_is_group() || bp_is_members_component() || bp_is_activity_directory() ) {
				wp_enqueue_script( 'sticky-kit' );
			}
		}
	}

	Edumall_BP_Enqueue::instance()->initialize();
}
