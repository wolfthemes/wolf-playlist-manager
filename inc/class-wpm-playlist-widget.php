<?php
/**
 * Playlist Widget
 *
 * Displays a playlist
 *
 * @author WolfThemes
 * @category Widgets
 * @package WolfPlaylistManager/Widgets
 * @version 1.2.8
 * @extends WP_Widget
 */

defined( 'ABSPATH' ) || exit;

class WPM_Playlist_Widget extends WP_Widget {

	var $wpm_widget_cssclass;
	var $wpm_widget_description;
	var $wpm_widget_idbase;
	var $wpm_widget_name;

	/**
	 * Constructor
	 *
	 * @since 1.0.1
	 * @see WP_Widget::construct()
	 */
	public function __construct() {

		/* Widget variable settings. */
		$this->wpm_widget_cssclass 	= 'wpm_playlist_widget';
		$this->wpm_widget_description 	= esc_html__( 'Displays your chosen playlist.', 'wolf-playlist-manager' );
		$this->wpm_widget_idbase 		= 'wpm_playlist_widget';
		$this->wpm_widget_name 		= esc_html__( 'Playlist', 'wolf-playlist-manager' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->wpm_widget_cssclass, 'description' => $this->wpm_widget_description );

		/* Create the widget. */
		parent::__construct( 'wpm_playlist_widget', $this->wpm_widget_name, $widget_ops );
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {

		extract( $args );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$post_id =  ( isset( $instance['post_id'] ) ) ? $instance['post_id'] : '';

		echo $before_widget;

		if ( $title )  {
			echo $before_title . $title . $after_title;
		}

		if ( $post_id ) {
			wpm_playlist( $post_id );
		}
		
		echo $after_widget;
	}

	/**
	 * Form to modify widget instance settings.
	 *
	 * @since 1.0.1
	 *
	 * @param array $instance Current widget instance settings.
	 */
	public function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array(
			'post_id' => '',
			'title'   => '',
		) );

		$playlists = get_posts( array(
			'post_type' => 'wpm_playlist',
			'orderby' => 'title',
			'order' => 'asc',
			'posts_per_page' => -1,
		) );

		$title = wp_strip_all_tags( $instance['title'] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wolf-playlist-manager' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ; ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>"><?php esc_html_e( 'Playlist:', 'wolf-playlist-manager' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'post_id' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>" class="widefat">
				<?php
				foreach ( $playlists as $playlist ) {
					printf(
						'<option value="%s"%s>%s</option>',
						$playlist->ID,
						selected( $instance['post_id'], $playlist->ID, false ),
						esc_html( $playlist->post_title )
					);
				}
				?>
			</select>
		</p>
		<?php
	}

	/**
	 * Save widget settings.
	 *
	 * @since 1.0.1
	 *
	 * @param array $new_instance New widget settings.
	 * @param array $old_instance Old widget settings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = wp_parse_args( $new_instance, $old_instance );

		$instance['title'] = wp_strip_all_tags( $new_instance['title'] );
		$instance['post_id'] = absint( $new_instance['post_id'] );

		return $instance;
	}
}