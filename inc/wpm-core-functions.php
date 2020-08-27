<?php
/**
 * Playlist Manager core functions
 *
 * General core functions available on admin and frontend
 *
 * @author WolfThemes
 * @category Core
 * @package WolfPlaylistManager/Core
 * @version 1.2.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Hack for old php versions to use boolval()
if ( ! function_exists( 'boolval' ) ) {
	function boolval( $val ) {
		return (bool) $val;
	}
}

/**
 * Add image sizes
 *
 * These size will be ued for the artwork thumbnail and playlist background
 */
function wpm_add_image_sizes() {

	add_image_size( 'wpm-thumb', 150, 150, true );
	add_image_size( 'wpm-cover', 960, 960, true );
}
add_action( 'init', 'wpm_add_image_sizes' );

/**
 * Return a markup of track for the admin from the file ids list
 *
 * @since 1.0.0
 */
function wpm_get_track_markup( $tracklist ) {

	if ( ! $tracklist ) {
		return;
	}

	if ( is_array( $tracklist ) ) {
		$tracks = $tracklist;
	} else {
		$tracks = wpm_list_to_array( $tracklist );
	}

	// var_dump($tracks);

	foreach ( $tracks as $attachment_id ) :

		$id = $attachment_id;
		$track = get_wpm_track_data( $id );
		$has_artwork = $track['artworkUrl'];
		$artwork_bg = ( $has_artwork ) ? 'background-image:url( ' . esc_url( $track['artworkUrl'] ) . ' );' : '';

		// var_dump( $track );
	?>
	<div class="wpm-track-container wpm-track-item" data-track-id="<?php echo absint( $id ); ?>">
		<div class="wpm-track menu-item-bar">
			<div class="menu-item-handle">
				<span class="item-title">
					<span class="menu-item-title wpm-track-title-label"><?php echo $track['title']; ?></span>
				</span>
				<span class="item-controls">
					<span class="item-order">
						<span class="wpm-toggle"><span>
					</span>
				</span>

			</div>
		</div><!-- .wpm-track -->
		<div class="wpm-track-content">
			<div class="wpm-track-loader"></div>
			<div class="wpm-track-column-group">
				<div class="wpm-track-column wpm-track-column-artwork">
					<input type="hidden" data-track-id="<?php echo absint( $id ); ?>" value="<?php echo absint( $track['artworkId'] ); ?>">
					<span style="<?php echo wpm_esc_style_attr( $artwork_bg ); ?>" class="wpm-track-artwork <?php echo ( $has_artwork ) ? 'wpm-track-has-artwork' : ''; ?>"></span>

					<a style="<?php echo ( $has_artwork ) ? 'display:inline-block;' : '' ;?>" data-track-id="<?php echo absint( $id ); ?>" class="wpm-remove-artwork"><?php esc_html_e( 'Remove artwork', 'wolf-playlist-manager' ); ?></a>
				</div><!-- .wpm-track-column-artwork -->

				<div class="wpm-track-column">
					<p>
						<label>
							<?php esc_html_e( 'Title', 'wolf-playlist-manager' ); ?>:<br>
							<input class="wpm-track-title regular-text" type="text" name="artist" placeholder="<?php esc_html_e( 'Title', 'wolf-playlist-manager' ); ?>" value="<?php echo esc_attr( $track['title'] ); ?>">
						</label>
					</p>
					<p>
						<label>
							<?php esc_html_e( 'Artist', 'wolf-playlist-manager' ); ?>:<br>
							<input class="wpm-track-artist regular-text" type="text" name="track" placeholder="<?php esc_html_e( 'Artist', 'wolf-playlist-manager' ); ?>" value="<?php echo esc_attr( $track['artist'] ); ?>">
						</label>
					</p>
					<p>
						<label>
							<?php esc_html_e( 'Lenght', 'wolf-playlist-manager' ); ?>:<br>
							<input class="wpm-track-length regular-text" type="text" name="track" placeholder="00:00" value="<?php echo esc_attr( $track['length'] ); ?>">
						</label>
					</p>
					<?php if ( class_exists( 'WooCommerce' ) ) :

						if ( class_exists( 'WooCommerce' ) ) {
						$product_posts = get_posts( 'post_type="product"&numberposts=-1' );

						$product_options = array();
						if ( $product_posts ) {
							$products[ esc_html__( 'Not linked', 'wolf-playlist-manager' ) ] = '';
							foreach ( $product_posts as $product ) {
								$product_options[ $product->ID ] = $product->post_title;
							}
						} else {
							$product_options[ esc_html__( 'No product yet', 'wolf-playlist-manager' ) ] = 0;
						}
					}
					?>
						<p>
							<label>
								<?php esc_html_e( 'WooCommerce Product ID', 'wolf-playlist-manager' ); ?>:<br>
								<select style="width:100%;" class="wpm-track-wc_product_id" name="wc_product_id">
									<option value=""><?php esc_html_e( 'None', 'wolf-playlist-manager' ); ?></option>
								<?php foreach ( $product_options as $id => $title ) : ?>
									<option <?php selected( $id, $track['wcProductId'] ); ?> value="<?php echo absint( $id ) ?>"><?php echo esc_attr( $title ); ?></option>
								<?php endforeach; ?>
								</select>
							</label>
						</p>
					<?php endif; ?>
				</div><!-- .wpm-track-column -->

				<div class="wpm-track-column">
					<p>
						<label>
							<?php esc_html_e( 'iTunes', 'wolf-playlist-manager' ); ?>:<br>
							<input class="wpm-track-itunes_url regular-text" type="text" name="itunes" placeholder="http://" value="<?php echo esc_url( $track['itunesUrl'] ); ?>">
						</label>
					</p>

					<p>
						<label>
							<?php esc_html_e( 'amazon', 'wolf-playlist-manager' ); ?>:<br>
							<input class="wpm-track-amazon_url regular-text" type="text" name="amazon" placeholder="http://" value="<?php echo esc_url( $track['amazonUrl'] ); ?>">
						</label>
					</p>

					<p>
						<label>
							<?php esc_html_e( 'googleplay', 'wolf-playlist-manager' ); ?>:<br>
							<input class="wpm-track-googleplay_url regular-text" type="text" name="googleplay" placeholder="http://" value="<?php echo esc_url( $track['googleplayUrl'] ); ?>">
						</label>
					</p>

					<p>
						<label>
							<?php esc_html_e( 'Other buy URL', 'wolf-playlist-manager' ); ?>:<br>
							<input class="wpm-track-buy_url regular-text" type="text" name="buy_url" placeholder="http://" value="<?php echo esc_url( $track['buyUrl'] ); ?>">
						</label>
					</p>

					<p>
						<label>
							<?php esc_html_e( 'Free Download', 'wolf-playlist-manager' ); ?>:<br>
							<input class="wpm-track-free_dl" type="checkbox" <?php checked( true, esc_attr( $track['freeDownload'] ) ); ?> name="free_dl" value="yes">
						</label>
						<?php esc_html_e( 'It will overwrite the settings above', 'wolf-playlist-manager' ); ?>
					</p>

				</div><!-- .wpm-track-column -->
			</div><!-- .wpm-track-column-group -->
			<div class="wpm-track-actions">
				<a class="wpm-track-remove"><?php esc_html_e( 'Remove', 'wolf-playlist-manager' ); ?></a> |
				<a class="wpm-toggle"><?php esc_html_e( 'Close', 'wolf-playlist-manager' ); ?></a>
			</div>
		</div><!-- .wpm-track-content -->
	</div><!-- .wpm-track-container -->
	<?php endforeach;
}

/**
 * Retreve all track infos and data as an nice array:
 *
 * @since 1.0.0
 * @param int $post_id
 * @return array
 */
function get_wpm_track_data( $post_id ) {

	$track = get_wpm_default_track(); // set default track args

	$post = get_post( $post_id );
	$meta = wp_get_attachment_metadata( $post_id );

	$title = ( $post->post_title ) ? $post->post_title : $post->post_name;
	$file_url = $post->guid;
	$artwork_id = absint( get_post_meta( $post_id, '_wpm_track_artwork', true ) );
	$artwork_url = esc_url( wpm_get_url_from_attachment_id( $artwork_id, 'wpm-thumb' ) );

	// buy URL
	$itunes_url = esc_url( get_post_meta( $post_id, '_wpm_track_itunes_url', true ) );
	$amazon_url = esc_url( get_post_meta( $post_id, '_wpm_track_amazon_url', true ) );
	$googleplay_url = esc_url( get_post_meta( $post_id, '_wpm_track_googleplay_url', true ) );
	$buy_url = esc_url( get_post_meta( $post_id, '_wpm_track_buy_url', true ) );
	$free_dl = wpm_bool( get_post_meta( $post_id, '_wpm_track_free_dl', true ) );
	$wc_product_id = absint( get_post_meta( $post_id, '_wpm_track_wc_product_id', true ) );

	$track['artist'] = ( isset( $meta['artist'] ) ) ? $meta['artist'] : '';
	$track['title'] = $title;
	$track['length'] = ( isset( $meta['length_formatted'] ) ) ? $meta['length_formatted'] : '';
	$track['format'] = ( isset( $meta['fileformat'] ) ) ? $meta['fileformat'] : '';
	$track['audioId'] = $post_id;
	$track['audioUrl'] = $file_url;
	$track['mp3'] = $file_url;
	$track['artworkId'] = $artwork_id;
	$track['artworkUrl'] = $artwork_url;
	$track['itunesUrl'] = $itunes_url;
	$track['amazonUrl'] = $amazon_url;
	$track['googleplayUrl'] = $googleplay_url;
	$track['buyUrl'] = $buy_url;
	$track['freeDownload'] = $free_dl;
	$track['wcProductId'] = $wc_product_id;

	// var_dump( $track );

	return wpm_sanitize_track( $track );
}

/**
 * Retrieve a default track.
 *
 * Useful for whitelisting allowed keys.
 *
 * @since 1.0.0
 * @return array
 */
function get_wpm_default_track() {
	$args = array(
		'artist'     => '',
		'artworkId'  => '',
		'artworkUrl' => '',
		'audioId'    => '',
		'audioUrl'   => '',
		'length'     => '',
		'format'     => '',
		'order'      => '',
		'title'      => '',
		'itunesUrl' => '',
		'amazonUrl' => '',
		'googleplayUrl' => '',
		'buyUrl' => '',
		'freeDownload' => '',
		'wcProductId' => '',
	);

	return apply_filters( 'wpm_default_track_properties', $args );
}

/**
 * Sanitize track arguments array
 *
 * @since 1.0.0
 * @param array $track Track data.
 * @return array
 */
function wpm_sanitize_track( $track ) {

	// Sanitize valid properties.
	$track['artist']     = sanitize_text_field( $track['artist'] );
	$track['artworkId']  = absint( $track['artworkId'] );
	$track['artworkUrl'] = esc_url_raw( $track['artworkUrl'] );
	$track['audioId']    = absint( $track['audioId'] );
	$track['audioUrl']   = esc_url_raw( $track['audioUrl'] );
	$track['length']     = sanitize_text_field( $track['length'] );
	$track['format']     = sanitize_text_field( $track['format'] );
	$track['title']      = sanitize_text_field( $track['title'] );
	$track['order']      = absint( $track['order'] );

	return apply_filters( 'wpm_sanitize_track', $track );
}

/**
 * Sanitize html style attribute
 *
 * @since 1.0.0
 * @param string $style
 * @return string
 */
function wpm_esc_style_attr( $style ) {
	return esc_attr( trim( wpm_clean_spaces( $style ) ) );
}

/**
 * Convert list of IDs to array
 *
 * @since 1.0.0
 * @param string $list
 * @return array
 */
function wpm_list_to_array( $list, $separator = ',' ) {
	return ( $list ) ? explode( $separator, trim( wpm_clean_spaces( wpm_clean_list( $list ) ) ) ) : array();
}

/**
 * Convert array of ids to list
 *
 * @since 1.0.0
 * @param string $list
 * @return array
 */
function wpm_array_to_list( $array ) {

	$list = '';

	if ( is_array( $array ) ) {
		$list = rtrim( implode( ',',  $array ), ',' );
	}

	return wpm_clean_list( $list );
}

/**
 * Clean list of numbers
 *
 * Used to clean the list of IDs
 *
 * @since 1.0.0
 * @param string $list
 * @return string
 */
function wpm_clean_list( $list ) {
	$list = wpm_clean_spaces( trim( rtrim( $list, ',' ) ) );
	$list = preg_replace( "/[^0-9,]/", '', $list );
	return $list;
}

/**
 * Remove all double spaces
 *
 * This function is mainly used to clean up inline CSS
 *
 * @since 1.0.0
 * @param string $css
 * @return string
 */
function wpm_clean_spaces( $string, $hard = true ) {
	return preg_replace( '/\s+/', ' ', $string );
}

/**
 * Get the URL of an attachment from its id
 *
 * @since 1.0.0
 * @param int $id
 * @param string $size
 * @return string
 */
function wpm_get_url_from_attachment_id( $id, $size = 'thumbnail' ) {
	if ( is_numeric( $id ) ) {
		$src = wp_get_attachment_image_src( absint( $id ), $size );

		if ( isset( $src[0] ) ) {
			return esc_url( $src[0] );
		}
	}
}

/**
 * Get options
 *
 * @param string $key
 * @param string $default
 * @return string
 */
function wpm_get_option( $key, $default = null ) {

	$wpm_settings = get_option( 'wolf_playlist_manager_settings' );

	if ( isset( $wpm_settings[ $key ] ) && '' != $wpm_settings[ $key ] ) {

		return $wpm_settings[ $key ];

	} elseif ( $default ) {

		return $default;
	}
}

/**
 * Get current page URL
 */
function wpm_get_current_url() {
	global $wp;
	return esc_url( home_url( add_query_arg( array(),$wp->request ) ) );
}

/**
 * Add to cart tag
 *
 * @param int $product_id
 * @param string $text link text content
 * @param string $class button class
 * @return string
 */
function wpm_add_to_cart( $product_id, $classes = '', $text = '' ) {
	//<a rel="nofollow" href="/factory/retine/shop/shop-boxed/?add-to-cart=60" data-quantity="1" data-product_id="60" data-product_sku="" class="button product_type_simple add_to_cart_button ajax_add_to_cart"><span>Add to cart</span></a>
	$wc_url = untrailingslashit( wpm_get_current_url() ) . '/?add-to-cart=' . absint( $product_id );

	$classes .= ' product_type_simple add_to_cart_button ajax_add_to_cart';

	return '<a
		href="' . esc_url( $wc_url ) . '"
		rel="nofollow"
		data-quantity="1" data-product_id="' . absint( $product_id ) . '"
		class="' . wpm_sanitize_html_classes( $classes ) . '">' . $text . '</a>';
}

/**
 * Helper method to determine if a shortcode attribute is true or false.
 *
 * @since 1.0.2
 *
 * @param string|int|bool $var Attribute value.
 * @return bool
 */
function wpm_bool( $var ) {
	$falsey = array( 'false', '0', 'no', 'n' );
	return ( ! $var || in_array( strtolower( $var ), $falsey, true ) ) ? false : true;
}

/**
 * sanitize_html_class works just fine for a single class
 * Some times le wild <span class="blue hedgehog"> appears, which is when you need this function,
 * to validate both blue and hedgehog,
 * Because sanitize_html_class doesn't allow spaces.
 *
 * @uses sanitize_html_class
 * @param (mixed: string/array) $class   "blue hedgehog goes shopping" or array("blue", "hedgehog", "goes", "shopping")
 * @param (mixed) $fallback Anything you want returned in case of a failure
 * @return (mixed: string / $fallback )
 */
function wpm_sanitize_html_classes( $class, $fallback = null ) {

	// Explode it, if it's a string
	if ( is_string( $class ) ) {
		$class = explode( ' ', $class);
	}

	if ( is_array( $class ) && count( $class ) > 0 ) {
		$class = array_unique( array_map( 'sanitize_html_class', $class ) );
		return trim( implode( ' ', $class ) );
	}
	else {
		return trim( sanitize_html_class( $class, $fallback ) );
	}
}