<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>
<div class='col-sm-12'>
<div class='row'>
	<div class='col-sm-6' >
	<div style="background:#f0b105;padding: 9px 12px; color:white;border-radius:6px">
	  <div class='row'>
		<div class='col-sm-6' style='margin-top:12px;'>
		<img src='http://localhost/DesignFoundry/wp-content/uploads/2020/12/Group-1-1.png' alt='Icon'>
		<p style='margin-top:18px'> <b>Go to the Design Foundry </br>
		     Forum & Group Activities</b> </p>
		</div>
		<div class='col-sm-6'>
		  <img src="http://localhost/DesignFoundry/wp-content/uploads/2020/12/Group-5-1.png" alt='icon'>
		</div>
	  </div>
	</div>
	</div>
	<div class='col-sm-6'>
	<div style='background:#6558fe;padding: 24px 24px;color:white;border-radius:6px'>
	
	 <div class='row'>
		<div class='col-sm-8' style='margin-top:12px;text-align: center;'>
		<h6 style='color:white;margin:12px 0px;'><img src='http://localhost/DesignFoundry/wp-content/uploads/2020/12/Group-2.png'> Join Our Slack Group </h6>
		<p> <b>Head Over to The Design Foundry Slack Group</b> </p>
		</div>
		<div class='col-sm-4' style='text-align:center'>
		<img src="http://localhost/DesignFoundry/wp-content/uploads/2020/12/Group-1-2.png" alt='icon'>
		</div>
	 </div>
	
	</div>
	</div>
</div>
<br>
<!-- Bootcamps part start--->

<div class='row'>
<div class='col-sm-12'>
  <h2> Bootcamps </h2>
 </div> </div>
 <div class='row'>
<div class='col-sm-8'> 
<div style="background:white;padding: 9px 12px;border-radius:6px">
<div class='row'>
  <div class='col-sm-6'>
  <img src="http://localhost/DesignFoundry/wp-content/uploads/2020/12/Group-51.png" width="100%">
  </div>
  <div class='col-sm-6'>
  <h6>UI/UX Design Bootcamp: Become a UI/UX Designer. </h6>
  <ul class='bootcamp-ul-li'>
  <li>UI/UX</li>
  <li>Beginner</li>
  <li>30 Days</li>
  </ul>
 <div class='row'>
	<div class='col-sm-3'>
	
	</div>
	<div class='col-sm-9'>
	<p><strong>Presented by Design Foundry</strong></p>
	</div></div>
	<br>
	<div class='row'>
	<div class='col-sm-7'>
	<a href='#' class='a-btn'> Continue Bootcamp </a>
	</div>
	<div class='col-sm-5' style='padding-left:0px'>
	<a href='#' class='a-btn' style='background:#E2EEFA;color:black'> Ask for help</a>
	</div>
	</div>
 </div>
</div>

<div class='col-sm-12'>
  <br>
  <div class="tutor-progress-bar-wrap ">
                        <div class="tutor-progress-bar bootcamp-par">
                            <div class="tutor-proess-filled bootcamp-filled" style="--tutor-progress-left: 50%;"></div>
                        </div>
        </div>
		<h6 style='margin-top:6px;'> 4 Activities completed </h6>
  </div>
  </div>
  
  </div>


<div class='col-sm-4'>
<div style="background:#f0b105;padding: 9px 12px;border-radius:6px">
<div class='col-sm-12'>
<img src="">
</div>
<div class='col-sm-12'>
<h6>You are 14/30<br> Days until your<br> card will be<br> charged <br>another month</h6>
</div>

<div class='col-sm-12'>
<a class='a-btn' href='#' style='background:#E2EEFA'> Cancel Bootcamp </a>
</div>


</div>
</div>


</div>




<!-- bootcamps part end--->

<br>



<h3><?php esc_html_e( 'Dashboard', 'edumall' ); ?></h3>

<div class="tutor-dashboard-content-inner">

	<?php
	$enrolled_course   = tutor_utils()->get_enrolled_courses_by_user();
	$completed_courses = tutor_utils()->get_completed_courses_ids_by_user();

	$enrolled_course_count  = $enrolled_course ? $enrolled_course->post_count : 0;
	$completed_course_count = count( $completed_courses );
	$active_course_count    = $enrolled_course_count - $completed_course_count;
	?>

	<div class="row dashboard-info-cards">
		<div class="col-md-4 col-sm-6 dashboard-info-card enrolled-courses">
			<a class="dashboard-info-card-box"
			   href="<?php echo esc_url( tutor_utils()->get_tutor_dashboard_page_permalink( 'enrolled-courses' ) ); ?>">
				<div class="dashboard-info-card-icon">
					<span class="edumi edumi-open-book"></span>
				</div>
				<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $enrolled_course_count ) ); ?></span>
					<span
						class="dashboard-info-card-heading"><?php esc_html_e( 'Enrolled Courses', 'edumall' ); ?></span>
				</div>
			</a>
		</div>

		<div class="col-md-4 col-sm-6 dashboard-info-card yellow-card active-courses">
			<a class="dashboard-info-card-box"
			   href="<?php echo esc_url( tutor_utils()->get_tutor_dashboard_page_permalink( 'enrolled-courses/active-courses' ) ); ?>">
				<div class="dashboard-info-card-icon">
					<span class="edumi edumi-streaming"></span>
				</div>
				<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $active_course_count ) ); ?></span>
					<span
						class="dashboard-info-card-heading"><?php esc_html_e( 'Active Courses', 'edumall' ); ?></span>
				</div>
			</a>
		</div>

		<div class="col-md-4 col-sm-6 dashboard-info-card green-card completed-courses">
			<a class="dashboard-info-card-box"
			   href="<?php echo esc_url( tutor_utils()->get_tutor_dashboard_page_permalink( 'enrolled-courses/completed-courses' ) ); ?>">
				<div class="dashboard-info-card-icon">
					<span class="edumi edumi-correct"></span>
				</div>
				<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $completed_course_count ) ); ?></span>
					<span
						class="dashboard-info-card-heading"><?php esc_html_e( 'Completed Courses', 'edumall' ); ?></span>
				</div>
			</a>
		</div>

		<?php if ( current_user_can( tutor()->instructor_role ) ) : ?>
			<?php
			$total_students = tutor_utils()->get_total_students_by_instructor( get_current_user_id() );
			$my_courses     = tutor_utils()->get_courses_by_instructor( get_current_user_id(), 'publish' );
			$earning_sum    = tutor_utils()->get_earning_sum();

			$my_course_count = count( $my_courses );
			?>
			<div class="col-md-4 col-sm-6 dashboard-info-card pink-card total-students">
				<div class="dashboard-info-card-box">
					<div class="dashboard-info-card-icon">
						<span class="edumi edumi-group"></span>
					</div>
					<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $total_students ) ); ?></span>
						<span
							class="dashboard-info-card-heading"><?php esc_html_e( 'Total Students', 'edumall' ); ?></span>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-sm-6 dashboard-info-card purple-card total-courses">
				<div class="dashboard-info-card-box">
					<div class="dashboard-info-card-icon">
						<span class="edumi edumi-user-support"></span>
					</div>
					<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $my_course_count ) ); ?></span>
						<span
							class="dashboard-info-card-heading"><?php esc_html_e( 'Total Courses', 'edumall' ); ?></span>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-sm-6 dashboard-info-card orange-card card-total-earnings">
				<div class="dashboard-info-card-box">
					<div class="dashboard-info-card-icon">
						<span class="edumi edumi-coin"></span>
					</div>
					<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo tutor_utils()->tutor_price( $earning_sum->instructor_amount ); ?></span>
						<span
							class="dashboard-info-card-heading"><?php esc_html_e( 'Total Earnings', 'edumall' ); ?></span>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
</div>