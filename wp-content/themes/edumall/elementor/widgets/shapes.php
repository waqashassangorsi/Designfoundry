<?php

namespace Edumall_Elementor;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Schemes\Color as Scheme_Color;

defined( 'ABSPATH' ) || exit;

class Widget_Shapes extends Base {

	public function get_name() {
		return 'tm-shapes';
	}

	public function get_title() {
		return esc_html__( 'Modern Shapes', 'edumall' );
	}

	public function get_icon_part() {
		return 'eicon-favorite';
	}

	public function get_keywords() {
		return [ 'shapes' ];
	}

	protected function _register_controls() {
		$this->add_content_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Shape', 'edumall' ),
		] );

		$this->add_control( 'type', [
			'label'        => esc_html__( 'Type', 'edumall' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'circle'        => esc_html__( 'Circle', 'edumall' ),
				'border-circle' => esc_html__( 'Border Circle', 'edumall' ),
				'distortion'    => esc_html__( 'Distortion', 'edumall' ),
				'distortion-02' => esc_html__( 'Distortion 02', 'edumall' ),
				'distortion-03' => esc_html__( 'Distortion 03', 'edumall' ),
				'distortion-04' => esc_html__( 'Distortion 04', 'edumall' ),
				'distortion-05' => esc_html__( 'Distortion 05', 'edumall' ),
			],
			'default'      => 'circle',
			'prefix_class' => 'edumall-shape-',
			'render_type'  => 'template',
		] );

		$this->add_responsive_control( 'shape_size', [
			'label'      => esc_html__( 'Size', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'vw' ],
			'range'      => [
				'px' => [
					'min' => 5,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'vw' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 50,
			],
			'selectors'  => [
				'{{WRAPPER}} .shape'                    => 'width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .edumall-shape-1-1 .shape' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'shape_border_size', [
			'label'     => esc_html__( 'Border', 'edumall' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 1,
					'max' => 50,
				],
			],
			'default'   => [
				'unit' => 'px',
				'size' => 3,
			],
			'selectors' => [
				'{{WRAPPER}} .shape' => 'border-width: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'type' => [ 'border-circle' ],
			],
		] );

		$this->add_control( 'shape_flip_x', [
			'label'     => esc_html__( 'Flip Horizontally', 'edumall' ),
			'type'      => Controls_Manager::CHOOSE,
			'toggle'    => false,
			'options'   => [
				'1'  => [
					'title' => esc_html__( 'None', 'edumall' ),
					'icon'  => 'eicon-ban',
				],
				'-1' => [
					'title' => esc_html__( 'Yes', 'edumall' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default'   => '1',
			'selectors' => [
				'{{WRAPPER}} .shape' => 'transform: rotate({{shape_rotate.SIZE}}{{shape_rotate.UNIT}}) scale({{VALUE}}, {{shape_flip_y.VALUE}});',
			],
			'condition' => [
				'type' => [
					'distortion',
					'distortion-02',
					'distortion-03',
					'distortion-04',
					'distortion-05',
				],
			],
		] );

		$this->add_control( 'shape_flip_y', [
			'label'     => esc_html__( 'Flip Vertical', 'edumall' ),
			'type'      => Controls_Manager::CHOOSE,
			'toggle'    => false,
			'options'   => [
				'1'  => [
					'title' => esc_html__( 'None', 'edumall' ),
					'icon'  => 'eicon-ban',
				],
				'-1' => [
					'title' => esc_html__( 'Yes', 'edumall' ),
					'icon'  => 'eicon-v-align-bottom',
				],
			],
			'default'   => '1',
			'selectors' => [
				'{{WRAPPER}} .shape' => 'transform: rotate({{shape_rotate.SIZE}}{{shape_rotate.UNIT}}) scale({{shape_flip_x.VALUE}}, {{VALUE}});',
			],
			'condition' => [
				'type' => [
					'distortion',
					'distortion-02',
					'distortion-03',
					'distortion-04',
					'distortion-05',
				],
			],
		] );

		$this->add_control( 'shape_rotate', [
			'label'      => esc_html__( 'Rotate', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'deg' ],
			'range'      => [
				'deg' => [
					'min'  => -360,
					'max'  => 360,
					'step' => 1,
				],
			],
			'default'    => [
				'size' => 0,
				'unit' => 'deg',
			],
			'selectors'  => [
				'{{WRAPPER}} .shape' => 'transform: rotate({{SIZE}}{{UNIT}}) scale({{shape_flip_x.VALUE}}, {{shape_flip_y.VALUE}});',
			],
			'condition'  => [
				'type' => [
					'distortion',
					'distortion-02',
					'distortion-03',
					'distortion-04',
					'distortion-05',
				],
			],
		] );

		$this->add_responsive_control( 'shape_offset_x', [
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
					'min'  => -200,
					'max'  => 200,
					'step' => 1,
				],
			],
			'default'     => [
				'size' => '0',
				'unit' => 'px',
			],
			'selectors'   => [
				'{{WRAPPER}} .edumall-shape' => 'transform: translate({{SIZE}}{{UNIT}}, {{shape_offset_y.SIZE}}{{shape_offset_y.UNIT}});',
			],
			'device_args' => [
				Controls_Stack::RESPONSIVE_TABLET => [
					'selectors' => [
						'{{WRAPPER}} .edumall-shape' => 'transform: translate({{SIZE}}{{UNIT}}, {{shape_offset_y_tablet.SIZE}}{{shape_offset_y_tablet.UNIT}})',
					],
				],
				Controls_Stack::RESPONSIVE_MOBILE => [
					'selectors' => [
						'{{WRAPPER}} .edumall-shape' => 'transform: translate({{SIZE}}{{UNIT}}, {{shape_offset_y_mobile.SIZE}}{{shape_offset_y_mobile.UNIT}})',
					],
				],
			],
		] );

		$this->add_responsive_control( 'shape_offset_y', [
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
					'min'  => -200,
					'max'  => 200,
					'step' => 1,
				],
			],
			'default'     => [
				'size' => '0',
				'unit' => 'px',
			],
			'selectors'   => [
				'{{WRAPPER}} .edumall-shape' => 'transform: translate({{shape_offset_x.SIZE}}{{shape_offset_x.UNIT}}, {{SIZE}}{{UNIT}});',
			],
			'device_args' => [
				Controls_Stack::RESPONSIVE_TABLET => [
					'selectors' => [
						'{{WRAPPER}} .edumall-shape' => 'transform: translate({{shape_offset_x_tablet.SIZE}}{{shape_offset_x_tablet.UNIT}}, {{SIZE}}{{UNIT}});',
					],
				],
				Controls_Stack::RESPONSIVE_MOBILE => [
					'selectors' => [
						'{{WRAPPER}} .edumall-shape' => 'transform: translate({{shape_offset_x_mobile.SIZE}}{{shape_offset_x_mobile.UNIT}}, {{SIZE}}{{UNIT}});',
					],
				],
			],
		] );

		$this->add_control( 'shape_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .shape'                => 'color: {{VALUE}};',
				'{{WRAPPER}} .elementor-shape-fill' => 'fill: {{VALUE}};',
			],
			'scheme'    => [
				'type'  => Scheme_Color::get_type(),
				'value' => Scheme_Color::COLOR_1,
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'box', 'class', 'edumall-shape' );

		if ( in_array( $settings['type'], [
			'circle',
			'border-circle',
		], true ) ) {
			$this->add_render_attribute( 'box', 'class', 'edumall-shape-1-1' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'box' ) ?>>
			<?php if ( in_array( $settings['type'], [
				'distortion',
				'distortion-02',
				'distortion-03',
				'distortion-04',
				'distortion-05',
			], true ) ): ?>
				<div class="shape">
					<?php echo \Edumall_Helper::get_file_contents( EDUMALL_THEME_DIR . '/assets/shape/' . $settings['type'] . '.svg' ); ?>
				</div>
			<?php else: ?>
				<div class="shape"></div>
			<?php endif; ?>
		</div>
		<?php
	}
}
