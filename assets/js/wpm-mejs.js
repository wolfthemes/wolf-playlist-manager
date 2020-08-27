/*global jQuery:false, MediaElementPlayer:false */

( function( window, $, undefined )  {
	'use strict';

	$.extend( MediaElementPlayer.prototype, {
		buildcuebarplayertoggle: function( player, controls, layers, media ) {
			var state = player.options.cuebarInitialState,
				history = player.cueHistory || null,
				selectors = player.options.cueSelectors;

			if ( history ) {
				state = history.get( 'visibility' ) || state;
			}

			$( selectors.playlist ).toggleClass( 'is-closed', ( 'closed' === state ) );

			$( '<div class="mejs-button mejs-toggle-player-button mejs-toggle-player">' +
				'<button type="button" aria-controls="' + player.id + '" title="' + WPMParams.l10n.togglePlayer + '"></button>' +
				'</div>' )
			.appendTo( player.controls )
			.on( 'click', function() {
				state = 'open' === state ? 'closed' : 'open';
				$( this ).closest( selectors.playlist ).toggleClass( 'is-closed', ( 'closed' === state ) );

				if ( history ) {
					history.set( 'visibility', state );
				}
			} );
		}
	} );

	$.extend( MediaElementPlayer.prototype, {

		buildcuebackground: function( player, controls, layers, media ) {

			var $background = player.container.append( $( '<div />', {
				'class': 'wpm-playlist-background',
				'style' : 'background-image:url(' + player.options.cueBackgroundUrl + ');'
			} ) ).find( '.wpm-playlist-background' );

			//// Set each track to use the background image as artwork if it doesn't have artwork.
			//$.each( player.options.cuePlaylistTracks, function( index, track ) {
			//	player.options.cuePlaylistTracks[ index ].thumb.src = track.thumb.src || player.options.cueBackgroundUrl;
			//} );
			//player.$node.on( 'setTrack.cue', function( e, track, player ) {
			//	track.thumb = track.thumb || {};

			//	if ( '' === player.options.cueBackgroundUrl ) {
			//		$background.css( { 'background-image' : 'url(' + track.thumb.src + ')' } );
			//	}
			//} ).trigger( 'backgroundCreate.cue', player );
		}
	} );

} )( this, jQuery );