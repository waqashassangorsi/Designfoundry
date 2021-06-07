<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Top_Bar' ) ) {
	class Edumall_Top_Bar {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {

		}

		/**
		 * @return array List top bar types include id & name.
		 */
		public function get_type() {
			return array(
				'01' => esc_html__( '01', 'edumall' ),
				'02' => esc_html__( '02', 'edumall' ),
				'03' => esc_html__( '03', 'edumall' ),
				'04' => esc_html__( '04', 'edumall' ),
				'05' => esc_html__( '05', 'edumall' ),
			);
		}

		/**
		 * @param bool   $default_option Show or hide default select option.
		 * @param string $default_text   Custom text for default option.
		 *
		 * @return array A list of options for select field.
		 */
		public function get_list( $default_option = false, $default_text = '' ) {
			$top_bars = array(
				'none' => esc_html__( 'Hide', 'edumall' ),
			);

			$top_bars += $this->get_type();

			if ( $default_option === true ) {
				if ( $default_text === '' ) {
					$default_text = esc_html__( 'Default', 'edumall' );
				}

				$top_bars = array( '' => $default_text ) + $top_bars;
			}

			return $top_bars;
		}

		public function get_support_components() {
			$list = [
				'widget'            => esc_html__( 'Widget', 'edumall' ),
				'text'              => esc_html__( 'Text', 'edumall' ),
				'language_switcher' => esc_html__( 'Language Switcher', 'edumall' ),
				'info_list'         => esc_html__( 'Info List', 'edumall' ),
				'user_links'        => esc_html__( 'User Links', 'edumall' ),
				'social_links'      => esc_html__( 'Social Links', 'edumall' ),
			];

			return $list;
		}

		/**
		 * Add classes to the top barr.
		 *
		 * @var string $class Custom class.
		 */
		public function get_wrapper_class( $class = '' ) {
			$classes = array( 'page-top-bar' );

			$type = Edumall_Global::instance()->get_top_bar_type();

			$classes[] = "top-bar-{$type}";

			if ( ! empty( $class ) ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_merge( $classes, $class );
			} else {
				// Ensure that we always coerce class to being an array.
				$class = array();
			}

			$classes = apply_filters( 'edumall_top_bar_class', $classes, $class );

			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		public function render() {
			$type = Edumall_Global::instance()->get_top_bar_type();

			if ( 'none' !== $type ) {
				edumall_load_template( 'top-bar/top-bar', $type );
			}
		}

		public function print_components( $position = 'left' ) {
			$type       = Edumall_Global::instance()->get_top_bar_type();
			$components = Edumall::setting( "top_bar_style_{$type}_{$position}_components" );

			if ( empty( $components ) ) {
				return;
			}

			foreach ( $components as $component ) {
				switch ( $component ) {
					case 'text' :
						$this->print_text();
						break;
					case 'widget' :
						$this->print_widgets();
						break;
					case 'language_switcher' :
						$this->print_language_switcher();
						break;
					case 'info_list' :
						$this->print_info_list();
						break;
					case 'user_links' :
						$this->print_user_links();
						break;
					case 'social_links' :
						$this->print_social_links();
						break;
				}
			}
		}

		public function print_text() {
			$type = Edumall_Global::instance()->get_top_bar_type();
			$text = Edumall::setting( "top_bar_style_{$type}_text" );

			echo '<div class="top-bar-text">' . wp_kses( $text, 'edumall-default' ) . '</div>';
		}

		/**
		 * Print WPML switcher html template.
		 *
		 * @var string $class Custom class.
		 */
		public function print_language_switcher() {
			$topbar_type = Edumall_Global::instance()->get_title_bar_type();

			do_action( 'edumall_before_add_language_selector_top_bar', $topbar_type, '1' );

			if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
				return;
			}
			?>
			<div id="switcher-language-wrapper" class="switcher-language-wrapper">
				<?php do_action( 'wpml_add_language_selector' ); ?>
			</div>
			<?php
		}

		public function print_button( $type = '01' ) {
			$button_text        = Edumall::setting( "top_bar_style_{$type}_button_text" );
			$button_link        = Edumall::setting( "top_bar_style_{$type}_button_link" );
			$button_link_target = Edumall::setting( "top_bar_style_{$type}_button_link_target" );
			$button_classes     = 'top-bar-button';
			?>
			<?php if ( $button_link !== '' && $button_text !== '' ) : ?>
				<a class="<?php echo esc_attr( $button_classes ); ?>"
				   href="<?php echo esc_url( $button_link ); ?>"
					<?php if ( $button_link_target === '1' ) : ?>
						target="_blank"
					<?php endif; ?>
				>
					<?php echo esc_html( $button_text ); ?>
				</a>
			<?php endif;
		}

		public function print_user_links() {
			?>
			<div class="top-bar-user-links">
				<div class="link-wrap">
					<?php if ( ! is_user_logged_in() ) { ?>
						<?php
						$login_url    = wp_login_url();
						$register_url = wp_registration_url();
						?>
						<a href="<?php echo esc_url( $login_url ); ?>"
						   title="<?php esc_attr_e( 'Log in', 'edumall' ); ?>"
						   class="open-popup-login top-bar-login-link"
						><?php esc_html_e( 'Log in', 'edumall' ); ?></a>
						<a href="<?php echo esc_url( $register_url ); ?>"
						   title="<?php esc_attr_e( 'Register', 'edumall' ); ?>"
						   class="open-popup-register top-bar-register-link"
						><?php esc_html_e( 'Register', 'edumall' ); ?></a>
					<?php } else { ?>
						<?php
						$profile_url  = apply_filters( 'edumall_user_profile_url', '' );
						$profile_text = apply_filters( 'edumall_user_profile_text', esc_html__( 'Profile', 'edumall' ) );
						?>
						<?php if ( '' !== $profile_url && '' !== $profile_text ): ?>
							<a href="<?php echo esc_url( $profile_url ); ?>"><?php echo esc_html( $profile_text ); ?></a>
						<?php endif; ?>
						<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Log out', 'edumall' ); ?></a>
					<?php } ?>
				</div>
			</div>
			<?php
		}

		public function print_social_links() {
			?>
			<div class="top-bar-social-network">
				<div class="inner">
					<?php Edumall_Templates::social_icons( array(
						'display'        => 'icon',
						'tooltip_enable' => false,
					) ); ?>
				</div>
			</div>
			<?php
		}

		public function print_widgets() {
			?>
			<div class="top-bar-widgets">
				<?php Edumall_Sidebar::instance()->generated_sidebar( 'top_bar_widgets' ); ?>
			</div>
			<?php
		}

		public function print_info_list() {
			$type      = Edumall_Global::instance()->get_top_bar_type();
			$info_list = Edumall::setting( "top_bar_style_{$type}_info_list" );

			if ( empty( $info_list ) ) {
				return;
			}
			?>
			<div class="top-bar-info">
				<ul class="info-list">
					<?php
					foreach ( $info_list as $item ) {
						$url  = isset( $item['url'] ) ? $item['url'] : '';
						$icon = isset( $item['icon_class'] ) ? $item['icon_class'] : '';
						$text = isset( $item['text'] ) ? $item['text'] : '';

						$link_class = 'info-link';

						if ( ! empty( $item['link_class'] ) ) {
							$link_class .= ' ' . $item['link_class'];
						}
						?>
						<li class="info-item">
							<?php if ( $url !== '' ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $link_class ); ?>">
								<?php endif; ?>

								<?php if ( $icon !== '' ) : ?>
									<i class="info-icon <?php echo esc_attr( $icon ); ?>"></i>
								<?php endif; ?>

								<?php echo '<span class="info-text">' . $text . '</span>'; ?>

								<?php if ( $url !== '' ) : ?>
							</a>
						<?php endif; ?>
						</li>
					<?php } ?>
				</ul>
			</div>
			<?php
		}
	}

	Edumall_Top_Bar::instance()->initialize();
}
