<?php
/**
 * Display Topics and Lesson lists for learn
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$currentPost = $post;

$course_id = 0;
if ( $post->post_type === 'tutor_quiz' ) {
	$course    = tutor_utils()->get_course_by_quiz( get_the_ID() );
	$course_id = $course->ID;
} elseif ( $post->post_type === 'tutor_assignments' ) {
	$course_id = get_post_meta( $post->ID, '_tutor_course_id_for_assignments', true );
} else {
	$course_id = get_post_meta( $post->ID, '_tutor_course_id_for_lesson', true );
}

$enable_q_and_a_on_course = tutor_utils()->get_option( 'enable_q_and_a_on_course' );
?>

<?php do_action( 'tutor_lesson/single/before/lesson_sidebar' ); ?>

<div class="tutor-sidebar-tabs-wrap">
	<div class="tutor-tabs-btn-group">
		<a href="#tutor-lesson-sidebar-tab-content"
			<?php if ( $enable_q_and_a_on_course ): ?>
				class="active"
			<?php endif; ?>
		>
			<i class="far fa-book-open"></i>
			<span> <?php esc_html_e( 'Lesson List', 'edumall' ); ?></span></a>
		<?php if ( $enable_q_and_a_on_course ) { ?>
			<a href="#tutor-lesson-sidebar-qa-tab-content">
				<i class="far fa-question-circle"></i>
				<span><?php esc_html_e( 'Browse Q&A', 'edumall' ); ?></span>
			</a>
		<?php } ?>
	</div>

	<div class="tutor-sidebar-tabs-content">

		<div id="tutor-lesson-sidebar-tab-content" class="tutor-lesson-sidebar-tab-item">
			<?php
			$topics = tutor_utils()->get_topics( $course_id );
			if ( $topics->have_posts() ) {
				while ( $topics->have_posts() ) {
					$topics->the_post();
					$topic_id      = get_the_ID();
					$topic_summery = get_the_content();

					$topic_title_class = 'tutor-topics-title';
					if ( $topic_summery ) {
						$topic_title_class .= ' has-summery';
					}
					?>

					<div class="tutor-topics-in-single-lesson tutor-topics-<?php echo esc_attr( $topic_id ); ?>">
						<div class="<?php echo esc_attr( $topic_title_class ); ?>">
							<h3>
								<?php
								the_title();
								if ( $topic_summery ) {
									echo "<span class='toogle-informaiton-icon'><i class='fas fa-info'></i></span>";
								}
								?>
							</h3>
							<button class="tutor-single-lesson-topic-toggle"><i class="tutor-icon-plus"></i>
							</button>
						</div>

						<?php if ( $topic_summery ) : ?>
							<div class="tutor-topics-summery">
								<?php echo esc_html( $topic_summery ); ?>
							</div>
						<?php endif; ?>

						<div class="tutor-lessons-under-topic" style="display: none">
							<?php
							do_action( 'tutor/lesson_list/before/topic', $topic_id );

							$lessons = tutor_utils()->get_course_contents_by_topic( get_the_ID(), -1 );
							if ( $lessons->have_posts() ) {
								while ( $lessons->have_posts() ) {
									$lessons->the_post();

									if ( $post->post_type === 'tutor_quiz' ) {
										$quiz = $post;

										$wrapper_class = 'tutor-single-lesson-items quiz-single-item';
										$wrapper_class .= ' quiz-single-item-' . $quiz->ID;
										if ( $currentPost->ID === get_the_ID() ) {
											$wrapper_class .= ' active';
										}
										?>
										<div class="<?php echo esc_attr( $wrapper_class ); ?>"
										     data-quiz-id="<?php echo esc_attr( $quiz->ID ); ?>">
											<a href="<?php echo esc_url( get_permalink( $quiz->ID ) ); ?>"
											   class="sidebar-single-quiz-a"
											   data-quiz-id="<?php echo esc_attr( $quiz->ID ); ?>">
												<i class="far fa-question-circle"></i>
												<span
													class="lesson_title"><?php echo esc_html( $quiz->post_title ); ?></span>
												<span class="tutor-lesson-right-icons">
                                                    <?php
                                                    do_action( 'tutor/lesson_list/right_icon_area', $post );

                                                    $time_limit = tutor_utils()->get_quiz_option( $quiz->ID, 'time_limit.time_value' );
                                                    if ( $time_limit ) {
	                                                    $time_type = tutor_utils()->get_quiz_option( $quiz->ID, 'time_limit.time_type' );
	                                                    echo "<span class='quiz-time-limit'>{$time_limit} {$time_type}</span>";
                                                    }
                                                    ?>
                                                    </span>
											</a>
										</div>
										<?php
									} elseif ( $post->post_type === 'tutor_assignments' ) {
										/**
										 * Assignments
										 *
										 * @since this block v.1.3.3
										 */
										$wrapper_class = 'tutor-single-lesson-items assignments-single-item';
										$wrapper_class .= ' assignment-single-item-' . $post->ID;
										if ( $currentPost->ID === get_the_ID() ) {
											$wrapper_class .= ' active';
										}
										?>
										<div class="<?php echo esc_attr( $wrapper_class ); ?>"
										     data-assignment-id="<?php echo esc_attr( $post->ID ); ?>">
											<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"
											   class="sidebar-single-assignment-a"
											   data-assignment-id="<?php echo esc_attr( $post->ID ); ?>">
												<i class="far fa-file-edit"></i>
												<span
													class="lesson_title"> <?php echo esc_html( $post->post_title ); ?> </span>
												<span class="tutor-lesson-right-icons">
                                                    <?php do_action( 'tutor/lesson_list/right_icon_area', $post ); ?>
                                                </span>
											</a>
										</div>
										<?php
									} else {
										/**
										 * Lesson
										 */

										$video = tutor_utils()->get_video();
										$video = Edumall_Tutor::instance()->get_video_info( $video, get_the_ID() );

										$play_time = false;
										if ( $video ) {
											$play_time = $video->playtime;
										}
										$is_completed_lesson = tutor_utils()->is_completed_lesson();

										$wrapper_class = 'tutor-single-lesson-items';
										if ( $currentPost->ID === get_the_ID() ) {
											$wrapper_class .= ' active';
										}
										?>
										<div class="<?php echo esc_attr( $wrapper_class ); ?>">
											<a href="<?php the_permalink(); ?>" class="tutor-single-lesson-a"
											   data-lesson-id="<?php echo esc_attr( get_the_ID() ); ?>">

												<?php
												$tutor_lesson_type_icon = $play_time ? 'video' : 'document';
												?>
												<?php if ( 'video' === $tutor_lesson_type_icon ) : ?>
													<i class="far fa-play-circle"></i>
												<?php else : ?>
													<i class="far fa-file-alt"></i>
												<?php endif; ?>
												<span class="lesson_title"><?php the_title(); ?></span>
												<span class="tutor-lesson-right-icons">
                                                        <?php
                                                        do_action( 'tutor/lesson_list/right_icon_area', $post );
                                                        if ( $play_time ) {
	                                                        echo "<i class='tutor-play-duration'>$play_time</i>";
                                                        }
                                                        $lesson_complete_icon = $is_completed_lesson ? 'tutor-icon-mark tutor-done' : '';
                                                        echo "<i class='tutor-lesson-complete $lesson_complete_icon'></i>";
                                                        ?>
                                                    </span>
											</a>
										</div>

										<?php
									}
								}
								$lessons->reset_postdata();
							}
							?>

							<?php do_action( 'tutor/lesson_list/after/topic', $topic_id ); ?>
						</div>
					</div>

					<?php
				}
				$topics->reset_postdata();
				wp_reset_postdata();
			}
			?>
		</div>

		<div id="tutor-lesson-sidebar-qa-tab-content" class="tutor-lesson-sidebar-tab-item" style="display: none;">
			<?php
			tutor_lesson_sidebar_question_and_answer();
			?>
		</div>

	</div>

</div>

<?php do_action( 'tutor_lesson/single/after/lesson_sidebar' ); ?>
