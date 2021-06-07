<?php
/**
 * @package TutorLMS/Templates
 * @version 1.7.5
 */

defined( 'ABSPATH' ) || exit;

$user = wp_get_current_user();

$profile_placeholder = Edumall_Helper::placeholder_avatar_src();
$profile_photo_src   = $profile_placeholder;
$profile_photo_id    = get_user_meta( $user->ID, '_tutor_profile_photo', true );
if ( $profile_photo_id ) {
	$url = wp_get_attachment_image_url( $profile_photo_id, 'full' );
	! empty( $url ) ? $profile_photo_src = $url : 0;
}

$cover_placeholder = tutor()->url . 'assets/images/cover-photo.jpg';
$cover_photo_src   = $cover_placeholder;
$cover_photo_id    = get_user_meta( $user->ID, '_tutor_cover_photo', true );
if ( $cover_photo_id ) {
	$url = wp_get_attachment_image_url( $cover_photo_id, 'full' );
	! empty( $url ) ? $cover_photo_src = $url : 0;
}
?>
<div class="tutor-dashboard-content-inner">

	<?php do_action( 'tutor_profile_edit_form_before' ); ?>

	<form action="" method="post" enctype="multipart/form-data"
	      class="dashboard-settings-form dashboard-settings-profile-form">
		<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
		<input type="hidden" value="tutor_profile_edit" name="tutor_action"/>

		<?php
		$errors = apply_filters( 'tutor_profile_edit_validation_errors', array() );
		if ( is_array( $errors ) && count( $errors ) ) {
			echo '<div class="tutor-alert-warning tutor-mb-10"><ul class="tutor-required-fields">';
			foreach ( $errors as $error_key => $error_value ) {
				echo "<li>{$error_value}</li>";
			}
			echo '</ul></div>';
		}
		?>

		<?php do_action( 'tutor_profile_edit_input_before' ); ?>

		<div class="row">
			<div class="col-md-12 col-lg-6">
				<div class="dashboard-content-box">
					<h4 class="dashboard-content-box-title"><?php esc_html_e( 'Contact information', 'edumall' ); ?></h4>
					<p><?php esc_html_e( 'Provide your details below to create your account profile', 'edumall' ) ?></p>

					<div class="row">
						<div class="col-md-6">
							<div class="tutor-form-group">
								<label for="tutor_profile_first_name">
									<?php esc_html_e( 'First Name', 'edumall' ); ?>
								</label>
								<input type="text" id="tutor_profile_first_name" name="first_name"
								       value="<?php echo esc_attr( $user->first_name ); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="tutor-form-group">
								<label for="tutor_profile_last_name">
									<?php esc_html_e( 'Last Name', 'edumall' ); ?>
								</label>
								<input type="text" id="tutor_profile_last_name" name="last_name"
								       value="<?php echo esc_attr( $user->last_name ); ?>">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="tutor-form-group">
								<label for="tutor_profile_job_title">
									<?php esc_html_e( 'Job Title', 'edumall' ); ?>
								</label>
								<input type="text" id="tutor_profile_job_title" name="tutor_profile_job_title"
								       value="<?php echo esc_attr( get_user_meta( $user->ID, '_tutor_profile_job_title', true ) ); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="tutor-form-group">
								<label for="tutor_profile_phone_number">
									<?php esc_html_e( 'Phone Number', 'edumall' ); ?>
								</label>
								<input type="text" id="tutor_profile_phone_number" name="phone_number"
								       value="<?php echo esc_attr( get_user_meta( $user->ID, 'phone_number', true ) ); ?>">
							</div>
						</div>
					</div>

					<div class="tutor-form-group">
						<label for="tutor_profile_bio">
							<?php esc_html_e( 'Bio', 'edumall' ); ?>
						</label>
						<textarea name="tutor_profile_bio"
						          id="tutor_profile_bio"><?php echo strip_tags( get_user_meta( $user->ID, '_tutor_profile_bio', true ) ); ?></textarea>
					</div>

					<?php do_action( 'tutor_profile_edit_before_social_media', $user ); ?>

					<?php
					$tutor_user_social_icons = tutor_utils()->tutor_user_social_icons();
					foreach ( $tutor_user_social_icons as $key => $social_icon ) :
						?>
						<div class="tutor-form-group">
							<label
								for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $social_icon['label'] ); ?></label>
							<input type="text" id="<?php echo esc_attr( $key ); ?>"
							       name="<?php echo esc_attr( $key ); ?>"
							       value="<?php echo esc_attr( get_user_meta( $user->ID, $key, true ) ); ?>">
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-md-12 col-lg-6">
				<div class="dashboard-content-box">
					<div class="row">
						<div class="col-md-12">
							<h4 class="dashboard-content-box-title"><?php esc_html_e( 'Photo', 'edumall' ); ?></h4>
							<p><?php esc_html_e( 'Upload your profile photo.', 'edumall' ); ?></p>
							<div id="tutor_profile_cover_photo_editor">
								<input id="tutor_photo_dialogue_box" type="file" accept=".png,.jpg,.jpeg"/>
								<div id="tutor_cover_area" data-fallback="<?php echo $cover_placeholder; ?>"
								     style="background-image:url(<?php echo $cover_photo_src; ?>)">
						            <span class="tutor_cover_deleter">
						                <i class="far fa-trash-alt"></i>
						            </span>
									<div class="tutor_overlay">
										<button class="tutor_cover_uploader">
											<i class="far fa-camera"></i>&nbsp;
											<span>
                                                <?php echo $profile_photo_id ? esc_html__( 'Update Cover Photo', 'edumall' ) : esc_html__( 'Upload Cover Photo', 'edumall' ); ?>
                                            </span>
										</button>
									</div>
								</div>
								<div id="tutor_photo_meta_area">
									<img src="<?php echo tutor()->url . '/assets/images/'; ?>info-icon.svg"/>
									<span>Profile Photo Size: <span>200x200</span> pixels,</span>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;Cover Photo Size: <span>700x430</span> pixels </span>
									<span class="loader-area">Saving...</span>
								</div>
								<div id="tutor_profile_area" data-fallback="<?php echo $profile_placeholder; ?>"
								     style="background-image:url(<?php echo $profile_photo_src; ?>)">
									<div class="tutor_overlay">
										<i class="far fa-camera"></i>
									</div>
								</div>
								<div id="tutor_pp_option">
									<div class="up-arrow">
										<i></i>
									</div>
									<span class="tutor_pp_uploader">
						                <i class="far fa-upload"></i> <?php esc_html_e( 'Upload Photo', 'edumall' ); ?>
						            </span>
									<span class="tutor_pp_deleter">
						                <i class="far fa-trash-alt"></i> <?php esc_html_e( 'Delete', 'edumall' ); ?>
						            </span>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="tutor-form-group">
								<div class="tutor-form-group">
									<label
										for="display_name"><?php esc_html_e( 'Display name publicly as', 'edumall' ); ?></label>
									<select name="display_name" id="display_name">
										<?php
										$public_display                     = array();
										$public_display['display_nickname'] = $user->nickname;
										$public_display['display_username'] = $user->user_login;

										if ( ! empty( $user->first_name ) ) {
											$public_display['display_firstname'] = $user->first_name;
										}

										if ( ! empty( $user->last_name ) ) {
											$public_display['display_lastname'] = $user->last_name;
										}

										if ( ! empty( $user->first_name ) && ! empty( $user->last_name ) ) {
											$public_display['display_firstlast'] = $user->first_name . ' ' . $user->last_name;
											$public_display['display_lastfirst'] = $user->last_name . ' ' . $user->first_name;
										}

										if ( ! in_array( $user->display_name, $public_display ) ) { // Only add this if it isn't duplicated elsewhere
											$public_display = array( 'display_displayname' => $user->display_name ) + $public_display;
										}

										$public_display = array_map( 'trim', $public_display );
										$public_display = array_unique( $public_display );

										foreach ( $public_display as $id => $item ) {
											?>
											<option <?php selected( $user->display_name, $item ); ?>><?php echo esc_html( $item ); ?></option>
											<?php
										}
										?>
									</select>
									<p class="form-description">
										<?php esc_html_e( 'The display name is shown in all public fields, such as the author name, instructor name, student name, and name that will be printed on the certificate.', 'edumall' ); ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="tutor-form-group tutor-profile-form-btn-wrap form-submit-wrap">
			<button type="submit" name="tutor_register_student_btn" value="register"
			        class="tutor-button"><?php esc_html_e( 'Update Profile', 'edumall' ); ?></button>
		</div>

		<?php do_action( 'tutor_profile_edit_input_after' ); ?>

	</form>

	<?php do_action( 'tutor_profile_edit_form_after' ); ?>

</div>
