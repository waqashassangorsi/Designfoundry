<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Single_Lesson' ) ) {
	class Edumall_Single_Lesson {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'body_class', [ $this, 'body_class' ] );

			add_filter( 'edumall_title_bar_type', [ $this, 'setup_title_bar' ] );
		}

		public function body_class( $classes ) {
			if ( Edumall_Tutor::instance()->is_single_lessons() ) {
				$enable_spotlight_mode = tutor_utils()->get_option( 'enable_spotlight_mode' );

				if ( '1' === $enable_spotlight_mode ) {
					$classes [] = 'lesson-spotlight-mode';
				}
			}

			return $classes;
		}

		public function setup_title_bar( $type ) {
			if ( Edumall_Tutor::instance()->is_single_lessons() ) {
				return '05';
			}

			return $type;
		}
	}
}
