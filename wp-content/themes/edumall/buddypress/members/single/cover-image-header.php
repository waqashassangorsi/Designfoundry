<?php
/**
 * BuddyPress - Users Cover Image Header
 *
 * @since   3.0.0
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="cover-image-container">
	<div id="header-cover-image"></div>

	<div id="item-header-cover-image">
		<div id="item-header-avatar">
			<a href="<?php bp_displayed_user_link(); ?>">
				<?php bp_displayed_user_avatar( 'type=full' ); ?>
			</a>
		</div><!-- #item-header-avatar -->

	</div><!-- #item-header-cover-image -->

	<div class="entry-member-content">
		<div class="entry-member-meta">
			<?php
			bp_nouveau_member_header_buttons( [
				'container'         => 'ul',
				'button_element'    => 'button',
				'container_classes' => array( 'member-header-actions' ),
			] );
			?>
		</div>
		<div class="entry-member-info">
			<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
				<h2 class="user-nicename"><?php echo bp_get_displayed_user_fullname(); ?></h2>
			<?php endif; ?>

			<?php bp_nouveau_member_hook( 'before', 'header_meta' ); ?>

			<?php if ( bp_nouveau_member_has_meta() ) : ?>
				<div class="item-meta">
					<?php bp_nouveau_member_meta(); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="entry-member-social-links">
			<?php echo Edumall_Templates::get_user_socials_html( bp_displayed_user_id() ); ?>
		</div>
	</div><!-- #item-header-content -->


</div><!-- #cover-image-container -->
