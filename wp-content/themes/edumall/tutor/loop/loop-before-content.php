<?php
/**
 * Course Loop Start
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

global $edumall_course;

$unique_id = $edumall_course->get_unique_id();
?>
<div <?php post_class( 'grid-item' ); ?>>
	<div class="course-loop-wrapper edumall-box edumall-tooltip"
	     data-tooltip="<?php echo esc_attr( 'quick-view-' . $unique_id ); ?>">
