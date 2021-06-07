<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_WP_Widget_Posts' ) ) {
	class Edumall_WP_Widget_Posts extends Edumall_WP_Widget_Base {

		public function __construct() {
			$cat_options = array(
				'recent_posts' => esc_html__( 'Recent Posts', 'edumall' ),
				'sticky_posts' => esc_html__( 'Sticky Posts', 'edumall' ),
			);
			$categories  = get_categories( 'hide_empty=0' );
			if ( $categories ) {
				foreach ( $categories as $category ) {
					$cat_options[ $category->term_id ] = esc_html__( 'Category: ', 'edumall' ) . $category->name;
				}
			}

			$this->widget_id          = 'edumall-wp-widget-posts';
			$this->widget_cssclass    = 'edumall-wp-widget-posts';
			$this->widget_name        = esc_html__( '[Edumall] Posts', 'edumall' );
			$this->widget_description = esc_html__( 'Get list blog post.', 'edumall' );
			$this->settings           = array(
				'title'           => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Title', 'edumall' ),
				),
				'cat'             => array(
					'type'    => 'select',
					'std'     => 'recent_posts',
					'label'   => esc_html__( 'Category', 'edumall' ),
					'options' => $cat_options,
				),
				'show_thumbnail'  => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Thumbnail', 'edumall' ),
				),
				'show_categories' => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Categories', 'edumall' ),
				),
				'show_date'       => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show Date', 'edumall' ),
				),
				'num'             => array(
					'type'  => 'number',
					'step'  => 1,
					'min'   => 1,
					'max'   => 40,
					'std'   => 5,
					'label' => esc_html__( 'Number', 'edumall' ),
				),
			);

			parent::__construct();
		}

		public function widget( $args, $instance ) {
			$cat             = $this->get_value( $instance, 'cat' );
			$num             = $this->get_value( $instance, 'num' );
			$show_thumbnail  = $this->get_value( $instance, 'show_thumbnail' );
			$show_categories = $this->get_value( $instance, 'show_categories' );
			$show_date       = $this->get_value( $instance, 'show_date' );

			$this->widget_start( $args, $instance );

			$query_args = [
				'post_type'      => 'post',
				'posts_per_page' => $num,
				'no_found_rows'  => true,
			];

			if ( $cat === 'recent_posts' ) {
				$query_args = wp_parse_args( $query_args, [
					'ignore_sticky_posts' => 1,
					'orderby'             => 'date',
					'order'               => 'DESC',
				] );
			} elseif ( $cat === 'sticky_posts' ) {
				$sticky     = get_option( 'sticky_posts' );
				$query_args = wp_parse_args( $query_args, [
					'post__in' => $sticky,
				] );
			} else {
				$query_args = wp_parse_args( $query_args, [
					'cat'                 => $cat,
					'ignore_sticky_posts' => 1,
				] );
			}

			$edumall_query = new WP_Query( $query_args );
			if ( $edumall_query->have_posts() ) :
				?>
				<div class="post-list edumall-animation-zoom-in">
					<?php while ( $edumall_query->have_posts() ) : $edumall_query->the_post(); ?>
						<?php
						$classes = array( 'post-item edumall-box' );
						?>
						<div <?php post_class( implode( ' ', $classes ) ); ?> >
							<?php if ( $show_thumbnail ) : ?>
								<div class="post-widget-thumbnail edumall-image">
									<a href="<?php the_permalink(); ?>">
										<?php if ( has_post_thumbnail() ) { ?>
											<?php Edumall_Image::the_post_thumbnail( [ 'size' => '100x80' ] ); ?>
											<?php
										} else {
											Edumall_Templates::image_placeholder( 100, 80 );
										}
										?>
										<?php if ( $show_categories ) : ?>
											<?php Edumall_Post::instance()->the_category( [
												'classes'    => 'post-widget-overlay-categories',
												'show_links' => false,
											] ); ?>
										<?php endif; ?>
									</a>
								</div>
							<?php endif; ?>
							<div class="post-widget-info">
								<h5 class="post-widget-title">
									<a href="<?php the_permalink(); ?>" class="link-in-title"><?php the_title(); ?></a>
								</h5>
								<?php if ( $show_date ) : ?>
									<span class="post-date style-1"><?php echo get_the_date(); ?></span>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			<?php
			endif;

			$this->widget_end( $args );
		}
	}
}
