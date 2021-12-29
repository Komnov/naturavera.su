<div class="wd-opacity wd-<?php echo $way2_options->prefix; ?>-opacity"></div>	 <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Roboto:400,500,300,300italic" rel="stylesheet">

<div class="wd-deactivate-popup wd-<?php echo $way2_options->prefix; ?>-deactivate-popup">
	<div class="wd-deactivate-popup-opacity wd-deactivate-popup-opacity-<?php echo $way2_options->prefix; ?>">
	    
	     

<img src="spinner.gif" class="wd-img-loader" ></div>

<form method="post" id="<?php echo $way2_options->prefix; ?>_deactivate_form">
	    
	  
	    
		<div class="wd-deactivate-popup-header"><div class="welcomenotice"></div>

		    
			<?php _e( "HEY! <a href='https://wordpress.org/support/view/plugin-reviews/regenerate-thumbnails-and-delete-unused?rate=5#postform' target='_blank'>RATE US</a> / <a href='https://wordpress.org/support/plugin/regenerate-thumbnails-and-delete-unused/' target='_blank' >REPORT BUG</a>", $way2_options->prefix ); ?> :
		</div>

		<div class="wd-deactivate-popup-body">
			<?php foreach( $deactivate_reasons as $deactivate_reason_slug => $deactivate_reason ) { ?>
				<div class="wd-<?php echo $way2_options->prefix; ?>-reasons">
					<input type="radio" value="<?php echo $deactivate_reason["id"];?>" id="<?php echo $way2_options->prefix . "-" .$deactivate_reason["id"]; ?>" name="<?php echo $way2_options->prefix; ?>_reasons" >
					<label for="<?php echo $way2_options->prefix . "-" . $deactivate_reason["id"]; ?>"><?php echo $deactivate_reason["text"];?></label>
				</div>
			<?php } ?>
            
           
			<div class="<?php echo $way2_options->prefix; ?>_additional_details_wrap"></div>
            
            <!--     <i><b>You continue to get 1000 free credit/month.Unused credit will be forwarded to next month but it will expire if not uploaded 1 image atleast in 30days.</b></i> -->

		</div>
		<div class="wd-btns">
        
			<a href="<?php echo $deactivate_url; ?>" data-val="1" class="button button-secondary button-close wd-<?php echo $way2_options->prefix; ?>-deactivate" id="wd-<?php echo $way2_options->prefix; ?>-deactivate"><?php _e( "Deactivate" , $way2_options->prefix ); ?></a>
			<a href="<?php echo $deactivate_url; ?>" data-val="2" class="button button-secondary button-close wd-<?php echo $way2_options->prefix; ?>-deactivate" id="wd-<?php echo $way2_options->prefix; ?>-submit-and-deactivate" style="display:none;"><?php _e( "Submit and deactivate" , $way2_options->prefix ); ?></a>
			<a href="<?php echo admin_url( 'plugins.php' ); ?>" class="button button-primary  wd-<?php echo $way2_options->prefix; ?>-cancel"><?php _e( "Cancel" , $way2_options->prefix ); ?></a>
		</div>
		<input type="hidden" name="<?php echo $way2_options->prefix . "_submit_and_deactivate"; ?>" value="" >
		<?php wp_nonce_field( $way2_options->prefix . '_save_form', $way2_options->prefix . '_save_form_fild'); ?>
      
	</form>
	
        

</div>