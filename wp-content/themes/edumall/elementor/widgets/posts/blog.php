<?php

namespace Edumall_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Base\Document;
use Elementor\Plugin;
use ElementorPro\Modules\QueryControl\Module as QueryControlModule;

defined( 'ABSPATH' ) || exit;

class Widget_Blog extends Posts_Base {

	public function get_name() {
		return 'tm-blog';
	}

	public function get_title() {
		return esc_html__( 'Blog', 'edumall' );
	}

	public function get_icon_part() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return [ 'blog', 'post' ];
	}

	protected function get_post_type() {
		return 'post';
	}

	protected function get_post_category() {
		return 'category';
	}

	public function get_script_depends() {
		return [
			'edumall-grid-query',
			'edumall-widget-grid-post',
		];
	}

	public function is_reload_preview_required() {
		return false;
	}

	protected function _register_controls() {
		$this->add_layout_section();

		$this->add_banners_section();

		$this->add_grid_section();

		$this->add_pagination_section();

		$this->add_box_style_section();

		$this->add_thumbnail_style_section();

		$this->add_caption_style_section();

		$this->add_overlay_style_section();

		$this->add_pagination_style_section();

		parent::_register_controls();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'edumall' ),
		] );

		$this->add_control( 'layout', [
			'label'   => esc_html__( 'Layout', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'grid'              => esc_html__( 'Grid', 'edumall' ),
				'masonry'           => esc_html__( 'Masonry', 'edumall' ),
				'metro'             => esc_html__( 'Metro', 'edumall' ),
				'zigzag'            => esc_html__( 'Zigzag', 'edumall' ),
				'list-01'           => esc_html__( 'List - Large Image', 'edumall' ),
				'list-02'           => esc_html__( 'List - Left Image', 'edumall' ),
				'list-03'           => esc_html__( 'List - Simple', 'edumall' ),
				'one-left-featured' => esc_html__( '1 Left Featured', 'edumall' ),
			],
			'default' => 'grid',
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'edumall' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'edumall' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'edumall' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'edumall' ),
			],
			'default'      => '',
			'prefix_class' => 'edumall-animation-',
		] );

		$this->add_control( 'show_thumbnail', [
			'label'        => esc_html__( 'Show Thumbnail', 'edumall' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
			'condition'    => [
				'layout' => 'list-03',
			],
		] );

		$this->add_caption_popover();

		$this->add_overlay_popover();

		$this->add_control( 'metro_image_size_width', [
			'label'     => esc_html__( 'Image Size', 'edumall' ),
			'type'      => Controls_Manager::NUMBER,
			'step'      => 1,
			'default'   => 480,
			'condition' => [
				'layout' => 'metro',
			],
		] );

		$this->add_control( 'metro_image_ratio', [
			'label'     => esc_html__( 'Image Ratio', 'edumall' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 2,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'default'   => [
				'size' => 1,
			],
			'condition' => [
				'layout' => 'metro',
			],
		] );

		$this->add_control( 'thumbnail_default_size', [
			'label'        => esc_html__( 'Use Default Thumbnail Size', 'edumall' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
			'separator'    => 'before',
			'condition'    => [
				'layout!' => 'metro',
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'thumbnail',
			'default'   => 'full',
			'condition' => [
				'thumbnail_default_size!' => '1',
			],
		] );

		$this->end_controls_section();
	}

	private function add_banners_section() {
		$this->start_controls_section( 'banners_section', [
			'label'     => esc_html__( 'Banners', 'edumall' ),
			'condition' => [
				'layout' => [ 'masonry' ],
			],
		] );

		$banner_repeater = new Repeater();

		$document_types = Plugin::$instance->documents->get_document_types( [
			'show_in_library' => true,
		] );

		$banner_repeater->add_control( 'template_id', [
			'label'        => esc_html__( 'Choose Template', 'edumall' ),
			'type'         => QueryControlModule::QUERY_CONTROL_ID,
			'label_block'  => true,
			'autocomplete' => [
				'object' => QueryControlModule::QUERY_OBJECT_LIBRARY_TEMPLATE,
				'query'  => [
					'meta_query' => [
						[
							'key'     => Document::TYPE_META_KEY,
							'value'   => array_keys( $document_types ),
							'compare' => 'IN',
						],
					],
				],
			],
		] );

		$banner_repeater->add_control( 'position', [
			'label' => esc_html__( 'After Post Number', 'edumall' ),
			'type'  => Controls_Manager::NUMBER,
			'step'  => 1,
		] );

		$this->add_control( 'banners', [
			'label'         => esc_html__( 'Banners', 'edumall' ),
			'type'          => Controls_Manager::REPEATER,
			'fields'        => $banner_repeater->get_controls(),
			'prevent_empty' => false,
		] );

		$this->end_controls_section();
	}

	private function add_caption_popover() {
		$this->add_control( 'show_caption', [
			'label'        => esc_html__( 'Caption', 'edumall' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => esc_html__( 'Default', 'edumall' ),
			'label_on'     => esc_html__( 'Custom', 'edumall' ),
			'return_value' => 'yes',
			'condition'    => [
				'layout!' => [ 'zigzag', 'one-left-featured' ],
			],
		] );

		$this->start_popover();

		$this->add_control( 'caption_style', [
			'label'   => esc_html__( 'Style', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => '01',
				'02' => '02',
				'03' => '03',
			],
			'default' => '01',
		] );

		$this->add_control( 'show_caption_category', [
			'label'     => esc_html__( 'Category', 'edumall' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'edumall' ),
			'label_off' => esc_html__( 'Hide', 'edumall' ),
			'default'   => 'yes',
			'separator' => 'before',
		] );

		$this->add_control( 'show_caption_excerpt', [
			'label'     => esc_html__( 'Excerpt', 'edumall' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'edumall' ),
			'label_off' => esc_html__( 'Hide', 'edumall' ),
			'default'   => 'yes',
			'separator' => 'before',
		] );

		$this->add_control( 'excerpt_length', [
			'label'     => esc_html__( 'Excerpt Length', 'edumall' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 5,
			'condition' => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		$this->add_control( 'show_caption_read_more', [
			'label'     => esc_html__( 'Read More', 'edumall' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'edumall' ),
			'label_off' => esc_html__( 'Hide', 'edumall' ),
			'default'   => 'yes',
			'separator' => 'before',
		] );

		$this->add_control( 'read_more_text', [
			'label'     => esc_html__( 'Read More Text', 'edumall' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Read More', 'edumall' ),
			'condition' => [
				'show_caption_read_more' => 'yes',
			],
		] );

		$this->add_control( 'show_caption_share', [
			'label'     => esc_html__( 'Share', 'edumall' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'edumall' ),
			'label_off' => esc_html__( 'Hide', 'edumall' ),
		] );

		$this->add_control( 'show_caption_meta', [
			'label'       => esc_html__( 'Meta', 'edumall' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'default'     => [
				'date',
				'views',
			],
			'options'     => [
				'author'   => esc_html__( 'Author', 'edumall' ),
				'date'     => esc_html__( 'Date', 'edumall' ),
				'views'    => esc_html__( 'View Count', 'edumall' ),
				'comments' => esc_html__( 'Comment Count', 'edumall' ),
			],
		] );

		$this->end_popover();
	}

	private function add_overlay_popover() {
		$this->add_control( 'show_overlay', [
			'label'        => esc_html__( 'Overlay', 'edumall' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => esc_html__( 'Default', 'edumall' ),
			'label_on'     => esc_html__( 'Custom', 'edumall' ),
			'return_value' => 'yes',
			'condition'    => [
				'layout!' => [
					'zigzag',
					'list-03',
					'one-left-featured',
				],
			],
		] );

		$this->start_popover();

		$this->add_control( 'overlay_style', [
			'label'   => esc_html__( 'Style', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'float' => esc_html__( 'Float', 'edumall' ),
			],
			'default' => 'float',
		] );

		$this->add_control( 'show_overlay_category', [
			'label'     => esc_html__( 'Category', 'edumall' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'edumall' ),
			'label_off' => esc_html__( 'Hide', 'edumall' ),
			'default'   => 'yes',
			'separator' => 'before',
		] );

		$this->add_control( 'show_overlay_title', [
			'label'     => esc_html__( 'Title', 'edumall' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'edumall' ),
			'label_off' => esc_html__( 'Hide', 'edumall' ),
			'separator' => 'before',
		] );

		$overlay_meta_repeater = new Repeater();

		$overlay_meta_repeater->add_control( 'meta', [
			'label'       => esc_html__( 'Select Meta', 'edumall' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT,
			'default'     => 'date',
			'options'     => [
				'author'   => esc_html__( 'Author', 'edumall' ),
				'date'     => esc_html__( 'Date', 'edumall' ),
				'comments' => esc_html__( 'Comments', 'edumall' ),
			],
		] );

		$this->add_control( 'overlay_meta_data', [
			'label'         => esc_html__( 'Meta Data', 'edumall' ),
			'type'          => Controls_Manager::REPEATER,
			'fields'        => $overlay_meta_repeater->get_controls(),
			'title_field'   => '{{{ meta }}}',
			'classes'       => 'edumall-control-repeater-title-capitalize',
			'prevent_empty' => false,
		] );

		$this->end_popover();
	}

	private function add_grid_section() {
		$this->start_controls_section( 'grid_options_section', [
			'label'     => esc_html__( 'Grid Options', 'edumall' ),
			'condition' => [
				'layout' => [
					'grid',
					'masonry',
					'metro',
				],
			],
		] );

		$this->add_responsive_control( 'grid_columns', [
			'label'          => esc_html__( 'Columns', 'edumall' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 12,
			'step'           => 1,
			'default'        => 3,
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

		$metro_layout_repeater = new Repeater();

		$metro_layout_repeater->add_control( 'size', [
			'label'   => esc_html__( 'Item Size', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1:1',
			'options' => Widget_Utils::get_grid_metro_size(),
		] );

		$this->add_control( 'grid_metro_layout', [
			'label'       => esc_html__( 'Metro Layout', 'edumall' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $metro_layout_repeater->get_controls(),
			'default'     => [
				[ 'size' => '2:2' ],
				[ 'size' => '1:1' ],
				[ 'size' => '1:1' ],
				[ 'size' => '1:1' ],
				[ 'size' => '2:2' ],
				[ 'size' => '1:1' ],
			],
			'title_field' => '{{{ size }}}',
			'condition'   => [
				'layout' => 'metro',
			],
		] );

		$this->end_controls_section();
	}

	private function add_box_style_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'edumall' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'box_border_radius', [
			'label'     => esc_html__( 'Border Radius', 'edumall' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .edumall-box' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->start_controls_tabs( 'box_skin_tabs' );

		$this->start_controls_tab( 'box_skin_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_box_shadow',
			'selector' => '{{WRAPPER}} .edumall-box',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'box_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_box_shadow_hover',
			'selector' => '{{WRAPPER}} .edumall-box:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_thumbnail_style_section() {
		$this->start_controls_section( 'thumbnail_style_section', [
			'label' => esc_html__( 'Thumbnail', 'edumall' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'thumbnail_border_radius', [
			'label'     => esc_html__( 'Border Radius', 'edumall' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .post-thumbnail' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab( 'thumbnail_effects_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'thumbnail_box_shadow',
			'selector' => '{{WRAPPER}} .post-thumbnail',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters',
			'selector' => '{{WRAPPER}} .post-thumbnail img',
		] );

		$this->add_control( 'thumbnail_opacity', [
			'label'     => esc_html__( 'Opacity', 'edumall' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .post-thumbnail img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'thumbnail_effects_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'thumbnail_box_shadow_hover',
			'selector' => '{{WRAPPER}} .edumall-box:hover .post-thumbnail',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters_hover',
			'selector' => '{{WRAPPER}} .edumall-box:hover .post-thumbnail img',
		] );

		$this->add_control( 'thumbnail_opacity_hover', [
			'label'     => esc_html__( 'Opacity', 'edumall' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .edumall-box:hover .post-thumbnail img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_caption_style_section() {
		$this->start_controls_section( 'caption_style_section', [
			'label'      => esc_html__( 'Caption', 'edumall' ),
			'tab'        => Controls_Manager::TAB_STYLE,
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'show_caption',
						'operator' => '===',
						'value'    => 'yes',
					],
					[
						'name'     => 'layout',
						'operator' => '===',
						'value'    => 'list-03',
					],
				],
			],
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'edumall' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .post-wrapper' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'caption_margin', [
			'label'      => esc_html__( 'Margin', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .post-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'caption_padding', [
			'label'      => esc_html__( 'Padding', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .post-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		// Meta style.
		$this->add_control( 'caption_meta_heading', [
			'label'     => esc_html__( 'Meta', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_caption_meta!' => '',
			],
		] );

		$this->add_responsive_control( 'caption_meta_margin', [
			'label'      => esc_html__( 'Margin', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .post-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'show_caption_meta!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'caption_meta_typography',
			'label'     => esc_html__( 'Typography', 'edumall' ),
			'selector'  => '{{WRAPPER}} .post-meta',
			'condition' => [
				'show_caption_meta!' => '',
			],
		] );

		$this->start_controls_tabs( 'caption_meta_style_tabs', [
			'condition' => [
				'show_caption_meta!' => '',
			],
		] );

		$this->start_controls_tab( 'caption_meta_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_control( 'caption_meta_text_color', [
			'label'     => esc_html__( 'Text Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-meta' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_meta_link_color', [
			'label'     => esc_html__( 'Link Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-meta a' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_meta_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_control( 'caption_meta_link_hover_color', [
			'label'     => esc_html__( 'Link Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-meta a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Category style.
		$this->add_control( 'caption_category_heading', [
			'label'     => esc_html__( 'Category', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_caption_category' => 'yes',
			],
		] );

		$this->add_responsive_control( 'caption_category_margin', [
			'label'      => esc_html__( 'Margin', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .post-categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'show_caption_category' => 'yes',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'caption_category_typography',
			'label'     => esc_html__( 'Typography', 'edumall' ),
			'selector'  => '{{WRAPPER}} .post-categories',
			'condition' => [
				'show_caption_category' => 'yes',
			],
		] );

		$this->start_controls_tabs( 'caption_category_style_tabs', [
			'condition' => [
				'show_caption_category' => 'yes',
			],
		] );

		$this->start_controls_tab( 'caption_category_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_control( 'caption_category_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-categories' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_category_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_control( 'caption_category_hover_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-categories a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Title style.
		$this->add_control( 'caption_title_heading', [
			'label'     => esc_html__( 'Title', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'caption_title_margin', [
			'label'      => esc_html__( 'Margin', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_title_typography',
			'label'    => esc_html__( 'Typography', 'edumall' ),
			'selector' => '{{WRAPPER}} .post-title',
		] );

		$this->start_controls_tabs( 'caption_title_style_tabs' );

		$this->start_controls_tab( 'caption_title_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_control( 'caption_title_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-title a' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_title_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_control( 'caption_title_hover_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-title a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Excerpt style.
		$this->add_control( 'caption_excerpt_heading', [
			'label'     => esc_html__( 'Excerpt', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		$this->add_responsive_control( 'caption_excerpt_margin', [
			'label'      => esc_html__( 'Margin', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .post-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'caption_excerpt_typography',
			'label'     => esc_html__( 'Typography', 'edumall' ),
			'selector'  => '{{WRAPPER}} .post-excerpt',
			'condition' => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		$this->add_control( 'caption_excerpt_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-excerpt' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		// Read more style.
		$this->add_control( 'caption_read_more_heading', [
			'label'     => esc_html__( 'Read More', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_caption_read_more' => 'yes',
			],
		] );

		$this->add_responsive_control( 'caption_read_more_margin', [
			'label'      => esc_html__( 'Margin', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .post-read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'show_caption_read_more' => 'yes',
			],
		] );

		$this->start_controls_tabs( 'read_more_style_tabs', [
			'condition' => [
				'show_caption_read_more' => 'yes',
			],
		] );

		$this->start_controls_tab( 'read_more_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_control( 'button_text_color', [
			'label'     => esc_html__( 'Text Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .tm-button' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_background_color', [
			'label'     => esc_html__( 'Background Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button:before' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_border_color', [
			'label'     => esc_html__( 'Border Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'read_more_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_control( 'button_hover_text_color', [
			'label'     => esc_html__( 'Text Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_hover_background_color', [
			'label'     => esc_html__( 'Background Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button:after' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_hover_border_color', [
			'label'     => esc_html__( 'Border Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button:hover' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_overlay_style_section() {
		$this->start_controls_section( 'overlay_style_section', [
			'label'     => esc_html__( 'Overlay', 'edumall' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'show_overlay' => 'yes',
			],
		] );

		$this->add_control( 'overlay_category_heading', [
			'label'     => esc_html__( 'Category', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'overlay_category_typography',
			'label'    => esc_html__( 'Typography', 'edumall' ),
			'selector' => '{{WRAPPER}} .post-overlay-categories',
		] );

		$this->start_controls_tabs( 'overlay_category_style_tabs' );

		$this->start_controls_tab( 'overlay_category_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_control( 'overlay_category_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-overlay-categories' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'overlay_category_bg_color', [
			'label'     => esc_html__( 'Background', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-overlay-categories a' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'overlay_category_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_control( 'overlay_category_hover_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-overlay-categories a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'overlay_category_hover_bg_color', [
			'label'     => esc_html__( 'Background', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-overlay-categories a:hover' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->query_posts();
		/**
		 * @var $query \WP_Query
		 */
		$query     = $this->get_query();
		$post_type = $this->get_post_type();

		$this->add_render_attribute( 'wrapper', 'class', [
			'edumall-grid-wrapper edumall-blog',
			'edumall-blog-' . $settings['layout'],
		] );

		if ( ! empty( $settings['show_caption'] ) && 'yes' === $settings['show_caption'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'edumall-blog-caption-style-' . $settings['caption_style'] );
		}

		if ( ! empty( $settings['show_overlay'] ) && 'yes' === $settings['show_overlay'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'edumall-blog-overlay-style-' . $settings['overlay_style'] );
		}

		if ( 'metro' === $settings['layout'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'edumall-grid-metro' );
		}

		$this->add_render_attribute( 'content-wrapper', 'class', 'edumall-grid' );

		if ( $this->is_grid() ) {
			$grid_options = $this->get_grid_options( $settings );

			$this->add_render_attribute( 'wrapper', 'data-grid', wp_json_encode( $grid_options ) );

			$this->add_render_attribute( 'content-wrapper', 'class', 'lazy-grid' );
		}

		if ( 'current_query' === $settings['query_source'] ) {
			$this->add_render_attribute( 'wrapper', 'data-query-main', '1' );
		}

		if ( ! empty( $settings['pagination_type'] ) && $query->found_posts > $settings['query_number'] ) {
			$this->add_render_attribute( 'wrapper', 'data-pagination', $settings['pagination_type'] );
		}

		if ( ! empty( $settings['pagination_custom_button_id'] ) ) {
			$this->add_render_attribute( 'wrapper', 'data-pagination-custom-button-id', $settings['pagination_custom_button_id'] );
		}
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php if ( $query->have_posts() ) : ?>

				<?php
				$edumall_grid_query['source']        = $settings['query_source'];
				$edumall_grid_query['action']        = "{$post_type}_infinite_load";
				$edumall_grid_query['max_num_pages'] = $query->max_num_pages;
				$edumall_grid_query['found_posts']   = $query->found_posts;
				$edumall_grid_query['count']         = $query->post_count;
				$edumall_grid_query['query_vars']    = $this->get_query_args();
				$edumall_grid_query['settings']      = $settings;
				$edumall_grid_query                  = htmlspecialchars( wp_json_encode( $edumall_grid_query ) );
				?>
				<input type="hidden"
				       class="edumall-query-input" <?php echo 'value="' . $edumall_grid_query . '"'; ?>/>

				<div <?php $this->print_attributes_string( 'content-wrapper' ); ?>>
					<?php if ( $this->is_grid() ) : ?>
						<div class="grid-sizer"></div>
					<?php endif; ?>

					<?php
					set_query_var( 'edumall_query', $query );
					set_query_var( 'settings', $settings );

					get_template_part( 'loop/widgets/blog/style', $settings['layout'] );
					?>
				</div>

				<?php $this->print_pagination( $query, $settings ); ?>

				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
		<?php
	}
}
