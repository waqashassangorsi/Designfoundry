<?php
/**
 * Template for displaying course content
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

$preview_box_classes = 'tutor-price-preview-box';

if ( '01' === Edumall::setting( 'single_course_layout' ) ) {
	if ( $edumall_course->has_video() || has_post_thumbnail() ) {
		$preview_box_classes .= ' box-has-media';
	}
}
?>

<div class="<?php echo esc_attr( $preview_box_classes ); ?>">

	<?php if ( '01' === Edumall::setting( 'single_course_layout' ) ) : ?>
		<div class="tutor-price-box-thumbnail">
			<?php
			if ( $edumall_course->has_video() ) {
				tutor_course_video();
			} else {
				Edumall_Image::the_post_thumbnail( [ 'size' => '340x200' ] );
			}
			?>
		</div>

		<?php do_action( 'tutor_course/single/enroll_box/after_thumbnail' ); ?>
	<?php endif; ?>

	<?php tutor_course_price(); ?>

	<div class="tutor-single-course-meta tutor-meta-top">
		<?php if ( '1' !== get_tutor_option( 'disable_course_level' ) ) { ?>
			<div class="tutor-course-level">
				<span class="meta-label">
					<i class="meta-icon far fa-sliders-h"></i>
					<?php esc_html_e( 'Level', 'edumall' ); ?>
				</span>
				<div class="meta-value"><?php echo get_tutor_course_level(); ?></div>
			</div>
		<?php } ?>

		<?php
		$disable_course_duration = get_tutor_option( 'disable_course_duration' );
		$course_duration         = Edumall_Tutor::instance()->get_course_duration_context();

		if ( ! empty( $course_duration ) && ! $disable_course_duration ) { ?>
			<div class="tutor-course-duration">
				<span class="meta-label">
					<i class="meta-icon far fa-clock"></i>
					<?php esc_html_e( 'Duration', 'edumall' ) ?>
				</span>
				<?php echo esc_html( $course_duration ); ?>
			</div>
		<?php } ?>

		<?php
		$tutor_lesson_count = $edumall_course->get_lesson_count();

		if ( $tutor_lesson_count ) : ?>
			<div class="tutor-course-lesson-count">
				<span class="meta-label">
					<i class="meta-icon far fa-play-circle"></i>
					<?php esc_html_e( 'Lectures', 'edumall' ); ?>
				</span>
				<div class="meta-value">
					<?php echo esc_html( sprintf( _n( '%s lecture', '%s lectures', $tutor_lesson_count, 'edumall' ), $tutor_lesson_count ) ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php Edumall_Tutor::instance()->entry_course_categories(); ?>

		<?php Edumall_Tutor::instance()->entry_course_language(); ?>
	</div>

	<?php tutor_course_material_includes_html(); ?>

	<?php if ( '02' !== Edumall::setting( 'single_course_layout' ) ): ?>
		<?php Edumall_Tutor::instance()->single_course_add_to_cart(); ?>

		<?php tutor_load_template( 'custom.wishlist-button-01' ); ?>
	<?php endif; ?>

	<?php if ( ! get_tutor_option( 'disable_course_share' ) ) { ?>
		<div class="tutor-social-share">
			<?php tutor_social_share(); ?>
		</div>
	<?php } ?>

</div> <!-- tutor-price-preview-box -->
