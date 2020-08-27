<?php
/**
 * Playlist Manager Shortcode.
 *
 * @class WPM_Shortcode
 * @author WolfThemes
 * @category Core
 * @package PACKAGENAME/Shortcode
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

/**
 * WPM_Shortcode class.
 */
class WPM_Shortcode {
	/**
	 * Constructor
	 */
	public function __construct() {

		add_shortcode( 'wolf_playlist', array( $this, 'shortcode' ) );
	}

	/**
	 * Render shortcode
	 */
	public function shortcode( $atts = array() ) {

		$atts = shortcode_atts(
			array(
				'id' => 0, // playlist post ID
				'theme' => get_wpm_default_theme(),
				'show_tracklist' => true,
			),
			$atts
		);

		$id = absint( $atts['id'] );
		unset( $atts['id'] ); // remove ID from extratced attributes

		$atts['show_tracklist'] = $this->shortcode_bool( $atts['show_tracklist'] );

		ob_start();
		wpm_playlist( $id, $atts );
		return ob_get_clean();
	}

	/**
	 * Helper method to determine if a shortcode attribute is true or false.
	 *
	 * @since 1.0.2
	 *
	 * @param string|int|bool $var Attribute value.
	 * @return bool
	 */
	protected function shortcode_bool( $var ) {
		$falsey = array( 'false', '0', 'no', 'n' );
		return ( ! $var || in_array( strtolower( $var ), $falsey, true ) ) ? false : true;
	}

} // end class

return new WPM_Shortcode();