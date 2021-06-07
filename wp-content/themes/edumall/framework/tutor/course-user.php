<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Course_User' ) ) {
	class Edumall_Course_User {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'edumall_user_profile_url', [ $this, 'update_profile_url' ], 20 );
			add_filter( 'edumall_user_profile_text', [ $this, 'update_profile_text' ], 20 );

			add_action( 'tutor_after_enroll', [ $this, 'update_instructor_meta_total_students' ], 10, 2 );
			add_action( 'tutor_after_enroll', [ $this, 'update_course_meta_total_enrolls' ], 10, 2 );

			add_action( 'wp_footer', [ $this, 'add_popup_instructor_registration' ] );

			add_action( 'wp_ajax_nopriv_edumall_instructor_register', [ $this, 'instructor_register' ] );

			add_action( 'wp_ajax_edumall_apply_instructor', [ $this, 'apply_instructor' ] );
			add_action( 'wp_ajax_nopriv_edumall_apply_instructor', [ $this, 'apply_instructor' ] );

			add_filter( 'tutor/options/attr', [ $this, 'add_teacher_setting' ] );

			// Add job title to setting tab in Dashboard.
			add_action( 'tutor_profile_update_after', [ $this, 'add_job_title_setting' ] );
		}

		public function update_profile_url() {
			return tutor_utils()->get_tutor_dashboard_page_permalink();
		}

		public function update_profile_text() {
			return esc_html__( 'Dashboard', 'edumall' );
		}

		public function add_job_title_setting( $user_id ) {
			$job_title = sanitize_text_field( tutor_utils()->input_old( 'tutor_profile_job_title' ) );

			update_user_meta( $user_id, '_tutor_profile_job_title', $job_title );
		}

		public function add_teacher_setting( $setting ) {
			$setting['instructors']['sections']['general']['fields']['instructor_register_immediately'] = [
				'type'        => 'checkbox',
				'label'       => __( 'Become Instructor Immediately', 'edumall' ),
				'label_title' => __( 'Enable', 'edumall' ),
				'default'     => '0',
				'desc'        => __( 'Enabling this feature will make user become Instructor immediately without review from Admin.', 'edumall' ),
			];

			return $setting;
		}

		public function add_popup_instructor_registration() {
			tutor_load_template( 'global.popup-instructor-registration' );
		}

		public function instructor_register() {
			if ( ! check_ajax_referer( 'instructor_register', 'instructor_register_nonce' ) ) {
				wp_die();
			}

			if ( Edumall_Helper::is_demo_site() ) {
				echo json_encode( Edumall_Helper::get_failed_demo_code() );
				wp_die();
			}

			$fullname = $_POST['fullname'];
			$email    = $_POST['email'];
			$password = $_POST['password'];

			$userdata = [
				'first_name' => $fullname,
				'user_login' => $email,
				'user_email' => $email,
				'user_pass'  => $password,
			];

			// Remove all illegal characters from email.
			$email = filter_var( $email, FILTER_SANITIZE_EMAIL );

			$response = [
				'success'  => false,
				'messages' => esc_html__( 'Username/Email address is existing', 'edumall' ),
			];

			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$response = [
					'success'  => false,
					'messages' => esc_html__( 'A valid email address is required', 'edumall' ),
				];
			} else {
				$user_id = wp_insert_user( $userdata );

				if ( ! is_wp_error( $user_id ) ) {
					update_user_meta( $user_id, '_is_tutor_instructor', tutor_time() );

					$register_immediately = get_tutor_option( 'instructor_register_immediately' );

					if ( 1 == $register_immediately || ( defined( 'EDUMALL_LIVE_SITE' ) && true === EDUMALL_LIVE_SITE ) ) {
						/**
						 * @see \TUTOR\Instructor::instructor_approval_action()
						 */
						do_action( 'tutor_before_approved_instructor', $user_id );

						update_user_meta( $user_id, '_tutor_instructor_status', 'approved' );
						update_user_meta( $user_id, '_tutor_instructor_approved', tutor_time() );

						$instructor = new \WP_User( $user_id );
						$instructor->add_role( tutor()->instructor_role );

						//TODO: send E-Mail to this user about instructor approval, should via hook
						do_action( 'tutor_after_approved_instructor', $user_id );
					} else {
						update_user_meta( $user_id, '_tutor_instructor_status', apply_filters( 'tutor_initial_instructor_status', 'pending' ) );
					}

					do_action( 'tutor_new_instructor_after', $user_id );

					$creds                  = array();
					$creds['user_login']    = $email;
					$creds['user_email']    = $email;
					$creds['user_password'] = $password;
					$creds['remember']      = true;
					$user                   = wp_signon( $creds, false );

					$response = [
						'success'  => true,
						'messages' => esc_html__( 'Congratulations, register successful, Redirecting...', 'edumall' ),
						'redirect' => true,
					];
				}
			}

			echo wp_json_encode( $response );

			wp_die();
		}

		public function apply_instructor() {
			if ( ! check_ajax_referer( 'apply_instructor', 'apply_instructor_nonce' ) ) {
				wp_die();
			}

			$user_id = get_current_user_id();

			$response = [
				'success'  => 0,
				'messages' => '',
			];

			if ( $user_id ) {
				if ( tutor_utils()->is_instructor() ) {
					$response = [
						'success'  => 0,
						'messages' => esc_html__( 'Already applied for instructor!', 'edumall' ),
					];
				} else {
					update_user_meta( $user_id, '_is_tutor_instructor', tutor_time() );

					$register_immediately = get_tutor_option( 'instructor_register_immediately' );

					if ( 1 == $register_immediately || ( defined( 'EDUMALL_LIVE_SITE' ) && true === EDUMALL_LIVE_SITE ) ) {
						/**
						 * @see \TUTOR\Instructor::instructor_approval_action()
						 */
						do_action( 'tutor_before_approved_instructor', $user_id );

						update_user_meta( $user_id, '_tutor_instructor_status', 'approved' );
						update_user_meta( $user_id, '_tutor_instructor_approved', tutor_time() );

						$instructor = new \WP_User( $user_id );
						$instructor->add_role( tutor()->instructor_role );

						//TODO: send E-Mail to this user about instructor approval, should via hook
						do_action( 'tutor_after_approved_instructor', $user_id );

						$response = [
							'success'  => 1,
							'messages' => esc_html__( 'Congratulations, You are Instructor.', 'edumall' ),
						];
					} else {
						update_user_meta( $user_id, '_tutor_instructor_status', apply_filters( 'tutor_initial_instructor_status', 'pending' ) );

						$response = [
							'success'  => 1,
							'messages' => esc_html__( 'Your request is sent successfully.', 'edumall' ),
						];
					}

					do_action( 'tutor_new_instructor_after', $user_id );
				}
			} else {
				$response = [
					'success'  => 0,
					'messages' => esc_html__( 'Permission denied!', 'edumall' ),
				];
			}

			echo wp_json_encode( $response );

			wp_die();
		}

		/**
		 * Update total students for all instructors of enrolled course.
		 *
		 * @param $course_id
		 * @param $isEnrolled
		 */
		public function update_instructor_meta_total_students( $course_id, $isEnrolled ) {
			// Get all instructors of the course.
			$instructors = tutor_utils()->get_instructors_by_course( $course_id );

			if ( empty( $instructors ) ) {
				return;
			}

			foreach ( $instructors as $instructor ) {
				// Then update instructor meta total students.
				$total_students = tutor_utils()->get_total_students_by_instructor( $instructor->ID );

				update_user_meta( $instructor->ID, '_tutor_total_students', $total_students );
			}
		}

		public function update_course_meta_total_enrolls( $course_id, $isEnrolled ) {
			$total_enrolls = tutor_utils()->count_enrolled_users_by_course( $course_id );

			update_post_meta( $course_id, '_course_total_enrolls', $total_enrolls );
		}
	}
}
