<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_WP_Widget_Course_Duration_Filter' ) ) {
	class Edumall_WP_Widget_Course_Duration_Filter extends Edumall_Course_Layered_Nav_Base {

		public function __construct() {
			$this->widget_id          = 'edumall-wp-widget-course-duration-filter';
			$this->widget_cssclass    = 'edumall-wp-widget-course-duration-filter edumall-wp-widget-course-filter';
			$this->widget_name        = esc_html__( '[Edumall] Course Duration Filter', 'edumall' );
			$this->widget_description = esc_html__( 'Shows duration in a widget which lets you narrow down the list of courses when viewing courses.', 'edumall' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by duration', 'edumall' ),
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

			$class = ' filter-checkbox-list';
			$class .= ' show-display-' . $display_type;
			$class .= ' show-items-count-' . $items_count;

			$duration_ranges = array(
				'short'     => esc_html__( 'Less than 2 hours', 'edumall' ),
				'medium'    => esc_html__( '3 - 6 hours', 'edumall' ),
				'long'      => esc_html__( '7 - 16 hours', 'edumall' ),
				'extraLong' => esc_html__( '17+ Hours', 'edumall' ),
			);

			$duration_ranges = apply_filters( 'edumall_widget_course_duration_filter_ranges', $duration_ranges );

			$filter_name = 'duration';

			$base_link      = Edumall_Tutor::instance()->get_course_listing_page_url();
			$base_link      = remove_query_arg( $filter_name, $base_link );
			$current_values = isset( $_GET[ $filter_name ] ) ? explode( ',', Edumall_Helper::data_clean( $_GET[ $filter_name ] ) ) : array();

			// List display.
			echo '<ul class="' . esc_attr( $class ) . '">';

			foreach ( $duration_ranges as $option_key => $option_name ) {
				$count = $this->get_filtered_course_count( $option_key );

				// Only show options with count > 0.
				if ( empty( $count ) ) {
					continue;
				}

				$option_is_set = in_array( $option_key, $current_values, true );

				$current_filter = $current_values;

				if ( ! $option_is_set ) {
					$current_filter[] = $option_key;
				}

				foreach ( $current_filter as $key => $value ) {
					if ( $option_is_set && $value === $option_key ) {
						unset( $current_filter[ $key ] );
					}
				}

				$link = $base_link;

				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( array(
						'filtering'  => '1',
						$filter_name => implode( ',', $current_filter ),
					), $link );
				}

				$item_classes = [ 'term-item-' . $option_key ];

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
					esc_html( $option_name ),
					$count_html
				);

				echo '' . $li_html;
			}

			echo '</ul>';
		}

		/**
		 * @param $duration
		 *
		 * @return int
		 */
		protected function get_filtered_course_count( $duration ) {
			global $wpdb;

			$tax_query  = Edumall_Course_Query::instance()->get_main_tax_query();
			$meta_query = Edumall_Course_Query::instance()->get_main_meta_query();

			// Unset current duration filter.
			foreach ( $meta_query as $key => $query ) {
				if ( isset( $query['key'] ) && '_course_duration_in_seconds' === $query['key'] ) {
					unset( $meta_query[ $key ] );
					break;
				}
			}

			// Set new duration filter.
			$meta_query = Edumall_Course_Query::set_meta_query_duration( $meta_query, (array) $duration );

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
