/**
 * Elementor Dynamic Popup - Frontend Script.
 *
 * Handles opening/closing the dynamic popup modal.
 */
( function( $ ) {
	'use strict';

	const EDP = {
		modal: null,
		content: null,
		scrollPosition: 0,
		$body: null,

		init: function() {
			this.$body = $( document.body );
			$( document ).on( 'click', '.edp-trigger', this.onTriggerClick.bind( this ) );
			$( document ).on( 'keydown', this.onKeydown.bind( this ) );
		},

		onTriggerClick: function( e ) {
			e.preventDefault();
			const $trigger = $( e.currentTarget );
			const $wrapper = $trigger.closest( '.edp-trigger-wrapper' );
			const $content = $wrapper.find( '.edp-popup-content' );

			if ( ! $content.length ) {
				return;
			}

			this.open( $content );
		},

		open: function( $content ) {
			// Prevent double-open.
			if ( this.modal && this.modal.hasClass( 'edp-visible' ) ) {
				return;
			}

			this.content = $content;
			this.scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

			this.createModal();
			var $inner = $content.find( '.edp-popup-inner' );
			var $toAppend = $inner.length ? $inner.clone() : $content.contents().clone();
			this.modal.find( '.edp-popup-body' ).append( $toAppend );
			this.modal.addClass( 'edp-visible' );
			this.$body.addClass( 'edp-modal-open' ).css( '--edp-scroll-top', -this.scrollPosition + 'px' );

			// Focus trap and accessibility.
			this.modal.attr( 'aria-hidden', 'false' );
			this.modal.find( '.edp-popup-close' ).focus();

			// Init Elementor frontend for dynamically added content (e.g. widgets).
			if ( typeof elementorFrontend !== 'undefined' ) {
				elementorFrontend.elementsHandler.runReadyTrigger( this.modal[ 0 ] );
			}
		},

		close: function() {
			if ( ! this.modal || ! this.modal.hasClass( 'edp-visible' ) ) {
				return;
			}

			this.modal.removeClass( 'edp-visible' );
			this.$body.removeClass( 'edp-modal-open' ).css( '--edp-scroll-top', '' );

			setTimeout( () => {
				// Restore scroll position.
				window.scrollTo( 0, this.scrollPosition );
				this.destroyModal();
			}, 300 );
		},

		createModal: function() {
			this.destroyModal();

			const $modal = $( `
				<div class="edp-modal" role="dialog" aria-modal="true" aria-labelledby="edp-popup-title" aria-hidden="true">
					<div class="edp-overlay"></div>
					<div class="edp-popup-container">
						<div class="edp-popup">
							<button type="button" class="edp-popup-close" aria-label="${ this.getCloseLabel() }">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true">
									<path d="M18.3 5.71a1 1 0 0 0-1.41 0L12 10.59 7.11 5.7A1 1 0 0 0 5.7 7.11L10.59 12 5.7 16.89a1 1 0 1 0 1.41 1.41L12 13.41l4.89 4.88a1 1 0 0 0 1.41-1.41L13.41 12l4.89-4.89z"/>
								</svg>
							</button>
							<div class="edp-popup-body"></div>
						</div>
					</div>
				</div>
			` );

			$modal.find( '.edp-overlay, .edp-popup-close' ).on( 'click', ( e ) => {
				e.preventDefault();
				this.close();
			} );

			// Don't close when clicking inside popup content.
			$modal.find( '.edp-popup' ).on( 'click', ( e ) => e.stopPropagation() );
			$modal.find( '.edp-overlay' ).on( 'click', () => this.close() );

			this.$body.append( $modal );
			this.modal = $modal;
		},

		destroyModal: function() {
			if ( this.modal ) {
				this.modal.remove();
				this.modal = null;
			}
			this.content = null;
		},

		onKeydown: function( e ) {
			if ( e.key !== 'Escape' ) {
				return;
			}
			if ( this.modal && this.modal.hasClass( 'edp-visible' ) ) {
				e.preventDefault();
				this.close();
			}
		},

		getCloseLabel: function() {
			return typeof elementorDynamicPopup !== 'undefined' && elementorDynamicPopup.i18n
				? elementorDynamicPopup.i18n.close
				: 'Close';
		}
	};

	$( function() {
		EDP.init();
	} );

	// Expose for programmatic use.
	window.EDP = EDP;

} )( jQuery );
