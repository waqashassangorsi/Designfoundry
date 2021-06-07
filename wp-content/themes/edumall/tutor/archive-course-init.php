<?php
/**
 * @hook   tutor_course/archive/before_loop
 *
 * @hooked tutor_course_archive_filter_bar
 * @see    tutor_course_archive_filter_bar()
 */
do_action( 'tutor_course/archive/before_loop' );
?>

<?php if ( have_posts() ) : ?>

	<?php
	global $edumall_course;
	$edumall_course_clone = $edumall_course;
	?>

	<?php tutor_course_loop_start(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<?php
		/***
		 * Setup course object.
		 */
		$edumall_course = new Edumall_Course();
		?>
		<?php
		/**
		 * Usage Idea, you may keep a loop within a wrap, such as bootstrap col
		 *
		 * @hook   tutor_course/archive/before_loop_course
		 *
		 * @hooked tutor_course_loop_before_content
		 * @see    tutor_course_loop_before_content()
		 */
		do_action( 'tutor_course/archive/before_loop_course' );
		?>

		<?php tutor_load_template( 'loop.course' ); ?>

		<?php
		/**
		 * Usage Idea, If you start any div before course loop, you can end it here, such as </div>
		 *
		 * @hook   tutor_course/archive/after_loop_course
		 *
		 * @hooked tutor_course_loop_after_content
		 * @see    tutor_course_loop_after_content()
		 */
		do_action( 'tutor_course/archive/after_loop_course' );
		?>
	<?php endwhile; ?>

	<?php tutor_course_loop_end(); ?>

	<?php
	/**
	 * Reset course object.
	 */
	$edumall_course = $edumall_course_clone;
	?>

	<?php tutor_course_archive_pagination(); ?>
<?php endif; ?>

<?php do_action( 'tutor_course/archive/after_loop' ); ?>
