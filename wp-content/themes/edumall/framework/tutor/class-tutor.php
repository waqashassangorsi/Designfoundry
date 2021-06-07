<?php
defined( 'ABSPATH' ) || exit;

/**
 * Entry & Utils class for tutor lms.
 */
if ( ! class_exists( 'Edumall_Tutor' ) ) {
	class Edumall_Tutor {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			require_once EDUMALL_TUTOR_DIR . '/course-query.php';
			require_once EDUMALL_TUTOR_DIR . '/course-user.php';
			require_once EDUMALL_TUTOR_DIR . '/class-course.php';
			require_once EDUMALL_TUTOR_DIR . '/class-lesson.php';
			require_once EDUMALL_TUTOR_DIR . '/archive-course.php';
			require_once EDUMALL_TUTOR_DIR . '/single-course.php';
			require_once EDUMALL_TUTOR_DIR . '/single-lesson.php';
			require_once EDUMALL_TUTOR_DIR . '/course-builder.php';
			require_once EDUMALL_TUTOR_DIR . '/course-review.php';
			require_once EDUMALL_TUTOR_DIR . '/course-layout-switcher.php';
			require_once EDUMALL_TUTOR_DIR . '/shortcode.php';
			require_once EDUMALL_TUTOR_DIR . '/enqueue.php';
			require_once EDUMALL_TUTOR_DIR . '/cart.php';
			require_once EDUMALL_TUTOR_DIR . '/custom-css.php';
			require_once EDUMALL_TUTOR_DIR . '/sidebar.php';
			require_once EDUMALL_TUTOR_DIR . '/dashboard.php';
			require_once EDUMALL_TUTOR_DIR . '/profile.php';

			if ( ! $this->is_activated() ) {
				return;
			}

			Edumall_Course_Query::instance()->initialize();
			Edumall_Course_User::instance()->initialize();
			Edumall_Archive_Course::instance()->initialize();
			Edumall_Single_Course::instance()->initialize();
			Edumall_Single_Lesson::instance()->initialize();
			Edumall_Tutor_Course_Builder::instance()->initialize();
			Edumall_Tutor_Course_Review::instance()->initialize();
			Edumall_Course_Layout_Switcher::instance()->initialize();
			Edumall_Tutor_Enqueue::instance()->initialize();
			Edumall_Tutor_Cart::instance()->initialize();
			Edumall_Tutor_Shortcode::instance()->initialize();
			Edumall_Tutor_Custom_Css::instance()->initialize();
			Edumall_Tutor_Sidebar::instance()->initialize();
			Edumall_Tutor_Dashboard::instance()->initialize();
			Edumall_Tutor_Profile::instance()->initialize();
		}

		public function is_activated() {
			if ( defined( 'TUTOR_VERSION' ) ) {
				return true;
			}

			return false;
		}

		public function get_course_type() {
			return 'courses';
		}

		public function get_lesson_type() {
			return 'lesson';
		}

		public function get_quiz_type() {
			return 'tutor_quiz';
		}

		public function get_tax_category() {
			return 'course-category';
		}

		public function get_tax_tag() {
			return 'course-tag';
		}

		public function get_tax_language() {
			return 'course-language';
		}

		public function is_single_course() {
			return is_singular( $this->get_course_type() );
		}

		public function is_single_lesson() {
			return is_singular( $this->get_lesson_type() );
		}

		public function is_single_quiz() {
			return is_singular( $this->get_quiz_type() );
		}

		public function is_student() {
			if ( ! $this->is_instructor() ) {
				return true;
			}

			return false;
		}

		public function is_instructor() {
			$user_id = get_current_user_id();

			$register_time = get_user_meta( $user_id, '_is_tutor_instructor', true );

			if ( empty( $register_time ) ) {
				return false;
			}

			$instructor_status = get_user_meta( $user_id, '_tutor_instructor_status', true );

			if ( 'approved' !== $instructor_status ) {
				return false;
			}

			return true;
		}

		public function is_pending_instructor() {
			$user_id = get_current_user_id();

			$register_time = get_user_meta( $user_id, '_is_tutor_instructor', true );

			if ( empty( $register_time ) ) {
				return false;
			}

			$instructor_status = get_user_meta( $user_id, '_tutor_instructor_status', true );

			if ( 'pending' !== $instructor_status ) {
				return false;
			}

			return true;
		}

		/**
		 * Check if current page is Dashboard page.
		 */
		public function is_dashboard() {
			global $post;

			$dashboard = tutor_utils()->dashboard_page_id();

			return isset( $post ) && $dashboard === $post->ID ? true : false;
		}

		/**
		 * Check if current page is Profile page.
		 */
		public function is_profile() {
			global $wp_query;

			if ( ! empty( $wp_query->query['tutor_student_username'] ) ) {
				return true;
			}

			return false;
		}

		/**
		 * @return bool True if is single lesson or quiz
		 */
		public function is_single_lessons() {
			return $this->is_single_lesson() || $this->is_single_quiz();
		}

		/**
		 * Check if current page is category or tag pages
		 */
		public function is_taxonomy() {
			return is_tax( get_object_taxonomies( $this->get_course_type() ) );
		}

		/**
		 * Check if current page is tag pages
		 */
		public function is_tag() {
			return is_tax( $this->get_tax_tag() );
		}

		/**
		 * Check if current page is category pages
		 */
		public function is_category() {
			return is_tax( $this->get_tax_category() );
		}

		/**
		 * Check if current page is archive pages
		 */
		public function is_archive() {
			return $this->is_taxonomy() || is_post_type_archive( $this->get_course_type() );
		}

		public function is_course_listing() {
			global $post;

			if ( $this->is_archive() ) {
				return true;
			}

			if ( ! empty( $post ) && isset( $post->ID ) && $this->get_page_id( 'courses' ) === $post->ID ) {
				return true;
			}

			return false;
		}

		public function get_course_ids_by_current_tax() {
			if ( ! $this->is_taxonomy() ) {
				return;
			}

			$current_tax    = get_queried_object();
			$transient_name = 'edumall_course_ids_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );
			$ids            = get_transient( $transient_name );

			if ( false === $ids ) {
				$args = [
					'post_type'      => Edumall_Tutor::instance()->get_course_type(),
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'no_found_rows'  => true,
					'fields'         => 'ids',
					'tax_query'      => array(
						array(
							'taxonomy' => $current_tax->taxonomy,
							'field'    => 'term_id',
							'terms'    => [ $current_tax->term_id ],
						),
					),
				];

				$ids = get_posts( $args );

				set_transient( $transient_name, $ids, 1 * HOUR_IN_SECONDS );
			}

			return $ids;
		}

		public function get_course_listing_base_url() {
			if ( is_post_type_archive( Edumall_Tutor::instance()->get_course_type() ) || is_page( Edumall_Tutor::instance()->get_page_id( 'courses' ) ) ) {
				$link = get_post_type_archive_link( Edumall_Tutor::instance()->get_course_type() );
			} elseif ( Edumall_Tutor::instance()->is_category() ) {
				$link = get_term_link( get_query_var( 'course-category' ), Edumall_Tutor::instance()->get_tax_category() );
			} elseif ( Edumall_Tutor::instance()->is_tag() ) {
				$link = get_term_link( get_query_var( 'course-tag' ), Edumall_Tutor::instance()->get_tax_tag() );
			} else {
				$queried_object = get_queried_object();
				$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			}

			// Shop layout preset.
			if ( isset( $_GET['course_archive_preset'] ) ) {
				$link = add_query_arg( 'course_archive_preset', Edumall_Helper::data_clean( wp_unslash( $_GET['course_archive_preset'] ) ), $link );
			}

			return $link;
		}

		/**
		 * Get course listing page URL with various filtering props supported by Edumall.
		 *
		 * @return string
		 * @since  1.0.0
		 */
		public function get_course_listing_page_url() {
			$link = $this->get_course_listing_base_url();

			// Course category.
			if ( isset( $_GET['filter_course-category'] ) ) {
				$link = add_query_arg( 'filter_course-category', Edumall_Helper::data_clean( wp_unslash( $_GET['filter_course-category'] ) ), $link );
			}

			// Course language.
			if ( isset( $_GET['filter_course-language'] ) ) {
				$link = add_query_arg( 'filter_course-language', Edumall_Helper::data_clean( wp_unslash( $_GET['filter_course-language'] ) ), $link );
			}

			// Course level.
			if ( isset( $_GET['level'] ) ) {
				$link = add_query_arg( 'level', Edumall_Helper::data_clean( wp_unslash( $_GET['level'] ) ), $link );
			}

			// Course duration.
			if ( isset( $_GET['duration'] ) ) {
				$link = add_query_arg( 'duration', Edumall_Helper::data_clean( wp_unslash( $_GET['duration'] ) ), $link );
			}

			// Course instructor.
			if ( isset( $_GET['instructor'] ) ) {
				$link = add_query_arg( 'instructor', Edumall_Helper::data_clean( wp_unslash( $_GET['instructor'] ) ), $link );
			}

			// Price filter.
			if ( isset( $_GET['price_type'] ) ) {
				$link = add_query_arg( 'price_type', Edumall_Helper::data_clean( wp_unslash( $_GET['price_type'] ) ), $link );
			}

			// Order by.
			if ( isset( $_GET['orderby'] ) ) {
				$link = add_query_arg( 'orderby', Edumall_Helper::data_clean( wp_unslash( $_GET['orderby'] ) ), $link );
			}

			/**
			 * Search Arg.
			 * Custom args post_title_like
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( isset( $_GET['filter_name'] ) ) {
				$link = add_query_arg( 'filter_name', rawurlencode( wp_specialchars_decode( esc_attr( $_GET['filter_name'] ) ) ), $link );
			}

			// Post Type Arg.
			if ( isset( $_GET['post_type'] ) ) {
				$link = add_query_arg( 'post_type', Edumall_Helper::data_clean( wp_unslash( $_GET['post_type'] ) ), $link );

				// Prevent post type and page id when pretty permalinks are disabled.
				if ( Edumall_Tutor::instance()->is_course_listing() ) {
					$link = remove_query_arg( 'page_id', $link );
				}
			}

			// Min Rating Arg.
			if ( isset( $_GET['rating_filter'] ) ) {
				$link = add_query_arg( 'rating_filter', Edumall_Helper::data_clean( wp_unslash( $_GET['rating_filter'] ) ), $link );
			}

			return apply_filters( 'edumall_course_get_current_page_url', $link, $this );
		}

		public function get_page_id( $page = '' ) {
			$key = '';
			switch ( $page ) {
				case 'dashboard':
					$key = 'tutor_dashboard_page_id';
					break;
				case 'courses':
					$key = 'course_archive_page';
					break;
			}

			$page = (int) tutor_utils()->get_option( $key );

			return $page ? absint( $page ) : -1;
		}

		/**
		 * Get list of all categories.
		 *
		 * @param array $args
		 *
		 * @return array
		 */
		public function get_categories( $args = array() ) {
			$defaults = array(
				'all' => true,
			);
			$args     = wp_parse_args( $args, $defaults );
			$terms    = get_terms( [
				'taxonomy' => $this->get_tax_category(),
			] );
			$results  = array();

			if ( $args['all'] === true ) {
				$results['-1'] = esc_html__( 'All', 'edumall' );
			}

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$results[ $term->slug ] = $term->name;
				}
			}

			return $results;
		}

		/**
		 * Get all categories of current post.
		 *
		 * @return false|WP_Error|WP_Term[]
		 */
		public function get_the_categories() {
			$terms = get_the_terms( get_the_ID(), $this->get_tax_category() );

			return $terms;
		}

		/**
		 * Get the first category of current post.
		 */
		public function get_the_category() {
			$terms = $this->get_the_categories();

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return false;
			}

			$term = $terms[0];

			return $term;
		}

		public function get_the_tags() {
			$id    = get_the_ID();
			$terms = get_the_terms( $id, $this->get_tax_tag() );

			return $terms;
		}

		public function get_related_courses( $args ) {
			$defaults = array(
				'post_id'      => '',
				'number_posts' => 3,
			);
			$args     = wp_parse_args( $args, $defaults );

			if ( $args['number_posts'] <= 0 || $args['post_id'] === '' ) {
				return false;
			}

			$related_by = Edumall::setting( 'course_related_by' );

			if ( empty( $related_by ) ) {
				return false;
			}

			$query_args = array(
				'post_type'      => $this->get_course_type(),
				'posts_per_page' => $args['number_posts'],
				'post_status'    => 'publish',
				'no_found_rows'  => true,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post__not_in'   => array( $args['post_id'] ),
			);

			if ( in_array( 'category', $related_by, true ) ) {
				$terms = $this->get_the_categories();

				if ( $terms && ! is_wp_error( $terms ) ) {

					$term_ids = array();

					foreach ( $terms as $category ) {
						if ( $category->parent === 0 ) {
							$term_ids[] = $category->term_id;
						} else {
							$term_ids[] = $category->parent;
							$term_ids[] = $category->term_id;
						}
					}

					// Remove duplicate values from the array.
					$unique_term_ids = array_unique( $term_ids );

					if ( empty( $query_args['tax_query'] ) ) {
						$query_args['tax_query'] = [];
					}

					$query_args['tax_query'][] = array(
						'taxonomy'         => $this->get_tax_category(),
						'terms'            => $unique_term_ids,
						'include_children' => false,
					);
				}
			}

			if ( in_array( 'tags', $related_by, true ) ) {
				$terms = $this->get_the_tags();

				if ( $terms && ! is_wp_error( $terms ) ) {
					$term_ids = array();

					foreach ( $terms as $tag ) {
						if ( $tag->parent === 0 ) {
							$term_ids[] = $tag->term_id;
						} else {
							$term_ids[] = $tag->parent;
							$term_ids[] = $tag->term_id;
						}
					}

					// Remove duplicate values from the array.
					$unique_term_ids = array_unique( $term_ids );

					if ( empty( $query_args['tax_query'] ) ) {
						$query_args['tax_query'] = [];
					}

					$query_args['tax_query'][] = array(
						'taxonomy'         => $this->get_tax_tag(),
						'terms'            => $unique_term_ids,
						'include_children' => false,
					);
				}
			}

			if ( count( $query_args['tax_query'] ) > 1 ) {
				$query_args['tax_query']['relation'] = 'OR';
			}

			$query = new WP_Query( $query_args );

			wp_reset_postdata();

			return $query;
		}

		public function get_the_price_html() {
			$course_id = get_the_ID();
			?>
			<div class="price">
				<?php
				if ( tutor_utils()->is_course_purchasable() ) {
					$product_id = tutor_utils()->get_course_product_id( $course_id );
					$product    = wc_get_product( $product_id );

					if ( $product ) {
						echo '' . $product->get_price_html();
					}
				} else {
					esc_html_e( 'Free', 'edumall' );
				}
				?>
			</div>
			<?php

		}

		public function get_course_language( $course_id = 0 ) {
			if ( ! $course_id ) {
				$course_id = get_the_ID();
			}
			$terms = get_the_terms( $course_id, $this->get_tax_language() );

			return $terms;
		}

		public function entry_course_language() {
			$disabled = get_tutor_option( 'disable_course_language' );

			if ( '1' === $disabled ) {
				return;
			}

			$terms = $this->get_course_language();

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return;
			}
			?>
			<div class="tutor-course-language">
				<span class="meta-label">
					<i class="meta-icon far fa-globe"></i>
					<?php esc_html_e( 'Language', 'edumall' ); ?>
				</span>
				<div class="meta-value">
					<?php foreach ( $terms as $term ): ?>
						<?php echo esc_html( $term->name ); ?>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		}

		public function entry_course_categories() {
			$terms = get_tutor_course_categories();

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return;
			}
			?>
			<div class="tutor-course-categories">
				<span class="meta-label">
					<i class="meta-icon far fa-tag"></i>
					<?php esc_html_e( 'Subject', 'edumall' ); ?>
				</span>
				<div class="meta-value">
					<?php
					foreach ( $terms as $course_category ) {
						$category_name = $course_category->name;
						$category_link = get_term_link( $course_category->term_id );
						echo "<a href='$category_link'>$category_name</a>";
					}
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * @param null   $user_id
		 * @param string $size
		 *
		 * Generate text to avatar
		 *
		 * Rewrite plugin function.
		 *
		 * @see   \TUTOR\Utils::get_tutor_avatar()
		 *
		 * @return string
		 */
		public function get_avatar( $user_id = null, $size = 'thumbnail' ) {
			if ( ! $user_id ) {
				return '';
			}

			$user = tutor_utils()->get_tutor_user( $user_id );
			if ( $user->tutor_profile_photo ) {
				return Edumall_Image::get_attachment_by_id( [
					'id'        => $user->tutor_profile_photo,
					'size'      => $size,
					'img_attrs' => [
						'class' => 'tutor-image-avatar',
					],
				] );
			}

			$name = $user->display_name;
			$arr  = explode( ' ', trim( $name ) );

			if ( count( $arr ) > 1 ) {
				$first_char  = substr( $arr[0], 0, 1 );
				$second_char = substr( $arr[1], 0, 1 );
			} else {
				$first_char  = substr( $arr[0], 0, 1 );
				$second_char = substr( $arr[0], 1, 1 );
			}

			$initial_avatar = strtoupper( $first_char . $second_char );

			$bg_color       = '#' . substr( md5( $initial_avatar ), 0, 6 );
			$initial_avatar = "<span class='tutor-text-avatar' style='background-color: {$bg_color}; color: #fff8e5'>{$initial_avatar}</span>";

			return $initial_avatar;
		}

		public function get_course_price_badge_text( $course_id = null, $format = '-%s' ) {
			if ( ! $course_id ) {
				$course_id = get_the_ID();
			}

			$badge_text = '';

			$is_purchasable = tutor_utils()->is_course_purchasable();
			$price          = apply_filters( 'get_tutor_course_price', null, get_the_ID() );

			if ( $is_purchasable && $price ) {
				if ( tutor_utils()->has_wc() ) {
					$product_id = tutor_utils()->get_course_product_id( $course_id );
					$product    = wc_get_product( $product_id );

					if ( $product && $product->is_on_sale() ) {
						if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {
							$_regular_price = $product->get_regular_price();
							$_sale_price    = $product->get_sale_price();

							$percentage = round( ( ( $_regular_price - $_sale_price ) / $_regular_price ) * 100 );

							$badge_text = sprintf( $format, "{$percentage}%" );
						} else {
							$badge_text = esc_html__( 'Sale Off', 'edumall' );
						}
					}
				}
				//@todo Sale flash need support EDD too.
			}

			return $badge_text;
		}

		/**
		 * @see get_tutor_course_duration_context()
		 *
		 * @param int $course_id
		 *
		 *
		 * @return bool|string
		 */
		public function get_course_duration_context( $course_id = 0 ) {
			if ( ! $course_id ) {
				$course_id = get_the_ID();
			}
			if ( ! $course_id ) {
				return false;
			}
			$duration        = get_post_meta( $course_id, '_course_duration', true );
			$durationHours   = intval( tutor_utils()->avalue_dot( 'hours', $duration ) );
			$durationMinutes = intval( tutor_utils()->avalue_dot( 'minutes', $duration ) );
			$durationSeconds = intval( tutor_utils()->avalue_dot( 'seconds', $duration ) );

			if ( $duration ) {
				$output        = '';
				$total_hours   = 0;
				$total_minutes = 0;

				if ( $durationSeconds > 0 ) {
					$total_minutes += $durationSeconds / 60;
				}

				if ( $durationMinutes > 0 ) {
					$total_minutes += $durationMinutes;
				}

				if ( $durationHours > 0 ) {
					$total_hours += $total_minutes / 60;

					$total_hours += $durationHours;
				}

				if ( $total_hours > 0 ) {
					$total_hours = round( $total_hours, 1 );

					$output .= sprintf( '%s %s', $total_hours, _n( 'hour', 'hours', intval( $total_hours ), 'edumall' ) );
				} else {
					$total_minutes = round( $total_minutes, 1 );

					if ( $total_minutes > 0 ) {
						$output .= sprintf( '%s %s', $total_minutes, _n( 'minute', 'minutes', intval( $total_minutes ), 'edumall' ) );
					}
				}

				return $output;
			}

			return false;
		}

		/**
		 * Dropdown categories.
		 *
		 * @param array $args Args to control display of dropdown.
		 */
		public function course_dropdown_categories( $args = array() ) {
			global $wp_query;

			$args = wp_parse_args(
				$args,
				array(
					'pad_counts'         => 1,
					'show_count'         => 1,
					'hierarchical'       => 1,
					'hide_empty'         => 1,
					'show_uncategorized' => 1,
					'orderby'            => 'name',
					'selected'           => isset( $wp_query->query_vars['course-category'] ) ? $wp_query->query_vars['course-category'] : '',
					'show_option_none'   => esc_html__( 'Select a category', 'edumall' ),
					'option_none_value'  => '',
					'value_field'        => 'slug',
					'taxonomy'           => 'course-category',
					'name'               => 'course-category',
					'class'              => 'dropdown-course-category',
				)
			);

			if ( 'order' === $args['orderby'] ) {
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'order'; // phpcs:ignore
			}

			wp_dropdown_categories( $args );
		}

		public function course_loop_price() {
			$is_purchasable = tutor_utils()->is_course_purchasable();
			$price          = apply_filters( 'get_tutor_course_price', null, get_the_ID() );
			?>
			<div class="course-loop-price">
				<?php
				if ( $is_purchasable && $price ) {
					echo '<div class="tutor-price">' . $price . '</div>';
				} else {
					?>
					<div class="tutor-price course-free">
						<?php esc_html_e( 'Free', 'edumall' ); ?>
					</div>
					<?php
				} ?>
			</div>
			<?php
		}

		/**
		 * Get first category of current course.
		 */
		public function course_loop_category() {
			$terms = get_tutor_course_categories();

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return;
			}
			?>
			<div class="course-category">
				<?php
				foreach ( $terms as $course_category ) {
					$category_name = $course_category->name;
					$category_link = get_term_link( $course_category->term_id );
					echo "<a href='$category_link'>$category_name</a>";

					break;
				}
				?>
			</div>
			<?php
		}

		/**
		 * Re-write function
		 *
		 * @see tutor_course_enroll_box()
		 *
		 * @param bool $echo
		 *
		 * @return mixed
		 */
		public function course_enroll_box( $echo = true ) {
			global $edumall_course;

			ob_start();

			if ( $edumall_course->is_enrolled() ) {
				tutor_load_template( 'single.course.course-enrolled-box' );
				$output = apply_filters( 'tutor_course/single/enrolled', ob_get_clean() );
			} else {
				tutor_load_template( 'single.course.course-enroll-box' );
				$output = apply_filters( 'tutor_course/single/enroll', ob_get_clean() );
			}

			if ( $echo ) {
				echo '' . $output;
			}

			return $output;
		}

		/**
		 * Re-write function
		 *
		 * @see get_lesson_type_icon()
		 *
		 * @param int  $lesson_id
		 * @param bool $html
		 * @param bool $echo
		 *
		 * @return string
		 */
		public function get_lesson_type_icon( $lesson_id = 0, $html = false, $echo = false ) {
			$post_id = tutor_utils()->get_post_id( $lesson_id );
			$video   = tutor_utils()->get_video_info( $post_id );

			$play_time = false;
			if ( $video ) {
				$play_time = $video->playtime;
			}

			$tutor_lesson_type_icon = $play_time ? 'youtube' : 'document';

			if ( $html ) {
				$tutor_lesson_type_icon = "<i class='tutor-icon-$tutor_lesson_type_icon'></i> ";
			}

			if ( $tutor_lesson_type_icon ) {
				echo '' . $tutor_lesson_type_icon;
			}

			return $tutor_lesson_type_icon;
		}

		/**
		 * Re-write function
		 *
		 * @see \Tutor\Utils::get_video_info()
		 *
		 * @param array $video Get info of given video.
		 * @param int   $post_id
		 *
		 * @return bool|object
		 */
		public function get_video_info( $video, $post_id ) {
			if ( ! $video ) {
				return false;
			}

			$info = array(
				'playtime' => '00:00',
			);

			$types = apply_filters( 'tutor_video_types', array(
				"mp4"  => "video/mp4",
				"webm" => "video/webm",
				"ogg"  => "video/ogg",
			) );

			$videoSource = tutor_utils()->avalue_dot( 'source', $video );

			if ( $videoSource === 'html5' ) {
				$sourceVideoID = tutor_utils()->avalue_dot( 'source_video_id', $video );
				$video_info    = get_post_meta( $sourceVideoID, '_wp_attachment_metadata', true );

				if ( $video_info && in_array( tutor_utils()->array_get( 'mime_type', $video_info ), $types ) ) {
					$path             = get_attached_file( $sourceVideoID );
					$info['playtime'] = $video_info['length_formatted'];
					$info['path']     = $path;
					$info['url']      = wp_get_attachment_url( $sourceVideoID );
					$info['ext']      = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
					$info['type']     = $types[ $info['ext'] ];
				}
			}

			if ( $videoSource !== 'html5' ) {
				$video = maybe_unserialize( get_post_meta( $post_id, '_video', true ) );

				$runtimeHours   = tutor_utils()->avalue_dot( 'runtime.hours', $video );
				$runtimeMinutes = tutor_utils()->avalue_dot( 'runtime.minutes', $video );
				$runtimeSeconds = tutor_utils()->avalue_dot( 'runtime.seconds', $video );

				$runtimeHours   = $runtimeHours ? $runtimeHours : '00';
				$runtimeMinutes = $runtimeMinutes ? $runtimeMinutes : '00';
				$runtimeSeconds = $runtimeSeconds ? $runtimeSeconds : '00';

				if ( '00' === $runtimeHours ) {
					$info['playtime'] = "$runtimeMinutes:$runtimeSeconds";
				} else {
					$info['playtime'] = "$runtimeHours:$runtimeMinutes:$runtimeSeconds";
				}
			}

			$info = array_merge( $info, $video );

			return (object) $info;
		}

		/**
		 * Re-write function
		 *
		 * @see tutor_single_course_add_to_cart()
		 *
		 * Remove login template, use global function.
		 *
		 * @param bool $echo
		 *
		 * @return string
		 *
		 * Get Only add to cart form
		 */
		public function single_course_add_to_cart( $echo = true ) {
			global $edumall_course;
			$total_enrolled   = $edumall_course->get_enrolled_users_count();
			$maximum_students = (int) tutor_utils()->get_course_settings( null, 'maximum_students' );

			ob_start();
			$output = '';
			if ( $maximum_students && $maximum_students <= $total_enrolled ) {
				$template = 'closed-enrollment';
			} else {
				$template = 'add-to-cart';
			}

			tutor_load_template( 'single.course.' . $template );
			$output .= apply_filters( 'tutor_course/single/' . $template, ob_get_clean() );

			if ( $echo ) {
				echo '' . $output;
			}

			return $output;
		}

		/**
		 * Re-write function
		 * Fix original function not get users with role instructor.
		 *
		 * @see   \Tutor\Utils::get_instructors()
		 *
		 * @return array|null|object
		 *
		 * Get all instructors.
		 *
		 * @since v.1.0.0
		 */
		public function get_instructors() {
			$instructors = get_users( [
				'fields'   => 'all',
				'role__in' => [ 'tutor_instructor' ],
			] );

			return $instructors;
		}

		/**
		 * @see   \Tutor\Utils::get_instructors_by_course()
		 *
		 * @param array $course_ids
		 *
		 * @return array|bool|null|object
		 *
		 * Get all instructors by course ids
		 *
		 * @since v.1.0.0
		 */
		public function get_popular_instructors_by_course_ids( array $course_ids ) {
			global $wpdb;

			$instructors = $wpdb->get_results( "SELECT ID, display_name, 
			get_course.meta_value as taught_course_id,
			tutor_job_title.meta_value as tutor_profile_job_title,
			tutor_bio.meta_value as tutor_profile_bio,
			tutor_photo.meta_value as tutor_profile_photo,
			tutor_total_students.meta_value as tutor_profile_total_students
			FROM {$wpdb->users}
			INNER JOIN {$wpdb->usermeta} get_course ON ID = get_course.user_id AND get_course.meta_key = '_tutor_instructor_course_id' AND get_course.meta_value IN (" . implode( ',', array_map( 'absint', $course_ids ) ) . ")
			LEFT JOIN {$wpdb->usermeta} tutor_job_title ON ID = tutor_job_title.user_id AND tutor_job_title.meta_key = '_tutor_profile_job_title'
			LEFT JOIN {$wpdb->usermeta} tutor_bio ON ID = tutor_bio.user_id AND tutor_bio.meta_key = '_tutor_profile_bio'
			LEFT JOIN {$wpdb->usermeta} tutor_photo ON ID = tutor_photo.user_id AND tutor_photo.meta_key = '_tutor_profile_photo'
			LEFT JOIN {$wpdb->usermeta} tutor_total_students ON ID = tutor_total_students.user_id AND tutor_total_students.meta_key = '_tutor_total_students'
			GROUP BY ID
			ORDER BY tutor_profile_total_students DESC 
			" );

			if ( is_array( $instructors ) && count( $instructors ) ) {
				return $instructors;
			}

			return false;
		}

		/**
		 * Count number of courses of given instructor.
		 *
		 * @param $instructor_id
		 *
		 * @return int
		 */
		public function get_total_courses_by_instructor( $instructor_id ) {
			global $wpdb;

			$sql = "SELECT COUNT( {$wpdb->users}.ID ) FROM {$wpdb->users}";
			$sql .= " INNER JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID = {$wpdb->usermeta}.user_id AND {$wpdb->usermeta}.meta_key = '_tutor_instructor_course_id'";
			$sql .= " INNER JOIN {$wpdb->posts} ON {$wpdb->posts}.ID = {$wpdb->usermeta}.meta_value";
			$sql .= " WHERE {$wpdb->users}.ID = {$instructor_id}";
			$sql .= " AND {$wpdb->posts}.post_type = 'courses' AND {$wpdb->posts}.post_status = 'publish'";

			return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
		}

		public function get_popular_instructors_by_current_tax() {
			$current_tax = get_queried_object();

			$transient_name      = 'edumall_course_instructors_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );
			$popular_instructors = get_transient( $transient_name );

			if ( false === $popular_instructors ) {
				$ids                 = Edumall_Tutor::instance()->get_course_ids_by_current_tax();
				$popular_instructors = [];

				if ( $ids ) {
					$popular_instructors = Edumall_Tutor::instance()->get_popular_instructors_by_course_ids( $ids );
				}

				set_transient( $transient_name, $popular_instructors, 1 * HOUR_IN_SECONDS );
			}

			return $popular_instructors;
		}

		public function get_featured_courses_by_current_tax() {
			$current_tax = get_queried_object();

			$transient_name = 'edumall_featured_courses_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );

			$featured_courses = get_transient( $transient_name );

			if ( false === $featured_courses ) {
				$featured_courses = [];

				$query_args = [
					'post_type'      => $this->get_course_type(),
					'posts_per_page' => 10,
					'post_status'    => 'publish',
					'no_found_rows'  => true,
					'orderby'        => 'date',
					'order'          => 'DESC',
					'tax_query'      => [
						'relation' => 'AND',
						array(
							'taxonomy' => $current_tax->taxonomy,
							'terms'    => $current_tax->term_id,
						),
						array(
							'taxonomy' => 'course-visibility',
							'field'    => 'slug',
							'terms'    => [ 'featured' ],
						),
					],
				];

				$query = new WP_Query( $query_args );

				if ( $query->have_posts() ) {
					$featured_courses = $query;
				}

				set_transient( $transient_name, $featured_courses, 1 * HOUR_IN_SECONDS );
			}

			return $featured_courses;
		}

		public function get_popular_courses_by_current_tax() {
			$current_tax = get_queried_object();

			$transient_name  = 'edumall_popular_courses_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );
			$popular_courses = get_transient( $transient_name );

			if ( false === $popular_courses ) {
				$popular_courses = [];

				$query_args = [
					'post_type'      => $this->get_course_type(),
					'posts_per_page' => 10,
					'post_status'    => 'publish',
					'no_found_rows'  => true,
					'tax_query'      => [
						array(
							'taxonomy' => $current_tax->taxonomy,
							'terms'    => $current_tax->term_id,
						),
					],
					'meta_query'     => [
						'relation' => 'OR',
						array(
							'key'     => '_course_total_enrolls',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => '_course_total_enrolls',
							'compare' => 'EXISTS',
						),
					],
					'order'          => 'DESC',
					'orderby'        => 'meta_value_num',
				];

				$query = new WP_Query( $query_args );

				if ( $query->have_posts() ) {
					$popular_courses = $query;
				}

				set_transient( $transient_name, $popular_courses, 1 * HOUR_IN_SECONDS );
			}

			return $popular_courses;
		}

		public function get_trending_courses_by_current_tax() {
			$current_tax = get_queried_object();

			$transient_name   = 'edumall_trending_courses_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );
			$trending_courses = get_transient( $transient_name );

			if ( false === $trending_courses ) {
				$trending_courses = [];

				$query_args = [
					'post_type'      => $this->get_course_type(),
					'posts_per_page' => 10,
					'post_status'    => 'publish',
					'no_found_rows'  => true,
					'tax_query'      => [
						array(
							'taxonomy' => $current_tax->taxonomy,
							'terms'    => $current_tax->term_id,
						),
					],
					'meta_query'     => [
						'relation' => 'OR',
						array(
							'key'     => 'views',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => 'views',
							'compare' => 'EXISTS',
						),
					],
					'order'          => 'DESC',
					'orderby'        => 'meta_value_num',
				];

				$query = new WP_Query( $query_args );

				if ( $query->have_posts() ) {
					$trending_courses = $query;
				}

				set_transient( $transient_name, $trending_courses, 1 * HOUR_IN_SECONDS );
			}

			return $trending_courses;
		}

		public function get_popular_topics_by_current_tax() {
			$current_tax = get_queried_object();

			$transient_name = 'edumall_course_tags_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );
			$popular_topics = get_transient( $transient_name );

			if ( false === $popular_topics ) {
				$ids            = Edumall_Tutor::instance()->get_course_ids_by_current_tax();
				$popular_topics = [];

				if ( $ids ) {
					/**
					 * Because we only query post ID's, the post caches are not updated which is
					 * good and bad
					 *
					 * GOOD -> It saves on resources because we do not need post data or post meta data
					 * BAD -> We loose the vital term cache, which will result in even more db calls
					 *
					 * To solve that, we manually update the term cache with update_object_term_cache
					 */
					//update_object_term_cache( $ids, 'courses' );

					$popular_topics = get_terms( [
						'taxonomy'   => Edumall_Tutor::instance()->get_tax_tag(),
						'object_ids' => $ids,
						'orderby'    => 'views',
						'order'      => 'DESC',
						'meta_query' => array(
							'relation' => 'OR',
							array(
								'key'     => 'views',
								'value'   => '0',
								'compare' => '>',
								'type'    => 'NUMERIC',
							),
							array(
								'key'     => 'views',
								'compare' => 'NOT EXISTS',
								'value'   => 'null',
							),
						),
					] );

					if ( is_wp_error( $popular_topics ) ) {
						$popular_topics = [];
					}
				}

				set_transient( $transient_name, $popular_topics, 1 * HOUR_IN_SECONDS );
			}

			return $popular_topics;
		}

		public function get_course_sorting_options() {
			$sorting_options = [
				'newest_first'    => __( 'Latest', 'edumall' ),
				'oldest_first'    => __( 'Oldest', 'edumall' ),
				'course_title_az' => __( 'Course Title (a-z)', 'edumall' ),
				'course_title_za' => __( 'Course Title (z-a)', 'edumall' ),
			];

			return apply_filters( 'edumall_course_sorting_options', $sorting_options );
		}

		public function get_course_default_sort_option() {
			return apply_filters( 'edumall_course_default_sorting_option', 'newest_first' );
		}

		public function get_course_archive_layout() {
			$layout = apply_filters( 'edumall_course_archive_layout', Edumall::setting( 'course_archive_layout' ) );

			return $layout;
		}

		public function get_course_archive_style() {
			$layout = $this->get_course_archive_layout();

			if ( 'list' === $layout ) {
				$style = Edumall::setting( 'course_archive_list_style' );
			} else {
				$style = Edumall::setting( 'course_archive_grid_style' );
			}

			$style = apply_filters( 'edumall_course_archive_style', $style );

			return $style;
		}

		/**
		 * @param $product_id
		 *
		 * @return array|bool|null|WP_Post
		 */
		public function get_course_by_wc_product( $product_id ) {
			if ( Edumall_Woo::instance()->is_tutor_product( $product_id ) ) {
				$course_meta = tutor_utils()->product_belongs_with_course( $product_id );

				if ( ! empty( $course_meta ) ) {
					$course_id = $course_meta->post_id;
					$course    = get_post( $course_id );

					return $course;
				}
			}

			return false;
		}

		public function course_prerequisites() {
			if ( ! class_exists( 'TUTOR_PREREQUISITES\init' ) ) {
				return;
			}

			tutor_load_template( 'single.course.course-prerequisites-alt' );
		}
	}

	Edumall_Tutor::instance()->initialize();
}
