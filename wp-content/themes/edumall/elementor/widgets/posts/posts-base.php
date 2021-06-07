<?php

namespace Edumall_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use ElementorPro\Modules\QueryControl\Module as Module_Query;

defined( 'ABSPATH' ) || exit;

abstract class Posts_Base extends Base {

	/**
	 * @var \WP_Query
	 */
	private $_query      = null;
	private $_query_args = null;

	abstract protected function get_post_type();

	abstract protected function get_post_category();

	public function query_posts() {
		$settings          = $this->get_settings_for_display();
		$post_type         = $this->get_post_type();
		$this->_query      = Module_Query_Base::instance()->get_query( $settings, $post_type );
		$this->_query_args = Module_Query_Base::instance()->get_query_args();
	}

	protected function get_query() {
		return $this->_query;
	}

	protected function get_query_args() {
		return $this->_query_args;
	}

	protected function _register_controls() {
		$this->register_query_section();
	}

	protected function get_query_author_object() {
		return Module_Query::QUERY_OBJECT_AUTHOR;
	}

	protected function register_query_section() {
		$this->start_controls_section( 'query_section', [
			'label' => esc_html__( 'Query', 'edumall' ),
		] );

		$this->add_control( 'query_source', [
			'label'   => esc_html__( 'Source', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'custom_query'  => esc_html__( 'Custom Query', 'edumall' ),
				'current_query' => esc_html__( 'Current Query', 'edumall' ),
			),
			'default' => 'custom_query',
		] );

		$this->start_controls_tabs( 'query_args_tabs', [
			'condition' => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->start_controls_tab( 'query_include_tab', [
			'label' => esc_html__( 'Include', 'edumall' ),
		] );

		$this->add_control( 'query_include', [
			'label'       => esc_html__( 'Include By', 'edumall' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'options'     => [
				'terms'   => esc_html__( 'Term', 'edumall' ),
				'authors' => esc_html__( 'Author', 'edumall' ),
			],
			'condition'   => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_include_term_ids', [
			'type'         => Module_Query::QUERY_CONTROL_ID,
			'options'      => [],
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => [
				'object'  => Module_Query::QUERY_OBJECT_CPT_TAX,
				'display' => 'detailed',
				'query'   => [
					'post_type' => $this->get_post_type(),
				],
			],
			'condition'    => [
				'query_include' => 'terms',
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_include_authors', [
			'label'        => esc_html__( 'Author', 'edumall' ),
			'label_block'  => true,
			'type'         => Module_Query::QUERY_CONTROL_ID,
			'multiple'     => true,
			'default'      => [],
			'options'      => [],
			'autocomplete' => [
				'object' => $this->get_query_author_object(),
			],
			'condition'    => [
				'query_include' => 'authors',
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'query_exclude_tab', [
			'label' => esc_html__( 'Exclude', 'edumall' ),
		] );

		$this->add_control( 'query_exclude', [
			'label'       => esc_html__( 'Exclude By', 'edumall' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'options'     => [
				'terms'   => esc_html__( 'Term', 'edumall' ),
				'authors' => esc_html__( 'Author', 'edumall' ),
			],
			'condition'   => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_exclude_term_ids', [
			'type'         => Module_Query::QUERY_CONTROL_ID,
			'options'      => [],
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => [
				'object'  => Module_Query::QUERY_OBJECT_CPT_TAX,
				'display' => 'detailed',
				'query'   => [
					'post_type' => $this->get_post_type(),
				],
			],
			'condition'    => [
				'query_exclude' => 'terms',
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_exclude_authors', [
			'label'        => esc_html__( 'Author', 'edumall' ),
			'label_block'  => true,
			'type'         => Module_Query::QUERY_CONTROL_ID,
			'multiple'     => true,
			'default'      => [],
			'options'      => [],
			'autocomplete' => [
				'object' => $this->get_query_author_object(),
			],
			'condition'    => [
				'query_exclude' => 'authors',
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'query_number', [
			'label'       => esc_html__( 'Items per page', 'edumall' ),
			'description' => esc_html__( 'Number of items to show per page. Input "-1" to show all posts. Leave blank to use global setting.', 'edumall' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => -1,
			'max'         => 100,
			'step'        => 1,
			'condition'   => [
				'query_source!' => [ 'current_query' ],
			],
			'separator'   => 'before',
		] );

		$this->add_control( 'query_offset', [
			'label'       => esc_html__( 'Offset', 'edumall' ),
			'description' => esc_html__( 'Number of items to displace or pass over.', 'edumall' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 100,
			'step'        => 1,
			'condition'   => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_orderby', [
			'label'       => esc_html__( 'Order by', 'edumall' ),
			'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'edumall' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array(
				'date'           => esc_html__( 'Date', 'edumall' ),
				'ID'             => esc_html__( 'Post ID', 'edumall' ),
				'author'         => esc_html__( 'Author', 'edumall' ),
				'title'          => esc_html__( 'Title', 'edumall' ),
				'modified'       => esc_html__( 'Last modified date', 'edumall' ),
				'parent'         => esc_html__( 'Post/page parent ID', 'edumall' ),
				'comment_count'  => esc_html__( 'Number of comments', 'edumall' ),
				'menu_order'     => esc_html__( 'Menu order/Page Order', 'edumall' ),
				'meta_value'     => esc_html__( 'Meta value', 'edumall' ),
				'meta_value_num' => esc_html__( 'Meta value number', 'edumall' ),
				'rand'           => esc_html__( 'Random order', 'edumall' ),
			),
			'default'     => 'date',
			'condition'   => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_sort_meta_key', [
			'label'     => esc_html__( 'Meta key', 'edumall' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [
				'query_orderby' => [
					'meta_value',
					'meta_value_num',
				],
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_order', [
			'label'     => esc_html__( 'Sort order', 'edumall' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'DESC' => esc_html__( 'Descending', 'edumall' ),
				'ASC'  => esc_html__( 'Ascending', 'edumall' ),
			),
			'default'   => 'DESC',
			'condition' => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->end_controls_section();
	}

	protected function add_pagination_section() {
		$this->start_controls_section( 'pagination_section', [
			'label' => esc_html__( 'Pagination', 'edumall' ),
		] );

		$this->add_control( 'pagination_type', [
			'label'   => esc_html__( 'Pagination', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				''              => esc_html__( 'None', 'edumall' ),
				'numbers'       => esc_html__( 'Numbers', 'edumall' ),
				'navigation'    => esc_html__( 'Navigation', 'edumall' ),
				'load-more'     => esc_html__( 'Button', 'edumall' ),
				'load-more-alt' => esc_html__( 'Custom Button', 'edumall' ),
				'infinite'      => esc_html__( 'Infinite Scroll', 'edumall' ),
			),
			'default' => '',
		] );

		$this->add_control( 'pagination_custom_button_id', [
			'label'       => esc_html__( 'Custom Button ID', 'edumall' ),
			'description' => esc_html__( 'Input id of custom button to load more posts when click. For e.g: #product-load-more-btn', 'edumall' ),
			'type'        => Controls_Manager::TEXT,
			'condition'   => [
				'pagination_type' => 'load-more-alt',
			],
		] );

		$this->end_controls_section();
	}

	protected function add_pagination_style_section() {
		$this->start_controls_section( 'pagination_style_section', [
			'label'     => esc_html__( 'Pagination', 'edumall' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'pagination_type!' => '',
			],
		] );

		$this->add_responsive_control( 'pagination_alignment', [
			'label'     => esc_html__( 'Alignment', 'edumall' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'   => 'center',
			'selectors' => [
				'{{WRAPPER}} .edumall-grid-pagination' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'pagination_spacing', [
			'label'       => esc_html__( 'Spacing', 'edumall' ),
			'type'        => Controls_Manager::SLIDER,
			'placeholder' => '70',
			'range'       => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'   => [
				'{{WRAPPER}} .edumall-grid-pagination' => 'padding-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'pagination_typography',
			'selector'  => '{{WRAPPER}} .nav-link',
			'condition' => [
				'pagination_type' => 'navigation',
			],
		] );

		$this->start_controls_tabs( 'pagination_style_tabs' );

		$this->start_controls_tab( 'pagination_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_control( 'pagination_link_color', [
			'label'     => esc_html__( 'Link Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .navigation-buttons' => 'color: {{VALUE}};',
				'{{WRAPPER}} .page-pagination'    => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'pagination_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_control( 'pagination_link_hover_color', [
			'label'     => esc_html__( 'Link Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .nav-link:hover'           => 'color: {{VALUE}};',
				'{{WRAPPER}} .page-pagination .current' => 'color: {{VALUE}};',
				'{{WRAPPER}} .page-pagination a:hover'  => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'pagination_loading_heading', [
			'label'     => esc_html__( 'Loading Icon', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'pagination_loading_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .edumall-infinite-loader .sk-wrap' => 'color: {{VALUE}};',
			],
			'condition' => [
				'pagination_type!' => 'numbers',
			],
		] );

		$this->end_controls_section();
	}

	protected function add_filter_section() {
		$this->start_controls_section( 'filter_section', [
			'label' => esc_html__( 'Filter', 'edumall' ),
		] );

		$this->add_control( 'filter_enable', [
			'label' => esc_html__( 'Show Filter', 'edumall' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'filter_style', [
			'label'   => esc_html__( 'Style', 'edumall' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'01' => '01',
			),
			'default' => '01',
		] );

		$this->add_control( 'filter_counter', [
			'label'        => esc_html__( 'Show Counter', 'edumall' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'condition'    => [
				'filter_style!' => '',
			],
		] );

		$this->add_control( 'filter_in_grid', [
			'label'        => esc_html__( 'In Grid', 'edumall' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'condition'    => [
				'filter_style!' => '',
			],
		] );

		$this->end_controls_section();
	}

	protected function add_filter_style_section() {
		$this->start_controls_section( 'filter_style_section', [
			'label'     => esc_html__( 'Filter', 'edumall' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'filter_enable' => 'yes',
			],
		] );

		$this->add_responsive_control( 'filter_spacing', [
			'label'      => esc_html__( 'Spacing', 'edumall' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .edumall-grid-filter' => 'padding-bottom: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'filter_alignment', [
			'label'     => esc_html__( 'Alignment', 'edumall' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'   => 'center',
			'selectors' => [
				'{{WRAPPER}} .edumall-grid-filter' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'filter_link_typography',
			'label'    => esc_html__( 'Link Typography', 'edumall' ),
			'selector' => '{{WRAPPER}} .btn-filter .filter-text',
		] );

		$this->start_controls_tabs( 'filter_link_tabs' );

		$this->start_controls_tab( 'filter_link_normal', [
			'label' => esc_html__( 'Normal', 'edumall' ),
		] );

		$this->add_control( 'filter_link_color', [
			'label'     => esc_html__( 'Link Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .btn-filter' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'filter_link_hover', [
			'label' => esc_html__( 'Hover', 'edumall' ),
		] );

		$this->add_control( 'filter_link_hover_color', [
			'label'     => esc_html__( 'Link Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .btn-filter.current, {{WRAPPER}} .btn-filter:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'filter_counter_style_heading', [
			'label'     => esc_html__( 'Filter Counter', 'edumall' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'filter_counter' => '1',
			],
		] );

		$this->add_control( 'filter_counter_text_color', [
			'label'     => esc_html__( 'Color', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .btn-filter .filter-counter' => 'color: {{VALUE}};',
			],
			'condition' => [
				'filter_counter' => '1',
			],
		] );

		$this->add_control( 'filter_counter_background_color', [
			'label'     => esc_html__( 'Background', 'edumall' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .btn-filter .filter-counter'        => 'background: {{VALUE}};',
				'{{WRAPPER}} .btn-filter .filter-counter:before' => 'border-top-color: {{VALUE}};',
			],
			'condition' => [
				'filter_counter' => '1',
			],
		] );

		$this->end_controls_section();
	}

	protected function add_sorting_section() {
		$this->start_controls_section( 'result_count_sorting_section', [
			'label' => esc_html__( 'Result Count & Sorting', 'edumall' ),
		] );

		$this->add_control( 'show_result_count', [
			'label'        => esc_html__( 'Show Result Count', 'edumall' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_ordering', [
			'label'        => esc_html__( 'Show Order', 'edumall' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->end_controls_section();
	}

	protected function get_sort_options() {
		return [
			''           => esc_html__( 'Default', 'edumall' ),
			'popularity' => esc_html__( 'Popularity', 'edumall' ),
			'date'       => esc_html__( 'Latest', 'edumall' ),
			'price'      => esc_html__( 'Price: low to high', 'edumall' ),
			'price-desc' => esc_html__( 'Price: high to low', 'edumall' ),
		];
	}

	protected function print_result_count_and_sorting() {
		$settings = $this->get_settings_for_display();
		// For now this feature support only cpt course.
		if ( 'courses' !== $this->get_post_type() ) {
			return;
		}

		// Do nothing if there is not both result & ordering.
		if ( empty( $settings['show_result_count'] ) && empty( $settings['show_ordering'] ) ) {
			return;
		}
		?>
		<div class="edumall-grid-sorting row row-sm-center">
			<div class="col-md-6 result-count">
				<?php if ( ! empty( $settings['show_result_count'] ) ) : ?>
					<?php
					$result_count = $this->_query->found_posts;
					$result_count_html = sprintf( _n( '%s course', '%s courses', $result_count, 'edumall' ), '<span class="count">' . number_format_i18n( $result_count ) . '</span>' );
					printf(
						wp_kses(
							__( 'We found %s available for you', 'edumall' ),
							array( 'span' => [ 'class' => [] ] )
						),
						$result_count_html
					);
					?>
				<?php endif; ?>
			</div>
			<div class="col-md-6 ordering">
				<?php if ( ! empty( $settings['show_ordering'] ) ) : ?>
					<?php
					$options         = $this->get_sort_options();
					$selected        = '';
					$select_settings = [
						'fieldLabel' => esc_html__( 'Sort by:', 'edumall' ),
					];

					$this->add_render_attribute( 'sorting', [
						'class'       => 'edumall-widget-nice-select orderby',
						'name'        => 'orderby',
						'data-select' => wp_json_encode( $select_settings ),
					] )
					?>
					<select <?php $this->print_render_attribute_string( 'sorting' ); ?>>
						<?php foreach ( $options as $value => $text ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $selected, $value ); ?> >
								<?php echo esc_html( $text ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Check if layout is grid|metro|masonry
	 *
	 * @return bool
	 */
	protected function is_grid() {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['layout'] ) &&
		     in_array( $settings['layout'], array(
			     'grid',
			     'metro',
			     'masonry',
		     ), true ) ) {
			return true;
		}

		return false;
	}

	protected function get_grid_options( array $settings ) {
		$grid_options = [];

		$grid_options['type'] = isset( $settings['layout'] ) ? $settings['layout'] : 'grid';

		if ( isset( $settings['metro_image_ratio'], $settings['metro_image_ratio']['size'] ) ) {
			$grid_options['ratio'] = $settings['metro_image_ratio']['size'];
		}

		// Columns.
		if ( isset( $settings['grid_columns'] ) && '' !== $settings['grid_columns'] ) {
			$grid_options['columns'] = $settings['grid_columns'];
		}

		if ( isset( $settings['grid_columns_tablet'] ) && '' !== $settings['grid_columns_tablet'] ) {
			$grid_options['columnsTablet'] = $settings['grid_columns_tablet'];
		}

		if ( isset( $settings['grid_columns_mobile'] ) && '' !== $settings['grid_columns_mobile'] ) {
			$grid_options['columnsMobile'] = $settings['grid_columns_mobile'];
		}

		// Gutter
		if ( isset( $settings['grid_gutter'] ) && '' !== $settings['grid_gutter'] ) {
			$grid_options['gutter'] = $settings['grid_gutter'];
		}

		if ( isset( $settings['grid_gutter_tablet'] ) && '' !== $settings['grid_gutter_tablet'] ) {
			$grid_options['gutterTablet'] = $settings['grid_gutter_tablet'];
		}

		if ( isset( $settings['grid_gutter_mobile'] ) && '' !== $settings['grid_gutter_mobile'] ) {
			$grid_options['gutterMobile'] = $settings['grid_gutter_mobile'];
		}

		// Zigzag height.
		if ( isset( $settings['zigzag_height'] ) && '' !== $settings['zigzag_height'] ) {
			$grid_options['zigzagHeight'] = $settings['zigzag_height'];
		}

		if ( isset( $settings['zigzag_height_tablet'] ) && '' !== $settings['zigzag_height_tablet'] ) {
			$grid_options['zigzagHeightTablet'] = $settings['zigzag_height_tablet'];
		}

		if ( isset( $settings['zigzag_height_mobile'] ) && '' !== $settings['zigzag_height_mobile'] ) {
			$grid_options['zigzagHeightMobile'] = $settings['zigzag_height_mobile'];
		}

		if ( isset( $settings['zigzag_reversed'] ) && 'yes' === $settings['zigzag_reversed'] ) {
			$grid_options['zigzagReversed'] = 1;
		}

		return $grid_options;
	}

	protected function print_pagination( $query, $settings ) {
		$number          = ! empty( $settings['query_number'] ) ? $settings['query_number'] : get_option( 'posts_per_page' );
		$pagination_type = $settings['pagination_type'];

		if ( $pagination_type !== '' && $query->found_posts > $number ) {
			?>
			<div class="edumall-grid-pagination">
				<div class="pagination-wrapper">

					<?php if ( in_array( $pagination_type, array(
						'load-more',
						'load-more-alt',
						'infinite',
						'navigation',
					), true ) ) { ?>
						<div class="inner">
							<div class="edumall-infinite-loader">
								<?php edumall_load_template( 'preloader/style', 'circle' ) ?>
							</div>
						</div>

						<div class="inner">
							<?php if ( $pagination_type === 'load-more' ) { ?>
								<a href="#" class="edumall-load-more-button tm-button style-border icon-right">
									<span
										class="button-text"><?php echo esc_html__( 'Load More', 'edumall' ); ?></span>
									<span class="button-icon fal fa-redo"></span>
								</a>
							<?php } elseif ( $pagination_type === 'navigation' ) { ?>
								<?php $this->print_pagination_type_navigation(); ?>
							<?php } ?>
						</div>
					<?php } elseif ( $pagination_type === 'numbers' ) { ?>
						<?php \Edumall_Templates::paging_nav( $query ); ?>
					<?php } ?>

				</div>
			</div>
			<div class="edumall-grid-messages" style="display: none;">
				<?php esc_html_e( 'All items displayed.', 'edumall' ); ?>
			</div>
			<?php
		}
	}

	protected function print_pagination_type_navigation() {
		?>
		<div class="navigation-buttons">
			<div class="nav-link prev-link disabled" data-action="prev">
				<?php esc_html_e( 'Prev Projects', 'edumall' ); ?>
			</div>
			<div class="nav-line"></div>
			<div class="nav-link next-link" data-action="next">
				<?php esc_html_e( 'Next Projects', 'edumall' ); ?>
			</div>
		</div>
		<?php
	}

	protected function print_filter( $total = 0, $list = '' ) {
		$settings  = $this->get_settings_for_display();
		$category  = $this->get_post_category();
		$post_type = $this->get_post_type();

		if ( empty( $settings['filter_enable'] ) || 'yes' !== $settings['filter_enable'] ) {
			return;
		}

		$this->add_render_attribute( 'filter', 'class', 'edumall-grid-filter' );

		if ( '1' === $settings['filter_counter'] ) {
			$this->add_render_attribute( 'filter', 'class', 'show-filter-counter' );
		}

		if ( '1' === $settings['filter_counter'] ) {
			$this->add_render_attribute( 'filter', 'data-filter-counter', true );
		}

		$current_cat = '';
		if ( \Edumall_Portfolio::instance()->is_taxonomy() ) {
			$current_cat = get_queried_object()->term_id;
		}

		$btn_filter_class     = 'btn-filter';
		$btn_filter_all_class = $btn_filter_class;

		if ( '' === $current_cat ) {
			$btn_filter_all_class .= ' current';
		}
		?>
		<div <?php $this->print_render_attribute_string( 'filter' ) ?>>
			<?php ob_start(); ?>
			<div class="edumall-grid-filter-buttons">
				<a href="<?php echo esc_url( get_post_type_archive_link( $post_type ) ); ?>"
				   class="<?php echo esc_attr( $btn_filter_all_class ); ?>"
				   data-filter="*" data-filter-count="<?php echo esc_attr( $total ); ?>">
					<span class="filter-text"><?php esc_html_e( 'All', 'edumall' ); ?></span>
				</a>
				<?php
				if ( $list === '' ) {
					$_categories = get_terms( [
						'taxonomy'   => $category,
						'hide_empty' => true,
					] );

					foreach ( $_categories as $term ) {
						$current_filter_class = $btn_filter_class;

						if ( $term->term_id === $current_cat ) {
							$current_filter_class .= ' current';
						}

						$term_link = get_term_link( $term );
						printf( '<a href="%s" class="%s" data-filter="%s" data-filter-count="%s"><span class="filter-text">%s</span></a>',
							esc_url( $term_link ),
							esc_attr( $current_filter_class ),
							esc_attr( "{$category}:{$term->slug}" ),
							$term->count,
							$term->name
						);
					}
				} else {
					$list = explode( ', ', $list );
					foreach ( $list as $item ) {
						$value = explode( ':', $item );

						$term = get_term_by( 'slug', $value[1], $value[0] );

						if ( $term === false ) {
							continue;
						}

						$term_link = get_term_link( $term );

						printf( '<a href="%s" class="btn-filter" data-filter="%s" data-filter-count="%s"><span class="filter-text">%s</span></a>',
							esc_url( $term_link ),
							esc_attr( "{$value[0]}:{$value[1]}" ),
							$term->count,
							$value[1]
						);
					}
				}
				?>
			</div>
			<?php
			$output = ob_get_clean();

			if ( '1' === $settings['filter_in_grid'] ) {
				printf( '<div class="container"><div class="row"><div class="col-md-12">%1$s</div></div></div>', $output );
			} else {
				echo '' . $output;
			}
			?>
		</div>
		<?php
	}
}
