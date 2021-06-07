<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Event_Enqueue' ) ) {
	class Edumall_Event_Enqueue extends Edumall_Event {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ], 15 );
		}

		public function frontend_scripts() {
			/**
			 * Dequeue because plugin prefix & enqueue all pages.
			 */
			wp_dequeue_script( 'wpems-owl-carousel-js' );
			wp_dequeue_style( 'wpems-owl-carousel-css' );

			wp_dequeue_script( 'wpems-magnific-popup-js' );
			wp_dequeue_style( 'wpems-magnific-popup-css' );

			wp_register_style( 'edumall-events-manager', EDUMALL_THEME_URI . '/events-manager.css', null, EDUMALL_THEME_VERSION );

			wp_enqueue_style( 'edumall-events-manager' );

			if ( $this->is_single() ) {
				wp_enqueue_style( 'magnific-popup' );
				wp_enqueue_script( 'magnific-popup' );

				wp_enqueue_script( 'edumall-quantity-button' );
			}
		}
	}
}
