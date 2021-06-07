<?php

namespace Codemanas\VczApi\Shortcodes;

class Recordings {

	/**
	 * Instance
	 *
	 * @var null
	 */
	private static $_instance = null;

	/**
	 * Create only one instance so that it may not Repeat
	 *
	 * @since 2.0.0
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Recordings API Shortcode
	 *
	 * @param $atts
	 *
	 * @return bool|false|string
	 */
	public function recordings_by_user( $atts ) {
		$atts = shortcode_atts(
			array(
				'host_id'      => '',
				'per_page'     => 300,
				'downloadable' => 'no'
			),
			$atts, 'zoom_recordings'
		);

		if ( empty( $atts['host_id'] ) ) {
			echo '<h3 class="no-host-id-defined"><strong style="color:red;">' . __( 'Invalid HOST ID. Please define a host ID to show recordings based on host.', 'video-conferencing-with-zoom-api' ) . '</h3>';

			return false;
		}

		wp_enqueue_style( 'video-conferencing-with-zoom-api-datable' );
		wp_enqueue_style( 'video-conferencing-with-zoom-api-datable-responsive' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-datable-responsive-js' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-datable-dt-responsive-js' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-shortcode-js' );

		$postParams = array(
			'page_size' => 300 //$atts['per_page'] disbled for now
		);

		//Pagination
		if ( isset( $_GET['pg'] ) && isset( $_GET['type'] ) && $_GET['type'] === "recordings" ) {
			$postParams['next_page_token'] = $_GET['pg'];
			$recordings                    = json_decode( zoom_conference()->listRecording( $atts['host_id'], $postParams ) );
		} else {
			$recordings = json_decode( zoom_conference()->listRecording( $atts['host_id'], $postParams ) );
		}

		unset( $GLOBALS['zoom_recordings'] );
		ob_start();
		if ( ! empty( $recordings ) ) {
			if ( ! empty( $recordings->code ) && ! empty( $recordings->message ) ) {
				echo $recordings->message;
			} else {
				if ( ! empty( $recordings->meetings ) ) {
					$GLOBALS['zoom_recordings']               = $recordings;
					$GLOBALS['zoom_recordings']->downloadable = ( ! empty( $atts['downloadable'] ) && $atts['downloadable'] === "yes" ) ? true : false;
					vczapi_get_template( 'shortcode/zoom-recordings.php', true );
				} else {
					_e( "No recordings found.", "video-conferencing-with-zoom-api" );
				}
			}
		} else {
			_e( "No recordings found.", "video-conferencing-with-zoom-api" );
		}

		return ob_get_clean();
	}

	/**
	 * Show recordings based on Meeting ID
	 *
	 * @param $atts
	 *
	 * @return bool|false|string
	 */
	public function recordings_by_meeting_id( $atts ) {
		$atts    = shortcode_atts(
			array(
				'meeting_id'   => '',
				'downloadable' => 'no'
			),
			$atts, 'zoom_recordings'
		);
		$post_id = get_the_ID();

		if ( empty( $atts['meeting_id'] ) ) {
			echo '<h3 class="no-meeting-id-defined"><strong style="color:red;">' . __( 'Invalid Meeting ID.', 'video-conferencing-with-zoom-api' ) . '</h3>';

			return false;
		}

		wp_enqueue_style( 'video-conferencing-with-zoom-api-datable' );
		wp_enqueue_style( 'video-conferencing-with-zoom-api-datable-responsive' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-datable-responsive-js' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-datable-dt-responsive-js' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-shortcode-js' );

		$recordings        = [];
		$flush_cache       = filter_input( INPUT_GET, 'flush_cache' );
		$cached_recordings = Helpers::get_post_cache( $post_id, '_vczapi_shortcode_recordings_by_meeting_id' );


		ob_start();
		unset( $GLOBALS['zoom_recordings'] );
		unset( $GLOBALS['zoom_recordings_is_downloadable'] );


		if ( ! empty( $cached_recordings ) && isset( $cached_recordings[ $atts['meeting_id'] ] ) && $flush_cache != 'yes' ) {
			//if cached recordings exist use that
			$recordings = $cached_recordings[ $atts['meeting_id'] ];
		} else {
			$all_past_meetings = json_decode( zoom_conference()->getPastMeetingDetails( $atts['meeting_id'] ) );
			if ( isset( $all_past_meetings->meetings ) && ! empty( $all_past_meetings->meetings ) && ! isset( $all_past_meetings->code ) ) {
				//loop through all instance of past / completed meetings and get recordings
				foreach ( $all_past_meetings->meetings as $meeting ) {
					$recordings[] = json_decode( zoom_conference()->recordingsByMeeting( $meeting->uuid ) );
				}
				Helpers::set_post_cache( $post_id, '_vczapi_shortcode_recordings_by_meeting_id', [ $atts['meeting_id'] => $recordings ], 86400 );
			} else {
				$recordings[] = json_decode( zoom_conference()->recordingsByMeeting( $atts['meeting_id'] ) );
				Helpers::set_post_cache( $post_id, '_vczapi_shortcode_recordings_by_meeting_id', [ $atts['meeting_id'] => $recordings ], 86400 );
			}
		}


		if ( ! empty( $recordings ) ) {
			if ( ! empty( $recordings->code ) && ! empty( $recordings->message ) ) {
				echo $recordings->message;
			} else {
				foreach ( $recordings as $recording ) {
					if ( ! empty( $recording->recording_files ) ) {
						$GLOBALS['zoom_recordings'][]               = $recording;
						$GLOBALS['zoom_recordings_is_downloadable'] = ( ! empty( $atts['downloadable'] ) && $atts['downloadable'] === "yes" ) ? true : false;
					}
				}
			}
		}

		if ( ! empty( $GLOBALS['zoom_recordings'] ) ) {
			vczapi_get_template( 'shortcode/zoom-recordings-by-meeting.php', true );
		} else {
			_e( "No recordings found.", "video-conferencing-with-zoom-api" );
			?>
            <a href="<?php echo add_query_arg( [ 'flush_cache' => 'yes' ], get_the_permalink() ) ?>"><?php _e( 'Check for latest' ); ?></a>
			<?php
		}


		return ob_get_clean();
	}
}