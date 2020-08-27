<?php
/**
 * Playlist Manager template functions
 *
 * Functions for the templating system.
 *
 * @author WolfThemes
 * @category Core
 * @package WolfPlaylistManager/Functions
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

/**
 * Output generator tag to aid debugging.
 */
function wpm_generator_tag( $gen, $type ) {
	switch ( $type ) {
		case 'html':
			$gen .= "\n" . '<meta name="generator" content="WolfPlaylist ' . esc_attr( WPM_VERSION ) . '">';
			break;
		case 'xhtml':
			$gen .= "\n" . '<meta name="generator" content="WolfPlaylist ' . esc_attr( WPM_VERSION ) . '" />';
			break;
	}
	return $gen;
}

/**
 * Add body classes
 *
 * @param  array $classes
 * @return array
 */
function wpm_body_class( $classes ) {

	$classes = ( array ) $classes;

	$classes[] = 'wolf-playlist-manager';
	$classes[] = sanitize_title_with_dashes( get_template() ); // theme slug

	// Specify if a sticky player is set
	if ( get_option( '_wpm_bar' ) || wpm_get_option( 'streaming_url' ) ) {
		$classes[] = 'is-wpm-bar-player';
	}

	// Specify if the sticky player is a streaming player
	if ( wpm_get_option( 'streaming_url' ) ) {
		$classes[] = 'wpm-bar-player-streaming';
	}

	return array_unique( $classes );
}