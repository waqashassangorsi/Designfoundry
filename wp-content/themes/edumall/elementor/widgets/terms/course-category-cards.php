<?php

namespace Edumall_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Course_Category_Cards extends Base {

	public function get_name() {
		return 'tm-course-category-cards';
	}

	public function get_title() {
		return esc_html__( 'Course Category Cards', 'edumall' );
	}

	public function get_icon_part() {
		return 'eicon-gallery-grid';
	}

	public function get_keywords() {
		return [ 'course', 'category' ];
	}

	public function get_script_depends() {
		return [ 'edumall-group-widget-grid' ];
	}

	public function get_taxonomy_name() {
		return \Edumall_Tutor::instance()->get_tax_category();
	}

	protected function _register_controls() {
		$this->add_layout_section();

		$this->add_grid_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'edumall' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => esc_html__( 'Style 01', 'edumall' ),
				'02' => esc_html__( 'Style 02', 'edumall' ),
			],
			'default' => '01',
		] );

		$this->add_control( 'show_description', [
			'label' => esc_html__( 'Show Description', 'edumall' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$taxonomy_name = $this->get_taxonomy_name();

		$categories = get_terms( [
			'taxonomy'   => $taxonomy_name,
			'parent'     => 0,
			'hide_empty' => 0,
		] );

		$options = [];
		foreach ( $categories as $category ) {
			$options[ $category->term_id ] = $category->name;
		}

		$repeater = new Repeater();

		$repeater->add_control( 'term_id', [
			'label'   => esc_html__( 'Category', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $options,
		] );

		$repeater->add_control( 'icon', [
			'label' => esc_html__( 'Icon', 'edumall' ),
			'type'  => Controls_Manager::ICONS,
		] );

		$this->add_control( 'categories', [
			'label'  => esc_html__( 'Select Categories', 'edumall' ),
			'type'   => Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'title_field' => "{{ EdumallElementor.helpers.getRepeaterSelectOptionText('tm-course-category-cards', 'categories', 'term_id', term_id) }}",
		] );

		$this->end_controls_section();
	}

	private function add_grid_section() {
		$this->start_controls_section( 'grid_options_section', [
			'label' => esc_html__( 'Grid Options', 'edumall' ),
		] );

		$this->add_responsive_control( 'grid_columns', [
			'label'          => esc_html__( 'Columns', 'edumall' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 12,
			'step'           => 1,
			'default'        => 4,
			'tablet_default' => 2,
			'mobile_default' => 1,
		] );

		$this->add_responsive_control( 'grid_gutter', [
			'label'   => esc_html__( 'Gutter', 'edumall' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 200,
			'step'    => 1,
			'default' => 30,
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['categories'] ) ) {
			return;
		}


		$new_cats    = [];
		$new_cat_ids = [];

		/**
		 * Valid terms.
		 * Skip invalid terms.
		 */
		foreach ( $settings['categories'] as $category ) {
			if ( isset( $category['term_id'] ) ) {
				$new_cats[ $category['term_id'] ] = $category;
				$new_cat_ids[]                    = intval( $category['term_id'] );
			}
		}

		if ( empty( $new_cat_ids ) ) {
			return;
		}

		$new_cat_ids = array_unique( $new_cat_ids );

		$new_terms = get_terms( [
			'taxonomy'   => $this->get_taxonomy_name(),
			'include'    => $new_cat_ids,
			'orderby'    => 'include',
			'hide_empty' => false,
		] );

		$this->add_render_attribute( 'grid-wrapper', 'class', 'edumall-grid-wrapper edumall-course-category-cards style-' . $settings['style'] );

		$this->add_render_attribute( 'content-wrapper', 'class', 'edumall-grid lazy-grid' );

		$grid_options = $this->get_grid_options();

		$this->add_render_attribute( 'grid-wrapper', 'data-grid', wp_json_encode( $grid_options ) );
		?>
		<div <?php $this->print_attributes_string( 'grid-wrapper' ); ?>>
			<div <?php $this->print_attributes_string( 'content-wrapper' ); ?>>
				<div class="grid-sizer"></div>
				<?php foreach ( $new_terms as $term ) : ?>
					<?php
					$item_key = "item_{$term->term_id}_";
					$box_key  = $item_key . 'box';

					$link = get_term_link( $term );

					$this->add_render_attribute( $box_key, [
						'class' => 'edumall-box',
						'href'  => $link,
					] );

					$term_settings = $new_cats[ $term->term_id ];

					$has_icon = ! empty( $term_settings['icon']['value'] ) ? true : false;
					?>
					<div class="grid-item">
						<a <?php $this->print_render_attribute_string( $box_key ); ?>>
							<?php if ( $has_icon ) : ?>
								<div class="category-icon">
									<?php Icons_Manager::render_icon( $term_settings['icon'] ); ?>
								</div>
							<?php endif; ?>
							<div class="category-info">
								<h6 class="category-name"><?php echo esc_html( $term->name ); ?></h6>

								<?php if ( ! empty( $settings['show_description'] ) ): ?>
									<div class="category-description">
										<?php echo esc_html( $term->description ); ?>
									</div>
								<?php endif; ?>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	protected function get_grid_options() {
		$settings = $this->get_settings_for_display();

		$grid_options = [
			'type' => 'grid',
		];

		// Columns.
		if ( ! empty( $settings['grid_columns'] ) ) {
			$grid_options['columns'] = $settings['grid_columns'];
		}

		if ( ! empty( $settings['grid_columns_tablet'] ) ) {
			$grid_options['columnsTablet'] = $settings['grid_columns_tablet'];
		}

		if ( ! empty( $settings['grid_columns_mobile'] ) ) {
			$grid_options['columnsMobile'] = $settings['grid_columns_mobile'];
		}

		// Gutter
		if ( ! empty( $settings['grid_gutter'] ) ) {
			$grid_options['gutter'] = $settings['grid_gutter'];
		}

		if ( ! empty( $settings['grid_gutter_tablet'] ) ) {
			$grid_options['gutterTablet'] = $settings['grid_gutter_tablet'];
		}

		if ( ! empty( $settings['grid_gutter_mobile'] ) ) {
			$grid_options['gutterMobile'] = $settings['grid_gutter_mobile'];
		}

		return $grid_options;
	}
}
