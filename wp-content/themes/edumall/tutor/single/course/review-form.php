<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

$isLoggedIn = is_user_logged_in();
$rating     = $isLoggedIn ? tutor_utils()->get_course_rating_by_user() : '';

if ( $isLoggedIn && ( ! empty( $rating->rating ) || ! empty( $rating->review ) ) ) {
	$heading = __( 'Edit review', 'edumall' );
} else {
	$heading = __( 'Write a review', 'edumall' );
}
?>

<div class="tutor-course-enrolled-review-wrap">
	<h4 class="tutor-segment-title"><?php echo esc_html( $heading ); ?></h4>

	<a href="javascript:void(0);" class="write-course-review-link-btn"><?php echo esc_html( $heading ); ?></a>
	<div class="tutor-write-review-form" style="display: none;">
		<?php if ( $isLoggedIn ) { ?>
			<form method="post">
				<input type="hidden" name="tutor_course_id" value="<?php echo get_the_ID(); ?>">
				<div class="tutor-write-review-box">
					<div class="tutor-form-group">
						<?php
						tutor_utils()->star_rating_generator( tutor_utils()->get_rating_value( $rating->rating ) );
						?>
					</div>
					<div class="tutor-form-group">
						<textarea name="review"
						          placeholder="<?php esc_attr_e( 'write a review', 'edumall' ); ?>"><?php echo stripslashes( $rating->review ); ?></textarea>
					</div>
					<div class="tutor-form-group">
						<button type="submit"
						        class="custom_tutor_submit_review_btn tutor-button tutor-success"><?php esc_html_e( 'Submit Review', 'edumall' ); ?></button>
					</div>
				</div>
			</form>
			<?php
		} else {
			ob_start();
			tutor_load_template( 'single.course.login' );
			$output = apply_filters( 'tutor_course/global/login', ob_get_clean() );
			echo '' . $output;
		}
		?>
	</div>
</div>
