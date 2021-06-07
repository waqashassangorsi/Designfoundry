<?php
/**
 * Template part for display login form on popup.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Edumall
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="popup-content-header">
	<h3 class="popup-title"><?php esc_html_e( 'Login', 'edumall' ); ?></h3>
	<p class="popup-description">
		<?php printf( esc_html__( 'Don\'t have an account yet? %sSign up for free%s', 'edumall' ), '<a href="#" class="open-popup-register link-transition-02">', '</a>' ); ?>
	</p>
</div>


<div class="popup-content-body">

	<?php do_action( 'edumall_before_popup_login_form' ); ?>

	<form id="edumall-login-form" class="edumall-login-form" method="post">

		<?php do_action( 'edumall_before_popup_login_form_fields' ); ?>

		<div class="form-group">
			<label for="ip_user_login"
			       class="form-label"><?php esc_html_e( 'Username or email', 'edumall' ); ?></label>
			<input type="text" id="ip_user_login" class="form-control form-input" name="user_login"
			       placeholder="<?php esc_attr_e( 'Your username or email', 'edumall' ); ?>">
		</div>

		<div class="form-group">
			<label for="ip_password" class="form-label"><?php esc_html_e( 'Password', 'edumall' ); ?></label>
			<div class="form-input-group form-input-password">
				<input type="password" id="ip_password" class="form-control form-input" name="password"
				       placeholder="<?php esc_attr_e( 'Password', 'edumall' ); ?>">
				<button type="button" class="btn-pw-toggle" data-toggle="0"
				        aria-label="<?php esc_attr_e( 'Show password', 'edumall' ); ?>">
				</button>
			</div>
		</div>

		<div class="form-group row-flex row-middle">
			<div class="col-grow">
				<label
					class="form-label form-label-checkbox" for="ip_rememberme">
					<input class="form-checkbox" name="rememberme"
					       type="checkbox" id="ip_rememberme" value="forever"/>
					<span><?php esc_html_e( 'Remember me', 'edumall' ); ?></span>
				</label>
			</div>
			<div class="col-shrink">
				<div class="forgot-password">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"
					   class="open-popup-lost-password forgot-password-link link-transition-02"><?php esc_html_e( 'Forgot your password?', 'edumall' ); ?></a>
				</div>
			</div>
		</div>

		<?php do_action( 'edumall_after_popup_login_form_fields' ); ?>

		<div class="form-response-messages"></div>

		<div class="form-group">
			<?php wp_nonce_field( 'user_login', 'user_login_nonce' ); ?>
			<input type="hidden" name="action" value="edumall_user_login">
			<button type="submit" class="button form-submit"><?php esc_html_e( 'Log In', 'edumall' ); ?></button>
		</div>
	</form>

	<?php do_action( 'edumall_after_popup_login_form' ); ?>

</div>
