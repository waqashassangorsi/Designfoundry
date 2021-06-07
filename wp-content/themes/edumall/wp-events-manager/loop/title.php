<?php
/**
 * The Template for displaying title in loop.
 *
 * Override this template by copying it to yourtheme/wp-events-manager/loop/title.php
 *
 * @author        ThimPress, leehld
 * @package       WP-Events-Manager/Template
 * @version       2.1.7
 */

defined( 'ABSPATH' ) || exit;
?>
<h3 class="event-title">
	<a href="<?php the_permalink() ?>" class="link-in-title"><?php the_title(); ?></a>
</h3>
