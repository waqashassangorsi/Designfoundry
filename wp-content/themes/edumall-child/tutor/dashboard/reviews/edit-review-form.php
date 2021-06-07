<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>

<form method="post" id="custom_tutor_update_review_form">
	<div class="tutor-write-review-box">
		<div class="tutor-form-group">
			<?php
			tutor_utils()->star_rating_generator( tutor_utils()->get_rating_value( $rating->rating ) );
			?>
		</div>
		<div class="tutor-form-group">
			<textarea name="review"
			          placeholder="<?php esc_attr_e( 'Write a review', 'edumall' ); ?>"><?php echo stripslashes( $rating->review ); ?></textarea>
		</div>
		<div class="tutor-form-group">
			<button type="submit"
			        class="tutor-button tutor-success"><?php esc_html_e( 'Update Review', 'edumall' ); ?></button>
		</div>
	</div>
</form>
