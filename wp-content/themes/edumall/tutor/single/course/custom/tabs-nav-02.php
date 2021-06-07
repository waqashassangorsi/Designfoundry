<?php
/**
 * Display tabs nav
 *
 * @since   v.1.0.0
 * @author  thememove
 * @url https://thememove.com
 *
 * @package Edumall/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var Edumall_Course $edumall_course
 */
global $edumall_course;

/**
 * @var WP_Query $topics
 */
$topics = $edumall_course->get_topics();
?>
<ul id="tutor-single-course-tabs-nav" class="single-course-nav style-02">
	<li class="active">
		<a href="#tutor-course-tab-overview"><?php esc_html_e( 'Overview', 'edumall' ); ?></a>
	</li>
	<?php if ( $topics->have_posts() ): ?>
		<li>
			<a href="#tutor-course-tab-curriculum"><?php esc_html_e( 'Curriculum', 'edumall' ); ?></a>
		</li>
	<?php endif; ?>
	<?php if ( $edumall_course->is_enrolled() && ! empty( $edumall_course->get_attachments() ) ): ?>
		<li>
			<a href="#tutor-course-tab-resources"><?php esc_html_e( 'Resources', 'edumall' ); ?></a>
		</li>
	<?php endif; ?>
	<?php if ( $edumall_course->is_enrolled() ): ?>
		<li>
			<a href="#tutor-course-tab-question-and-answer"><?php esc_html_e( 'Question & Answer', 'edumall' ); ?></a>
		</li>
	<?php endif; ?>
	<li>
		<a href="#tutor-course-tab-instructors"><?php esc_html_e( 'Instructors', 'edumall' ); ?></a>
	</li>
	<?php if ( $edumall_course->is_enrolled() ): ?>
		<li>
			<a href="#tutor-course-tab-announcements"><?php esc_html_e( 'Announcements', 'edumall' ); ?></a>
		</li>
	<?php endif; ?>
	<!--
	<li>
		<a href="#tutor-course-tab-frequently"><?php /*esc_html_e( 'Frequently', 'edumall' ); */ ?></a>
		</li>
	-->
	<!-- Future support -->
	<?php if ( $edumall_course->get_reviews() ) : ?>
		<li>
			<a href="#tutor-course-tab-reviews"><?php esc_html_e( 'Reviews', 'edumall' ); ?></a>
		</li>
	<?php endif; ?>
	<!--
	<li>
		<a href="#tutor-course-tab-more-courses"><?php /*esc_html_e( 'More Courses', 'edumall' ); */ ?></a>
		</li>
	-->
	<!-- Future support -->
</ul>
