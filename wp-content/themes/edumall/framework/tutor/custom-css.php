<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Tutor_Custom_Css' ) ) {
	class Edumall_Tutor_Custom_Css {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'edumall_customize_output_button_typography_selectors', [
				$this,
				'customize_output_button_typography_selectors',
			] );

			add_filter( 'edumall_customize_output_button_selectors', [
				$this,
				'customize_output_button_selectors',
			] );

			add_filter( 'edumall_customize_output_button_hover_selectors', [
				$this,
				'customize_output_button_hover_selectors',
			] );

			add_filter( 'edumall_customize_output_form_input_selectors', [
				$this,
				'customize_output_form_input_selectors',
			] );

			add_filter( 'edumall_customize_output_form_input_focus_selectors', [
				$this,
				'customize_output_form_input_focus_selectors',
			] );
		}

		public function customize_output_button_typography_selectors( $selectors ) {
			$new_selectors = [ '.single_add_to_cart_button, a.tutor-button, .tutor-button, a.tutor-btn, .tutor-btn' ];

			$final_selectors = array_merge( $selectors, $new_selectors );

			return $final_selectors;
		}

		public function customize_output_button_selectors( $selectors ) {
			$new_selectors = [ '.single_add_to_cart_button, a.tutor-button, .tutor-button, a.tutor-btn, .tutor-btn, .tutor-button.tutor-success' ];

			$final_selectors = array_merge( $selectors, $new_selectors );

			return $final_selectors;
		}

		public function customize_output_button_hover_selectors( $selectors ) {
			$new_selectors = [ '.single_add_to_cart_button:hover, a.tutor-button:hover, .tutor-button:hover, a.tutor-btn:hover, .tutor-btn:hover, .tutor-button.tutor-success:hover' ];

			$final_selectors = array_merge( $selectors, $new_selectors );

			return $final_selectors;
		}

		public function customize_output_form_input_selectors( $selectors ) {
			$new_selectors = [ '.tutor-option-field textarea, .tutor-option-field select, .tutor-option-field input[type=text], .tutor-option-field input[type=number], .tutor-option-field input[type="pas.tutor-dashboard-content-innersword"], .tutor-form-group textarea, .tutor-form-group select, .tutor-form-group input[type=text], .tutor-form-group input[type=number], .tutor-form-group input[type=password]' ];

			$final_selectors = array_merge( $selectors, $new_selectors );

			return $final_selectors;
		}

		public function customize_output_form_input_focus_selectors( $selectors ) {
			$new_selectors = [ '.tutor-option-field textarea:focus, .tutor-option-field input:not([type=submit]):focus, .tutor-form-group textarea:focus, .tutor-form-group input:not([type=submit]):focus' ];

			$final_selectors = array_merge( $selectors, $new_selectors );

			return $final_selectors;
		}
	}
}
