<div id="page-title-bar" <?php Edumall_Title_Bar::instance()->the_wrapper_class(); ?>>
	<div class="page-title-bar-inner">
		<div class="page-title-bar-bg"></div>

		<?php edumall_load_template( 'breadcrumb' ); ?>

		<div class="container">
			<div class="row row-xs-center">
				<div class="col-md-12">
					<?php Edumall_THA::instance()->title_bar_heading_before(); ?>

					<?php Edumall_Title_Bar::instance()->render_title(); ?>

					<?php Edumall_THA::instance()->title_bar_heading_after(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
