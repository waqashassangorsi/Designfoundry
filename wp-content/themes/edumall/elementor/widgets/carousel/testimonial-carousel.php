<?php

namespace Edumall_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || exit;

class Widget_Testimonial_Carousel extends Static_Carousel {

	private $slider_looped_slides = 4;

	public function get_name() {
		return 'tm-testimonial';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Carousel', 'edumall' );
	}

	public function get_icon_part() {
		return 'eicon-testimonial-carousel';
	}

	public function get_keywords() {
		return [ 'testimonial', 'carousel' ];
	}

	protected function _register_controls() {
		$this->add_layout_section();

		$this->add_box_style_section();

		$this->add_content_style_section();

		$this->add_image_style_section();

		parent::_register_controls();

		$this->update_controls();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'default'        => '1',
			'tablet_default' => '1',
			'mobile_default' => '1',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 30,
		] );

		$this->update_control( 'slides', [
			'title_field' => '{{{ name }}}',
		] );
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'edumall' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'edumall' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => '',
			'options'      => [
				''   => esc_html__( 'None', 'edumall' ),
				'01' => esc_html__( '01', 'edumall' ),
				'02' => esc_html__( '02', 'edumall' ),
			],
			'render_type'  => 'template',
			'prefix_class' => 'edumall-testimonial-style-',
		] );

		$this->add_control( 'layout', [
			'label'        => esc_html__( 'Layout', 'edumall' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => 'image-stacked',
			'options'      => [
				'image-inline'  => esc_html__( 'Image Inline', 'edumall' ),
				'image-stacked' => esc_html__( 'Image Stacked', 'edumall' ),
				'image-top'     => esc_html__( 'Image Top Overlap', 'edumall' ),
				'image-top-02'  => esc_html__( 'Image Top', 'edumall' ),
				'image-above'   => esc_html__( 'Image Above', 'edumall' ),
				'image-left'    => esc_html__( 'Image Left', 'edumall' ),
			],
			'render_type'  => 'template',
			'prefix_class' => 'layout-',
		] );

		$this->add_control( 'image_position', [
			'label'        => esc_html__( 'Info Position', 'edumall' ),
			'type'         => Controls_Manager::CHOOSE,
			'label_block'  => false,
			'default'      => 'below',
			'options'      => [
				'above'  => [
					'title' => esc_html__( 'Above', 'edumall' ),
					'icon'  => 'eicon-v-align-top',
				],
				'below'  => [
					'title' => esc_html__( 'Below', 'edumall' ),
					'icon'  => 'eicon-v-align-bottom',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'edumall' ),
					'icon'  => 'eicon-v-align-stretch',
				],
			],
			'render_type'  => 'template',
			'prefix_class' => 'image-position-',
			'condition'    => [
				'layout' => [
					'image-inline',
					'image-stacked',
				],
			],
		] );

		$this->add_control( 'cite_layout', [
			'label'        => esc_html__( 'Cite Layout', 'edumall' ),
			'label_block'  => false,
			'type'         => Controls_Manager::CHOOSE,
			'default'      => 'block',
			'options'      => [
				'block'  => [
					'title' => esc_html__( 'Default', 'edumall' ),
					'icon'  => 'eicon-editor-list-ul',
				],
				'inline' => [
					'title' => esc_html__( 'Inline', 'edumall' ),
					'icon'  => 'eicon-ellipsis-h',
				],
			],
			'prefix_class' => 'edumall-testimonial-cite-layout-',
		] );

		$this->end_controls_section();
	}

	private function add_box_style_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'edumall' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'box_alignment', [
			'label'                => esc_html__( 'Alignment', 'edumall' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'selectors'            => [
				'{{WRAPPER}} .swiper-slide' => 'text-align: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'box_max_width', [
			'label'      => esc_html__( 'Max Width', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .testimonial-item' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .testimonial-item',
		] );

		$this->end_controls_section();
	}

	private function add_content_style_section() {
		$this->start_controls_section( 'content_style_section', [
			'label' => esc_html__( 'Content', 'edumall' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'content_max_width', [
			'label'      => esc_html__( 'Max Width', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .content-wrap' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'content_alignment', [
			'label'                => esc_html__( 'Alignment', 'edumall' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .testimonial-main-content' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_control( 'content_text_align', [
			'label'                => esc_html__( 'Text Align', 'edumall' ),
			'label_block'          => false,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'prefix_class'         => 'align-',
			//'render_type'  => 'template',
			'selectors'            => [
				'{{WRAPPER}} .content-wrap' => 'text-align: {{VALUE}};',
			],
		] );

		$quote_icon_condition = [
			'style' => [ '01' ],
		];

		$this->add_control( 'quote_icon_heading', [
			'label'     => esc_html__( 'Quote Icon', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $quote_icon_condition,
		] );

		$this->add_responsive_control( 'quote_icon_size', [
			'label'     => esc_html__( 'Size', 'edumall' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .testimonial-quote-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
			'condition' => $quote_icon_condition,
		] );

		$this->add_control( 'quote_icon_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .testimonial-quote-icon' => 'color: {{VALUE}};',
			],
			'condition' => $quote_icon_condition,
		] );

		$this->add_control( 'title_heading', [
			'label'     => esc_html__( 'Title', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'edumall' ),
			'selector' => '{{WRAPPER}} .title',
		] );

		$this->add_responsive_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'text_heading', [
			'label'     => esc_html__( 'Text', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'text_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .text' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'text_typography',
			'label'    => esc_html__( 'Typography', 'edumall' ),
			'selector' => '{{WRAPPER}} .text',
		] );

		$this->add_responsive_control( 'text_margin', [
			'label'      => esc_html__( 'Margin', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'text_padding', [
			'label'      => esc_html__( 'Padding', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'name_heading', [
			'label'     => esc_html__( 'Name', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'name_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .name' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'name_typography',
			'label'    => esc_html__( 'Typography', 'edumall' ),
			'selector' => '{{WRAPPER}} .name',
		] );

		$this->add_control( 'position_heading', [
			'label'     => esc_html__( 'Position', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'position_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .position' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'position_typography',
			'label'    => esc_html__( 'Typography', 'edumall' ),
			'selector' => '{{WRAPPER}} .position',
		] );

		$this->end_controls_section();
	}

	private function add_image_style_section() {
		$this->start_controls_section( 'image_style_section', [
			'label' => esc_html__( 'Image', 'edumall' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_spacing', [
			'label'     => esc_html__( 'Spacing', 'edumall' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 500,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .info' => 'padding-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'image_size', [
				'label'     => esc_html__( 'Size', 'edumall' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 30,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'edumall' ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXT,
		] );

		$repeater->add_control( 'content', [
			'label' => esc_html__( 'Content', 'edumall' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$repeater->add_control( 'image', [
			'label' => esc_html__( 'Avatar', 'edumall' ),
			'type'  => Controls_Manager::MEDIA,
		] );

		$repeater->add_control( 'name', [
			'label'   => esc_html__( 'Name', 'edumall' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'John Doe', 'edumall' ),
		] );

		$repeater->add_control( 'position', [
			'label'   => esc_html__( 'Position', 'edumall' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'CEO', 'edumall' ),
		] );

		$repeater->add_control( 'rating', [
			'label' => esc_html__( 'Rating', 'edumall' ),
			'type'  => Controls_Manager::NUMBER,
			'min'   => 0,
			'max'   => 5,
			'step'  => 0.1,
		] );
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'content'  => esc_html__( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'edumall' ),
				'name'     => esc_html__( 'Frankie Kao', 'edumall' ),
				'position' => esc_html__( 'Web Design', 'edumall' ),
				'image'    => [ 'url' => $placeholder_image_src ],
			],
			[
				'content'  => esc_html__( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'edumall' ),
				'name'     => esc_html__( 'Frankie Kao', 'edumall' ),
				'position' => esc_html__( 'Web Design', 'edumall' ),
				'image'    => [ 'url' => $placeholder_image_src ],
			],
			[
				'content'  => esc_html__( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'edumall' ),
				'name'     => esc_html__( 'Frankie Kao', 'edumall' ),
				'position' => esc_html__( 'Web Design', 'edumall' ),
				'image'    => [ 'url' => $placeholder_image_src ],
			],
		];
	}

	protected function update_slider_settings( $settings, $slider_settings ) {
		if ( 'image-above' === $settings['layout'] ) {
			$slider_settings['class'][]            = 'edumall-main-swiper';
			$slider_settings['data-looped-slides'] = $this->slider_looped_slides;
		}

		return $slider_settings;
	}

	private function print_testimonial_cite() {
		$slide = $this->get_current_slide();

		if ( empty( $slide['name'] ) && empty( $slide['position'] ) ) {
			return;
		}

		$html = '<div class="cite">';

		if ( ! empty( $slide['name'] ) ) {
			$html .= '<h6 class="name">' . $slide['name'] . '</h6>';
		}
		if ( ! empty( $slide['position'] ) ) {
			$html .= '<span class="position">' . $slide['position'] . '</span>';
		}
		$html .= '</div>';

		echo '' . $html;
	}

	private function print_testimonial_avatar() {
		$slide = $this->get_current_slide();

		if ( empty( $slide['image']['url'] ) ) {
			return;
		}
		?>
		<div class="image">
			<?php echo \Edumall_Image::get_elementor_attachment( [
				'settings'       => $slide,
				'image_size_key' => 'image_size',
			] ); ?>
		</div>
		<?php
	}

	private function print_testimonial_info() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="info">
			<?php if ( ! in_array( $settings['layout'], [ 'image-top', 'image-top-02', 'image-left' ], true ) ) : ?>
				<?php $this->print_testimonial_avatar(); ?>
			<?php endif; ?>

			<?php $this->print_testimonial_cite(); ?>
		</div>
		<?php
	}

	private function print_testimonial_main_content() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="testimonial-main-content">
			<div class="content-wrap">
				<?php if ( 'image-above' === $settings['layout'] ) : ?>
					<?php $this->print_layout_image_above(); ?>
				<?php else: ?>
					<?php $this->print_layout(); ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	protected function print_slide() {
		$settings = $this->get_settings_for_display();
		$item_key = $this->get_current_key();
		$this->add_render_attribute( $item_key . '-testimonial', [
			'class' => 'testimonial-item',
		] );
		?>
		<div <?php $this->print_attributes_string( $item_key . '-testimonial' ); ?>>

			<?php if ( in_array( $settings['layout'], [ 'image-top', 'image-left' ], true ) ) : ?>
				<?php $this->print_testimonial_avatar(); ?>
			<?php endif; ?>

			<?php if ( in_array( $settings['style'], [ '01' ], true ) ) : ?>
				<div class="testimonial-quote-icon">
					<?php echo \Edumall_Helper::get_file_contents( EDUMALL_THEME_DIR . '/assets/images/testimonial-icon-02.svg' ); ?>
				</div>
			<?php endif; ?>

			<?php $this->print_testimonial_main_content(); ?>
		</div>
		<?php
	}

	private function print_layout_image_above() {
		$slide = $this->get_current_slide();
		?>
		<?php if ( $slide['content'] ) : ?>
			<div class="content">
				<div class="text">
					<?php echo wp_kses( $slide['content'], 'edumall-default' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php $this->print_testimonial_cite(); ?>

		<?php
	}

	private function print_layout() {
		$slide    = $this->get_current_slide();
		$settings = $this->get_settings_for_display();
		?>
		<?php if ( 'image-top-02' === $settings['layout'] ) : ?>
			<?php $this->print_testimonial_avatar(); ?>
		<?php endif; ?>

		<?php if ( 'above' === $settings['image_position'] ) : ?>
			<?php $this->print_testimonial_info(); ?>
		<?php endif; ?>

		<?php if ( $slide['content'] ) : ?>
			<div class="content">
				<?php if ( ! empty( $slide['title'] ) ): ?>
					<h4 class="title"><?php echo esc_html( $slide['title'] ); ?></h4>
				<?php endif; ?>

				<?php if ( ! empty( $slide['rating'] ) ): ?>
					<?php echo \Edumall_Templates::render_rating( $slide['rating'], [ 'wrapper_class' => 'testimonial-rating' ] ); ?>
				<?php endif; ?>

				<div class="text">
					<?php echo wp_kses( $slide['content'], 'edumall-default' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( in_array( $settings['image_position'], array(
				'below',
				'bottom',
			), true ) || in_array( $settings['layout'], array(
				'image-top',
				'image-top-02',
				'image-left',
			), true ) ) : ?>
			<?php $this->print_testimonial_info(); ?>
		<?php endif; ?>

		<?php
	}

	/**
	 * Print Avatar Thumbs Slider
	 */
	protected function before_slider() {
		$settings = $this->get_active_settings();

		if ( 'image-above' !== $settings['layout'] ) {
			return;
		}

		$this->add_render_attribute( '_wrapper', 'class', 'edumall-swiper-linked-yes' );

		$testimonial_thumbs_template = '';

		foreach ( $settings['slides'] as $slide ) :
			if ( $slide['image']['url'] ) :
				$testimonial_thumbs_template .= '<div class="swiper-slide"><div class="post-thumbnail"><div class="image">' . \Edumall_Image::get_elementor_attachment( [
						'settings'       => $slide,
						'image_size_key' => 'image_size',
					] ) . '</div></div></div>';
			endif;
		endforeach;

		?>
		<div class="tm-swiper tm-slider-widget edumall-testimonial-pagination style-01 edumall-thumbs-swiper"
		     data-lg-items="3"
		     data-lg-gutter="30"
		     data-slide-to-clicked-slide="1"
		     data-centered="1"
		     data-loop="1"
		     data-looped-slides="<?php echo esc_attr( $this->slider_looped_slides ); ?>"
		>
			<div class="swiper-inner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php echo '' . $testimonial_thumbs_template; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
