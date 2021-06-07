<?php
defined( 'ABSPATH' ) || exit;

/**
 * Custom functions, filters, actions for WooCommerce.
 */
if ( ! class_exists( 'Edumall_Shop_Layout_Switcher' ) ) {
	class Edumall_Shop_Layout_Switcher {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'woocommerce_before_shop_loop', [ $this, 'add_switcher_button' ], 30 );

			add_action( 'wp_ajax_shop_layout_change', [ $this, 'do_switch_layout' ] );
			add_action( 'wp_ajax_nopriv_shop_layout_change', [ $this, 'do_switch_layout' ] );
		}

		public function add_switcher_button() {
			if ( '1' !== Edumall::setting( 'shop_archive_layout_switcher' ) ) {
				return;
			}

			$layout = $this->get_layout();
			?>
			<form id="archive-layout-switcher" class="archive-layout-switcher">
				<?php
				$switcher_grid_classes = 'switcher-item grid';
				$switcher_list_classes = 'switcher-item list';
				?>
				<div class="archive-layout-switcher-label heading"><?php esc_html_e( 'See', 'edumall' ); ?></div>
				<label class="<?php echo esc_attr( $switcher_grid_classes ); ?>">
					<input type="radio" name="layout" value="grid" <?php checked( $layout, 'grid' ); ?>>
				</label>
				<label class="<?php echo esc_attr( $switcher_list_classes ); ?>">
					<input type="radio" name="layout" value="list" <?php checked( $layout, 'list' ); ?>>
				</label>
				<input type="hidden" name="action" value="shop_layout_change">
			</form>
			<?php
		}

		public function do_switch_layout() {
			$layout = 'grid';

			if ( isset( $_POST['layout'] ) && 'list' === $_POST['layout'] ) {
				$layout = 'list';
			}

			setcookie( 'shop_layout', $layout, time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );

			wp_die();
		}

		public function get_layout() {
			if ( '1' === Edumall::setting( 'shop_archive_layout_switcher' ) && isset( $_COOKIE['shop_layout'] ) ) {
				return $_COOKIE['shop_layout'];
			}

			return 'grid';
		}
	}
}
