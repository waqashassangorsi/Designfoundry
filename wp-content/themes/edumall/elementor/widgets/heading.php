<?php

namespace Edumall_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Heading extends Base {

	public function get_name() {
		return 'tm-heading';
	}

	public function get_title() {
		return esc_html__( 'Modern Heading', 'edumall' );
	}

	public function get_icon_part() {
		return 'eicon-heading';
	}

	public function get_keywords() {
		return [ 'heading', 'title', 'text' ];
	}

	protected function _register_controls() {
		$this->add_title_section();

		$this->add_description_section();

		$this->add_sub_title_section();

		$this->add_wrapper_style_section();

		$this->add_title_style_section();

		$this->add_divider_style_section();

		$this->add_description_style_section();

		$this->add_sub_title_style_section();
	}

	private function add_title_section() {
		$this->start_controls_section( 'title_section', [
			'label' => esc_html__( 'Heading', 'edumall' ),
		] );

		$this->add_control( 'title', [
			'label'       => esc_html__( 'Text', 'edumall' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Enter your title', 'edumall' ),
			'default'     => esc_html__( 'Add Your Heading Text Here', 'edumall' ),
			'description' => esc_html__( 'Wrap any words with &lt;mark&gt;&lt;/mark&gt; tag to make them highlight.', 'edumall' ),
		] );

		$this->add_control( 'title_link', [
			'label'     => esc_html__( 'Link', 'edumall' ),
			'type'      => Controls_Manager::URL,
			'dynamic'   => [
				'active' => true,
			],
			'default'   => [
				'url' => '',
			],
			'separator' => 'before',
		] );

		$this->add_control( 'title_link_animate', [
			'label'        => esc_html__( 'Link Animate', 'edumall' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''                  => esc_html__( 'None', 'edumall' ),
				'animate-border'    => esc_html__( 'Animate Border', 'edumall' ),
				'animate-border-02' => esc_html__( 'Animate Border 02', 'edumall' ),
			],
			'default'      => '',
			'prefix_class' => 'edumall-link-',
		] );

		$this->add_control( 'title_size', [
			'label'   => esc_html__( 'HTML Tag', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p',
			],
			'default' => 'h2',
		] );

		$this->add_control( 'title_style', [
			'label'        => esc_html__( 'Style', 'edumall' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''   => esc_html__( 'None', 'edumall' ),
				'01' => esc_html__( 'Curve on mark tag', 'edumall' ),
				'02' => esc_html__( 'Underline on mark tag', 'edumall' ),
			],
			'default'      => '',
			'prefix_class' => 'edumall-modern-heading-style-',
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'edumall' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		// Divider.
		$this->add_control( 'divider_enable', [
			'label' => esc_html__( 'Display Divider', 'edumall' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->end_controls_section();
	}

	private function add_description_section() {
		$this->start_controls_section( 'description_section', [
			'label' => esc_html__( 'Description', 'edumall' ),
		] );

		$this->add_control( 'description', [
			'label'   => esc_html__( 'Text', 'edumall' ),
			'type'    => Controls_Manager::WYSIWYG,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->end_controls_section();
	}

	private function add_sub_title_section() {
		$this->start_controls_section( 'sub_title_section', [
			'label' => esc_html__( 'Secondary Heading', 'edumall' ),
		] );

		$this->add_control( 'sub_title_text', [
			'label'   => esc_html__( 'Text', 'edumall' ),
			'type'    => Controls_Manager::TEXTAREA,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'sub_title_size', [
			'label'   => esc_html__( 'HTML Tag', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p',
			],
			'default' => 'h3',
		] );

		$this->end_controls_section();
	}

	private function add_wrapper_style_section() {
		$this->start_controls_section( 'wrapper_style_section', [
			'tab'   => Controls_Manager::TAB_STYLE,
			'label' => esc_html__( 'Wrapper', 'edumall' ),
		] );

		$this->add_responsive_control( 'align', [
			'label'                => esc_html__( 'Text Align', 'edumall' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align_full(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}}' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'max_width', [
			'label'          => esc_html__( 'Max Width', 'edumall' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units'     => [ 'px', '%' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .tm-modern-heading' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'alignment', [
			'label'                => esc_html__( 'Alignment', 'edumall' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}}',
			],
		] );

		$this->end_controls_section();
	}

	private function add_title_style_section() {
		$this->start_controls_section( 'title_style_section', [
			'label'     => esc_html__( 'Heading', 'edumall' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'title!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .heading-primary',
		] );

		$this->add_group_control( Group_Control_Text_Stroke::get_type(), [
			'name'     => 'title_text_stroke',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .heading-primary',
		] );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), [
			'name'     => 'text_shadow',
			'selector' => '{{WRAPPER}} .heading-primary',
		] );

		$this->add_control( 'blend_mode', [
			'label'     => esc_html__( 'Blend Mode', 'edumall' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				''            => esc_html__( 'Normal', 'edumall' ),
				'multiply'    => 'Multiply',
				'screen'      => 'Screen',
				'overlay'     => 'Overlay',
				'darken'      => 'Darken',
				'lighten'     => 'Lighten',
				'color-dodge' => 'Color Dodge',
				'saturation'  => 'Saturation',
				'color'       => 'Color',
				'difference'  => 'Difference',
				'exclusion'   => 'Exclusion',
				'hue'         => 'Hue',
				'luminosity'  => 'Luminosity',
			],
			'selectors' => [
				'{{WRAPPER}} .heading-primary' => 'mix-blend-mode: {{VALUE}}',
			],
			'separator' => 'none',
		] );

		$this->start_controls_tabs( 'title_style_tabs' );

		$this->start_controls_tab( 'title_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .heading-primary',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_hover',
			'selector' => '{{WRAPPER}} .heading-primary > a:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'highlight_heading', [
			'label'     => esc_html__( 'Highlight Words', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_highlight',
			'selector' => '{{WRAPPER}} .heading-primary mark',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_highlight',
			'selector' => '{{WRAPPER}} .heading-primary mark',
		] );

		$this->add_group_control( Group_Control_Text_Stroke::get_type(), [
			'name'     => 'title_highlight_text_stroke',
			'selector' => '{{WRAPPER}} .heading-primary mark',
		] );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), [
			'name'     => 'title_highlight_shadow',
			'selector' => '{{WRAPPER}} .heading-primary mark',
		] );

		/**
		 * Title Line Animate
		 */
		$line_condition = [
			'title_link_animate' => [
				'animate-border',
				'animate-border-02',
			],
		];

		$this->add_control( 'title_animate_line_heading', [
			'label'     => esc_html__( 'Line', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $line_condition,
		] );

		$this->add_responsive_control( 'title_animate_line_height', [
			'label'      => esc_html__( 'Height', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 1,
					'max'  => 5,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .heading-primary a mark:before, {{WRAPPER}} .heading-primary a mark:after' => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $line_condition,
		] );

		$this->add_control( 'title_animate_line_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .heading-primary a mark:before, {{WRAPPER}} .heading-primary a mark:after' => 'background: {{VALUE}};',
			],
			'condition' => $line_condition,
		] );

		$this->add_control( 'hover_title_animate_line_color', [
			'label'     => esc_html__( 'Hover Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .heading-primary a mark:after' => 'background: {{VALUE}};',
			],
			'condition' => [
				'title_link_animate' => [
					'animate-border',
				],
			],
		] );

		$this->end_controls_section();
	}

	private function add_description_style_section() {
		$this->start_controls_section( 'description_style_section', [
			'label'     => esc_html__( 'Description', 'edumall' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'description!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			'selector' => '{{WRAPPER}} .heading-description',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'description',
			'selector' => '{{WRAPPER}} .heading-description',
		] );

		$this->add_responsive_control( 'description_spacing', [
			'label'      => esc_html__( 'Spacing', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .heading-description-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'description_max_width', [
			'label'          => esc_html__( 'Max Width', 'edumall' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units'     => [ 'px', '%' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .heading-description' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_divider_style_section() {
		$this->start_controls_section( 'divider_style_section', [
			'label'     => esc_html__( 'Divider', 'edumall' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'divider_enable' => 'yes',
			],
		] );

		$this->add_responsive_control( 'divider_spacing', [
			'label'      => esc_html__( 'Spacing', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .heading-divider-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'divider_width', [
			'label'          => esc_html__( 'Width', 'edumall' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units'     => [ 'px', '%' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .heading-divider' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'divider_height', [
			'label'          => esc_html__( 'Height', 'edumall' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units'     => [ 'px', '%' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .heading-divider' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'divider',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .heading-divider',
		] );

		$this->end_controls_section();
	}

	private function add_sub_title_style_section() {
		$this->start_controls_section( 'sub_title_style_section', [
			'label'     => esc_html__( 'Secondary Heading', 'edumall' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'sub_title_text!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'sub_title',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .heading-secondary',
		] );

		$this->start_controls_tabs( 'sub_title_style_tabs' );

		$this->start_controls_tab( 'sub_title_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'sub_title',
			'selector' => '{{WRAPPER}} .heading-secondary',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'sub_title_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'sub_title_hover',
			'selector' => '{{WRAPPER}} .heading-secondary > a:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'sub_title_spacing', [
			'label'      => esc_html__( 'Spacing', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .heading-secondary-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->add_responsive_control( 'sub_title_max_width', [
			'label'          => esc_html__( 'Max Width', 'edumall' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units'     => [ 'px', '%' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .heading-secondary' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tm-modern-heading' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php $this->print_sub_title( $settings ); ?>

			<?php $this->print_title( $settings ); ?>

			<?php $this->print_divider( $settings ); ?>

			<?php $this->print_description( $settings ); ?>
		</div>
		<?php
	}

	protected function _content_template() {
		// @formatter:off
		?>
		<#
		var title = settings.title;
		var title_html = '';

		if ( ''  !== title ) {
			if ( '' !== settings.title_link.url ) {
				title = '<a href="' + settings.title_link.url + '">' + title + '</a>';
			}

			view.addRenderAttribute( 'title', 'class', 'heading-primary elementor-heading-title' );

			view.addInlineEditingAttributes( 'title' );

			title_html = '<' + settings.title_size  + ' ' + view.getRenderAttributeString( 'title' ) + '>' + title + '</' + settings.title_size + '>';
			title_html = '<div class="heading-primary-wrap">' + title_html + '</div>';
		}

		var sub_title_html = '';

		if ( settings.sub_title_text ) {
			sub_title_html = settings.sub_title_text;

			view.addRenderAttribute( 'sub_title', 'class', 'heading-secondary elementor-heading-title' );

			sub_title_html = '<' + settings.sub_title_size  + ' ' + view.getRenderAttributeString( 'sub_title' ) + '>' + sub_title_html + '</' + settings.sub_title_size + '>';

			sub_title_html = '<div class="heading-secondary-wrap">' + sub_title_html + '</div>';
		}
		#>
		<div class="tm-modern-heading">

			<# if ( '' !== sub_title_html ) { #>
				<# print( sub_title_html ); #>
			<# } #>

			<# print( title_html ); #>

			<# if ( 'yes' === settings.divider_enable ) { #>
				<div class="heading-divider-wrap">
					<div class="heading-divider"></div>
				</div>
			<# } #>

			<# if ( settings.description ) { #>
				<div class="heading-description-wrap">
					<div class="heading-description">{{{ settings.description }}}</div>
				</div>
			<# } #>
		</div>
		<?php
		// @formatter:off
	}

	private function print_title(array $settings) {
		if ( empty( $settings['title'] ) ) {
			return;
		}

		// .elementor-heading-title -> Default color from section + column.
		$this->add_render_attribute( 'title', 'class', 'heading-primary elementor-heading-title' );

		$this->add_inline_editing_attributes( 'title' );

		$title = $settings['title'];

		if ( ! empty( $settings['title_link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['title_link'] );

			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}
		?>
		<div class="heading-primary-wrap">
			<?php printf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title' ), $title ); ?>
		</div>
		<?php
	}

	private function print_sub_title(array $settings) {
		if( empty( $settings['sub_title_text'] ) ) {
			return;
		}

		// .elementor-heading-title -> Default color from section + column.
		$this->add_render_attribute( 'sub_title', 'class', 'heading-secondary elementor-heading-title' );
		?>
		<div class="heading-secondary-wrap">
			<?php printf( '<%1$s %2$s>%3$s</%1$s>', $settings['sub_title_size'], $this->get_render_attribute_string( 'sub_title' ), $settings['sub_title_text'] ); ?>
		</div>
		<?php
	}

	private function print_description(array $settings) {
		if( empty( $settings['description'] ) ) {
			return;
		}

		$this->add_render_attribute( 'description', 'class', 'heading-description' );
		?>
		<div class="heading-description-wrap">
			<div <?php $this->print_attributes_string( 'description' ); ?>>
				<?php echo wp_kses_post($settings['description']); ?>
			</div>
		</div>
	<?php
    }

    private function print_divider(array $settings) {
		if( empty( $settings['divider_enable'] ) || 'yes' !== $settings['divider_enable'] ) {
			return;
		}
		?>
		<div class="heading-divider-wrap">
			<div class="heading-divider"></div>
		</div>
	    <?php
 	}
}
