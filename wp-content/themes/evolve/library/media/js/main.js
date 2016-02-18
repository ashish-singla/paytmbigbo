//
//
// 
// Responsive Menu
//
//
//  

	// Create the dropdown base
	jQuery('<select />').appendTo('.primary-menu .nav-holder');

	// Create default option 'Menu'
	jQuery('<option />', {
		'selected': 'selected',
		'value'   : '',
		'text'    : '<span class="glyphicon glyphicon-align-justify"></span>'
	}).appendTo('.primary-menu .nav-holder select');

	// Populate dropdown with menu items
	jQuery('.primary-menu .nav-holder a').each(function() {
		var el = jQuery(this);

		if(jQuery(el).parents('.sub-menu .sub-menu').length >= 1) {
			jQuery('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : '-- ' + el.text()
			}).appendTo('.primary-menu .nav-holder select');
		}
		else if(jQuery(el).parents('.sub-menu').length >= 1) {
			jQuery('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : '- ' + el.text()
			}).appendTo('.primary-menu .nav-holder select');
		}
		else {
			jQuery('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : el.text()
			}).appendTo('.primary-menu .nav-holder select');
		}
	});

	jQuery('.primary-menu .nav-holder select').ddslick({
		width: '100%',
	    onSelected: function(selectedData){
	    	if(selectedData.selectedData.value != '') {
	        	window.location = selectedData.selectedData.value;
	    	}
	    }   
	}); 
  
//
//
// 
// Responsive Images
//
//
//

var $addmenueffect = jQuery.noConflict();
$addmenueffect("#primary img").addClass("img-responsive");  


//
//
// 
// Carousel Slider Arrows
//
//
//

var $jx = jQuery.noConflict(); 
  $jx(document).ready(function() {  
    $jx('div#slide_holder').hover(function() {
        $jx(this).find('.arrow span').stop(true, true).fadeIn(200).show(10);
    }, function () {
        $jx(this).find('.arrow span').stop(true, true).fadeOut(200).hide(10);
    });
}); 


//
//
// 
// Tipsy
//
//
//


var $j = jQuery.noConflict();
  $j(document).ready(function(){  
    $j('.tipsytext').tipsy({gravity:'n',fade:true,offset:0,opacity:1});
   });