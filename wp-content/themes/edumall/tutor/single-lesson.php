<?php
/**
 * Template for displaying single lesson
 *
 * @since   v.1.0.0
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

get_tutor_header();

global $post;
$currentPost = $post;

$enable_spotlight_mode = tutor_utils()->get_option( 'enable_spotlight_mode' );
?>
	<div class="page-content">

		<?php do_action( 'tutor_lesson/single/before/wrap' ); ?>

		<?php
		$wrapper_class = 'tutor-single-lesson-wrap';
		if ( $enable_spotlight_mode ) {
			$wrapper_class .= ' tutor-spotlight-mode';
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<div class="tutor-lesson-sidebar-wrap">
				<div class="tutor-lesson-sidebar-inner">
					<a href="javascript:void(0);" class="btn-toggle-lesson-sidebar"><i class="far fa-exchange"></i></a>
					<div id="tutor-lesson-sidebar" class="tutor-lesson-sidebar">
						<?php tutor_lessons_sidebar(); ?>
					</div>
				</div>
			</div>
			<div id="tutor-single-entry-content"
			     class="tutor-lesson-content tutor-single-entry-content tutor-single-entry-content-<?php the_ID(); ?>">
				<?php tutor_lesson_content(); ?>
			</div>
		</div>

		<?php do_action( 'tutor_lesson/single/after/wrap' ); ?>

	</div>
<?php
get_tutor_footer();
