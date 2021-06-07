<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Tutor_Cart' ) ) {
	class Edumall_Tutor_Cart {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			/**
			 * Show course title, thumbnail, link instead of.
			 */
			add_filter( 'woocommerce_cart_item_name', [ $this, 'change_cart_item_name' ], 10, 3 );
			add_filter( 'woocommerce_cart_item_permalink', [ $this, 'change_cart_item_permalink' ], 10, 3 );
			add_filter( 'woocommerce_cart_item_thumbnail', [ $this, 'change_cart_item_thumbnail' ], 10, 3 );
		}

		public function change_cart_item_name( $name, $cart_item, $cart_item_key ) {
			/**
			 * @var WC_Product $_product
			 */
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			$course = Edumall_Tutor::instance()->get_course_by_wc_product( $_product->get_id() );

			if ( ! empty( $course ) ) {
				$name = $course->post_title;
			}

			return $name;
		}

		public function change_cart_item_permalink( $link, $cart_item, $cart_item_key ) {
			/**
			 * @var WC_Product $_product
			 */
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			$course = Edumall_Tutor::instance()->get_course_by_wc_product( $_product->get_id() );

			if ( ! empty( $course ) ) {
				$link = get_permalink( $course );
			}

			return $link;
		}

		public function change_cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
			/**
			 * @var WC_Product $_product
			 */
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			$course = Edumall_Tutor::instance()->get_course_by_wc_product( $_product->get_id() );

			if ( ! empty( $course ) ) {
				$thumbnail = get_the_post_thumbnail( $course, 'thumbnail' );
			}

			return $thumbnail;
		}
	}
}
