/**
 * Wolf Playilst Manager admin functions
 */
 /* jshint -W062 */
/* global WPMAdminParams */

var WPMAdmin = function ( $ ) {

	'use strict';

	return {

		/**
		 * Initialize all functions
		 */
		init : function () {
			this.manageFiles();
			this.sortable();
			this.removeTrack();
			this.toggleTrackContent();
			this.setTrackArtwork();
			this.removeArtwork();
			this.updateTrackMetas();
			this.selectShortcode();
		},

		/**
		 * Manage tracks using the WP media manager
		 */
		manageFiles : function () {
			/**
			 * Get files from media manager
			 */
			$( '#wpm_tracklist' ).on( 'click', '.wpm-upload', function( event ) {

				var frame;
				
				event.preventDefault();
				/* if there is a frame created, use it */
				if ( frame ) {
					frame.open();
					return;
				}
				/* get the hidden input ID from the button's inputid data attribute */
				var $panel = $( this ).parents( '.wpm-panel-body' ),
					$tracklist = $panel.find( '#wpm-tracklist' ),
					$input = $panel.find( 'input#file_ids' );
				
				/* open the wp.media frame with our localised title */
				frame = wp.media.frames.file_frame = wp.media( {
					title : WPMAdminParams.chooseAudio,
					library : { type : 'audio' },
					multiple : 'add',
					button : { text : WPMAdminParams.chooseAudio }
				} );

				frame.on( 'close', function() {
					/* get the selection object */
					var selection = frame.state().get( 'selection' ),
						/* array variable to hold new image IDs */
						length = selection.length,
						files = selection.models,
						data = {},
						ids = [], i;

					for( i = 0; i < length; i++ ) {
						ids.push( files[ i ].id );
					}

					// same list as before so don't do anything
					if ( $input.val() === $.trim( ids ) ) {
						return;
					}

					$input.val( ids ); // update hidden input

					data = {
						action : 'wpm_ajax_get_track_markup',
						tracklistIDs : ids
					};

					// Update markup
					$.post( WPMAdminParams.adminUrl, data, function( response ) {
						if ( response ) {
							// console.log( response );
							$tracklist.html( response ).sortable( 'refresh' );
						}
					} );
				} );

				// function WPMPushFile( array, param ) {
				// 	array.push( param );
				// }

				/* opens the wp.media frame and selects the appropriate files */
				frame.on( 'open', function() {
					
					/* get the image IDs from the hidden input */
					var fileIDs = $input.val().split( ',' ),
						/* get the selection object for the wp.media frame */
						selection = frame.state().get( 'selection' );
					
					if ( fileIDs && fileIDs.length ) {
						
						/* add each image to the selection */
						$.each( fileIDs, function( idx, val ) {
							var attachment;
							
							if ( $.isNumeric( val ) ) {
								attachment = wp.media.attachment( val );
							}

							if ( attachment ) {
								attachment.fetch();
								selection.add( attachment ? [ attachment ] : [] );
							}
						} );
					}
				} );
				frame.open();
			} );
		},

		/**
		 * Maje the tracks sortable and update the track list input when the order change
		 */
		sortable : function () {
			/**
		 	 * make sure the previews are sortable 
		 	 */
		 	$( '#wpm-tracklist' ).sortable( {
				update : function() {
					$( 'input#file_ids' ).val( $( this ).sortable( 'toArray', { attribute: 'data-track-id' } ) );
				},
				helper: 'clone',
				items: '.wpm-track-item'
			} );
		},

		/**
		 * Remove track from list
		 */
		removeTrack : function () {

			$( '#wpm-tracklist' ).on( 'click', '.wpm-track-remove', function( event ) {

		 	 	event.preventDefault();

		 	 	if ( window.confirm( WPMAdminParams.removeConfirmText ) ) {

					var $this = $( this ),
						$trackContainer = $this.parents( '.wpm-track-item' ),
						input = $( 'input#file_ids' ),
						trackID,
						listArray = [],
						list;

					$trackContainer.remove();

					$( '.wpm-track-item' ).each( function() {
						trackID = $( this ).data( 'track-id' );
						listArray.push( trackID );
					} );

					list = $.trim( listArray.join( ',' ) );

					input.val( list.toString() ); // update ids list in hidden input

					$( '#wpm-tracklist' ).sortable(); // for some reason it fixes a bug of playlist not playing

				}
			} );
		},

		/**
		 * Toggle track content
		 */
		toggleTrackContent : function () {
			$( '#wpm-tracklist' ).on( 'click', '.wpm-toggle', function( event ) {

		 	 	event.preventDefault();

				var $container = $( this ).parents( '.wpm-track-container' ),
					$content = $container.find( '.wpm-track-content' );

				if ( $container.hasClass( 'wpm-toggle-open' ) ) {
					$content.slideUp( 'fast' );
					$container.removeClass( 'wpm-toggle-open' );
				
				} else {
					$container.addClass( 'wpm-toggle-open' );
					$content.slideDown( 'fast' );
				}
			} );
		},

		/**
		 * Attach image artwork to track
		 */
		setTrackArtwork : function () {

		 	$( '#wpm_tracklist' ).on( 'click', '.wpm-track-artwork', function() {
				var $el = $( this ).parent(),
					selection,
					attachment,
					attachmentId,
					currentArtworkId,
					trackId,
					$removeArtworkButton = $el.find( '.wpm-remove-artwork' ),
					$loader = $el.parents( '.wpm-track-content' ).find( '.wpm-track-loader' ),
					data = {},
					frame = wp.media.frames.file_frame = wp.media({
						title : WPMAdminParams.chooseImage,
						library : { type : 'image'},
						multiple : false
					} )
				.on( 'select', function(){
					$loader.fadeIn();
					selection = frame.state().get('selection');
					attachment = selection.first().toJSON();
					currentArtworkId = parseInt( $( 'input', $el ).val(), 10 );
					trackId = $( 'input', $el ).data( 'track-id' );
					attachmentId = attachment.id;
					$( 'input', $el ).val( attachmentId );
					$( '.wpm-track-artwork', $el ).css( { 'background-image' : 'url(' + attachment.url + ')' } ).addClass( 'wpm-track-has-artwork' );

					data = {
						action : 'wpm_ajax_save_track_artwork',
						attachmentId : attachmentId, // track attachment
						trackId : trackId
					};

					// same artwork selected
					if ( currentArtworkId === attachmentId ) {
						$loader.fadeOut();
					} else {
						// AJAX save
						$.post( WPMAdminParams.adminUrl, data, function( response ) {
							// update post meta

							if ( 'OK' === response ) {
								$loader.fadeOut();
								$removeArtworkButton.fadeIn();
							}
						} );
					}
				} )
				.open();
			} );
		},

		/**
		 * Remove artwork attached to track
		 */
		removeArtwork : function () {
			
			$( '#wpm_tracklist' ).on( 'click', '.wpm-remove-artwork', function( event ) {

				event.preventDefault();

				var $this = $( this ),
					$removeArtworkButton = $( this ),
					$loader = $this.parents( '.wpm-track-content' ).find( '.wpm-track-loader' ),
					$container = $this.parents( '.wpm-track-column-artwork' ),
					$artworkHolder = $container.find( '.wpm-track-artwork' ),
					$input = $container.find( 'input' ),
					trackId = $( this ).data( 'track-id' ),
					data = {
						action : 'wpm_ajax_delete_track_artwork',
						trackId : trackId
					};

				$loader.fadeIn();

				// AJAX save
				$.post( WPMAdminParams.adminUrl, data, function( response ) {
					// delete post meta
					if ( 'OK' === response ) {
						$artworkHolder.removeClass( 'wpm-track-has-artwork' ).css( { 'background-image' : '' } );
						$input.val( '' );
						$loader.fadeOut();
						$removeArtworkButton.fadeOut();
					}
				} );
			} );
		},

		/**
		 * Update track meta
		 */
		updateTrackMetas : function() {
			this.updateMeta( 'title' );
			this.updateMeta( 'artist' );
			this.updateMeta( 'length' );
			this.updateMeta( 'itunes_url' );
			this.updateMeta( 'amazon_url' );
			this.updateMeta( 'googleplay_url' );
			this.updateMeta( 'buy_url' );
			this.updateMeta( 'wc_product_id' );
			this.updateMeta( 'free_dl' );
		},

		/**
		 * Update track meta while input is changing
		 */
		updateMeta : function( meta ) {
			var timer = null,
				time = 1000,
				action = 'keyup';

			if ( 'wc_product_id' === meta ) {
				
				action = 'change';
				time = 10;
		
			} else if ( 'free_dl' === meta ) {
				action = 'change';
				time = 10;
			}

			$( '#wpm_tracklist' ).on( action, '.wpm-track-' + meta, function() {

				clearTimeout( timer );

				var $this = $( this ),
					val = $this.val(),
					$container = $this.parents( '.wpm-track-item' ),
					$loader = $container.find( '.wpm-track-loader' ),
					trackId = $container.data( 'track-id' ),
					data;
					
				if ( 'free_dl' === meta ) {
					if ( ! $this.is(':checked') ) {
						val = 'no';
					} else {
						val = 'yes';
					}
				}

				data = {
					action : 'wpm_ajax_update_track_' + meta,
					newVal : val,
					trackId : trackId
				};

				console.log( data );

				// update meta
				timer = setTimeout( function() {
					$loader.fadeIn();
					$.post( WPMAdminParams.adminUrl, data, function( response ) {
						if ( response ) {
							console.log( response );
							$loader.fadeOut();
						}
					} );
				}, 1000 );
			} );
		},

		/**
		 * Select all shortcode on focus
		 */
		selectShortcode : function () {

			$( 'input#wpm-playlist-shortcode' ).on( 'focus', function() {
				$( this ).select();
			} );
		}
	};

}( jQuery );


;( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		WPMAdmin.init();
	} );


} )( jQuery );