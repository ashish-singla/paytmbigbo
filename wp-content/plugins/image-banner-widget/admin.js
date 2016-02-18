/** Image Banner Widget Admin Scripts */

jQuery(document).ready(function()
{
  
	jQuery(".ibw-overlay").hide();
	jQuery(".ibw-thumb").hover(function(){
	jQuery(this).find(".ibw-overlay").fadeIn();
	}, function(){
	jQuery(this).find(".ibw-overlay").fadeOut();
	});

	jQuery("div[id*='_banner-']").addClass('image-banner-widget');
  
});
