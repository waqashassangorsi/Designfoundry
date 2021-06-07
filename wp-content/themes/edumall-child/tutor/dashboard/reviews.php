<?php
/**
 * My Own reviews
 *
 * @since   v.1.1.2
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$reviews = tutor_utils()->get_reviews_by_user();
?>
<h3><?php esc_html_e( 'Reviews', 'edumall' ); ?></h3>

<div class="tutor-dashboard-content-inner">
	<?php if ( current_user_can( tutor()->instructor_role ) ) : ?>
		<div class="tutor-dashboard-inline-links">
			<ul>
				<li class="active">
					<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'reviews' ); ?>"> <?php esc_html_e( 'Given', 'edumall' ); ?></a>
				</li>
				<li>
					<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'reviews/received-reviews' ); ?>"> <?php esc_html_e( 'Received', 'edumall' ); ?></a>
				</li>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $reviews ) ) : ?>
		<div class="dashboard-given-reviews">
			<?php
			foreach ( $reviews as $review ) :
				$profile_url = tutor_utils()->profile_url( $review->user_id );
				?>
				<div class="dashboard-given-review <?php echo 'tutor-review-' . $review->comment_ID; ?>">
					<div class="review-header">
						<div class="review-course-thumbnail">
							<?php
							Edumall_Image::the_post_thumbnail( [
								'post_id' => $review->comment_post_ID,
								'size'    => '150x92',
							] );
							?>
						</div>
					</div>
					<div class="review-body">
						<div class="review-course-title-wrap">
							<h3 class="review-course-title">
								<span><?php esc_html_e( 'Course: ', 'edumall' ); ?></span>
								<a href="<?php echo esc_url( get_the_permalink( $review->comment_post_ID ) ); ?>"><?php echo esc_html( get_the_title( $review->comment_post_ID ) ); ?></a>
							</h3>
							<div class="review-links">
								<a href="javascript:;" class="open-tutor-edit-review-modal"
								   data-review-id="<?php echo esc_attr( $review->comment_ID ); ?>">
									<i class="far fa-pencil"></i>
									<span><?php esc_html_e( 'Edit Feedback', 'edumall' ); ?></span>
								</a>
							</div>
						</div>
						<div class="individual-star-rating-wrap">
							<?php Edumall_Templates::render_rating( $review->rating, [ 'style' => '03' ] ); ?>
							<p class="review-meta"><?php echo sprintf( esc_html__( '%s ago', 'edumall' ), human_time_diff( strtotime( $review->comment_date ) ) ); ?></p>
						</div>

						<div
							class="review-content"><?php echo wpautop( stripslashes( $review->comment_content ) ); ?></div>
					</div>

				</div>
			<?php endforeach; ?>
		</div>

	<?php else: ?>
		<div class="dashboard-no-content-found">
			<?php esc_html_e( 'You haven\'t given any reviews yet.', 'edumall' ); ?>
		</div>
	<?php endif; ?>

</div>

<div class="tutor-modal-wrap tutor-edit-review-modal-wrap">
	<div class="tutor-modal-content">
		<div class="modal-header">
			<div class="modal-title">
				<h1><?php esc_html_e( 'Edit Review', 'edumall' ); ?></h1>
			</div>
			<div class="modal-close-wrap">
				<a href="javascript:;" class="modal-close-btn"><i class="tutor-icon-line-cross"></i> </a>
			</div>
		</div>
		<div class="modal-container"></div>
	</div>
</div>
