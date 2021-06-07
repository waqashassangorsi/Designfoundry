<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$earning_sum  = tutor_utils()->get_earning_sum();
$min_withdraw = tutor_utils()->get_option( 'min_withdraw_amount' );

$saved_account        = tutor_utils()->get_user_withdraw_method();
$withdraw_method_name = tutor_utils()->avalue_dot( 'withdraw_method_name', $saved_account );

$user_id = get_current_user_id();

$balance_formatted = tutor_utils()->tutor_price( $earning_sum->balance );
?>
<div class="tutor-dashboard-content-inner">

	<div class="dashboard-content-box withdraw-page-current-balance">
		<h4 class="dashboard-content-box-title"><?php esc_html_e( 'Current Balance', 'edumall' ); ?></h4>

		<div class="withdraw-balance-row">
			<p class="withdraw-balance-col">
				<?php if ( $earning_sum->balance >= $min_withdraw ) : ?>
					<?php echo sprintf(
						esc_html__( 'You currently have %s ready to withdraw', 'edumall' ),
						'<strong class="available_balance">' . $balance_formatted . '</strong>'
					); ?>
				<?php else : ?>
					<?php echo sprintf(
						esc_html__( 'You currently have %s and this is insufficient balance to withdraw.', 'edumall' ),
						'<strong class="available_balance">' . $balance_formatted . '</strong>'
					); ?>
				<?php endif; ?>
			</p>
		</div>

		<div class="current-withdraw-account-wrap">
			<?php if ( $withdraw_method_name ) : ?>
				<p>
					<?php esc_html_e( 'You will get paid by', 'edumall' ); ?>
					<?php echo '<strong>' . $withdraw_method_name . '</strong>'; ?>
					<?php
					$my_profile_url = tutor_utils()->get_tutor_dashboard_page_permalink( 'settings/withdraw-settings' );
					echo sprintf( esc_html__( ', You can change your %s withdraw preference %s ', 'edumall' ), "<a href='{$my_profile_url}'>", '</a>' );
					?>
				</p>
			<?php else: ?>
				<p>
					<?php
					$my_profile_url = tutor_utils()->get_tutor_dashboard_page_permalink( 'settings/withdraw-settings' );
					echo sprintf( esc_html__( 'Please add your %s withdraw preference %s to make withdraw', 'edumall' ), "<a href='{$my_profile_url}'>", '</a>' );
					?>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( $earning_sum->balance >= $min_withdraw && $withdraw_method_name ) : ?>
			<div class="making-withdraw-wrap">
				<?php
				Edumall_Templates::render_button( [
					'link'        => [
						'url' => 'javascript:void(0);',
					],
					'text'        => esc_html__( 'Make a withdraw', 'edumall' ),
					'extra_class' => 'open-withdraw-form-btn',
					'size'        => 'xs',
				] );
				?>
				<div class="tutor-earning-withdraw-form-wrap" style="display: none;">
					<form id="tutor-earning-withdraw-form" action="" method="post" class="tutor-earning-withdraw-form">
						<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
						<input type="hidden" value="tutor_make_an_withdraw" name="action"/>
						<?php do_action( 'tutor_withdraw_form_before' ); ?>
						<div class="withdraw-form-field-row">
							<label for="tutor_withdraw_amount"
							       class="form-label"><?php esc_html_e( 'Amount:', 'edumall' ); ?></label>
							<div class="tutor-row">
								<div class="tutor-col-4">
									<div class="withdraw-form-field-amount">
										<input type="text" name="tutor_withdraw_amount"/>
									</div>
								</div>
								<div class="tutor-col">
									<div class="withdraw-form-field-button">
										<button class="tutor-btn" type="submit" id="tutor-earning-withdraw-btn"
										        name="withdraw-form-submit"><?php esc_html_e( 'Withdraw', 'edumall' ); ?></button>
									</div>
								</div>
							</div>
							<i><?php esc_html_e( 'Enter withdraw amount and click withdraw button', 'edumall' ) ?></i>
						</div>

						<div id="tutor-withdraw-form-response"></div>

						<?php do_action( 'tutor_withdraw_form_after' ); ?>
					</form>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?php
	$withdraw_pending_histories   = tutor_utils()->get_withdrawals_history( $user_id, array( 'status' => array( 'pending' ) ) );
	$withdraw_completed_histories = tutor_utils()->get_withdrawals_history( $user_id, array( 'status' => array( 'approved' ) ) );
	$withdraw_rejected_histories  = tutor_utils()->get_withdrawals_history( $user_id, array( 'status' => array( 'rejected' ) ) );
	?>
	<div class="dashboard-content-box withdraw-history-table-wrap">
		<h4 class="dashboard-content-box-title"><?php esc_html_e( 'Pending Withdrawals', 'edumall' ); ?></h4>
		<?php if ( tutor_utils()->count( $withdraw_pending_histories->results ) ) : ?>
			<table class="withdrawals-history tutor-table">
				<thead>
				<tr>
					<th><?php esc_html_e( 'Amount', 'edumall' ) ?></th>
					<th><?php esc_html_e( 'Withdraw Method', 'edumall' ) ?></th>
					<th><?php esc_html_e( 'Date', 'edumall' ) ?></th>
				</tr>
				</thead>
				<?php foreach ( $withdraw_pending_histories->results as $withdraw_history ) : ?>
					<tr>
						<td><?php echo tutor_utils()->tutor_price( $withdraw_history->amount ); ?></td>
						<td>
							<?php
							$method_data = maybe_unserialize( $withdraw_history->method_data );
							echo tutor_utils()->avalue_dot( 'withdraw_method_name', $method_data )
							?>
						</td>
						<td>
							<?php
							echo wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $withdraw_history->created_at ) );
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php else: ?>
			<p><?php esc_html_e( 'No withdrawals pending yet', 'edumall' ); ?></p>
		<?php endif; ?>
	</div>

	<div class="dashboard-content-box withdraw-history-table-wrap">
		<h4 class="dashboard-content-box-title"><?php esc_html_e( 'Completed Withdrawals', 'edumall' ); ?></h4>
		<?php if ( tutor_utils()->count( $withdraw_completed_histories->results ) ) : ?>
			<table class="withdrawals-history tutor-table">
				<thead>
				<tr>
					<th><?php esc_html_e( 'Amount', 'edumall' ) ?></th>
					<th><?php esc_html_e( 'Withdraw Method', 'edumall' ) ?></th>
					<th><?php esc_html_e( 'Requested At', 'edumall' ) ?></th>
					<th><?php esc_html_e( 'Approved At', 'edumall' ) ?></th>
				</tr>
				</thead>
				<?php foreach ( $withdraw_completed_histories->results as $withdraw_history ) : ?>
					<tr>
						<td><?php echo tutor_utils()->tutor_price( $withdraw_history->amount ); ?></td>
						<td>
							<?php
							$method_data = maybe_unserialize( $withdraw_history->method_data );
							echo tutor_utils()->avalue_dot( 'withdraw_method_name', $method_data )
							?>
						</td>
						<td>
							<?php
							echo wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $withdraw_history->created_at ) );
							?>
						</td>

						<td>
							<?php
							if ( $withdraw_history->updated_at ) {
								echo wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $withdraw_history->updated_at ) );
							}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php else : ?>
			<p><?php esc_html_e( 'No withdrawals completed yet', 'edumall' ); ?></p>
		<?php endif; ?>
	</div>

	<div class="dashboard-content-box withdraw-history-table-wrap">
		<h4 class="dashboard-content-box-title"><?php esc_html_e( 'Rejected Withdrawals', 'edumall' ); ?></h4>
		<?php if ( tutor_utils()->count( $withdraw_rejected_histories->results ) )  : ?>
			<table class="withdrawals-history tutor-table">
				<thead>
				<tr>
					<th><?php esc_html_e( 'Amount', 'edumall' ) ?></th>
					<th><?php esc_html_e( 'Withdraw Method', 'edumall' ) ?></th>
					<th><?php esc_html_e( 'Requested At', 'edumall' ) ?></th>
					<th><?php esc_html_e( 'Rejected At', 'edumall' ) ?></th>
				</tr>
				</thead>
				<?php foreach ( $withdraw_rejected_histories->results as $withdraw_history ) : ?>
					<tr>
						<td><?php echo tutor_utils()->tutor_price( $withdraw_history->amount ); ?></td>
						<td>
							<?php
							$method_data = maybe_unserialize( $withdraw_history->method_data );
							echo tutor_utils()->avalue_dot( 'withdraw_method_name', $method_data )
							?>
						</td>
						<td>
							<?php
							echo wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $withdraw_history->created_at ) );
							?>
						</td>
						<td>
							<?php
							if ( $withdraw_history->updated_at ) {
								echo wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $withdraw_history->updated_at ) );
							}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php else: ?>
			<p><?php esc_html_e( 'No withdrawals rejected yet', 'edumall' ); ?></p>
		<?php endif; ?>
	</div>

</div>
