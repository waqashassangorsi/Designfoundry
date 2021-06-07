<?php
/**
 * Template for displaying add to wishlist button.
 *
 * @since   v.1.0.0
 *
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Edumall/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $edumall_course;

$button_text            = $edumall_course->is_wishlisted() ? esc_html__( 'Remove from wishlist', 'edumall' ) : esc_html__( 'Add to wishlist', 'edumall' );
$wrapper_button_classes = 'course-loop-wishlist-button hint--bounce hint--top hint--primary';
$wrapper_button_classes .= $edumall_course->is_wishlisted() ? ' added' : '';

Edumall_Templates::render_button( [
	'link'               => [
		'url' => 'javascript:void(0);',
	],
	'icon'               => 'far fa-heart',
	'style'              => 'flat',
	'size'               => 'xs',
	'extra_class'        => 'edumall-course-wishlist-btn button-grey',
	'wrapper_class'      => $wrapper_button_classes,
	'attributes'         => [
		'data-course-id' => get_the_ID(),
	],
	'wrapper_attributes' => [
		'aria-label' => $button_text,
	],
] );
