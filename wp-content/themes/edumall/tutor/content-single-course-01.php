<?php
/**
 * Template for displaying single course
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

global $edumall_course;

$title_bar_type   = Edumall_Global::instance()->get_title_bar_type();
$top_info_classes = 'tutor-full-width-course-top tutor-course-top-info';

if ( '04' === $title_bar_type ) {
	$top_info_classes .= ' course-top-info-light';
}
?>

<div class="page-content tm-sticky-parent">

	<?php do_action( 'tutor_course/single/before/wrap' ); ?>

	<div <?php tutor_post_class(); ?>>
		<div class="<?php echo esc_attr( $top_info_classes ); ?>">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<?php tutor_course_lead_info(); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="tutor-full-width-course-body">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<div class="tutor-single-course-main-content tm-sticky-column">

							<?php do_action( 'tutor_course/single/before/inner-wrap' ); ?>

							<?php if ( $edumall_course->is_enrolled() ) : ?>
								<?php tutor_course_completing_progress_bar(); ?>
							<?php endif; ?>

							<?php Edumall_Tutor::instance()->course_prerequisites(); ?>
							<?php tutor_course_content(); ?>
							<?php tutor_course_benefits_html(); ?>
							<?php tutor_course_requirements_html(); ?>
							<?php tutor_course_target_audience_html(); ?>
							<?php tutor_course_topics(); ?>

							<?php if ( $edumall_course->is_enrolled() ) : ?>
								<?php get_tutor_posts_attachments(); ?>
								<?php tutor_course_question_and_answer(); ?>
							<?php endif; ?>

							<?php tutor_course_instructors_html(); ?>

							<?php if ( $edumall_course->is_enrolled() ) : ?>
								<?php tutor_course_announcements(); ?>
							<?php endif; ?>

							<?php tutor_course_target_reviews_html(); ?>

							<?php do_action( 'tutor_course/single/after/inner-wrap' ); ?>

						</div>
					</div>
					<div class="col-lg-4">
						<div class="tutor-single-course-sidebar tm-sticky-column">

							<?php do_action( 'tutor_course/single/before/sidebar' ); ?>

							<?php Edumall_Tutor::instance()->course_enroll_box(); ?>

							<?php do_action( 'tutor_course/single/after/sidebar' ); ?>

							<?php Edumall_Sidebar::instance()->generated_sidebar( 'course_single_sidebar' ); ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php do_action( 'tutor_course/single/after/wrap' ); ?>

</div>
