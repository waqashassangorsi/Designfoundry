<?php
/**
 * Template for displaying student Public Profile
 *
 * @since   v.1.0.0
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

get_header();

$user_name = sanitize_text_field( get_query_var( 'tutor_student_username' ) );
$sub_page  = sanitize_text_field( get_query_var( 'profile_sub_page' ) );
$get_user  = tutor_utils()->get_user_by_login( $user_name );

if ( empty( $get_user ) ) {
	return;
}

$user_id = $get_user->ID;

global $wp_query;

$profile_sub_page = '';
if ( isset( $wp_query->query_vars['profile_sub_page'] ) && $wp_query->query_vars['profile_sub_page'] ) {
	$profile_sub_page = $wp_query->query_vars['profile_sub_page'];
}
?>
	<div class="tutor-dashboard-left-menu">
		<div class="dashboard-nav-header">
			<?php edumall_load_template( 'branding' ); ?>
		</div>
		<div class="dashboard-nav-content">
			<div class="dashboard-nav-content-inner">
				<?php
				$permalinks          = tutor_utils()->user_profile_permalinks();
				$student_profile_url = tutor_utils()->profile_url( $user_id );
				?>
				<ul class="tutor-dashboard-permalinks">
					<li class="tutor-dashboard-menu-bio <?php echo '' === $profile_sub_page ? 'active' : ''; // WPCS: XSS OK. ?>">
						<a href="<?php echo tutor_utils()->profile_url( $user_id ); ?>"><?php esc_html_e( 'My Profile', 'edumall' ); ?></a>
					</li>
					<?php
					if ( is_array( $permalinks ) && count( $permalinks ) ) {
						foreach ( $permalinks as $permalink_key => $permalink ) {
							$li_class     = "tutor-dashboard-menu-{$permalink_key}";
							$active_class = $profile_sub_page == $permalink_key ? "active" : "";
							echo '<li class="' . $active_class . ' ' . $li_class . '"><a href="' . trailingslashit( $student_profile_url ) . $permalink_key . '"> ' . $permalink . ' </a> </li>';
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>

	<div class="page-content">
		<div class="tutor-dashboard-header-wrap">
			<div class="container small-gutter">
				<div class="row">
					<div class="col-md-12">
						<div class="tutor-dashboard-header">
							<div class="tutor-dashboard-header-avatar">
								<img
									src="<?php echo get_avatar_url( $user_id, array( 'size' => 150 ) ); ?>"/>
							</div>
							<div class="tutor-dashboard-header-info">
								<h4 class="tutor-dashboard-header-display-name">
									<span class="welcome-text"><?php esc_html_e( 'Howdy, I\'m', 'edumall' ); ?></span>
									<?php echo esc_html( $get_user->display_name ); ?>
								</h4>

								<?php if ( user_can( $user_id, tutor()->instructor_role ) ) : ?>
									<?php
									$instructor_rating       = tutils()->get_instructor_ratings( $get_user->ID );
									$instructor_rating_count = sprintf(
										_n( '%s rating', '%s ratings', $instructor_rating->rating_count, 'edumall' ),
										number_format_i18n( $instructor_rating->rating_count )
									);
									?>
									<div class="tutor-dashboard-header-stats">
										<div class="tutor-dashboard-header-ratings">
											<?php Edumall_Templates::render_rating( $instructor_rating->rating_avg ) ?>
											<span
												class="rating-average"><?php echo esc_html( $instructor_rating->rating_avg ); ?></span>
											<span class="rating-count">
												<?php echo '(' . esc_html( $instructor_rating_count ) . ')'; ?>
											</span>
										</div>
									</div>
								<?php endif; ?>
							</div>

							<?php
							$tutor_user_social_icons = tutor_utils()->tutor_user_social_icons();
							?>
							<?php if ( count( $tutor_user_social_icons ) ) : ?>
								<?php
								$social_html = '';

								ob_start();
								foreach ( $tutor_user_social_icons as $key => $social_icon ) {
									$icon_url = get_user_meta( $user_id, $key, true );
									if ( $icon_url ) {
										echo "<a href='" . esc_url( $icon_url ) . "' target='_blank' class='" . $social_icon['icon_classes'] . "'></a>";
									}
								}
								$social_html = ob_get_clean();
								?>
								<?php if ( ! empty( $social_html ) ) : ?>
									<div class="tutor-dashboard-social-icons">
										<h4 class="social-icons-text-help"><?php esc_html_e( 'Follow me', "edumall" ); ?></h4>
										<?php echo '' . $social_html; ?>
									</div>
								<?php endif; ?>
							<?php endif; ?>

							<div class="dashboard-header-toggle-menu">
								<span class="fal fa-bars"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container small-gutter">
			<div class="row">
				<div class="page-main-content">
					<div class="tutor-dashboard-content">
						<?php
						if ( $sub_page ) {
							tutor_load_template( 'profile.' . $sub_page, compact( 'get_user' ) );
						} else {
							tutor_load_template( 'profile.bio', compact( 'get_user' ) );
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
