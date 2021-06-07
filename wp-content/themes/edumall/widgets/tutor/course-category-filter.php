<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_WP_Widget_Course_Category_Filter' ) ) {
	class Edumall_WP_Widget_Course_Category_Filter extends Edumall_Course_Layered_Nav_Base {

		public function __construct() {
			$this->widget_id          = 'edumall-wp-widget-course-category-filter';
			$this->widget_cssclass    = 'edumall-wp-widget-course-category-filter edumall-wp-widget-course-filter';
			$this->widget_name        = esc_html__( '[Edumall] Course Category Filter', 'edumall' );
			$this->widget_description = esc_html__( 'Shows categories in a widget which lets you narrow down the list of courses when viewing courses.', 'edumall' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by category', 'edumall' ),
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

			$taxonomy = Edumall_Tutor::instance()->get_tax_category();

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			// Get only parent terms. Methods will recursively retrieve children.
			$terms = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => '1',
				'parent'     => 0,
			] );

			if ( empty( $terms ) ) {
				return;
			}

			$this->widget_start( $args, $instance );

			$this->layered_nav_list( $terms, $taxonomy, $instance );

			$this->widget_end( $args );
		}

		protected function layered_nav_list( $terms, $taxonomy, $instance ) {
			$items_count  = $this->get_value( $instance, 'items_count' );
			$display_type = $this->get_value( $instance, 'display_type' );

			$class = ' filter-checkbox-list';
			$class .= ' show-display-' . $display_type;
			$class .= ' show-items-count-' . $items_count;

			$filter_name    = 'filter_' . $taxonomy;
			$base_link      = Edumall_Tutor::instance()->get_course_listing_page_url();
			$base_link      = remove_query_arg( $filter_name, $base_link );
			$current_values = isset( $_GET[ $filter_name ] ) ? array_map( 'intval', explode( ',', $_GET[ $filter_name ] ) ) : array();

			// List display.
			echo '<ul class="' . esc_attr( $class ) . '">';

			foreach ( $terms as $term_key => $term ) {
				$option_key  = $term->term_id;
				$option_name = $term->name;

				$child_ids = get_terms( [
					'taxonomy' => $taxonomy,
					'parent'   => $term->term_id,
					'fields'   => 'ids',
				] );

				$child_ids[] = $term->term_id;

				$count = $this->get_filtered_term_counts( $child_ids, $taxonomy, 'or' );

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
		 * @param        $term_ids
		 * @param        $taxonomy
		 * @param string $query_type
		 *
		 * @return array|int
		 */
		protected function get_filtered_term_counts( $term_ids, $taxonomy, $query_type = 'and' ) {
			global $wpdb;

			$tax_query  = Edumall_Course_Query::instance()->get_main_tax_query();
			$meta_query = Edumall_Course_Query::instance()->get_main_meta_query();

			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}

			$meta_query = new WP_Meta_Query( $meta_query );
			$tax_query  = new WP_Tax_Query( $tax_query );

			$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
			$author_query_sql = Edumall_Course_Query::get_main_author_sql();
			$search_query_sql = Edumall_Course_Query::get_search_title_sql();

			$sql           = array();
			$sql['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID )";
			$sql['from']   = "FROM {$wpdb->posts}";
			$sql['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];
			$sql['where']  = "
			WHERE {$wpdb->posts}.post_type IN ( 'courses' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . $author_query_sql['where'] . $search_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";

			$sql = apply_filters( 'edumall_get_filtered_term_course_counts_query', $sql );

			$sql = implode( ' ', $sql );

			return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
		}
	}
}

