<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>

<h3><?php esc_html_e( 'Active Course', 'edumall' ); ?></h3>

<div class="tutor-dashboard-content-inner">
	<div class="tutor-dashboard-inline-links">
		<ul>
			<li>
				<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'enrolled-courses' ); ?>">
					<?php esc_html_e( 'All Courses', 'edumall' ); ?>
				</a>
			</li>
			<li class="active">
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
	$active_courses = tutor_utils()->get_active_courses_by_user();

	if ( $active_courses && $active_courses->have_posts() ):
		while ( $active_courses->have_posts() ):
			$active_courses->the_post();

			$avg_rating       = tutor_utils()->get_course_rating()->rating_avg;
			$tutor_course_img = Edumall_Image::get_the_post_thumbnail_url( [
				'size' => '260x160',
			] );
			?>
			<div class="tutor-mycourse-wrap tutor-mycourse-<?php the_ID(); ?>">

				<a class="tutor-stretched-link" href="<?php the_permalink(); ?>"><span
						class="sr-only"><?php the_title(); ?></span></a>
				<div class="tutor-mycourse-thumbnail"
				     style="background-image: url(<?php echo esc_url( $tutor_course_img ); ?>)"></div>

				<div class="tutor-mycourse-content">
					<?php Edumall_Templates::render_rating( $avg_rating, [
						'style'         => '03',
						'wrapper_class' => 'tutor-mycourse-rating',
					] ); ?>

					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

					<div class="tutor-meta tutor-course-metadata">
						<?php
						$total_lessons     = tutor_utils()->get_lesson_count_by_course();
						$completed_lessons = tutor_utils()->get_completed_lesson_count_by_course();
						?>
						<ul>
							<li>
								<?php
								esc_html_e( 'Total Lessons:', 'edumall' );
								echo "<span>$total_lessons</span>";
								?>
							</li>
							<li>
								<?php
								esc_html_e( 'Completed Lessons:', 'edumall' );
								echo "<span>$completed_lessons / $total_lessons</span>";
								?>
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
			<?php esc_html_e( 'You are not enrolled in any courses at this moment.', 'edumall' ); ?>
		</div>
	<?php endif; ?>
</div>
