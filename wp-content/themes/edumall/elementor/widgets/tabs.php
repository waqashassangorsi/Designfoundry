<?php

namespace Edumall_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Base\Document;
use ElementorPro\Modules\QueryControl\Module as QueryControlModule;
use Elementor\Plugin;

defined( 'ABSPATH' ) || exit;

class Widget_Tabs extends Base {

	public function __construct( array $data = [], array $args = null ) {
		parent::__construct( $data, $args );

		wp_register_script( 'edumall-widget-tabs', EDUMALL_ELEMENTOR_URI . '/assets/js/widgets/widget-tabs.js', array(
			'elementor-frontend',
			'edumall-tab-panel',
		), null, true );
	}

	public function get_name() {
		return 'tm-tabs';
	}

	public function get_title() {
		return esc_html__( 'Advanced Tabs', 'edumall' );
	}

	public function get_icon_part() {
		return 'eicon-tabs';
	}

	public function get_keywords() {
		return [ 'advanced', 'modern', 'tabs' ];
	}

	public function get_script_depends() {
		return [ 'edumall-widget-tabs' ];
	}

	public function is_reload_preview_required() {
		return false;
	}

	protected function _register_controls() {
		$this->add_content_section();

		$this->add_styling_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Items', 'edumall' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'edumall' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => '01',
			'options'      => [
				'01' => '01',
				'02' => '02',
				'03' => '03',
				'04' => '04',
			],
			'prefix_class' => 'edumall-tabs-style-',
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'edumall' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => esc_html__( 'Tab Title', 'edumall' ),
			'label_block' => true,
			'dynamic'     => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'description', [
			'label' => esc_html__( 'Description', 'edumall' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$repeater->add_control( 'icon', [
			'label' => esc_html__( 'Icon', 'edumall' ),
			'type'  => Controls_Manager::ICONS,
		] );

		$repeater->add_control( 'content_type', [
			'label'       => esc_html__( 'Content Type', 'edumall' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'toggle'      => false,
			'render_type' => 'template',
			'default'     => 'content',
			'separator'   => 'before',
			'options'     => [
				'content'  => [
					'title' => esc_html__( 'Content', 'edumall' ),
					'icon'  => 'eicon-edit',
				],
				'template' => [
					'title' => esc_html__( 'Saved Template', 'edumall' ),
					'icon'  => 'eicon-library-open',
				],
			],
		] );

		$repeater->add_control( 'content', [
			'label'      => esc_html__( 'Content', 'edumall' ),
			'show_label' => false,
			'type'       => Controls_Manager::WYSIWYG,
			'condition'  => [
				'content_type' => 'content',
			],
		] );

		$document_types = Plugin::$instance->documents->get_document_types( [
			'show_in_library' => true,
		] );

		$repeater->add_control( 'template_id', [
			'label'        => esc_html__( 'Choose Template', 'edumall' ),
			'label_block'  => true,
			'show_label'   => false,
			'type'         => QueryControlModule::QUERY_CONTROL_ID,
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
			'condition'    => [
				'content_type' => 'template',
			],
		] );

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'edumall' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title'   => esc_html__( 'Tab Title #1', 'edumall' ),
					'content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'edumall' ),
				],
				[
					'title'   => esc_html__( 'Tab Title #2', 'edumall' ),
					'content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'edumall' ),
				],
				[
					'title'   => esc_html__( 'Tab Title #3', 'edumall' ),
					'content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'edumall' ),
				],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'edumall' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		$this->add_control( 'type', [
			'label'        => esc_html__( 'Type', 'edumall' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => 'horizontal',
			'options'      => [
				'horizontal' => esc_html__( 'Horizontal', 'edumall' ),
				'vertical'   => esc_html__( 'Vertical', 'edumall' ),
			],
			'prefix_class' => 'edumall-tabs-view-',
			'separator'    => 'before',
			'render_type'  => 'template',
		] );

		$this->add_control( 'nav_position_reversed', [
			'label'     => esc_html__( 'Nav Position Reversed?', 'edumall' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [
				'type' => 'vertical',
			],
		] );

		$this->end_controls_section();
	}

	private function add_styling_section() {
		$this->start_controls_section( 'tabs_styling_section', [
			'label' => esc_html__( 'Styling', 'edumall' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'nav_width', [
			'label'      => esc_html__( 'Nav Width', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'vw' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'vw' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .edumall-tabpanel.edumall-tabpanel-vertical .edumall-nav-tabs' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'type' => 'vertical',
			],
		] );

		$this->start_controls_tabs( 'title_colors_settings' );

		$this->start_controls_tab( 'title_colors_normal', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_control( 'icon_color_hr', [
			'label' => esc_html__( 'Icon', 'edumall' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon',
			'selector' => '{{WRAPPER}} .icon',
		] );

		$this->add_control( 'title_color_hr', [
			'label' => esc_html__( 'Title', 'edumall' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .nav-tab-title',
		] );

		$this->add_control( 'description_color_hr', [
			'label' => esc_html__( 'Description', 'edumall' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'description',
			'selector' => '{{WRAPPER}} .nav-tab-description',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_colors_hover', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_control( 'hover_icon_color_hr', [
			'label' => esc_html__( 'Icon', 'edumall' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_icon',
			'selector' => '{{WRAPPER}} .edumall-tab-title:hover .icon',
		] );

		$this->add_control( 'hover_title_color_hr', [
			'label' => esc_html__( 'Title', 'edumall' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_title',
			'selector' => '{{WRAPPER}} .edumall-tab-title:hover .nav-tab-title',
		] );

		$this->add_control( 'hover_description_color_hr', [
			'label' => esc_html__( 'Description', 'edumall' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_description',
			'selector' => '{{WRAPPER}} .edumall-tab-title:hover .nav-tab-description',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'heading_style_hr', [
			'type' => Controls_Manager::DIVIDER,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'desktop_title_typography',
			'label'    => esc_html__( 'Desktop Title Typography', 'edumall' ),
			'selector' => '{{WRAPPER}} .edumall-desktop-heading',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'mobile_title_typography',
			'label'    => esc_html__( 'Mobile Title Typography', 'edumall' ),
			'selector' => '{{WRAPPER}} .edumall-mobile-heading',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'label'    => esc_html__( 'Description Typography', 'edumall' ),
			'selector' => '{{WRAPPER}} .nav-tab-description',
		] );

		$this->add_control( 'icon_style_hr', [
			'label' => esc_html__( 'Icon', 'edumall' ),
			'type'  => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'     => esc_html__( 'Size', 'edumall' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .edumall-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_margin', [
			'label'      => esc_html__( 'Margin', 'edumall' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .edumall-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Do nothing if there is not any items.
		if ( empty( $settings['items'] ) || count( $settings['items'] ) <= 0 ) {
			return;
		}

		$tabs = $settings['items'];
		$this->add_render_attribute( 'wrapper', 'class', 'edumall-tabpanel edumall-tabpanel-' . $settings['type'] );

		if ( isset( $settings['nav_position_reversed'] ) && 'yes' === $settings['nav_position_reversed'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'edumall-tabpanel-nav-reversed' );
		}

		$this->add_render_attribute( 'wrapper', 'role', 'tablist' );

		$title_kses = [
			'i' => [
				'class' => [],
				'style' => [],
			],
		];

		$tab_navs_html   = '';
		$tab_panels_html = '';
		$loop_count      = 0;
		?>
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<?php
			$tab_key = "nav_tab_{$key}_";
			$loop_count++;

			$this->add_render_attribute( $tab_key, 'role', 'tab' );

			if ( 1 === $loop_count ) {
				$this->add_render_attribute( $tab_key, 'class', 'active' );
			}

			$icon_key = $tab_key . '_icon';

			$this->add_render_attribute( $icon_key, 'class', [
				'nav-tab-icon',
				'edumall-icon',
				'icon',
			] );

			$is_svg = isset( $tab['icon']['library'] ) && 'svg' === $tab['icon']['library'] ? true : false;

			if ( $is_svg ) {
				$this->add_render_attribute( $icon_key, 'class', 'edumall-svg-icon' );
			}

			if ( isset( $settings['icon_color_type'] ) && '' !== $settings['icon_color_type'] ) {
				switch ( $settings['icon_color_type'] ) {
					case 'gradient' :
						$this->add_render_attribute( $icon_key, 'class', 'edumall-gradient-icon' );
						break;
					case 'classic' :
						$this->add_render_attribute( $icon_key, 'class', 'edumall-solid-icon' );
						break;
				}
			}

			ob_start();
			?>
			<li <?php $this->print_render_attribute_string( $tab_key ); ?>>
				<a href="javascript:void(0);" class="edumall-tab-title edumall-desktop-heading">
					<?php if ( ! empty( $tab['icon']['value'] ) ): ?>
						<div <?php $this->print_render_attribute_string( $icon_key ); ?>><?php $this->render_icon( $settings, $tab['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?></div>
					<?php endif; ?>
					<div class="nav-tab-heading">
						<div class="nav-tab-title"><?php echo wp_kses( $tab['title'], $title_kses ); ?></div>
						<?php if ( isset( $tab['description'] ) && '' !== $tab['description'] )  : ?>
							<div
								class="nav-tab-description"><?php echo wp_kses( $tab['description'], $title_kses ); ?></div>
						<?php endif; ?>
					</div>
				</a>
			</li>
			<?php $tab_navs_html .= ob_get_clean(); ?>

			<?php
			$tab_panel_key = "content_tab_{$key}_";

			$this->add_render_attribute( $tab_panel_key, 'class', 'tab-panel' );

			if ( 1 === $loop_count ) {
				$this->add_render_attribute( $tab_panel_key, 'class', 'active' );
			}

			ob_start();
			?>
			<div <?php $this->print_render_attribute_string( $tab_panel_key ); ?>>
				<div class="edumall-tab-title tab-mobile-heading">
					<?php if ( ! empty( $tab['icon']['value'] ) ): ?>
						<div <?php $this->print_render_attribute_string( $icon_key ); ?>><?php $this->render_icon( $settings, $tab['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?></div>
					<?php endif; ?>
					<div class="nav-tab-heading">
						<div class="nav-tab-title"><?php echo wp_kses( $tab['title'], $title_kses ); ?></div>
						<?php if ( isset( $tab['description'] ) && '' !== $tab['description'] )  : ?>
							<div
								class="nav-tab-description"><?php echo wp_kses( $tab['description'], $title_kses ); ?></div>
						<?php endif; ?>
					</div>
				</div>
				<div class="tab-content">
					<?php if ( 'template' === $tab['content_type'] ): ?>
						<?php
						if ( isset( $tab['template_id'] ) && '' !== $tab['template_id'] ) {
							echo Plugin::$instance->frontend->get_builder_content_for_display( $tab['template_id'] );
						}
						?>
					<?php else: ?>
						<?php echo '' . $this->parse_text_editor( $tab['content'] ); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php $tab_panels_html .= ob_get_clean(); ?>
		<?php endforeach; ?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<ul class="edumall-nav-tabs"><?php echo '' . $tab_navs_html; ?></ul>
			<div class="edumall-tab-content"><?php echo '' . $tab_panels_html; ?></div>
		</div>
		<?php
	}
}
