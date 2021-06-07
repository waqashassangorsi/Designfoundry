<?php
$section  = 'logo';
$priority = 1;
$prefix   = 'logo_';

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'logo',
	'label'       => esc_html__( 'Default Logo', 'edumall' ),
	'description' => esc_html__( 'Choose default logo.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'logo_dark',
	'choices'     => array(
		'logo_dark'  => esc_html__( 'Dark Logo', 'edumall' ),
		'logo_light' => esc_html__( 'Light Logo', 'edumall' ),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'image',
	'settings' => 'logo_dark',
	'label'    => esc_html__( 'Dark Version', 'edumall' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => array(
		'url' => EDUMALL_THEME_IMAGE_URI . '/logo/dark-logo.png',
	),
	'choices'  => array(
		'save_as' => 'array',
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'image',
	'settings' => 'logo_light',
	'label'    => esc_html__( 'Light Version', 'edumall' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => array(
		'url' => EDUMALL_THEME_IMAGE_URI . '/logo/light-logo.png',
	),
	'choices'  => array(
		'save_as' => 'array',
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'logo_width',
	'label'       => esc_html__( 'Logo Width', 'edumall' ),
	'description' => esc_html__( 'For e.g: 200', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '148',
	'output'      => array(
		array(
			'element'  => '.branding-logo-wrap img,
			.error404--header .branding-logo-wrap img
			',
			'property' => 'width',
			'units'    => 'px',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'spacing',
	'settings'    => $prefix . 'padding',
	'label'       => esc_html__( 'Logo Padding', 'edumall' ),
	'description' => esc_html__( 'For e.g: 30px 0px 30px 0px', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array(
		'top'    => '15px',
		'right'  => '0px',
		'bottom' => '15px',
		'left'   => '0px',
	),
	'output'      => array(
		array(
			'element'  => '.branding-logo-wrap img',
			'property' => 'padding',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Sticky Logo', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'dimension',
	'settings'    => 'sticky_logo_width',
	'label'       => esc_html__( 'Logo Width', 'edumall' ),
	'description' => esc_html__( 'Controls the width of sticky header logo. For e.g: 120', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '148',
	'output'      => array(
		array(
			'element'  => '
			.header-sticky-both .headroom.headroom--not-top .branding img,
			.header-sticky-up .headroom.headroom--not-top.headroom--pinned .branding img,
			.header-sticky-down .headroom.headroom--not-top.headroom--unpinned .branding img
			',
			'property' => 'width',
			'units'    => 'px',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'spacing',
	'settings'    => 'sticky_logo_padding',
	'label'       => esc_html__( 'Logo Padding', 'edumall' ),
	'description' => esc_html__( 'Controls the padding of sticky header logo.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array(
		'top'    => '0',
		'right'  => '0',
		'bottom' => '0',
		'left'   => '0',
	),
	'output'      => array(
		array(
			'element'  => '.headroom--not-top .branding-logo-wrap .sticky-logo',
			'property' => 'padding',
		),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Mobile Menu Logo', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'image',
	'settings'    => 'mobile_menu_logo',
	'label'       => esc_html__( 'Logo', 'edumall' ),
	'description' => esc_html__( 'Select an image file for mobile menu logo.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array(
		'url' => EDUMALL_THEME_IMAGE_URI . '/logo/dark-logo.png',
	),
	'choices'     => array(
		'save_as' => 'array',
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'mobile_logo_width',
	'label'       => esc_html__( 'Logo Width', 'edumall' ),
	'description' => esc_html__( 'Controls the width of mobile menu logo. For e.g: 120', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '148',
	'output'      => array(
		array(
			'element'  => '.page-mobile-popup-logo img',
			'property' => 'width',
			'units'    => 'px',
		),
	),
) );
