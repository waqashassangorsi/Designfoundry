<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_WP_Widget_Courses' ) ) {
	class Edumall_WP_Widget_Courses extends Edumall_WP_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'edumall-wp-widget-courses';
			$this->widget_cssclass    = 'edumall-wp-widget-courses';
			$this->widget_name        = esc_html__( '[Edumall] Courses', 'edumall' );
			$this->widget_description = esc_html__( 'Get list courses.', 'edumall' );
			$this->settings           = array(
				'title'           => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Recent Courses', 'edumall' ),
					'label' => esc_html__( 'Title', 'edumall' ),
				),
				'filter_by'       => array(
					'type'    => 'select',
					'std'     => 'recent',
					'label'   => esc_html__( 'Filter By', 'edumall' ),
					'options' => [],
				),
				'num'             => array(
					'type'  => 'number',
					'step'  => 1,
					'min'   => 1,
					'max'   => 40,
					'std'   => 5,
					'label' => esc_html__( 'Number', 'edumall' ),
				),
				'show_thumbnail'  => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Thumbnail', 'edumall' ),
				),
				'show_price'      => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Price', 'edumall' ),
				),
				'show_categories' => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show Categories', 'edumall' ),
				),
				'show_badge'      => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show Badge', 'edumall' ),
				),
			);

			parent::__construct();
		}

		public function set_form_settings() {
			$filter_by_options = array(
				'recent'  => esc_html__( 'Recent Courses', 'edumall' ),
				'related' => esc_html__( 'Related Courses', 'edumall' ),
			);
			$terms             = get_terms( [
				'taxonomy'   => 'course-category',
				'parent'     => 0,
				'hide_empty' => false,
			] );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$filter_by_options[ $term->term_id ] = sprintf( __( 'Category: %s', 'edumall' ), $term->name ); // XSS OK.
				}
			}

			$this->settings['filter_by']['options'] = $filter_by_options;
		}

		public function widget( $args, $instance ) {
			$filter_by       = $this->get_value( $instance, 'filter_by' );
			$num             = $this->get_value( $instance, 'num' );
			$show_thumbnail  = $this->get_value( $instance, 'show_thumbnail' );
			$show_price      = $this->get_value( $instance, 'show_price' );
			$show_categories = $this->get_value( $instance, 'show_categories' );
			$show_badge      = $this->get_value( $instance, 'show_badge' );

			if ( 'related' === $filter_by && ! is_singular( 'courses' ) ) {
				return;
			}

			$query_args = [
				'post_type'      => 'courses',
				'posts_per_page' => $num,
				'no_found_rows'  => true,
				'post_status'    => 'public',
			];

			if ( 'recent' === $filter_by ) {
				$query_args = wp_parse_args( $query_args, [
					'orderby' => 'date',
					'order'   => 'DESC',
				] );
			} elseif ( 'related' === $filter_by ) {
				$current_course = get_the_ID();

				$related_by = [ 'course-category', 'course-tag' ];

				$query_args['tax_query'] = [];

				foreach ( $related_by as $tax ) {
					$terms = get_the_terms( $current_course, $tax );
					if ( $terms && ! is_wp_error( $terms ) ) {
						$term_ids = array();
						foreach ( $terms as $term ) {
							$term_ids[] = $term->term_id;
						}
						$query_args['tax_query'][] = array(
							'terms'    => $term_ids,
							'taxonomy' => $tax,
						);
					}
				}
				if ( count( $query_args['tax_query'] ) > 1 ) {
					$query_args['tax_query']['relation'] = 'OR';
				}

				$query_args = wp_parse_args( $query_args, [
					'orderby'      => 'date',
					'order'        => 'DESC',
					'post__not_in' => [ $current_course ],
				] );
			} else {
				$query_args = wp_parse_args( $query_args, [
					'tax_query' => array(
						array(
							'taxonomy' => 'course-category',
							'field'    => 'id',
							'terms'    => $filter_by,
						),
					),
				] );
			}

			$query = new WP_Query( $query_args );
			if ( $query->have_posts() ) {
				$this->widget_start( $args, $instance );

				?>
				<div class="edumall-courses edumall-animation-zoom-in">
					<?php while ( $query->have_posts() ) :$query->the_post(); ?>
						<?php
						$classes = array( 'course-item edumall-box' );
						?>
						<div <?php post_class( implode( ' ', $classes ) ); ?> >
							<?php if ( $show_thumbnail ) : ?>
								<div class="course-thumbnail edumall-image">
									<?php if ( $show_badge ): ?>
										<?php
										$badge_text = Edumall_Tutor::instance()->get_course_price_badge_text();
										?>
										<?php if ( ! empty( $badge_text ) ): ?>
											<?php echo '<div class="tutor-course-badge onsale">' . $badge_text . '</div>'; ?>
										<?php endif; ?>
									<?php endif; ?>

									<a href="<?php the_permalink(); ?>">
										<?php if ( has_post_thumbnail() ) { ?>
											<?php Edumall_Image::the_post_thumbnail( array( 'size' => '120x72' ) ); ?>
											<?php
										} else {
											Edumall_Templates::image_placeholder( 120, 72 );
										}
										?>
									</a>
								</div>
							<?php endif; ?>
							<div class="course-info">

								<?php if ( $show_categories ) : ?>
									<?php Edumall_Tutor::instance()->course_loop_category(); ?>
								<?php endif; ?>

								<h5 class="course-title course-loop-title-collapse-2-rows">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h5>

								<?php if ( $show_price ): ?>
									<?php Edumall_Tutor::instance()->course_loop_price(); ?>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
				<?php
				wp_reset_postdata();

				$this->widget_end( $args );
			}
		}
	}
}
