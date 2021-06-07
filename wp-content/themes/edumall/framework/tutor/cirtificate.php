<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Tutor_Certificate' ) ) {
	class Edumall_Tutor_Certificate {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			//add_action('template_include', [$this, 'custom_template_certificate']);
		}
	}
}
