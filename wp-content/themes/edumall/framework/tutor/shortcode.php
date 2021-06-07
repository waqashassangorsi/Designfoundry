<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Tutor_Shortcode' ) ) {
	class Edumall_Tutor_Shortcode extends Edumall_Tutor {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_ajax_get_course_tabs', [ $this, 'get_courses' ] );
			add_action( 'wp_ajax_nopriv_get_course_tabs', [ $this, 'get_courses' ] );
		}

		public function get_courses() {
			$source = isset( $_POST['source'] ) ? $_POST['source'] : 'latest';
			$number = isset( $_POST['number'] ) ? $_POST['number'] : 10;
			$layout = isset( $_POST['layout'] ) ? $_POST['layout'] : 'grid';

			$query_args = [
				'post_type'      => $this->get_course_type(),
				'posts_per_page' => $number,
				'post_status'    => 'publish',
				'no_found_rows'  => true,
			];

			switch ( $source ) {
				case 'trending' :
					$query_args = wp_parse_args( [
						'meta_key' => 'views',
						'orderby'  => 'meta_value_num',
						'order'    => 'DESC',
					], $query_args );
					break;
				case 'featured' :
					$query_args = wp_parse_args( [
						'orderby'   => 'date',
						'order'     => 'DESC',
						'tax_query' => [
							'relation' => 'AND',
							array(
								'taxonomy' => 'course-visibility',
								'field'    => 'slug',
								'terms'    => [ 'featured' ],
							),
						],
					], $query_args );
					break;
				case 'popular' :
					$query_args = wp_parse_args( [
						'meta_key' => '_course_total_enrolls',
						'orderby'  => 'meta_value_num',
						'order'    => 'DESC',
					], $query_args );
					break;
				case 'by_category' :
					$query_args = wp_parse_args( [
						'orderby'   => 'date',
						'order'     => 'DESC',
						'tax_query' => [
							'relation' => 'AND',
							array(
								'taxonomy' => $this->get_tax_category(),
								'terms'    => $_POST['term_id'],
							),
						],
					], $query_args );
					break;
			}

			$query = new WP_Query( $query_args );

			ob_start();

			if ( $query->have_posts() ) {
				?>
				<?php
				global $edumall_course;
				$edumall_course_clone = $edumall_course;
				?>

				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php
					/**
					 * Setup course object.
					 */
					$edumall_course = new Edumall_Course();
					?>

					<?php if ( 'grid' === $layout ): ?>
						<?php tutor_load_template( 'loop.loop-before-content' ); ?>
						<?php tutor_load_template( 'loop.custom.content-course-grid-02' ); ?>
						<?php tutor_load_template( 'loop.loop-after-content' ); ?>
					<?php else: ?>
						<?php tutor_load_template( 'loop.custom.loop-before-slide-content' ); ?>
						<?php tutor_load_template( 'loop.custom.content-course-carousel-02' ); ?>
						<?php tutor_load_template( 'loop.custom.loop-after-slide-content' ); ?>
					<?php endif; ?>

				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>

				<?php
				/**
				 * Reset course object.
				 */
				$edumall_course = $edumall_course_clone;
				?>
				<?php
			}

			$template = ob_get_clean();

			$template = preg_replace( '~>\s+<~', '><', $template );

			$response['template'] = $template;

			echo json_encode( $response );

			wp_die();
		}
	}
}
