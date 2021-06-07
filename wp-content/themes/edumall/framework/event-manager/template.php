<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Event_Template' ) ) {
	class Edumall_Event_Template extends Edumall_Event {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'template_include', [ $this, 'load_event_archive_template' ], 99 );
		}

		/**
		 * Fixed archive event load archive.php instead of archive-event.php
		 * when has some query strings...
		 *
		 * @param string $template Path to template file.
		 *
		 * @return string Path to template file.
		 */
		public function load_event_archive_template( $template ) {
			global $wp_query;
			$post_type      = get_query_var( 'post_type' );
			$event_category = get_query_var( $this->get_tax_category() );

			if ( ( $post_type === $this->get_event_type() || ! empty( $event_category ) ) && $wp_query->is_archive ) {
				$file   = 'archive-event.php';
				$find[] = $file;
				$find[] = wpems_template_path() . '/' . $file;

				$template = locate_template( array_unique( $find ) );
				if ( ! $template ) {
					$template = untrailingslashit( WPEMS_PATH ) . '/templates/' . $file;
				}

				return $template;
			}

			return $template;
		}
	}
}
