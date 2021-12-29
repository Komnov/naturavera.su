!function( $, undefined ) {
	"use strict";
	/**
	 * Class for importing sections (shortcodes) into the WPBakery Page Builder
	 * @class US_VC_PasteSection
	 */
	let US_VC_PasteSection = function() {
		// Variables
		this.$window = $( '.us-paste-section-window:first' );
		this.$initButton = $( '#us_vc_paste_section_button' );
		this.$insertButton = $( '.us-paste-section-window-btn', this.$window );
		this.$insertButtonPreload = this.$insertButton.find( '> span' );
		this.$input = this.$window.find( 'textarea' );
		this.$errMessage = this.$window.find( '.vc_description' );
		this.errors = {};
		// Event Assignments
		this.$initButton.on( 'click', this._events.showWindow.bind( this ) );
		this.$insertButton.on( 'click', this._events.addSections.bind( this ) );
		this.$window.on( 'click', '.us-paste-section-window-closer', this._events.hideWindow.bind( this ) );
		// Load errors
		if ( this.$window.length ) {
			this.data = this.$window[ 0 ].onclick() || {};
		}
	};
	// Export API
	US_VC_PasteSection.prototype = {
		// Event handlers
		_events: {
			/**
			 * Show window
			 */
			showWindow: function() {
				this._hideError();
				this.$window.show();
				this.$input.focus();
			},
			/**
			 * Hide window
			 */
			hideWindow: function() {
				this.$window.hide();
				this.$input.val( '' );
				if ( this.$insertButtonPreload.hasClass( 'g-preloader' ) ) {
					this.$input.prop( 'disabled', false );
					this.$insertButtonPreload.removeClass( 'g-preloader' );
				}
			},
			/**
			 * Add section
			 */
			addSections: function() {
				this.value = $.trim( this.$input.val() );
				if ( !this._isValid() ) {
					return;
				}
				this.applyFilterToValue.call( this );
				$.each( vc.storage.parseContent( {}, this.value ), function( _, model ) {
					if ( model && model.hasOwnProperty( 'shortcode' ) ) {
						// Insert sections
						vc.shortcodes.create( model );
						this._events.hideWindow.call( this );
					}
				}.bind( this ) );
			},
		},
		/**
		 * Apply filters to shortcodes
		 * @return void
		 */
		applyFilterToValue: function() {
			// Search and replace use:placeholder
			this.value = this.value.replace( /use:placeholder/g, this.data.placeholder || '' );

			// Checking the post_type parameter
			this.value = this.value.replace( /\s?post_type="(.*?)"/g, function( match, post_type ) {
				if ( this.data.grid_post_types.indexOf( post_type ) === - 1 ) {
					// Default post_type
					return ' post_type="post"';
				}
				return match;
			}.bind( this ) );

			// Removing [us_post_content..] if post type is not us_page_block
			if ( this.data.post_type !== 'us_page_block' ) {
				this.value = this.value.replace( /(\[us_post_content.*?])/g, '' );
			}

			// Import data for grid layout
			this.value = this.value.replace( /(grid_layout_data="([^"]+)")/g, function( data ) {
				var matches = data.match( /grid_layout_data="([^"]+)/i );
				return 'items_layout="' + this.importShortcode.call( this, 'us_grid_layout', matches[ 1 ] || '' ) + '"';
			}.bind( this ) );
		},
		/**
		 * Importing Shortcode Data
		 *
		 * @param string postType
		 * @param string post_content
		 * @return string
		 */
		importShortcode: function( postType, post_content ) {
			this.$input.prop( 'disabled', true );
			this.$insertButtonPreload.addClass( 'g-preloader' );
			var output = '';
			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				async: false,
				data: {
					_nonce: this.$window.data( 'nonce' ),
					action: 'us_import_shortcode_data',
					post_content: post_content,
					post_type: postType,
				},
				dataType: 'json',
				success: function( res ) {
					if ( res.success || res.hasOwnProperty( 'data' ) ) {
						output = res.data;
					}
				}.bind( this ),
				error: console.error
			} );
			return output;
		},
		/**
		 * Validate value
		 * @return {boolean} True if valid, False otherwise.
		 */
		_isValid: function() {
			// Add notice if the text is empty
			if ( this.value === '' ) {
				this._showError( this.data.errors.empty );
				return false;
			}
			// Add a notification if the text does not contain the shortcode [vc_row ... [/vc_row]
			if ( !/\[vc_row([\s\S]*)\/vc_row\]/gim.test( this.value ) ) {
				this._showError( this.data.errors.not_valid );
				return false;
			}
			this._hideError();
			return true;
		},
		/**
		 * Show error message.
		 * @param {string} message Error text
		 */
		_showError: function( message ) {
			this.$errMessage
				.text( message )
				.show();
		},
		/**
		 * Hide error message.
		 */
		_hideError: function() {
			this.$errMessage
				.text( '' )
				.hide();
		}
	};

	// Init class
	$().ready( function() {
		new US_VC_PasteSection;
	} );
}( window.jQuery );
