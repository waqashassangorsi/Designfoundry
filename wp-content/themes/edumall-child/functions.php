<?php
/**
 * Child Theme functions loads the main theme class and extra options
 *
 * @package Swatch Child
 * @subpackage Child
 * @since 1.3
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.0
 */

function designFoundry_child_scripts()
{
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array(), false, 'all');
}
add_action('wp_enqueue_scripts', 'designFoundry_child_scripts');
