<?php
/**
 * Course enroll button
 *
 * @since   1.0.0
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Edumall/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $edumall_course;

$tutor_course_sell_by = apply_filters( 'tutor_course_sell_by', null );

$enroll_btn = Edumall_Templates::render_button( [
	'echo' => false,
	'link' => [
		'url' => get_the_permalink(),
	],
	'text' => esc_html__( 'Get Enrolled', 'edumall' ),
	'size' => 'xs',
] );

if ( $tutor_course_sell_by ) {
	switch ( $tutor_course_sell_by ) {
		case 'woocommerce' :
		case 'edd' :
			if ( $edumall_course->is_purchasable() ) {
				$enroll_btn = tutor_course_loop_add_to_cart( false );
			}
			break;
	}
}

echo '<div class="course-loop-enrolled-button">' . $enroll_btn . '</div>'; ?>
