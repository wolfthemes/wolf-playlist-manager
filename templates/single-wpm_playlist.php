<?php
/**
 * The Template for displaying all single playlist posts.
 *
 * @package WordPress
 * @subpackage Playlist Manager
 * @since Playlist Manager 1.0.0
 */
get_header( 'wpm_playlist' );
if ( function_exists( 'wolf_post_before' ) ) {
	wolf_post_before();
}
?>
<div id="primary" class="content-area">
	<main id="content" class="site-content clearfix" role="main">
		<?php
			/**
			 * wpm_before_main_content hook
			 */
			do_action( 'wpm_before_main_content' );
		?>

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php
					/**
					 * wpm_playlist_post_start hook
					 */
					do_action( 'wpm_playlist_post_start' );
				?>
				<?php
					/**
					 * Playlist template tag
					 */
					wpm_playlist( get_the_ID() );
				?>
				<footer class="entry-meta no-border">
					<?php edit_post_link( esc_html__( 'Edit', 'wolf-playlist-manager' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-meta -->
				<?php
					/**
					 * wpm_playlist_post_end hook
					 */
					do_action( 'wpm_playlist_post_end' );
				?>
			</article><!-- #post -->
			<?php comments_template(); ?>
		<?php endwhile; // end of the loop. ?>
		<?php
			/**
			 * wpm_after_main_content hook
			 */
			do_action( 'wpm_after_main_content' );
		?>

	</main><!-- main#content .site-content-->
</div><!-- #primary .content-area -->
<?php
if ( function_exists( 'wolf_post_after' ) ) {
	wolf_post_after();
}
get_footer( 'wpm_playlist' );
?>