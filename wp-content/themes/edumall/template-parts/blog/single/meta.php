<?php
/**
 * The template for displaying single post meta.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Edumall
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="entry-post-meta">
	<?php if ( Edumall::setting( 'single_post_author_enable' ) === '1' ) : ?>
		<?php Edumall_Post::instance()->meta_author_template(); ?>
	<?php endif; ?>

	<?php if ( Edumall::setting( 'single_post_date_enable' ) === '1' ) : ?>
		<?php Edumall_Post::instance()->meta_date_template(); ?>
	<?php endif; ?>

	<?php if ( Edumall::setting( 'single_post_view_count_enable' ) === '1' ) : ?>
		<?php Edumall_Post::instance()->meta_view_count_template(); ?>
	<?php endif; ?>

	<?php if ( Edumall::setting( 'single_post_comment_count_enable' ) === '1' ) : ?>
		<?php Edumall_Post::instance()->entry_meta_comment_count_template(); ?>
	<?php endif; ?>
</div>
