<?php
/**
 * Playlist Manager Template Hooks
 *
 * Action/filter hooks used for Playlist Manager functions/templates
 *
 * @author WolfThemes
 * @category Core
 * @package WolfPlaylistManager/Templates
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

/**
 * Body class
 *
 * @see  wpm_body_class()
 */
add_filter( 'body_class', 'wpm_body_class' );

/**
 * WP Header
 *
 * @see  wpm_generator_tag()
 */
add_action( 'get_the_generator_html', 'wpm_generator_tag', 10, 2 );
add_action( 'get_the_generator_xhtml', 'wpm_generator_tag', 10, 2 );