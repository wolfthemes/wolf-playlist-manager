<?php
/**
 * Playlist Manager Options.
 *
 * @class WPM_Options
 * @author WolfThemes
 * @category Admin
 * @package WolfPlaylistManager/Admin
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

/**
 * WPM_Options class.
 */
class WPM_Options {
	/**
	 * Constructor
	 */
	public function __construct() {
		
		// default options
		add_action( 'admin_init', array( $this, 'default_options' ) );

		// register settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// add option sub-menu
		add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
	}

	/**
	 * Add options menu
	 */
	public function add_settings_menu() {

		add_submenu_page( 'edit.php?post_type=wpm_playlist', esc_html__( 'Settings', 'wolf-playlist-manager' ), esc_html__( 'Settings', 'wolf-playlist-manager' ), 'edit_plugins', 'wolf-playlist-manager-settings', array( $this, 'options_form' ) );
	}

	/**
	 * Set default options
	 */
	public function default_options() {

		global $options;

		if ( false === get_option( 'wolf_playlist_manager_settings' ) ) {

			$default = array(
				'theme' => 'dark',
			);

			add_option( 'wolf_playlist_manager_settings', $default );
		}
	}

	/**
	 * Init Settings
	 */
	public function register_settings() {

		register_setting( 'wolf-playlist-manager-settings', 'wolf_playlist_manager_settings', array( $this, 'settings_validate' ) );
		
		add_settings_section( 'wolf-playlist-manager-settings', '', array( $this, 'section_intro' ), 'wolf-playlist-manager-settings' );
		
		add_settings_field( 'streaming_url', esc_html__( 'Streaming URL', 'wolf-playlist-manager' ), array( $this, 'setting_streaming_url' ), 'wolf-playlist-manager-settings', 'wolf-playlist-manager-settings' );
		add_settings_field( 'streaming_name', esc_html__( 'Streaming Name', 'wolf-playlist-manager' ), array( $this, 'setting_streaming_name' ), 'wolf-playlist-manager-settings', 'wolf-playlist-manager-settings' );
		add_settings_field( 'streaming_description', esc_html__( 'Streaming Description', 'wolf-playlist-manager' ), array( $this, 'setting_streaming_description' ), 'wolf-playlist-manager-settings', 'wolf-playlist-manager-settings' );
	}

	/**
	 * Validate settings
	 */
	public function settings_validate( $input ) {

		if ( isset( $input['streaming_url'] ) ) {
			
			$input['streaming_url'] = esc_url_raw( $input['streaming_url'] );
			delete_option( '_wpm_bar' );
		
		} else {
			
			$input['streaming_url'] = '';
		}

		$input['streaming_name'] = esc_attr( $input['streaming_name'] );
		$input['streaming_description'] = esc_attr( $input['streaming_description'] );

		return $input;
	}

	/**
	 * Intro section
	 *
	 * @return string
	 */
	public function section_intro() {

		// add instructions
		//var_dump( get_option( 'wolf_playlist_manager_settings' ) );
		//var_dump( wpm_get_option( 'streaming_url' ) );
	}

	/**
	 * Streaming URL
	 *
	 * @return string
	 */
	public function setting_streaming_url() {
		?>
		<input type="text" placeholder="<?php esc_html_e( 'http://streamingexample.com:8010/stream.mp3', 'wolf-playlist-manager' ); ?>" class="regular-text" name="wolf_playlist_manager_settings[streaming_url]"
			value="<?php echo esc_attr( wpm_get_option( 'streaming_url' ) ); ?>"
		>
		<p>
			<em>
			<?php
				/*
				 *  Instructions
				 */
				echo wp_kses_post(
					sprintf(
						__( 'Enter your mp3 streaming URL here and a player will be displayed at the bottom of your pages. It will overwrite the current playlist set as "sticky player".<br>Your theme must support this feature. <a href="%s">Get a theme with sticky player support</a>.', 'wolf-playlist-manager' ),
						esc_url( 'https://docs.wolfthemes.com/features/theme-wolf-playlist-sticky-player-support/' )
					)
				);
			?>
			</em>
		</p>
		<?php
	}

	/**
	 * Streaming name
	 *
	 * @return string
	 */
	public function setting_streaming_name() {
		?>
		<input type="text" placeholder="<?php esc_html_e( 'My Radio', 'wolf-playlist-manager' ); ?>" class="regular-text" name="wolf_playlist_manager_settings[streaming_name]"
			value="<?php echo esc_attr( wpm_get_option( 'streaming_name' ) ); ?>"
		>
		<?php
	}

	/**
	 * Streaming description
	 *
	 * @return string
	 */
	public function setting_streaming_description() {
		?>
		<input type="text" placeholder="<?php esc_html_e( 'The Best Radio in the World', 'wolf-playlist-manager' ); ?>" class="regular-text" name="wolf_playlist_manager_settings[streaming_description]"
			value="<?php echo esc_attr( wpm_get_option( 'streaming_description' ) ); ?>"
		>
		<?php
	}

	/**
	 * Display options form
	 *
	 */
	public function options_form() {
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Playlist Settings' ) ?></h2>
			<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
			<div id="setting-error-settings_updated" class="updated settings-error">
				<p><strong><?php esc_html_e( 'Settings saved.', 'wolf-playlist-manager' ); ?></strong></p>
			</div>
			<?php } ?>
			<form action="options.php" method="post">
				<?php settings_fields( 'wolf-playlist-manager-settings' ); ?>
				<?php do_settings_sections( 'wolf-playlist-manager-settings' ); ?>
				<p class="submit"><input name="save" type="submit" class="button-primary" value="<?php esc_html_e( 'Save Changes', 'wolf-playlist-manager' ); ?>" /></p>
			</form>
		</div>
		<?php
	}
}

return new WPM_Options();