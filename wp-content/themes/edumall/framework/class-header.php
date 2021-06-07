<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Header' ) ) {

	class Edumall_Header {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_ajax_edumall_actions', [ $this, 'edumall_actions' ] );
			add_action( 'wp_ajax_nopriv_edumall_actions', [ $this, 'edumall_actions' ] );
		}

		public function edumall_actions() {
			$id     = $_POST['cat_id'];
			$action = $_POST['action'];

			$query_args = array(
				'post_type'      => Edumall_Tutor::instance()->get_course_type(),
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => 6,
				'no_found_rows'  => true,
				'tax_query'      => array(
					array(
						'taxonomy' => Edumall_Tutor::instance()->get_tax_category(),
						'field'    => 'id',
						'terms'    => $id,
					),
				),
			);

			$query    = new WP_Query( $query_args );
			$response = [];
			ob_start();

			if ( $query->have_posts() ) {
				set_query_var( 'edumall_query', $query );
				get_template_part( 'loop/menu/category' );
				wp_reset_postdata();
			} else {
				get_template_part( 'loop/menu/content-none' );
			}

			$template             = ob_get_clean();
			$template             = preg_replace( '~>\s+<~', '><', $template );
			$response['template'] = $template;

			echo json_encode( $response );

			wp_die();
		}

		/**
		 * @return array List header types include id & name.
		 */
		public function get_type() {
			return array(
				'01' => esc_html__( 'Style 01', 'edumall' ),
				'02' => esc_html__( 'Style 02', 'edumall' ),
				'03' => esc_html__( 'Style 03', 'edumall' ),
				'04' => esc_html__( 'Style 04', 'edumall' ),
				'05' => esc_html__( 'Style 05', 'edumall' ),
				'06' => esc_html__( 'Style 06', 'edumall' ),
				'07' => esc_html__( 'Style 07', 'edumall' ),
				'08' => esc_html__( 'Style 08', 'edumall' ),
			);
		}

		/**
		 * @param bool   $default_option Show or hide default select option.
		 * @param string $default_text   Custom text for default option.
		 *
		 * @return array A list of options for select field.
		 */
		public function get_list( $default_option = false, $default_text = '' ) {
			$headers = array(
				'none' => esc_html__( 'Hide', 'edumall' ),
			);

			$headers += $this->get_type();

			if ( $default_option === true ) {
				if ( $default_text === '' ) {
					$default_text = esc_html__( 'Default', 'edumall' );
				}

				$headers = array( '' => $default_text ) + $headers;
			}

			return $headers;
		}

		/**
		 * Get list of button style option for customizer.
		 *
		 * @return array
		 */
		public function get_button_style() {
			return array(
				'flat'         => esc_attr__( 'Flat', 'edumall' ),
				'border'       => esc_attr__( 'Border', 'edumall' ),
				'thick-border' => esc_attr__( 'Thick Border', 'edumall' ),
			);
		}

		public function get_button_kirki_output( $header_style, $header_skin, $hover = false ) {
			$prefix_selector = ".header-{$header_style}.header-{$header_skin} ";

			if ( $hover ) {
				$button_selector    = $prefix_selector . ".header-button:hover";
				$button_bg_selector = $prefix_selector . ".header-button:after";
			} else {
				$button_selector    = $prefix_selector . ".header-button";
				$button_bg_selector = $prefix_selector . ".header-button:before";
			}

			return array(
				array(
					'choice'   => 'color',
					'property' => 'color',
					'element'  => $button_selector,
				),
				array(
					'choice'   => 'border',
					'property' => 'border-color',
					'element'  => $button_selector,
				),
				array(
					'choice'   => 'background',
					'property' => 'background',
					'element'  => $button_bg_selector,
				),
			);
		}

		public function get_search_form_kirki_output( $header_style, $header_skin, $hover = false ) {
			$prefix_selector = ".header-{$header_style}.header-{$header_skin} ";

			if ( $hover ) {
				$form_selector = $prefix_selector . '.search-field:focus';
			} else {
				$form_selector = $prefix_selector . '.search-field';
			}

			return array(
				array(
					'choice'   => 'color',
					'property' => 'color',
					'element'  => $form_selector,
				),
				array(
					'choice'   => 'border',
					'property' => 'border-color',
					'element'  => $form_selector,
				),
				array(
					'choice'   => 'background',
					'property' => 'background',
					'element'  => $form_selector,
				),
			);
		}

		/**
		 * Add classes to the header.
		 *
		 * @var string $class Custom class.
		 */
		public function get_wrapper_class( $class = '' ) {
			$classes = array( 'page-header' );

			$header_type    = Edumall_Global::instance()->get_header_type();
			$header_overlay = Edumall_Global::instance()->get_header_overlay();
			$header_skin    = Edumall_Global::instance()->get_header_skin();

			$classes[] = "header-{$header_type}";
			$classes[] = "header-{$header_skin}";

			if ( '1' === $header_overlay ) {
				$classes[] = 'header-layout-fixed';
			}

			if ( '06' !== $header_type ) {
				$classes[] = 'nav-links-hover-style-01';
			}

			$_sticky_logo = Edumall::setting( "header_sticky_logo" );
			$classes[]    = "header-sticky-$_sticky_logo-logo";

			if ( ! empty( $class ) ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_merge( $classes, $class );
			} else {
				// Ensure that we always coerce class to being an array.
				$class = array();
			}

			$classes = apply_filters( 'edumall_header_class', $classes, $class );

			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		/**
		 * Print WPML switcher html template.
		 *
		 * @var string $class Custom class.
		 */
		public function print_language_switcher() {
			$header_type = Edumall_Global::instance()->get_header_type();
			$enabled     = Edumall::setting( "header_style_{$header_type}_language_switcher_enable" );

			do_action( 'edumall_before_add_language_selector_header', $header_type, $enabled );

			if ( $enabled !== '1' || ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
				return;
			}
			?>
			<div id="switcher-language-wrapper" class="switcher-language-wrapper">
				<?php do_action( 'wpml_add_language_selector' ); ?>
			</div>
			<?php
		}

		public function print_social_networks( $args = array() ) {
			$header_type   = Edumall_Global::instance()->get_header_type();
			$social_enable = Edumall::setting( "header_style_{$header_type}_social_networks_enable" );

			if ( '1' !== $social_enable ) {
				return;
			}

			$defaults = array(
				'style' => 'icons',
			);

			$args       = wp_parse_args( $args, $defaults );
			$el_classes = 'header-social-networks';

			if ( ! empty( $args['style'] ) ) {
				$el_classes .= " style-{$args['style']}";
			}
			?>
			<div class="<?php echo esc_attr( $el_classes ); ?>">
				<div class="inner">
					<?php
					$defaults = array(
						'tooltip_position' => 'bottom-left',
					);

					if ( 'light' === Edumall_Global::instance()->get_header_skin() ) {
						$defaults['tooltip_skin'] = 'white';
					}

					$args = wp_parse_args( $args, $defaults );

					Edumall_Templates::social_icons( $args );
					?>
				</div>
			</div>
			<?php
		}

		public function print_widgets() {
			$header_type = Edumall_Global::instance()->get_header_type();

			$enabled = Edumall::setting( "header_style_{$header_type}_widgets_enable" );
			if ( '1' === $enabled ) {
				?>
				<div class="header-widgets">
					<?php Edumall_Sidebar::instance()->generated_sidebar( 'header_widgets' ); ?>
				</div>
				<?php
			}
		}

		public function print_search() {
			$header_type = Edumall_Global::instance()->get_header_type();
			$search_type = Edumall::setting( "header_style_{$header_type}_search_enable" );

			if ( 'inline' === $search_type ) {
				?>
				<div class="header-search-form">
					<?php get_search_form(); ?>
				</div>
				<?php
			} elseif ( 'popup' === $search_type ) {
				?>
				<div id="page-open-popup-search" class="header-icon page-open-popup-search">
					<i class="far fa-search"></i>
				</div>
				<?php
			}
		}

		public function print_notification() {
			$header_type  = Edumall_Global::instance()->get_header_type();
			$component_on = Edumall::setting( "header_style_{$header_type}_notification_enable" );

			if ( ! is_user_logged_in() || '1' !== $component_on ) {
				return;
			}

			if ( ! function_exists( 'bp_is_active' ) || ! bp_is_active( 'notifications' ) ) {
				return;
			}

			$menu_link                 = trailingslashit( bp_get_loggedin_user_link() . bp_get_notifications_slug() );
			$notifications             = bp_notifications_get_unread_notification_count( bp_loggedin_user_id() );
			$unread_notification_count = ! empty( $notifications ) ? $notifications : 0;
			?>
			<div id="header-notifications" class="header-notifications notification-wrap menu-item-has-children">
				<a href="javascript:void(0);" class="header-notifications-open">
					<div class="header-icon">
						<i class="far fa-bell"></i>
						<span class="badge"><?php echo esc_html( $unread_notification_count ); ?></span>
					</div>
				</a>
				<div id="header-notification-list" class="header-notification-list">
					<?php do_action( 'edumall_header_notification_content_before' ); ?>

					<h4 class="notification-list-heading"><?php esc_html_e( 'Notifications', 'edumall' ); ?></h4>

					<ul class="notification-list bb-nouveau-list"></ul>

					<footer class="notification-footer">
						<a href="<?php echo $menu_link ?>" class="delete-all">
							<?php esc_html_e( 'More', 'edumall' ); ?>
							<i class="far fa-angle-right"></i>
						</a>
					</footer>

					<?php do_action( 'edumall_header_notification_content_after' ); ?>
				</div>
			</div>

			<?php
		}

		public function print_category_menu() {
			$header_type     = Edumall_Global::instance()->get_header_type();
			$category_enable = Edumall::setting( "header_style_{$header_type}_category_menu_enable" );

			if ( '1' !== $category_enable ) {
				return;
			}

			$show_all_links = true;

			$default_args = [
				'taxonomy'     => Edumall_Tutor::instance()->get_tax_category(),
				'orderby'      => 'name',
				'show_count'   => 0,
				'hierarchical' => 0,
				'title_li'     => '',
				'hide_empty'   => 0,
			];

			$top_args = wp_parse_args( [
				'parent' => 0,
			], $default_args );

			$categories = get_categories( $top_args );

			if ( empty( $categories ) ) {
				return;
			}

			$menu_class = 'header-category-dropdown';
			$item_class = 'cat-item';
			?>
			<div class="header-category-menu">
				<a href="#" class="header-icon category-menu-toggle">
					<div class="category-toggle-icon">
						<?php echo \Edumall_Helper::get_file_contents( EDUMALL_THEME_SVG_DIR . '/icon-grid-dots.svg' ); ?>
					</div>
					<div class="category-toggle-text">
						<?php esc_html_e( 'Category', 'edumall' ); ?>
					</div>
				</a>

				<nav class="header-category-dropdown-wrap">
					<ul class="<?php echo esc_attr( $menu_class ); ?>">
						<?php foreach ( $categories as $category ) : ?>
							<?php
							$has_children = false;
							$sub_args     = wp_parse_args( [
								'parent' => $category->term_id,
							], $default_args );

							$sub_categories = get_categories( $sub_args );

							if ( ! empty( $sub_categories ) || $category->count > 0 ) {
								$has_children = true;
							}
							?>
							<li class="cat-item">
								<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
									<?php echo esc_html( $category->name ); ?>
									<?php if ( $has_children ): ?>
										<span class="toggle-sub-menu"></span>
									<?php endif; ?>
								</a>

								<?php if ( ! empty( $sub_categories ) ) : ?>
									<ul class="children sub-categories">
										<?php if ( $show_all_links ): ?>
											<li class="cat-item">
												<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
													<?php printf( esc_html__( 'All %s', 'edumall' ), $category->name ); ?>
												</a>
											</li>
										<?php endif; ?>

										<?php foreach ( $sub_categories as $sub_category ) : ?>
											<?php
											$has_children       = false;
											$current_item_class = $item_class;

											if ( $sub_category->count > 0 ) {
												$has_children       = true;
												$current_item_class .= ' has-children';
											}
											?>

											<li data-id="<?php echo esc_attr( $sub_category->term_id ); ?>"
											    class="<?php echo esc_attr( $current_item_class ); ?>">
												<a href="<?php echo esc_url( get_term_link( $sub_category ) ); ?>"><?php echo esc_html( $sub_category->name ); ?>
													<?php if ( $has_children ): ?>
														<span class="toggle-sub-menu"></span>
													<?php endif; ?>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>

						<?php endforeach; ?>
					</ul>
				</nav>
			</div>
			<?php
		}

		/**
		 * Print login button + register button.
		 * If logged in then print profile & logout instead of.
		 */
		public function print_user_buttons() {
			$header_type     = Edumall_Global::instance()->get_header_type();
			$user_buttons_on = Edumall::setting( "header_style_{$header_type}_login_enable" );

			if ( '1' !== $user_buttons_on ) {
				return;
			}

			$header_skin      = Edumall_Global::instance()->get_header_skin();
			$button_2_classes = 'button-thin';

			if ( 'light' === $header_skin ) {
				$button_2_classes .= ' button-secondary-white';
			} else {
				$button_2_classes .= ' button-light-primary';
			}
			?>
			<div class="header-user-buttons">
				<div class="inner">
					<?php
					if ( ! is_user_logged_in() ) {
						$login_url    = wp_login_url();
						$register_url = wp_registration_url();

						Edumall_Templates::render_button( [
							'link'        => [
								'url' => $login_url,
							],
							'text'        => esc_html__( 'Log In', 'edumall' ),
							'extra_class' => 'open-popup-login',
							'size'        => 'sm',
							'style'       => 'bottom-line-alt button-thin',
						] );

						Edumall_Templates::render_button( [
							'link'        => [
								'url' => $register_url,
							],
							'text'        => esc_html__( 'Sign Up', 'edumall' ),
							'extra_class' => 'open-popup-register ' . $button_2_classes,
							'size'        => 'sm',
						] );
					} else {
						$profile_url  = apply_filters( 'edumall_user_profile_url', '' );
						$profile_text = apply_filters( 'edumall_user_profile_text', esc_html__( 'Profile', 'edumall' ) );

						if ( '' !== $profile_url && '' !== $profile_text ) {
							Edumall_Templates::render_button( [
								'link'  => [
									'url' => $profile_url,
								],
								'text'  => $profile_text,
								'size'  => 'sm',
								'style' => 'bottom-line-alt',
							] );
						}

						Edumall_Templates::render_button( [
							'link'        => [
								'url' => esc_url( wp_logout_url( home_url() ) ),
							],
							'text'        => esc_html__( 'Log out', 'edumall' ),
							'extra_class' => $button_2_classes,
							'size'        => 'sm',
						] );
					}
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Other style for user links
		 *
		 * @see Edumall_Header::print_user_buttons()
		 */
		public function print_user_links_box() {
			$header_type     = Edumall_Global::instance()->get_header_type();
			$user_buttons_on = Edumall::setting( "header_style_{$header_type}_login_enable" );

			if ( '1' !== $user_buttons_on ) {
				return;
			}
			?>
			<div class="header-user-links-box">
				<div class="user-icon">
					<span class="fal fa-user"></span>
				</div>
				<div class="user-links">
					<?php
					if ( ! is_user_logged_in() ) {
						$login_url    = wp_login_url();
						$register_url = wp_registration_url();
						?>
						<a class="header-login-link open-popup-login"
						   href="<?php echo esc_url( $login_url ); ?>"><?php esc_html_e( 'Log In', 'edumall' ); ?></a>
						<a class="header-register-link open-popup-register"
						   href="<?php echo esc_url( $register_url ); ?>"><?php esc_html_e( 'Register', 'edumall' ); ?></a>
						<?php
					} else {
						$profile_url  = apply_filters( 'edumall_user_profile_url', '' );
						$profile_text = apply_filters( 'edumall_user_profile_text', esc_html__( 'Profile', 'edumall' ) );
						?>
						<a class="header-profile-link"
						   href="<?php echo esc_url( $profile_url ); ?>"><?php echo esc_html( $profile_text ); ?></a>
						<a class="header-logout-link"
						   href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Log out', 'edumall' ); ?></a>
					<?php } ?>
				</div>
			</div>
			<?php
		}

		public function print_contact_info_box() {
			$header_type     = Edumall_Global::instance()->get_header_type();
			$contact_info_on = Edumall::setting( "header_style_{$header_type}_contact_info_enable" );

			if ( '1' !== $contact_info_on ) {
				return;
			}

			$phone_number = Edumall::setting( 'contact_info_phone' );
			$email        = Edumall::setting( 'contact_info_email' );

			$phone_number_url = str_replace( ' ', '', $phone_number );
			?>
			<div class="header-contact-links-box">
				<div class="contact-icon">
					<span class="fal fa-phone"></span>
				</div>
				<div class="contact-links">
					<?php if ( ! empty( $phone_number ) ): ?>
						<a class="header-contact-phone"
						   href="<?php echo esc_url( 'tel:' . $phone_number_url ); ?>"><?php echo esc_html( $phone_number ); ?></a>
					<?php endif; ?>

					<?php if ( ! empty( $email ) ): ?>
						<a class="header-contact-email"
						   href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		public function print_button( $args = array() ) {
			$header_type = Edumall_Global::instance()->get_header_type();

			$button_style        = Edumall::setting( "header_style_{$header_type}_button_style" );
			$button_text         = Edumall::setting( "header_style_{$header_type}_button_text" );
			$button_link         = Edumall::setting( "header_style_{$header_type}_button_link" );
			$button_link_target  = Edumall::setting( "header_style_{$header_type}_button_link_target" );
			$button_link_rel     = Edumall::setting( "header_style_{$header_type}_button_link_rel" );
			$button_classes      = 'tm-button';
			$sticky_button_style = Edumall::setting( "header_sticky_button_style" );

			$icon_class = Edumall::setting( "header_style_{$header_type}_button_icon" );
			$icon_align = 'right';

			if ( $icon_class !== '' ) {
				$button_classes .= ' has-icon icon-right';
			}

			$defaults = array(
				'extra_class' => '',
				'style'       => '',
				'size'        => 'nm',
			);

			$args = wp_parse_args( $args, $defaults );

			if ( $args['extra_class'] !== '' ) {
				$button_classes .= " {$args['extra_class']}";
			}

			$header_button_classes = $button_classes . " tm-button-{$args['size']} header-button";
			$sticky_button_classes = $button_classes . ' tm-button-xs header-sticky-button';

			$header_button_classes .= " style-{$button_style}";
			$sticky_button_classes .= " style-{$sticky_button_style}";
			?>
			<?php if ( $button_link !== '' && $button_text !== '' ) : ?>

				<?php ob_start(); ?>

				<?php if ( $icon_class !== '' && $icon_align === 'right' ) { ?>
					<span class="button-icon">
						<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
					</span>
				<?php } ?>

				<span class="button-text">
					<?php echo esc_html( $button_text ); ?>
				</span>

				<?php if ( $icon_class !== '' && $icon_align === 'right' ) { ?>
					<span class="button-icon">
						<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
					</span>
				<?php } ?>

				<?php $button_content_html = ob_get_clean(); ?>

				<div class="header-buttons">
					<a class="<?php echo esc_attr( $header_button_classes ); ?>"
					   href="<?php echo esc_url( $button_link ); ?>"

						<?php if ( '1' === $button_link_target ) : ?>
							target="_blank"
						<?php endif; ?>

						<?php if ( ! empty ( $button_link_rel ) ) : ?>
							rel="<?php echo esc_attr( $button_link_rel ); ?>"
						<?php endif; ?>
					>
						<?php echo '' . $button_content_html; ?>
					</a>
					<a class="<?php echo esc_attr( $sticky_button_classes ); ?>"
					   href="<?php echo esc_url( $button_link ); ?>"

						<?php if ( '1' === $button_link_target ) : ?>
							target="_blank"
						<?php endif; ?>

						<?php if ( ! empty ( $button_link_rel ) ) : ?>
							rel="<?php echo esc_attr( $button_link_rel ); ?>"
						<?php endif; ?>
					>
						<?php echo '' . $button_content_html; ?>
					</a>
				</div>
			<?php endif;
		}

		public function print_open_mobile_menu_button() {
			?>
			<div id="page-open-mobile-menu" class="header-icon page-open-mobile-menu">
				<div class="burger-icon">
					<span class="burger-icon-top"></span>
					<span class="burger-icon-bottom"></span>
				</div>
			</div>
			<?php
		}

		public function print_more_tools_button() {
			?>
			<div id="page-open-components" class="header-icon page-open-components">
				<div class="inner">
					<div class="circle circle-one"></div>
					<div class="circle circle-two"></div>
					<div class="circle circle-three"></div>
				</div>
			</div>
			<?php
		}

		public function print_open_canvas_menu_button( $args = array() ) {
			$defaults = array(
				'extra_class' => '',
				'style'       => '01',
			);
			$args     = wp_parse_args( $args, $defaults );

			$classes = "header-icon page-open-main-menu style-{$args['style']}";

			if ( ! empty( $args['extra_class'] ) ) {
				$classes .= " {$args['extra_class']}";
			}

			$title = Edumall::setting( 'navigation_minimal_01_menu_title' );
			?>
			<div id="page-open-main-menu" class="<?php echo esc_attr( $classes ); ?>">
				<div class="burger-icon">
					<span class="burger-icon-top"></span>
					<span class="burger-icon-bottom"></span>
				</div>

				<?php if ( ! empty( $title ) ) : ?>
					<div class="burger-title"><?php echo esc_html( $title ); ?></div>
				<?php endif; ?>
			</div>
			<?php
		}
	}

	Edumall_Header::instance()->initialize();
}
