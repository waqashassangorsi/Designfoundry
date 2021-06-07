<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$uid  = get_current_user_id();
$user = get_userdata( $uid );

$profile_settings_link = tutor_utils()->get_tutor_dashboard_page_permalink( 'settings' );
$rdate                 = wp_date( 'D d M Y, h:i:s a', strtotime( $user->user_registered ) );
$fname                 = $user->first_name;
$lname                 = $user->last_name;
$uname                 = $user->user_login;
$email                 = $user->user_email;
$phone                 = get_user_meta( $uid, 'phone_number', true );
$bio                   = nl2br( strip_tags( get_user_meta( $uid, '_tutor_profile_bio', true ) ) );
$job_title             = strip_tags( get_user_meta( $uid, '_tutor_profile_job_title', true ) );
?>
<h3><?php esc_html_e( 'My Profile', 'edumall' ); ?></h3>

<div class="tutor-dashboard-content-inner">
	<div class="dashboard-profile-info">
		<div class="dashboard-profile-info-item profile-registration-date">
			<div class="heading">
				<?php esc_html_e( 'Registration Date', 'edumall' ); ?>
			</div>
			<div class="content">
				<p><?php echo esc_html( $rdate ); ?></p>
			</div>
		</div>
		<div class="dashboard-profile-info-item profile-first-name">
			<div class="heading">
				<?php esc_html_e( 'First Name', 'edumall' ); ?>
			</div>
			<div class="content">
				<p><?php printf( '%s', $fname ? esc_html( $fname ) : esc_html__( '________', 'edumall' ) ); ?></p>
			</div>
		</div>
		<div class="dashboard-profile-info-item profile-last-name">
			<div class="heading">
				<?php esc_html_e( 'Last Name', 'edumall' ); ?>
			</div>
			<div class="content">
				<p><?php printf( '%s', $lname ? esc_html( $lname ) : esc_html__( '________', 'edumall' ) ); ?></p>
			</div>
		</div>
		<div class="dashboard-profile-info-item profile-username">
			<div class="heading">
				<?php esc_html_e( 'Username', 'edumall' ); ?>
			</div>
			<div class="content">
				<p><?php echo esc_html( $uname ); ?></p>
			</div>
		</div>
		<div class="dashboard-profile-info-item profile-email">
			<div class="heading">
				<?php esc_html_e( 'Email', 'edumall' ); ?>
			</div>
			<div class="content">
				<p><?php echo esc_html( $email ); ?></p>
			</div>
		</div>
		<div class="dashboard-profile-info-item profile-phone-number">
			<div class="heading">
				<?php esc_html_e( 'Phone Number', 'edumall' ); ?>
			</div>
			<div class="content">
				<p><?php printf( '%s', $phone ? esc_html( $phone ) : esc_html__( '________', 'edumall' ) ); ?></p>
			</div>
		</div>
		<div class="dashboard-profile-info-item profile-job-title">
			<div class="heading">
				<?php esc_html_e( 'Job Title', 'edumall' ); ?>
			</div>
			<div class="content">
				<p><?php printf( '%s', $job_title ? esc_html( $job_title ) : esc_html__( '________', 'edumall' ) ); ?></p>
			</div>
		</div>
		<div class="dashboard-profile-info-item profile-bio">
			<div class="heading">
				<?php esc_html_e( 'Bio', 'edumall' ); ?>
			</div>
			<div class="content">
				<p><?php printf( '%s', $bio ? esc_html( $bio ) : esc_html__( '________', 'edumall' ) ); ?></p>
			</div>
		</div>
	</div>

</div>

