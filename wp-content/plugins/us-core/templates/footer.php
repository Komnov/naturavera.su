<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Outputs page's Footer
 */

$us_layout = US_Layout::instance();
?>
</div>
<?php
global $us_iframe, $us_hide_footer;
if ( ( ! isset( $us_iframe ) OR ! $us_iframe ) AND ( ! isset( $us_hide_footer ) OR ! $us_hide_footer ) ) {
	do_action( 'us_before_footer' );
	?>
	<footer id="page-footer" class="l-footer"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemscope itemtype="https://schema.org/WPFooter"' : ''; ?>>
		<?php
		$footer_id = us_get_page_area_id( 'footer' );

		// Output content of Page Block (us_page_block) post
		$footer_content = '';
		if ( $footer_id != '' ) {

			$footer = get_post( (int) $footer_id );

			us_open_wp_query_context();
			if ( $footer ) {
				$translated_footer_id = apply_filters( 'wpml_object_id', $footer->ID, 'us_page_block', TRUE );
				if ( $translated_footer_id != $footer->ID ) {
					$footer = get_post( $translated_footer_id );
				}

				us_add_to_page_block_ids( $translated_footer_id );

				us_add_page_shortcodes_custom_css( $translated_footer_id );

				$footer_content = $footer->post_content;
			}
			us_close_wp_query_context();

			// Apply filters to Page Block content and echoing it ouside of us_open_wp_query_context,
			// so all WP widgets (like WP Nav Menu) would work as they should
			echo apply_filters( 'us_page_block_the_content', $footer_content );

			if ( $footer ) {
				us_remove_from_page_block_ids();
			}

		}
		?>
	</footer>
	<?php
	do_action( 'us_after_footer' );
}
if ( us_get_option( 'back_to_top', 1 ) ) {
	?>
	<a class="w-toplink pos_<?php echo us_get_option( 'back_to_top_pos', 'right' ) ?>" href="#" title="<?php _e( 'Back to top', 'us' ); ?>" aria-hidden="true"></a>
	<?php
}
if ( $us_layout->header_show != 'never' ) {
	?>
	<a class="w-header-show" href="javascript:void(0);"><span><?php echo us_translate( 'Menu' ) ?></span></a>
	<div class="w-header-overlay"></div>
	<?php
}
?>
<script>
	// Store some global theme options used in JS
	if ( window.$us === undefined ) {
		window.$us = {};
	}
	$us.canvasOptions = ( $us.canvasOptions || {} );
	$us.canvasOptions.disableEffectsWidth = <?php echo intval( us_get_option( 'disable_effects_width', 900 ) ) ?>;
	$us.canvasOptions.columnsStackingWidth = <?php echo intval( us_get_option( 'columns_stacking_width', 768 ) ) ?>;
	$us.canvasOptions.responsive = <?php echo us_get_option( 'responsive_layout', TRUE ) ? 'true' : 'false' ?>;
	$us.canvasOptions.backToTopDisplay = <?php echo intval( us_get_option( 'back_to_top_display', 100 ) ) ?>;
	$us.canvasOptions.scrollDuration = <?php echo intval( us_get_option( 'smooth_scroll_duration', 1000 ) ) ?>;

	$us.langOptions = ( $us.langOptions || {} );
	$us.langOptions.magnificPopup = ( $us.langOptions.magnificPopup || {} );
	$us.langOptions.magnificPopup.tPrev = '<?php _e( 'Previous (Left arrow key)', 'us' ); ?>';
	$us.langOptions.magnificPopup.tNext = '<?php _e( 'Next (Right arrow key)', 'us' ); ?>';
	$us.langOptions.magnificPopup.tCounter = '<?php _ex( '%curr% of %total%', 'Example: 3 of 12', 'us' ); ?>';

	$us.navOptions = ( $us.navOptions || {} );
	$us.navOptions.mobileWidth = <?php echo intval( us_get_option( 'menu_mobile_width', 900 ) ) ?>;
	$us.navOptions.togglable = <?php echo us_get_option( 'menu_togglable_type', TRUE ) ? 'true' : 'false' ?>;
	$us.ajaxLoadJs = <?php echo us_get_option( 'ajax_load_js', 0 ) ? 'true' : 'false' ?>;
	$us.templateDirectoryUri = '<?php global $us_template_directory_uri; echo $us_template_directory_uri; ?>';
</script>
<?php wp_footer(); ?>


<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(61286473, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/61286473" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-161815911-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-161815911-1');
</script>

<script>
	jQuery(document).on("click", '#page-header a[href^="tel:"]', function() {
		ym(61286473, 'reachGoal', 'click_phone_head');
		gtag('event', 'click', { 'event_category': 'phone_click', 'event_action': 'click_phone_head', });
	});
	jQuery(document).on("click", '.w-contacts-item.for_phone', function() {
		ym(61286473, 'reachGoal', 'click_phone_footer');
		gtag('event', 'click', { 'event_category': 'phone_click', 'event_action': 'click_phone_footer', });
	});
	jQuery(document).on("click", 'div.vc_custom_1575622588359 a[href^="tel:"]', function() {
		ym(61286473, 'reachGoal', 'click_phone_contakty');
		gtag('event', 'click', { 'event_category': 'phone_click', 'event_action': 'click_phone_contakty', });
	});
	jQuery(document).on("click", '.single_add_to_cart_button', function() {
		ym(61286473, 'reachGoal', 'click_korzina_tovar');
		gtag('event', 'click', { 'event_category': 'korzina', 'event_action': 'click_korzina_tovar', });
	});
	jQuery(document).on("click", '.add_to_cart_button', function() {
		ym(61286473, 'reachGoal', 'click_vibrat-preview');
		gtag('event', 'click', { 'event_category': 'vibrat', 'event_action': 'click_vibrat-preview', });
	});
	jQuery(document).on("click", '.checkout.wc-forward', function() {
		ym(61286473, 'reachGoal', 'click_zakaz');
		gtag('event', 'click', { 'event_category': 'zakaz', 'event_action': 'click_zakaz', });
	});
	jQuery(document).on("click", '.place-order button[type=submit]', function() {
		ym(61286473, 'reachGoal', 'otpravka_formy');
		gtag('event', 'click', { 'event_category': 'lead', 'event_action': 'otpravka_formy', });
	});
	jQuery(document).on("click", '#rev_slider_5_1 a.rev-btn', function() {
		ym(61286473, 'reachGoal', 'venyoo-lead-sent');
		gtag('event', 'click', { 'event_category': 'zakaz', 'event_action': 'venyoo-lead-sent', });
	});
</script>




</body>
</html>
