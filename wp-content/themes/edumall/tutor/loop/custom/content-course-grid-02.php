<?php
/**
 * A single course loop
 *
 * @since   1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package Edumall/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'tutor_course/loop/before_content' );

/**
 * @hooked tutor_course_loop_header
 * @see    tutor_course_loop_header()
 */
do_action( 'tutor_course/loop/before_header' );
do_action( 'tutor_course/loop/header' );
do_action( 'tutor_course/loop/after_header' );

do_action( 'tutor_course/loop/start_content_wrap' );

do_action( 'tutor_course/loop/before_title' );
do_action( 'tutor_course/loop/title' );
do_action( 'tutor_course/loop/after_title' );

tutor_load_template( 'loop.custom.instructor' );

Edumall_Tutor::instance()->course_loop_price();

do_action( 'tutor_course/loop/rating' );

do_action( 'tutor_course/loop/end_content_wrap' );

do_action( 'tutor_course/loop/after_content' );

tutor_load_template( 'loop.custom.content-grid-quick-view' );
