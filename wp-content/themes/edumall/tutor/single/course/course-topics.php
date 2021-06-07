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
$topics    = $edumall_course->get_topics();
$course_id = get_the_ID();
?>

<?php do_action( 'tutor_course/single/before/topics' ); ?>

<?php if ( $topics->have_posts() ) { ?>
	<div class="tutor-single-course-segment tutor-course-topics-wrap">
		<div class="tutor-course-topics-header">
			<div class="tutor-course-topics-header-left">
				<h4 class="tutor-segment-title"><?php esc_html_e( 'Curriculum', 'edumall' ); ?></h4>
			</div>
			<div class="tutor-course-topics-header-right">
				<?php
				$tutor_lesson_count = $edumall_course->get_lesson_count();

				if ( $tutor_lesson_count ) {
					echo '<span class="topics-total-lessons">' . sprintf( _n( '%s Lesson', '%s Lessons', $tutor_lesson_count, 'edumall' ), $tutor_lesson_count ) . '</span>';
				}

				$tutor_course_duration = get_tutor_course_duration_context( $course_id );
				if ( $tutor_course_duration ) {
					echo '<span class="topics-total-duration">' . $tutor_course_duration . '</span>';
				}
				?>
			</div>
		</div>
		<div class="tutor-course-topics-contents">
			<?php

			$index = 0;

			if ( $topics->have_posts() ) {
				while ( $topics->have_posts() ) {
					$topics->the_post();
					$index++;
					?>

					<div class="tutor-course-topic <?php if ( $index == 1 ) {
						echo "tutor-active";
					} ?>">
						<div class="tutor-course-title">
							<h4><i class="tutor-icon-plus"></i> <?php the_title(); ?></h4>
						</div>


						<div class="tutor-course-lessons"
							<?php if ( $index > 1 ): ?>
								style="display: none;"
							<?php endif; ?>
						>
							<?php
							$lessons = tutor_utils()->get_course_contents_by_topic( get_the_ID(), -1 );

							if ( $lessons->have_posts() ) {
								while ( $lessons->have_posts() ) {
									$lessons->the_post();
									global $post;

									$video = tutor_utils()->get_video_info();

									$play_time = false;
									if ( $video ) {
										$play_time = $video->playtime;
									}

									$lesson_icon = $play_time ? 'far fa-play-circle' : 'far fa-file-alt';

									if ( $post->post_type === 'tutor_quiz' ) {
										$lesson_icon = 'far fa-question-circle';
									}
									if ( $post->post_type === 'tutor_assignments' ) {
										$lesson_icon = 'far fa-file-edit';
									}
									?>

									<div class="tutor-course-lesson">
										<h5>
											<?php
											$lesson_title = '';
											if ( has_post_thumbnail() ) {
												$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
												$lesson_title  .= "<i style='background:url({$thumbnail_url})' class='tutor-lesson-thumbnail-icon $lesson_icon'></i>";
											} else {
												$lesson_title .= "<i class='$lesson_icon'></i>";
											}

											if ( $edumall_course->is_enrolled() ) {
												$lesson_title .= "<a href='" . get_the_permalink() . "'> " . get_the_title() . " </a>";

												$lesson_title .= $play_time ? "<span class='tutor-lesson-duration'>$play_time</span>" : '';

												echo '' . $lesson_title;
											} else {
												$lesson_title .= get_the_title();
												$lesson_title .= $play_time ? "<span class='tutor-lesson-duration'>$play_time</span>" : '';

												/**
												 * Can't remove plugin filter.
												 * Then used theme hook instead of.
												 */
												//echo apply_filters( 'tutor_course/contents/lesson/title', $lesson_title, get_the_ID() );
												echo apply_filters( 'edumall/tutor_course/contents/lesson/title', $lesson_title, get_the_ID() );
											}

											?>
										</h5>
									</div>

									<?php
								}
								$lessons->reset_postdata();
							}
							?>
						</div>
					</div>
					<?php
				}
				$topics->reset_postdata();
			}
			?>
		</div>
	</div>
<?php } ?>


<?php do_action( 'tutor_course/single/after/topics' ); ?>
