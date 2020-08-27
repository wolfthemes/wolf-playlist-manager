/**
 * Wolf Playilst Manager custom functions
 */
 /* jshint -W062 */

var WPM = function ( $ ) {

	'use strict';

	return {

		init : function () {

			var _this = this;

			this.setWPMBarPlayerHtmlClass();
			this.setTracklistCountClass();
			this.cuePlaylists();

			$( window ).resize( function() {
				_this.resetTimerail();
				_this.sizeClasses();
			} );
		},

		/**
		 * WPM bar
		 */
		setWPMBarPlayerHtmlClass : function () {
			if( $( 'body' ).hasClass( 'is-wpm-bar-player' ) ) {
				$( 'html' ).addClass( 'wpm-bar' );
			}
		},

		setTracklistCountClass : function() {
			$( '.wpm-tracks' ).each( function() {
				if ( $( this ).data( 'track-count' ) > 5 ) {
					$( this ).addClass( 'wpm-tracks-has-scrollbar' );
				}
			} );
		},

		/**
		 * Fire cue playlists
		 */
		cuePlaylists : function () {

			$( '.wpm-playlist' ).each( function() {

				var $playlist = $( this ),
					data = {},
					$data = $playlist.closest( '.wpm-playlist-container' ).find( '.wpm-playlist-data' );

				if ( $data.length ) {
					data = $.parseJSON( $data.first().html() );
				}

				$playlist.cuePlaylist( {
					startVolume: 1,
					pauseOtherPlayers: data.pauseOtherPlayers,
					cueBackgroundUrl: data.thumbnail || '',
					cueEmbedLink: data.embed_link || '',
					cuePermalink: data.permalink || '',
					cueResponsiveProgress: true,
					defaultAudioHeight: 0,
					cuePlaylistLoop: false,
					cuePlaylistTracks: data.tracks || [],
					cueSkin: data.skin || 'wpm-theme-default',
					cueSelectors: {
						playlist: '.wpm-playlist',
						track: '.wpm-track',
						tracklist: '.wpm-tracks'
					},

					features: data.cueFeatures
				} );
			} );
		},

		/**
		 * Reset time bar
		 */
		resetTimerail : function () {
			$( '.wpm-playlist' ).each( function() {
				$( this ).find( '.mejs-time-rail, .mejs-time-slider' ).css( 'width', '' );
			} );
		},

		/**
		 * Change the player appearence depending on window's width
		 */
		sizeClasses : function () {

			$( '.wpm-playlist' ).each( function() {
				var width = $( this ).width();

				if ( 500 > width && 380 < width  ) {
					$( this ).addClass( 'wpm-playlist-500' );
					$( this ).removeClass( 'wpm-playlist-380' );

				} else if ( 380 > width ) {
					$( this ).removeClass( 'wpm-playlist-500' );
					$( this ).addClass( 'wpm-playlist-380' );
				} else {
					$( this ).removeClass( 'wpm-playlist-500' );
					$( this ).removeClass( 'wpm-playlist-380' );
				}
			} );
		}
	};

}( jQuery );


;( function( $ ) {

	'use strict';

	$( document ).ready( function() {
		WPM.init();
	} );
} )( jQuery );