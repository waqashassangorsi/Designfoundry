<?php
defined( 'ABSPATH' ) || exit;

/**
 * Templates & hooks for Dashboard page.
 */
if ( ! class_exists( 'Edumall_Tutor_Dashboard' ) ) {
	class Edumall_Tutor_Dashboard {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'body_class', [ $this, 'body_class' ] );
			add_filter( 'edumall_top_bar_type', [ $this, 'hide_top_bar' ] );
			add_filter( 'edumall_header_type', [ $this, 'hide_header' ] );
			add_filter( 'edumall_title_bar_type', [ $this, 'hide_title_bar' ] );
			add_filter( 'edumall_footer', [ $this, 'hide_footer' ] );
			add_filter( 'edumall_sidebar_1', [ $this, 'hide_sidebar' ] );
		}

		public function body_class( $classes ) {
			global $wp_query;

			$dashboard_page_slug = '';
			$dashboard_page_name = '';
			if ( isset( $wp_query->query_vars['tutor_dashboard_page'] ) && $wp_query->query_vars['tutor_dashboard_page'] ) {
				$dashboard_page_slug = $wp_query->query_vars['tutor_dashboard_page'];
				$dashboard_page_name = $wp_query->query_vars['tutor_dashboard_page'];
			}

			/**
			 * Getting dashboard sub pages
			 */
			if ( isset( $wp_query->query_vars['tutor_dashboard_sub_page'] ) && $wp_query->query_vars['tutor_dashboard_sub_page'] ) {
				if ( ! empty( $dashboard_page_slug ) ) {
					$dashboard_page_slug = $dashboard_page_slug . '-' . $wp_query->query_vars['tutor_dashboard_sub_page'];
				}
			}

			if ( Edumall_Tutor::instance()->is_dashboard() ) {
				$classes[] = "dashboard-{$dashboard_page_slug}-page";

				if ( is_user_logged_in() && 'create-course' !== $dashboard_page_name ) {
					$classes[] = 'dashboard-page';

					if ( ! Edumall::is_handheld() ) {
						$classes[] = 'dashboard-nav-fixed';
					}
				} else {
					$classes[] = 'required-login';
				}
			}

			return $classes;
		}

		public function hide_top_bar( $type ) {
			if ( Edumall_Tutor::instance()->is_dashboard() && is_user_logged_in() ) {
				return 'none';
			}

			return $type;
		}

		public function hide_header( $type ) {
			if ( Edumall_Tutor::instance()->is_dashboard() && is_user_logged_in() ) {
				return 'none';
			}

			return $type;
		}

		public function hide_title_bar( $type ) {
			if ( Edumall_Tutor::instance()->is_dashboard() ) {
				if ( is_user_logged_in() ) {
					return 'none';
				} else {
					return '05';
				}
			}

			return $type;
		}

		public function hide_footer( $type ) {
			if ( Edumall_Tutor::instance()->is_dashboard() && is_user_logged_in() ) {
				return 'none';
			}

			return $type;
		}

		public function hide_sidebar( $type ) {
			if ( Edumall_Tutor::instance()->is_dashboard() && is_user_logged_in() ) {
				return 'none';
			}

			return $type;
		}
	}
}
