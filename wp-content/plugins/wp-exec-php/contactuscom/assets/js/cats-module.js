jQuery('#customer-categories .parent-title').on('click', function(){

	jQuery('#customer-categories .parent-category').hide(); // hide parent categories when one is selected.
	
	jQuery(this).parent('li').addClass('cat-selected').show();
	jQuery(this).next('.sub-category').show();
	jQuery("#Selected-Category").text( jQuery(this).attr('data-maincat') );
	
	// assign the category name
	jQuery('#CU_category').val( jQuery(this).attr('data-maincat') );
	jQuery(this).colorbox.resize();
	
	if(jQuery('#back').length == 1){
	
	}else{
		jQuery(this).after("<div class='btn' id='back' onclick='back_cat()'>X Clear Category</div>");	
	}
		jQuery("#category-message").text('Please choose a sub-category that best describes you');
});

function back_cat(){
	jQuery(this).colorbox.resize();
	
	jQuery("#category-message").text('Select the Category of Your Website:');
	jQuery("#open-intestes").addClass("unactive");
	jQuery("#save").addClass("unactive");
	jQuery("#Selected-Category").text('');
	jQuery("#Sub-Category").text('');
	jQuery("#back").remove();
	jQuery("#customer-categories li").removeClass("Selected-Subcategory");
	jQuery("#customer-categories li").removeClass("cat-selected");
	jQuery(".sub-category").hide();
	jQuery(".parent-category").show();
	
	// clear inputs
	jQuery('#CU_category').val('');
	jQuery('#CU_subcategory').val('');

}

jQuery('#customer-categories .parent-category .sub-category li').on('click', function(){
	
	jQuery(this).colorbox.resize();
	jQuery('#CU_subcategory').val( jQuery(this).attr('data-subcat') );
	jQuery("#open-intestes").removeClass("unactive");
	jQuery('#customer-categories .parent-category .sub-category li').removeClass('Selected-Subcategory');
	jQuery(this).addClass('Selected-Subcategory');
	jQuery("#Sub-Category").text( jQuery(this).attr('data-subcat') );
	
});

/* User Interests */
jQuery('#user-interests li').live('click', function(){
	
	jQuery(this).colorbox.resize();
	
	// if it is selected , deselect
	if( jQuery(this).hasClass('Selected-Subcategory') ){
		
		jQuery(this).removeClass("Selected-Subcategory");
		
		var actual = jQuery(this).attr('data-goals');
		
		jQuery('input[name="the_goals[]"]').each(function(index, value){
			 
			if( actual === jQuery(value).val() ){
				jQuery(value).remove();
			}	
			
		});
		
		
		if( jQuery(this).attr('id') == 'other'){
			jQuery('#other-interest').removeClass('obj-visible');
			jQuery('#other_goal').val('');
		}
			

	}else{
		var addDiv = jQuery('#goals_added');
		jQuery('<input type="hidden" name="the_goals[]" value="'+jQuery(this).attr('data-goals')+'" />').appendTo(addDiv);
		jQuery(this).addClass("Selected-Subcategory");
		jQuery("#save").removeClass("unactive");

	}
	
});

jQuery("#open-intestes").live('click',function(){
	jQuery(this).colorbox.resize();
	jQuery("#customer-categories-box").hide();
	jQuery("#user-interests-box").show();
});

/* Other Option */
jQuery("#other").on('click', function() {
	jQuery("#other-interest").addClass("obj-visible");
});
