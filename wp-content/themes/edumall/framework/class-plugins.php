<?php
defined( 'ABSPATH' ) || exit;

/**
 * Plugin installation and activation for WordPress themes
 */
if ( ! class_exists( 'Edumall_Register_Plugins' ) ) {
	class Edumall_Register_Plugins {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_filter( 'insight_core_tgm_plugins', array( $this, 'register_required_plugins' ) );
		}

		public function register_required_plugins( $plugins ) {
			/*
			 * Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */
			$new_plugins = array(
				array(
					'name'     => esc_html__( 'Insight Core', 'edumall' ),
					'slug'     => 'insight-core',
					'source'   => 'https://www.dropbox.com/s/ggihxceeo04q9xp/insight-core-1.7.6.zip?dl=1',
					'version'  => '1.7.6',
					'required' => true,
				),
				array(
					'name'     => esc_html__( 'Edumall Addons', 'edumall' ),
					'slug'     => 'edumall-addons',
					'source'   => 'https://www.dropbox.com/s/d7gibuxon1ydjc7/edumall-addons-1.0.0.zip?dl=1',
					'version'  => '1.0.0',
					'required' => true,
				),
				array(
					'name'     => esc_html__( 'Elementor', 'edumall' ),
					'slug'     => 'elementor',
					'required' => true,
				),
				array(
					'name'     => esc_html__( 'Elementor Pro', 'edumall' ),
					'slug'     => 'elementor-pro',
					'source'   => 'https://www.dropbox.com/s/ufbwne7jx50m8wx/elementor-pro-3.0.5.zip?dl=1',
					'version'  => '3.0.5',
					'required' => true,
				),
				array(
					'name'    => esc_html__( 'Revolution Slider', 'edumall' ),
					'slug'    => 'revslider',
					'source'  => 'https://www.dropbox.com/s/anj8r2m6jtjxfv4/revslider.zip?dl=1',
					'version' => '6.2.23',
				),
				array(
					'name' => esc_html__( 'Tutor LMS', 'edumall' ),
					'slug' => 'tutor',
				),
				array(
					'name'    => esc_html__( 'Tutor LMS Pro', 'edumall' ),
					'slug'    => 'tutor-pro',
					'source'  => 'https://www.dropbox.com/s/diznqa6uk96mc4y/tutor-pro-1.7.5.zip?dl=1',
					'version' => '1.7.5',
				),
				array(
					'name' => esc_html__( 'Paid Memberships Pro', 'edumall' ),
					'slug' => 'paid-memberships-pro',
				),
				array(
					'name' => esc_html__( 'WP Events Manager', 'edumall' ),
					'slug' => 'wp-events-manager',
				),
				array(
					'name' => esc_html__( 'Video Conferencing with Zoom', 'edumall' ),
					'slug' => 'video-conferencing-with-zoom-api',
				),
				array(
					'name' => esc_html__( 'BuddyPress', 'edumall' ),
					'slug' => 'buddypress',
				),
				array(
					'name' => esc_html__( 'MediaPress', 'edumall' ),
					'slug' => 'mediapress',
				),
				array(
					'name' => esc_html__( 'WordPress Social Login', 'edumall' ),
					'slug' => 'miniorange-login-openid',
				),
				array(
					'name' => esc_html__( 'Contact Form 7', 'edumall' ),
					'slug' => 'contact-form-7',
				),
				array(
					'name' => esc_html__( 'MailChimp for WordPress', 'edumall' ),
					'slug' => 'mailchimp-for-wp',
				),
				array(
					'name' => esc_html__( 'WooCommerce', 'edumall' ),
					'slug' => 'woocommerce',
				),
				array(
					'name' => esc_html__( 'WPC Smart Compare for WooCommerce', 'edumall' ),
					'slug' => 'woo-smart-compare',
				),
				array(
					'name' => esc_html__( 'WPC Smart Wishlist for WooCommerce', 'edumall' ),
					'slug' => 'woo-smart-wishlist',
				),
				array(
					'name'    => esc_html__( 'Insight Swatches', 'edumall' ),
					'slug'    => 'insight-swatches',
					'source'  => 'https://www.dropbox.com/s/3mkswh3so7syfg3/insight-swatches-1.1.0.zip?dl=1',
					'version' => '1.1.0',
				),
				array(
					'name' => esc_html__( 'WP-PostViews', 'edumall' ),
					'slug' => 'wp-postviews',
				),
			);

			$plugins = array_merge( $plugins, $new_plugins );

			return $plugins;
		}

	}

	Edumall_Register_Plugins::instance()->initialize();
}
