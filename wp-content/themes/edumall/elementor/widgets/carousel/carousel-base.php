<?php

namespace Edumall_Elementor;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || exit;

abstract class Carousel_Base extends Base {

	const SLIDER_KEY = 'slider';

	public function get_script_depends() {
		return [ 'edumall-group-widget-carousel' ];
	}

	abstract protected function print_slides( array $settings );

	protected function _register_controls() {
		$this->add_swiper_options_section();

		$this->add_swiper_arrows_style_section();

		$this->add_swiper_dots_style_section();
	}

	private function add_swiper_options_section() {
		$this->start_controls_section( 'swiper_options_section', [
			'label' => esc_html__( 'Carousel Options', 'edumall' ),
		] );

		$this->add_responsive_control( 'swiper_items', [
			'label'          => esc_html__( 'Slides Per View', 'edumall' ),
			'type'           => Controls_Manager::SELECT,
			'options'        => array(
				'auto'       => esc_html__( 'Auto', 'edumall' ),
				'auto-fixed' => esc_html__( 'Auto - Fixed Width', 'edumall' ),
				'1'          => '1',
				'2'          => '2',
				'3'          => '3',
				'4'          => '4',
				'5'          => '5',
				'6'          => '6',
				'7'          => '7',
				'8'          => '8',
			),
			'default'        => '3',
			'tablet_default' => '2',
			'mobile_default' => '1',
		] );

		$this->add_responsive_control( 'swiper_slides_width', [
			'label'       => esc_html__( 'Slides Width', 'edumall' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px', '%' ],
			'range'       => [
				'px' => [
					'min'  => 100,
					'max'  => 1000,
					'step' => 1,
				],
				'%'  => [
					'min' => 10,
					'max' => 100,
				],
			],
			'selectors'   => [
				'{{WRAPPER}} .swiper-slide' => 'width: {{SIZE}}{{UNIT}} !important;',
			],
			'condition'   => [
				'swiper_items' => 'auto-fixed',
			],
			'device_args' => [
				Controls_Stack::RESPONSIVE_TABLET => [
					'condition' => [
						'swiper_items_tablet' => [ 'auto-fixed' ],
					],
				],
				Controls_Stack::RESPONSIVE_MOBILE => [
					'condition' => [
						'swiper_items_mobile' => [ 'auto-fixed' ],
					],
				],
			],
		] );

		$this->add_responsive_control( 'swiper_slides_max_width', [
			'label'       => esc_html__( 'Slides Max Width', 'edumall' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px', '%' ],
			'range'       => [
				'px' => [
					'min'  => 100,
					'max'  => 1000,
					'step' => 1,
				],
				'%'  => [
					'min' => 10,
					'max' => 100,
				],
			],
			'selectors'   => [
				'{{WRAPPER}} .swiper-slide' => 'max-width: {{SIZE}}{{UNIT}} !important;',
			],
			'condition'   => [
				'swiper_items' => 'auto-fixed',
			],
			'device_args' => [
				Controls_Stack::RESPONSIVE_TABLET => [
					'condition' => [
						'swiper_items_tablet' => [ 'auto-fixed' ],
					],
				],
				Controls_Stack::RESPONSIVE_MOBILE => [
					'condition' => [
						'swiper_items_mobile' => [ 'auto-fixed' ],
					],
				],
			],
		] );

		$this->add_responsive_control( 'swiper_gutter', [
			'label'   => esc_html__( 'Space Between', 'edumall' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 200,
			'step'    => 1,
			'default' => 30,
		] );

		$this->add_control( 'swiper_effect', [
			'label'   => esc_html__( 'Transition', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'slide' => esc_html__( 'Slide', 'edumall' ),
				'fade'  => esc_html__( 'Fade', 'edumall' ),
			),
			'default' => 'slide',
		] );

		$this->add_control( 'swiper_speed', [
			'label'   => esc_html__( 'Transition Duration', 'edumall' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 1000,
		] );

		$this->add_control( 'swiper_autoplay', [
			'label'       => esc_html__( 'Auto Play', 'edumall' ),
			'description' => esc_html__( 'Delay between transitions (in ms). For e.g: 3000. Leave blank to disabled. Input 1 to make smooth transition.', 'edumall' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => '',
		] );

		$this->add_control( 'swiper_loop', [
			'label'   => esc_html__( 'Infinite Loop', 'edumall' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'swiper_centered', [
			'label' => esc_html__( 'Centered', 'edumall' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'swiper_centered_highlight', [
			'label'     => esc_html__( 'Highlight Active Items', 'edumall' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [
				'swiper_centered' => 'yes',
			],
		] );

		$this->add_control( 'swiper_free_mode', [
			'label' => esc_html__( 'Free Mode', 'edumall' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'swiper_touch', [
			'label'       => esc_html__( 'Touchable', 'edumall' ),
			'description' => esc_html__( 'Click and drag to change slides', 'edumall' ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
		] );

		$this->add_control( 'swiper_mousewheel', [
			'label' => esc_html__( 'Mousewheel', 'edumall' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'swiper_navigation_heading', [
			'label'     => esc_html__( 'Navigation', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_swiper_arrows_popover();

		$this->add_swiper_dots_popover();

		$this->add_control( 'swiper_inner_heading', [
			'label'     => esc_html__( 'Slider Inner', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'swiper_inner_margin', [
			'label'      => esc_html__( 'Margin', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .swiper-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'swiper_container_heading', [
			'label'     => esc_html__( 'Slider Container', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'swiper_container_padding', [
			'label'      => esc_html__( 'Padding', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .swiper-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'swiper_content_alignment_heading', [
			'label'     => esc_html__( 'Content Alignment', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'swiper_content_horizontal_align', [
			'label'       => esc_html__( 'Horizontal Align', 'edumall' ),
			'label_block' => true,
			'type'        => Controls_Manager::CHOOSE,
			'options'     => Widget_Utils::get_control_options_horizontal_alignment(),
		] );

		$this->add_responsive_control( 'swiper_content_vertical_align', [
			'label'       => esc_html__( 'Vertical Align', 'edumall' ),
			'label_block' => true,
			'type'        => Controls_Manager::CHOOSE,
			'options'     => Widget_Utils::get_control_options_vertical_full_alignment(),
		] );

		$this->end_controls_section();
	}

	/**
	 * Register swiper arrows options in popover.
	 */
	private function add_swiper_arrows_popover() {
		$this->add_control( 'swiper_arrows_show', [
			'label'        => esc_html__( 'Arrows', 'edumall' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => esc_html__( 'Hide', 'edumall' ),
			'label_on'     => esc_html__( 'Show', 'edumall' ),
			'return_value' => 'yes',
		] );

		$this->start_popover();

		$this->add_control( 'swiper_arrows_style', [
			'label'   => esc_html__( 'Style', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => '01',
				'02' => '02',
			],
			'default' => '01',
		] );

		$this->add_control( 'swiper_arrows_aligned_by', [
			'label'       => esc_html__( 'Aligned By', 'edumall' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'slider' => esc_html__( 'Slider', 'edumall' ),
				'grid'   => esc_html__( 'Grid', 'edumall' ),
			],
			'default'     => 'slider',
			'render_type' => 'template',
		] );

		$this->add_responsive_control( 'swiper_arrows_horizontal_align', [
			'label'                => esc_html__( 'Horizontal Align', 'edumall' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment_full(),
			'default'              => 'stretch',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'    => 'flex-start',
				'right'   => 'flex-end',
				'stretch' => 'space-between',
			],
			'selectors'            => [
				'{{WRAPPER}} .swiper-nav-buttons' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_vertical_align', [
			'label'                => esc_html__( 'Vertical Align', 'edumall' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_vertical_alignment(),
			'default'              => 'center',
			'toggle'               => false,
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .swiper-nav-buttons' => 'align-items: {{VALUE}}',
			],
		] );

		$this->add_control( 'swiper_left_arrow_hr', [
			'type' => Controls_Manager::DIVIDER,
		] );

		$this->add_control( 'swiper_left_arrow_heading', [
			'label' => esc_html__( 'Left Arrow', 'edumall' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( 'swiper_left_arrow_margin', [
			'label'      => esc_html__( 'Offset', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .swiper-button-prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'swiper_right_arrow_hr', [
			'type' => Controls_Manager::DIVIDER,
		] );

		$this->add_control( 'swiper_right_arrow_heading', [
			'label' => esc_html__( 'Right Arrow', 'edumall' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( 'swiper_right_arrow_margin', [
			'label'      => esc_html__( 'Offset', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .swiper-button-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'swiper_arrows_visibility_hr', [
			'type' => Controls_Manager::DIVIDER,
		] );

		$this->add_control( 'swiper_arrows_visibility_heading', [
			'label' => esc_html__( 'Visibility', 'edumall' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_control( 'swiper_arrows_show_always', [
			'label' => esc_html__( 'Show Always', 'edumall' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->end_popover();
	}

	/**
	 * Register swiper bullets options in popover.
	 */
	private function add_swiper_dots_popover() {
		$this->add_control( 'swiper_dots_show', [
			'label'        => esc_html__( 'Dots', 'edumall' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => esc_html__( 'Hide', 'edumall' ),
			'label_on'     => esc_html__( 'Show', 'edumall' ),
			'return_value' => 'yes',
		] );

		$this->start_popover();

		$this->add_control( 'swiper_dots_style', [
			'label'   => esc_html__( 'Style', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => esc_html__( '01 - Circle Bullets', 'edumall' ),
				'02' => esc_html__( '02 - Rectangle Bullets', 'edumall' ),
				'03' => esc_html__( '03 - Fraction', 'edumall' ),
				'04' => esc_html__( '04 - Circle Fraction & Arrows', 'edumall' ),
				'05' => esc_html__( '05 - Modern Circle Bullets', 'edumall' ),
				'06' => esc_html__( '06 - Modern Circle Bullets 02', 'edumall' ),
				'07' => esc_html__( '07 - Fraction 02', 'edumall' ),
			],
			'default' => '01',
		] );

		$this->add_control( 'swiper_dots_aligned_by', [
			'label'       => esc_html__( 'Aligned By', 'edumall' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'slider' => esc_html__( 'Slider', 'edumall' ),
				'grid'   => esc_html__( 'Grid', 'edumall' ),
			],
			'default'     => 'slider',
			'render_type' => 'template',
		] );

		$this->add_responsive_control( 'swiper_dots_direction', [
			'label'   => esc_html__( 'Direction', 'edumall' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'horizontal' => [
					'title' => esc_html__( 'Horizontal', 'edumall' ),
					'icon'  => 'eicon-navigation-horizontal',
				],
				'vertical'   => [
					'title' => esc_html__( 'Vertical', 'edumall' ),
					'icon'  => 'eicon-navigation-vertical',
				],
			],
			'default' => 'horizontal',
			'toggle'  => false,
		] );

		$this->add_responsive_control( 'swiper_dots_horizontal_align', [
			'label'                => esc_html__( 'Horizontal Align', 'edumall' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'              => 'center',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .swiper-pagination-wrap' => 'justify-content: {{VALUE}}',
			],
			'render_type'          => 'template',
		] );

		$this->add_responsive_control( 'swiper_dots_vertical_align', [
			'label'                => esc_html__( 'Vertical Align', 'edumall' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => [
				'top'    => [
					'title' => esc_html__( 'Top', 'edumall' ),
					'icon'  => 'eicon-v-align-top',
				],
				'middle' => [
					'title' => esc_html__( 'Middle', 'edumall' ),
					'icon'  => 'eicon-v-align-middle',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'edumall' ),
					'icon'  => 'eicon-v-align-bottom',
				],
				'below'  => [
					'title' => esc_html__( 'Below Slider', 'edumall' ),
					'icon'  => 'eicon-thumbnails-down',
				],
			],
			'default'              => 'below',
			'toggle'               => false,
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
				'below'  => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .swiper-pagination-wrap' => 'align-items: {{VALUE}}',
			],
			'prefix_class'         => 'bullets%s-v-align-',
			'render_type'          => 'template',
		] );

		$this->add_responsive_control( 'swiper_dots_vertical_offset', [
			'label'       => esc_html__( 'Vertical Offset', 'edumall' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px', '%' ],
			'range'       => [
				'px' => [
					'min'  => -1000,
					'max'  => 1000,
					'step' => 1,
				],
				'%'  => [
					'min' => -100,
					'max' => 100,
				],
			],
			'selectors'   => [
				'{{WRAPPER}}.bullets-v-align-below .swiper-pagination-wrap'                                                                                   => 'margin-top: {{SIZE}}{{UNIT}}',
				'(desktop){{WRAPPER}}.bullets-v-align-top .swiper-pagination-inner, {{WRAPPER}}.bullets-v-align-middle .swiper-pagination-inner'              => 'margin-top: {{SIZE}}{{UNIT}}',
				'(desktop){{WRAPPER}}.bullets-v-align-bottom .swiper-pagination-inner'                                                                        => 'margin-bottom: {{SIZE}}{{UNIT}}',
				'(tablet){{WRAPPER}}.bullets-tablet-v-align-top .swiper-pagination-inner, {{WRAPPER}}.bullets-tablet-v-align-middle .swiper-pagination-inner' => 'margin-bottom: 0 !important; margin-top: {{SIZE}}{{UNIT}} !important',
				'(tablet){{WRAPPER}}.bullets-tablet-v-align-bottom .swiper-pagination-inner'                                                                  => 'margin-top: 0 !important; margin-bottom: {{SIZE}}{{UNIT}} !important',
				'(mobile){{WRAPPER}}.bullets-mobile-v-align-top .swiper-pagination-inner, {{WRAPPER}}.bullets-mobile-v-align-middle .swiper-pagination-inner' => 'margin-bottom: 0 !important; margin-top: {{SIZE}}{{UNIT}} !important',
				'(mobile){{WRAPPER}}.bullets-mobile-v-align-bottom .swiper-pagination-inner'                                                                  => 'margin-top: 0 !important; margin-bottom: {{SIZE}}{{UNIT}} !important',
			],
			'render_type' => 'template',
		] );

		$this->add_responsive_control( 'swiper_dots_horizontal_offset', [
			'label'       => esc_html__( 'Horizontal Offset', 'edumall' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px', '%' ],
			'range'       => [
				'px' => [
					'min'  => -1000,
					'max'  => 1000,
					'step' => 1,
				],
				'%'  => [
					'min' => -100,
					'max' => 100,
				],
			],
			'selectors'   => [
				'{{WRAPPER}} .bullets-h-align-left .swiper-pagination-inner'   => 'margin-left: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .bullets-h-align-center .swiper-pagination-inner' => 'margin-left: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .bullets-h-align-right .swiper-pagination-inner'  => 'margin-right: {{SIZE}}{{UNIT}}',
			],
			'render_type' => 'template',
		] );

		$this->end_popover();
	}

	private function add_swiper_arrows_style_section() {
		$this->start_controls_section( 'swiper_arrows_style_section', [
			'label'     => esc_html__( 'Carousel Arrows', 'edumall' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'swiper_arrows_show' => 'yes',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_size', [
			'label'      => esc_html__( 'Size', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .swiper-nav-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_icon_size', [
			'label'      => esc_html__( 'Icon Size', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 8,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .swiper-nav-button' => 'font-size: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->start_controls_tabs( 'swiper_arrows_style_tabs' );

		$this->start_controls_tab( 'swiper_arrows_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_control( 'swiper_arrows_text_color', [
			'label'     => esc_html__( 'Text Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-nav-button' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_background_color', [
			'label'     => esc_html__( 'Background Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-nav-button' => 'background: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_border_color', [
			'label'     => esc_html__( 'Border Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-nav-button' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'swiper_arrows_box_shadow',
			'selector' => '{{WRAPPER}} .swiper-nav-button',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'swiper_arrows_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_control( 'swiper_arrows_hover_text_color', [
			'label'     => esc_html__( 'Text Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-nav-button:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_hover_background_color', [
			'label'     => esc_html__( 'Background Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-nav-button:hover' => 'background: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_hover_border_color', [
			'label'     => esc_html__( 'Border Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-nav-button:hover' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'swiper_arrows_hover_box_shadow',
			'selector' => '{{WRAPPER}} .swiper-nav-button:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'swiper_arrows_border_width', [
			'label'     => esc_html__( 'Border Width', 'edumall' ),
			'type'      => Controls_Manager::SLIDER,
			'selectors' => [
				'{{WRAPPER}} .swiper-nav-button' => 'border-width: {{SIZE}}{{UNIT}}',
			],
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'swiper_arrows_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .swiper-nav-button' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->end_controls_section();
	}

	private function add_swiper_dots_style_section() {
		$this->start_controls_section( 'swiper_dots_style_section', [
			'label'     => esc_html__( 'Carousel Dots', 'edumall' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'swiper_dots_show' => 'yes',
			],
		] );

		$this->add_control( 'swiper_dots_primary_color', [
			'label'     => esc_html__( 'Primary Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-pagination-bullet'                 => 'color: {{VALUE}};',
				'{{WRAPPER}} .swiper-pagination .fraction'              => 'color: {{VALUE}};',
				'{{WRAPPER}} .pagination-style-04 .fraction'            => 'color: {{VALUE}};',
				'{{WRAPPER}} .pagination-style-04 .progressbar .filled' => 'background: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_dots_secondary_color', [
			'label'     => esc_html__( 'Secondary Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-pagination-bullet:hover'                           => 'color: {{VALUE}};',
				'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'color: {{VALUE}};',
				'{{WRAPPER}} .swiper-pagination .fraction .current'                     => 'color: {{VALUE}};',
				'{{WRAPPER}} .pagination-style-04 .progressbar'                         => 'background: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function before_slider() {
	}

	protected function after_slider() {
	}

	protected function before_slider_container() {
	}

	protected function after_slider_container() {
	}

	protected function update_slider_settings( $settings, $slider_settings ) {
		return $slider_settings;
	}

	protected function get_slider_settings( array $settings ) {
		$slider_settings = [
			'class'          => [ 'tm-swiper tm-slider-widget' ],
			'data-lg-items'  => $settings['swiper_items'],
			'data-md-items'  => $settings['swiper_items_tablet'],
			'data-sm-items'  => $settings['swiper_items_mobile'],
			'data-lg-gutter' => $settings['swiper_gutter'],
			'data-md-gutter' => $settings['swiper_gutter_tablet'],
			'data-sm-gutter' => $settings['swiper_gutter_mobile'],
		];

		if ( ! empty( $settings['swiper_content_vertical_align'] ) ) {
			$slider_settings['class'][] = 'v-' . $settings['swiper_content_vertical_align'];
		}

		if ( ! empty( $settings['swiper_content_horizontal_align'] ) ) {
			$slider_settings['class'][] = 'h-' . $settings['swiper_content_horizontal_align'];
		}

		if ( ! empty( $settings['swiper_arrows_show'] ) ) {
			$slider_settings['data-nav']            = '1';
			$slider_settings['data-nav-aligned-by'] = $settings['swiper_arrows_aligned_by'];
			$slider_settings['class'][]             = 'nav-style-' . $settings['swiper_arrows_style'];

			if ( 'yes' === $settings['swiper_arrows_show_always'] ) {
				$slider_settings['class'][] = 'nav-show-always';
			}
		}

		if ( ! empty( $settings['swiper_dots_show'] ) ) {
			$slider_settings['class'][]                    = 'pagination-style-' . $settings['swiper_dots_style'];
			$slider_settings['data-pagination-aligned-by'] = $settings['swiper_dots_aligned_by'];
			$slider_settings['class'][]                    = 'bullets-' . $settings['swiper_dots_direction'];
			$slider_settings['class'][]                    = 'bullets-h-align-' . $settings['swiper_dots_horizontal_align'];
			$slider_settings['class'][]                    = 'bullets-v-align-' . $settings['swiper_dots_vertical_align'];

			$slider_settings['data-pagination'] = '1';

			if ( in_array( $settings['swiper_dots_style'], array( '03', '04', '07' ) ) ) {
				$slider_settings['data-pagination-type'] = 'custom';
			}
		}

		if ( ! empty( $settings['swiper_loop'] ) && 'yes' === $settings['swiper_loop'] ) {
			$slider_settings['data-loop'] = '1';
		}

		if ( ! empty( $settings['swiper_centered'] ) && 'yes' === $settings['swiper_centered'] ) {
			$slider_settings['data-centered'] = '1';

			if ( ! empty( $settings['swiper_centered_highlight'] ) && 'yes' === $settings['swiper_centered_highlight'] ) {
				$slider_settings['class'][] = 'highlight-centered-items';
			}
		}

		if ( ! empty( $settings['swiper_free_mode'] ) && 'yes' === $settings['swiper_free_mode'] ) {
			$slider_settings['data-free-mode'] = '1';
		}

		if ( ! empty( $settings['swiper_mousewheel'] ) && 'yes' === $settings['swiper_mousewheel'] ) {
			$slider_settings['data-mousewheel'] = '1';
		}

		if ( ! empty( $settings['swiper_touch'] ) && 'yes' === $settings['swiper_touch'] ) {
			$slider_settings['data-simulate-touch'] = '1';
		}

		if ( ! empty( $settings['swiper_speed'] ) ) {
			$slider_settings['data-speed'] = $settings['swiper_speed'];
		}

		if ( ! empty( $settings['swiper_autoplay'] ) ) {
			$slider_settings['data-autoplay'] = $settings['swiper_autoplay'];
		}

		if ( ! empty( $settings['swiper_effect'] ) ) {
			$slider_settings['data-effect'] = $settings['swiper_effect'];
		}

		$slider_settings = $this->update_slider_settings( $settings, $slider_settings );

		return $slider_settings;
	}

	protected function print_slider( array $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_active_settings();
		}

		$slider_settings = $this->get_slider_settings( $settings );

		$this->add_render_attribute( self::SLIDER_KEY, $slider_settings );
		?>

		<div <?php $this->print_attributes_string( 'slider' ); ?>>
			<div class="swiper-inner">

				<?php $this->before_slider_container(); ?>

				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php $this->print_slides( $settings ); ?>
					</div>
				</div>

				<?php $this->after_slider_container(); ?>

			</div>
		</div>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->before_slider();

		$this->print_slider( $settings );

		$this->after_slider();
	}
}
