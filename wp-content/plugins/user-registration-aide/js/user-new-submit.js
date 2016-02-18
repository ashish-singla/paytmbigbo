jQuery(document).ready(function($) {

$('#createusersub').click(function(){
		var username = $('#user_login').val();
		// var fields = new Array()
		// fields = document.getElementsByName('fieldNames[]');
		// alert('Array OK');
		// alert(fields.length);
		// var extraFields = new Array();
		// var i = 0;
			
		// for( i=0 ; i < fields.length; i++ ){
			// if(fields[i].val() != ''){
				// alert(fields[i].val());
				// extraFields.push(fields[i].val());
			// }
		// }
		
		// alert(username);
		
		$.post(ajaxurl, {
			action: 'create_new_user_extra_fields_update',
			user: username,
			//extraFields: extraFields,
			csds_ura_user_new_ajax_nonce: csds_userRegAide_user_new_submit_vars.csds_ura_user_new_submit_ajax_nonce
		},
		
		function(response){
			$('#createuser').html(response);
			
			
		});
		
	});
	
});