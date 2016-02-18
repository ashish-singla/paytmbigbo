// Getting user name for checking password question
jQuery(document).ready(function($) {
		
		var user_index = $('#dash_widget_index').val();
		var user_count = $('#dash_widget_count').val();
		var high_count = user_count - 5;
		
		if(user_index <= 5){
			$('#previous_users').attr('disabled', true);
		}
		
		if(user_index >= user_count){
			$('#next_users').attr('disabled', true);
		}
	
	// $('#previous_users').click(function(){
		
		// var indx = $('#dash_widget_index').val();
			
		// var prev_data = {
			// action: 'wp_ajax_csds_ura_dashboard_widget_previous',
			// pindex: indx,
			// csds_userRegAide_dash_widget_nonce: csds_userRegAide_dash_vars.csds_userRegAide_dash_widget_nonce
		// };
		
		// $.post(ajaxurl, prev_data, function(response){
			// $('#dash_widget_ajax').html(response);
			// // if(indx >= 5){
				// // $('#previous_users').attr('disabled', false);
			// // }
		// });
		// return false;
	// });
		
	jQuery('#next_users').click(function(){
		var n_index = $('#dash_widget_index').val();
			// var u_count = $('#dash_widget_count').val();
			// var h_count = user_count - 1;
			// alert(n_index);
		var next_data = {
			action: 'wp_ajax_csds_ura_dashboard_widget_next',
			next_index: n_index,
			csds_userRegAide_dash_widget_nonce: csds_userRegAide_dash_vars.csds_userRegAide_dash_widget_nonce
		};
		
		alert('message 2');
		
		jQuery.post(ajaxurl, next_data, function(response){
			$('#dash_widget_ajax').html(response);
			//alert('response');
			// if(indx <= h_count){
				// $('#next_users').attr('disabled', false);
			// }
			
		});
		return false;
	});
		
});
