<?php
defined( 'ABSPATH' ) || exit;

/**
 * Abstract Class: Course Layered Nav Base
 *
 * @version  1.0
 * @extends  WP_Widget
 */
if ( ! class_exists( 'Edumall_Course_Layered_Nav_Base' ) ) {
	abstract class Edumall_Course_Layered_Nav_Base extends Edumall_WP_Widget_Base {
		/**
		 * Return the currently viewed taxonomy name.
		 *
		 * @return string
		 */
		protected function get_current_taxonomy() {
			return is_tax() ? get_queried_object()->taxonomy : '';
		}

		/**
		 * Return the currently viewed term ID.
		 *
		 * @return int
		 */
		protected function get_current_term_id() {
			return absint( is_tax() ? get_queried_object()->term_id : 0 );
		}

		/**
		 * Return the currently viewed term slug.
		 *
		 * @return int
		 */
		protected function get_current_term_slug() {
			return absint( is_tax() ? get_queried_object()->slug : 0 );
		}
	}
}
