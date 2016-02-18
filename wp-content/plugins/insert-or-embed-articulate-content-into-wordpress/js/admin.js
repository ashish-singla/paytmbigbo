/*
Using WordPress Media Uploader System with plugin settings
Author: oneTarek
Author URI: http://onetarek.com
Source: http://onetarek.com/wordpress/how-to-use-wordpress-media-uploader-in-plugin-or-theme-admin-page/
*/
jQuery(document).ready(function() {
 
	// File uploader
	var file_frame;
	var CURRENT_FORM_FIELD;
	jQuery('.img_upload_button').live('click', function( event ){
 
		event.preventDefault();
		CURRENT_FORM_FIELD=jQuery(this).prev('input');
	 
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: 'Select an Image',
		  button: {
			text: 'Use this Image',
		  },
		  multiple: false  // only allow the one file to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  // We set multiple to false so only get one image from the uploader
		  attachment = file_frame.state().get('selection').first().toJSON();
		  jQuery(CURRENT_FORM_FIELD).val(attachment.url);
		  var imgbox=jQuery(CURRENT_FORM_FIELD).prev('div');
		  jQuery(imgbox).html('<img src="'+jQuery(CURRENT_FORM_FIELD).val()+'"  />');
		});
		// Finally, open the modal
		file_frame.open();
	});
	
	jQuery(".close_button").live('click', function (){
			jQuery(this).parent().remove();
		
		 });
 
});

function add_new_button()
{
	
	jQuery("#button_area").append('<div class="button_box" title="Delete"><div class="close_button"></div><div class="imgbox"></div><input type="text" size="20" name="buttons[]" class="image_source" value="">&nbsp;<input class="img_upload_button button" type="button" value="Select Image"></div>');	
	
}


