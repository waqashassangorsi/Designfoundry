<?php
/**
 * Course loop category
 *
 * @since   1.0.0
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Edumall/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$category = Edumall_Tutor::instance()->get_the_category();
//print_r($category);
$icon_id  = get_term_meta($category->term_id,'icon_id',true);
$image =  wp_get_attachment_url($icon_id);
?>
<?php if ( $category ): ?>
	<?php
	$link = get_term_link( $category );
	?>
	<div class="course-loop-category">
		<a href="<?php echo esc_url( $link ); ?>"><img src='<?php echo $image;?>'> <?php echo esc_html( $category->name ); ?></a>
	</div>
<?php endif; ?>
