jQuery(document).ready(function($) {
	
	// var newDiv = document.createElement("div");
	// newDiv.id = "new-user-ajax";
	// document.body.appendChild(newDiv);
	// $('#wpbody-content').append("<div id='new-user-ajax'>Show Shit HEre 1</div>");
	
	
	$('#user_login').focus(function(){
		
		var newDiv = document.createElement("div");
		newDiv.id = "new-user-ajax";
		document.body.appendChild(newDiv);
		$('#wpbody-content').append("<div id='new-user-ajax'>My Crap Here</div>");
		//var fields = new array();
		//fields = csds_userRegAide_user_new_submit_vars;
		//alert(fields.length)
		// for( i=0 ; i < fields.length; i++ ){
			// if(fields[i].val() != ''){
				// //alert(caps[i].value);
				// selCaps.push(caps[i].value);
			// }
		// }
		$.post(ajaxurl, {
			action: 'csds_user_new_ajax',
			csds_ura_user_new_ajax_nonce: csds_userRegAide_user_new_ajax_vars.csds_ura_user_new_ajax_nonce
		},
		
		function(response){
			//$('.form-table').html(response);
			$('#new-user-ajax').html(response);
			//$('#wp-submit').attr('disabled', false);
			
		});
		
	
	});
});