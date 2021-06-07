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

Edumall_Templates::render_button( [
	'link'        => [
		'url' => 'javascript:void(0);',
	],
	'text'        => esc_html__( 'Add to wishlist', 'edumall' ),
	'icon'        => 'fal fa-heart',
	'style'       => 'text',
	'extra_class' => 'edumall-course-wishlist-btn wishlist-button-02',
	'attributes'  => [
		'data-course-id' => get_the_ID(),
	],
] );
