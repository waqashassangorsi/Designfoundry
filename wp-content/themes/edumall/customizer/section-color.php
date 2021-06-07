<?php
$section  = 'color_';
$priority = 1;
$prefix   = 'color_';

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'color',
	'settings'  => 'primary_color',
	'label'     => esc_html__( 'Primary Color', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => Edumall::PRIMARY_COLOR,
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'color',
	'settings'  => 'secondary_color',
	'label'     => esc_html__( 'Secondary Color', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => Edumall::SECONDARY_COLOR,
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'color',
	'settings'  => 'third_color',
	'label'     => esc_html__( 'Third Color', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => Edumall::THIRD_COLOR,
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'color',
	'settings'    => 'body_color',
	'label'       => esc_html__( 'Text Color', 'edumall' ),
	'description' => esc_html__( 'Controls the default color of all text.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => Edumall::TEXT_COLOR,
	'output'      => array(
		array(
			'element'  => 'body',
			'property' => 'color',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'color',
	'settings'  => 'body_lighten_color',
	'label'     => esc_html__( 'Text Lighten Color', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => Edumall::TEXT_LIGHTEN_COLOR,
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => 'link_color',
	'label'       => esc_html__( 'Link Color', 'edumall' ),
	'description' => esc_html__( 'Controls the default color of all links.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'normal' => esc_attr__( 'Normal', 'edumall' ),
		'hover'  => esc_attr__( 'Hover', 'edumall' ),
	),
	'default'     => array(
		'normal' => '#696969',
		'hover'  => Edumall::PRIMARY_COLOR,
	),
	'output'      => array(
		array(
			'choice'   => 'normal',
			'element'  => 'a',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '
			a:hover,
			a:focus,
			.edumall-map-overlay-info a:hover,
			.widget_rss li a:hover,
			.widget_recent_entries li a:hover,
			.widget_recent_entries li a:after
			',
			'property' => 'color',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'color',
	'settings'    => 'heading_color',
	'label'       => esc_html__( 'Heading Color', 'edumall' ),
	'description' => esc_html__( 'Controls the color of heading.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => Edumall::HEADING_COLOR,
	'output'      => array(
		array(
			'element'  => '
			h1,h2,h3,h4,h5,h6,caption,th,blockquote,fieldset legend,
			.heading,
			.heading-color,
			.widget_rss li a,
			.edumall-grid-wrapper.filter-style-01 .btn-filter.current,
			.edumall-grid-wrapper.filter-style-01 .btn-filter:hover,
			.elementor-accordion .elementor-tab-title,
			.tm-table.style-01 td,
			.tm-table caption,
			.page-links,
            .comment-list .comment-actions a
			',
			'property' => 'color',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Button Color', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Button Default', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_color',
	'label'     => esc_html__( 'Normal', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'edumall' ),
		'background' => esc_attr__( 'Background', 'edumall' ),
		'border'     => esc_attr__( 'Border', 'edumall' ),
	),
	'default'   => array(
		'color'      => '#fff',
		'background' => Edumall::PRIMARY_COLOR,
		'border'     => Edumall::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => Edumall_Helper::get_button_css_selector(),
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => Edumall_Helper::get_button_css_selector(),
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => Edumall_Helper::get_button_css_selector(),
			'property' => 'background-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.wp-block-button.is-style-outline',
			'property' => 'color',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_hover_color',
	'label'     => esc_html__( 'Hover', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'edumall' ),
		'background' => esc_attr__( 'Background', 'edumall' ),
		'border'     => esc_attr__( 'Border', 'edumall' ),
	),
	'default'   => array(
		'color'      => Edumall::THIRD_COLOR,
		'background' => Edumall::SECONDARY_COLOR,
		'border'     => Edumall::SECONDARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => Edumall_Helper::get_button_hover_css_selector(),
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => Edumall_Helper::get_button_hover_css_selector(),
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => Edumall_Helper::get_button_hover_css_selector(),
			'property' => 'background-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.wp-block-button.is-style-outline .wp-block-button__link:hover',
			'property' => 'color',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Button Flat', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_style_flat_color',
	'label'     => esc_html__( 'Normal', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'edumall' ),
		'background' => esc_attr__( 'Background', 'edumall' ),
		'border'     => esc_attr__( 'Border', 'edumall' ),
	),
	'default'   => array(
		'color'      => '#fff',
		'background' => Edumall::PRIMARY_COLOR,
		'border'     => Edumall::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => '.tm-button.style-flat',
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => '.tm-button.style-flat',
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.tm-button.style-flat:before',
			'property' => 'background-color',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_style_flat_hover_color',
	'label'     => esc_html__( 'Hover', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'edumall' ),
		'background' => esc_attr__( 'Background', 'edumall' ),
		'border'     => esc_attr__( 'Border', 'edumall' ),
	),
	'default'   => array(
		'color'      => Edumall::THIRD_COLOR,
		'background' => Edumall::SECONDARY_COLOR,
		'border'     => Edumall::SECONDARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => '.tm-button.style-flat:hover',
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => '.tm-button.style-flat:hover',
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.tm-button.style-flat:after',
			'property' => 'background-color',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Button Border', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_style_border_color',
	'label'     => esc_html__( 'Normal', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'edumall' ),
		'background' => esc_attr__( 'Background', 'edumall' ),
		'border'     => esc_attr__( 'Border', 'edumall' ),
	),
	'default'   => array(
		'color'      => Edumall::PRIMARY_COLOR,
		'background' => 'rgba(0, 0, 0, 0)',
		'border'     => Edumall::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => '
			.tm-button.style-border,
			.tm-button.style-thick-border
			',
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => '
			.tm-button.style-border,
			.tm-button.style-thick-border
			',
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '
			.tm-button.style-border:before,
			.tm-button.style-thick-border:before
			',
			'property' => 'background-color',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_style_border_hover_color',
	'label'     => esc_html__( 'Hover', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'edumall' ),
		'background' => esc_attr__( 'Background', 'edumall' ),
		'border'     => esc_attr__( 'Border', 'edumall' ),
	),
	'default'   => array(
		'color'      => '#fff',
		'background' => Edumall::PRIMARY_COLOR,
		'border'     => Edumall::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => '
			.tm-button.style-border:hover,
			.tm-button.style-thick-border:hover
			',
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => '
			.tm-button.style-border:hover,
			.tm-button.style-thick-border:hover
			',
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '
			.tm-button.style-border:after,
			.tm-button.style-thick-border:after
			',
			'property' => 'background-color',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Form Color', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => 'form_input_color',
	'label'       => esc_html__( 'Color', 'edumall' ),
	'description' => esc_html__( 'Controls the color of form inputs.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'color'      => esc_attr__( 'Color', 'edumall' ),
		'background' => esc_attr__( 'Background', 'edumall' ),
		'border'     => esc_attr__( 'Border', 'edumall' ),
	),
	'default'     => array(
		'color'      => Edumall::HEADING_COLOR,
		'background' => '#f8f8f8',
		'border'     => '#f8f8f8',
	),
	'output'      => array(
		array(
			'choice'   => 'color',
			'element'  => Edumall_Helper::get_form_input_css_selector(),
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => Edumall_Helper::get_form_input_css_selector(),
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => Edumall_Helper::get_form_input_css_selector(),
			'property' => 'background-color',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => 'form_input_focus_color',
	'label'       => esc_html__( 'Focus Color', 'edumall' ),
	'description' => esc_html__( 'Controls the color of form inputs when focus.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'color'      => esc_attr__( 'Color', 'edumall' ),
		'background' => esc_attr__( 'Background', 'edumall' ),
		'border'     => esc_attr__( 'Border', 'edumall' ),
	),
	'default'     => array(
		'color'      => Edumall::HEADING_COLOR,
		'background' => '#fff',
		'border'     => Edumall::PRIMARY_COLOR,
	),
	'output'      => array(
		array(
			'choice'   => 'color',
			'element'  => Edumall_Helper::get_form_input_focus_css_selector(),
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => Edumall_Helper::get_form_input_focus_css_selector(),
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => Edumall_Helper::get_form_input_focus_css_selector(),
			'property' => 'background-color',
		),
	),
) );
