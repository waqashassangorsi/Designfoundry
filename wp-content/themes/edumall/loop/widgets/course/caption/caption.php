<div class="course-info">
	<?php if ( 'yes' === $settings['show_caption_instructor'] ): ?>

	<?php endif; ?>

	loop price

	<?php if ( 'yes' === $settings['show_caption_date'] ) : ?>
		<div class="course-date"><?php echo get_the_date(); ?></div>
	<?php endif; ?>

	<h3 class="course-title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h3>

	<?php if ( 'yes' === $settings['show_caption_excerpt'] ) : ?>
		<?php
		if ( empty( $settings['excerpt_length'] ) ) {
			$settings['excerpt_length'] = 18;
		}
		?>
		<div class="course-excerpt">
			<?php Edumall_Templates::excerpt( array(
				'limit' => $settings['excerpt_length'],
				'type'  => 'word',
			) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $settings['show_caption_meta'] ) ) : ?>
		<?php $meta = $settings['show_caption_meta']; ?>
		<div class="course-meta">
			<?php if ( in_array( 'lessons', $meta, true ) ): ?>

			<?php endif; ?>

			<?php if ( in_array( 'students', $meta, true ) ): ?>

			<?php endif; ?>

			<?php if ( in_array( 'duration', $meta, true ) ): ?>

			<?php endif; ?>

			<?php if ( in_array( 'category', $meta, true ) ): ?>

			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( 'yes' === $settings['show_caption_buttons'] ) : ?>
		<div class="course-buttons">

		</div>
	<?php endif; ?>
</div>
