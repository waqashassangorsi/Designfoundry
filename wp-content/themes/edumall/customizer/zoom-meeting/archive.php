<?php
$section  = 'zoom_meeting_archive';
$priority = 1;
$prefix   = 'zoom_meeting_archive_';

$sidebar_positions   = Edumall_Helper::get_list_sidebar_positions();
$registered_sidebars = Edumall_Helper::get_registered_sidebars();

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Header', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'zoom_meeting_archive_header_type',
	'label'       => esc_html__( 'Header Style', 'edumall' ),
	'description' => esc_html__( 'Select header style that displays on event archive pages.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Edumall_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'edumall' ) ),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'zoom_meeting_archive_header_overlay',
	'label'    => esc_html__( 'Header Overlay', 'edumall' ),
	'section'  => $section,
	'priority' => $priority++,
	'choices'  => array(
		''  => esc_html__( 'Use Global', 'edumall' ),
		'0' => esc_html__( 'No', 'edumall' ),
		'1' => esc_html__( 'Yes', 'edumall' ),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'zoom_meeting_archive_header_skin',
	'label'    => esc_html__( 'Header Skin', 'edumall' ),
	'section'  => $section,
	'priority' => $priority++,
	'choices'  => array(
		''      => esc_html__( 'Use Global', 'edumall' ),
		'dark'  => esc_html__( 'Dark', 'edumall' ),
		'light' => esc_html__( 'Light', 'edumall' ),
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Page Title Bar', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'zoom_meeting_archive_title_bar_layout',
	'label'       => esc_html__( 'Title Bar Style', 'edumall' ),
	'description' => esc_html__( 'Select default Title Bar that displays on archive event pages.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '01',
	'choices'     => Edumall_Title_Bar::instance()->get_list( true, esc_html__( 'Use Global Title Bar', 'edumall' ) ),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Sidebar', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'zoom_meeting_archive_page_sidebar_1',
	'label'       => esc_html__( 'Sidebar 1', 'edumall' ),
	'description' => esc_html__( 'Select sidebar 1 that will display on event archive pages.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'none',
	'choices'     => $registered_sidebars,
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'zoom_meeting_archive_page_sidebar_2',
	'label'       => esc_html__( 'Sidebar 2', 'edumall' ),
	'description' => esc_html__( 'Select sidebar 2 that will display on event archive pages.', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'none',
	'choices'     => $registered_sidebars,
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'zoom_meeting_archive_page_sidebar_position',
	'label'    => esc_html__( 'Sidebar Position', 'edumall' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'right',
	'choices'  => $sidebar_positions,
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Others', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'zoom_meeting_archive_number_item',
	'label'       => esc_html__( 'Number items', 'edumall' ),
	'description' => esc_html__( 'Controls the number of products display on events page', 'edumall' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 9,
	'choices'     => array(
		'min'  => 1,
		'max'  => 50,
		'step' => 1,
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Grid Columns', 'edumall' ) . '</div>',
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => 'zoom_meeting_archive_lg_columns',
	'label'     => esc_html__( 'Large Device', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 3,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 6,
		'step' => 1,
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => 'zoom_meeting_archive_md_columns',
	'label'     => esc_html__( 'Medium Device', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 2,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 6,
		'step' => 1,
	),
) );

Edumall_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => 'zoom_meeting_archive_sm_columns',
	'label'     => esc_html__( 'Small Device', 'edumall' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 1,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 6,
		'step' => 1,
	),
) );
