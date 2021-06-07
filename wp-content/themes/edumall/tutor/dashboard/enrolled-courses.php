<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>

<h3><?php esc_html_e( 'Enrolled Courses', 'edumall' ); ?></h3>

<div class="tutor-dashboard-content-inner">
	<div class="tutor-dashboard-inline-links">
		<ul>
			<li class="active">
				<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'enrolled-courses' ); ?>">
					<?php esc_html_e( 'All Courses', 'edumall' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'enrolled-courses/active-courses' ); ?>">
					<?php esc_html_e( 'Active Courses', 'edumall' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'enrolled-courses/completed-courses' ); ?>">
					<?php esc_html_e( 'Completed Courses', 'edumall' ); ?>
				</a>
			</li>
		</ul>
	</div>

	<?php
	$my_courses = tutor_utils()->get_enrolled_courses_by_user();

	if ( $my_courses && $my_courses->have_posts() ):
		while ( $my_courses->have_posts() ):
			$my_courses->the_post();

			$avg_rating       = tutor_utils()->get_course_rating()->rating_avg;
			$tutor_course_img = Edumall_Image::get_the_post_thumbnail_url( [
				'size' => '260x160',
			] );
			?>
			<div class="tutor-mycourse-wrap tutor-mycourse-<?php the_ID(); ?>">
				<div class="tutor-mycourse-thumbnail"
				     style="background-image: url(<?php echo esc_url( $tutor_course_img ); ?>)"></div>
				<div class="tutor-mycourse-content">
					<?php Edumall_Templates::render_rating( $avg_rating, [
						'style'         => '03',
						'wrapper_class' => 'tutor-mycourse-rating',
					] ); ?>

					<h3 class="course-title"><a href="<?php the_permalink(); ?>"
					                            class="link-in-title"><?php the_title(); ?></a></h3>

					<div class="tutor-meta tutor-course-metadata">
						<?php
						$total_lessons     = tutor_utils()->get_lesson_count_by_course();
						$completed_lessons = tutor_utils()->get_completed_lesson_count_by_course();
						?>
						<ul class="course-meta">
							<li class="course-meta-lesson-count">
								<span class="meta-label"><?php esc_html_e( 'Total Lessons:', 'edumall' ); ?></span>
								<span class="meta-value"><?php echo number_format_i18n( $total_lessons ); ?></span>
							</li>
							<li class="course-meta-completed-lessons">
								<span class="meta-label"><?php esc_html_e( 'Completed Lessons:', 'edumall' ); ?></span>
								<span class="meta-value"><?php echo number_format_i18n( $completed_lessons ) . '/' . number_format_i18n( $total_lessons ); ?></span>
							</li>
						</ul>
					</div>
					<?php tutor_course_completing_progress_bar(); ?>
				</div>

			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<?php else: ?>
		<div class="dashboard-no-content-found">
			<?php esc_html_e( 'You didn\'t purchased any courses.', 'edumall' ); ?>
		</div>
	<?php endif; ?>

</div>
