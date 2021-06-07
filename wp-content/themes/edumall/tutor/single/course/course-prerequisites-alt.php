<?php
/**
 * Custom template by EduMall.
 * EduMall use course-prerequisites-alt.php instead of course-prerequisites.php.
 * Because now there is no way to move it below top lead info section.
 */

defined( 'ABSPATH' ) || exit;

$savedPrerequisitesIDS = maybe_unserialize( get_post_meta( get_the_ID(), '_tutor_course_prerequisites_ids', true ) );

if ( ! is_array( $savedPrerequisitesIDS ) || empty( $savedPrerequisitesIDS ) ) {
	return;
}
?>
<div class="tutor-single-course-segment tutor-course-prerequisites-wrap">
	<h4 class="tutor-segment-title"><?php esc_html_e( 'Course Prerequisites', 'edumall' ); ?></h4>
	<div class="course-prerequisites-lists-wrap">
		<ul class="prerequisites-course-lists">
			<li class="prerequisites-warning">
				<span class="far fa-exclamation-triangle"></span>
				<?php esc_html_e( 'Please note that this course has the following prerequisites which must be completed before it can be accessed', 'edumall' ); ?>
			</li>
			<?php foreach ( $savedPrerequisitesIDS as $courseID ) : ?>
				<li>
					<a href="<?php echo get_the_permalink( $courseID ); ?>" class="prerequisites-course-item">
                        <span class="prerequisites-course-feature-image">
                            <?php echo get_the_post_thumbnail( $courseID ); ?>
                        </span>
						<span class="prerequisites-course-title">
                            <?php echo get_the_title( $courseID ); ?>
                        </span>
						<?php if ( tutor_utils()->is_completed_course( $courseID ) ) : ?>
							<div class="is-complete-prerequisites-course"><i class="tutor-icon-mark"></i></div>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
