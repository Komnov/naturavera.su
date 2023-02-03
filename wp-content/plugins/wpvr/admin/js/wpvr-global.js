(function( $ ) {
	'use strict';
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

  $(document).ready(function(){
		$('.setup-wizard-carousel').owlCarousel({
            loop:false,
            items:1,
            dots: false,
            mouseDrag: false,
            touchDrag: false,
            navText: ['Previous','Next'],
        });

        $(".choose-tour input[type='radio']").on('click', function(){
            var val = $(this).val();
            $('#'+val).show();
            $('#'+val).siblings().hide();
        });

				function wpvr_bf_notice_dismiss(event) {
					event.preventDefault();
					console.log("test");
					var ajaxurl = wpvr_global_obj.ajaxurl;
					var that = $(this);
					$.ajax({
							type : "post",
							dataType : "json",
							url : ajaxurl,
							data : { action: "wpvr_black_friday_offer_notice_dismiss" },
							success: function(response) {
									if(response.success) {
											that.fadeOut('slow');
											console.log(response)
									}
							}
					})
				}
				$(document).on('click', '.wpvr-black-friday-offer notice-dismiss', wpvr_bf_notice_dismiss);
	});

	$(document).on("click","#wpvr-dismissible",function(e) {

		e.preventDefault();
		var ajaxurl = wpvr_global_obj.ajaxurl;
			jQuery.ajax({
					type:    "POST",
					url:     ajaxurl,
					data: {
						action: "wpvr_notice",
					},
					success: function( response ){
						$('#wpvr-warning').hide();
					}
		});
	});

	function wpvr_bf_notice_dismiss(event) {
		event.preventDefault();
		console.log("test");
		var ajaxurl = wpvr_global_obj.ajaxurl;
		var that = $(this);
		$.ajax({
				type : "post",
				dataType : "json",
				url : ajaxurl,
				data : { action: "wpvr_black_friday_offer_notice_dismiss" },
				success: function(response) {
						if(response.success) {
								that.fadeOut('slow');
								console.log(response)
						}
				}
		})
	}
	$(document).on('click', '.wpvr-black-friday-offer .notice-dismiss', wpvr_bf_notice_dismiss);

	
    // video setup wizard__video
    $( document ).on( 'click', '.box-video', function() {
        $('iframe',this)[0].src += "?autoplay=1";
        $(this).addClass('open');
    });
	$(document).on('click','.wpvr-halloween-notice .notice-dismiss',function (){
		var ajaxurl = wpvr_global_obj.ajaxurl;
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: {
				action: "wpvr_notice",
			},
		});
	})

})( jQuery );
