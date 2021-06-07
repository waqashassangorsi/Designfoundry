<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_WP_Widget_Course_Price_Filter' ) ) {
	class Edumall_WP_Widget_Course_Price_Filter extends Edumall_Course_Layered_Nav_Base {

		public function __construct() {
			$this->widget_id          = 'edumall-wp-widget-course-price-filter';
			$this->widget_cssclass    = 'edumall-wp-widget-course-price-filter edumall-wp-widget-course-filter';
			$this->widget_name        = esc_html__( '[Edumall] Course Price Filter', 'edumall' );
			$this->widget_description = esc_html__( 'Shows prices in a widget which lets you narrow down the list of courses when viewing courses.', 'edumall' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by price', 'edumall' ),
					'label' => esc_html__( 'Title', 'edumall' ),
				),
				'display_type' => array(
					'type'    => 'select',
					'std'     => 'list',
					'label'   => esc_html__( 'Display type', 'edumall' ),
					'options' => array(
						'list'   => esc_html__( 'List', 'edumall' ),
						'inline' => esc_html__( 'Inline', 'edumall' ),
					),
				),
				'items_count'  => array(
					'type'    => 'select',
					'std'     => 'on',
					'label'   => esc_html__( 'Show items count', 'edumall' ),
					'options' => array(
						'on'  => esc_html__( 'ON', 'edumall' ),
						'off' => esc_html__( 'OFF', 'edumall' ),
					),
				),
			);

			parent::__construct();
		}

		public function widget( $args, $instance ) {
			global $wp_the_query;

			if ( ! $wp_the_query->post_count ) {
				return;
			}

			if ( ! Edumall_Tutor::instance()->is_course_listing() && ! Edumall_Tutor::instance()->is_taxonomy() ) {
				return;
			}

			$this->widget_start( $args, $instance );

			$this->layered_nav_list( $instance );

			$this->widget_end( $args );
		}

		protected function layered_nav_list( $instance ) {
			$items_count  = $this->get_value( $instance, 'items_count' );
			$display_type = $this->get_value( $instance, 'display_type' );

			$class = ' filter-radio-list';
			$class .= ' show-display-' . $display_type;
			$class .= ' show-items-count-' . $items_count;

			$price_options = [
				''     => esc_html__( 'All', 'edumall' ),
				'free' => esc_html__( 'Free', 'edumall' ),
				'paid' => esc_html__( 'Paid', 'edumall' ),
			];

			$filter_name   = 'price_type';
			$base_link     = Edumall_Tutor::instance()->get_course_listing_page_url();
			$base_link     = remove_query_arg( $filter_name, $base_link );
			$current_value = isset( $_GET[ $filter_name ] ) ? Edumall_Helper::data_clean( $_GET[ $filter_name ] ) : '';

			// List display.
			echo '<ul class="' . esc_attr( $class ) . '">';

			foreach ( $price_options as $price_key => $price_name ) {
				$count = $this->get_filtered_course_count( $price_key );

				// Only show options with count > 0.
				if ( empty( $count ) ) {
					continue;
				}

				$option_is_set = $price_key === $current_value ? true : false;

				$link = $base_link;

				// Skip add param if price type is All.
				if ( ! $option_is_set && '' !== $price_key ) {
					$link = add_query_arg( array(
						'filtering'  => '1',
						$filter_name => $price_key,
					), $link );
				}

				$item_classes = [];

				if ( $option_is_set ) {
					$item_classes [] = 'chosen';
				}

				$count_html = '';

				if ( $items_count ) {
					$count_html = '<span class="count">(' . $count . ')</span>';
				}

				$li_html = sprintf(
					'<li class="%1$s" ><a href="%2$s">%3$s %4$s</a></li>',
					implode( ' ', $item_classes ),
					esc_url( $link ),
					esc_html( $price_name ),
					$count_html
				);

				echo '' . $li_html;
			}

			echo '</ul>';
		}

		/**
		 * Count courses after other filters have occurred by adjusting the main query.
		 *
		 * @param string $price_type
		 *
		 * @return int
		 */
		protected function get_filtered_course_count( $price_type ) {
			global $wpdb;

			$tax_query  = Edumall_Course_Query::instance()->get_main_tax_query();
			$meta_query = Edumall_Course_Query::instance()->get_main_meta_query();

			// Unset current price type filter.
			foreach ( $meta_query as $key => $query ) {
				if ( isset( $query['key'] ) && '_tutor_course_product_id' === $query['key'] ) {
					unset( $meta_query[ $key ] );
					break;
				}
			}

			// Set new price type filter.
			$meta_query = Edumall_Course_Query::set_meta_query_price( $meta_query, $price_type );

			$tax_query  = new WP_Tax_Query( $tax_query );
			$meta_query = new WP_Meta_Query( $meta_query );

			$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
			$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$author_query_sql = Edumall_Course_Query::get_main_author_sql();
			$search_query_sql = Edumall_Course_Query::get_search_title_sql();

			$sql = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
			$sql .= $tax_query_sql['join'] . $meta_query_sql['join'];
			$sql .= " WHERE {$wpdb->posts}.post_type = 'courses' AND {$wpdb->posts}.post_status = 'publish' ";
			$sql .= $tax_query_sql['where'] . $meta_query_sql['where'] . $author_query_sql['where'] . $search_query_sql['where'];

			return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
		}
	}
}
