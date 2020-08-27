<?php
/**
 * Playlist Manager register post type
 *
 * Register playlist post type
 *
 * @author WolfThemes
 * @category Core
 * @package WolfPlaylistManager/Admin
 * @version 1.2.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$labels = array(
	'name' => esc_html__( 'Playlists', 'wolf-playlist-manager' ),
	'singular_name' => esc_html__( 'Playlist', 'wolf-playlist-manager' ),
	'add_new' => esc_html__( 'Add New', 'wolf-playlist-manager' ),
	'add_new_item' => esc_html__( 'Add New Playlist', 'wolf-playlist-manager' ),
	'all_items'  => esc_html__( 'All Playlists', 'wolf-playlist-manager' ),
	'edit_item' => esc_html__( 'Edit Playlist', 'wolf-playlist-manager' ),
	'new_item' => esc_html__( 'New Playlist', 'wolf-playlist-manager' ),
	'view_item' => esc_html__( 'View Playlist', 'wolf-playlist-manager' ),
	'search_items' => esc_html__( 'Search Playlists', 'wolf-playlist-manager' ),
	'not_found' => esc_html__( 'No playlist found', 'wolf-playlist-manager' ),
	'not_found_in_trash' => esc_html__( 'No playlist found in Trash', 'wolf-playlist-manager' ),
	'parent_item_colon' => '',
	'menu_name' => esc_html__( 'Playlists', 'wolf-playlist-manager' ),
);

$args = array(
	'labels' => $labels,
	'public' => true,
	'publicly_queryable' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'query_var' => false,
	'rewrite' => array( 'slug' => 'playlist' ),
	'capability_type' => 'post',
	'has_archive' => false,
	'hierarchical' => false,
	'menu_position' => 5,
	'taxonomies' => array(),
	'supports' => array( 'title', 'thumbnail', 'comments' ),
	'exclude_from_search' => false,
	'menu_icon' => 'dashicons-playlist-audio',
);

register_post_type( 'wpm_playlist', $args );
