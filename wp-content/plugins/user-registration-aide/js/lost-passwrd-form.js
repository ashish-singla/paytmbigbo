// Getting user name for checking password question
jQuery(document).ready(function($) {
		var sec_question = $('#sec_question').val();
		
		if(sec_question == 1){
			$('#wp-submit').attr('disabled', true);
		}
		
	$('#user_login').focusout(function(){
			var uname = $('#user_login').val();
			
		$.post(ajaxurl, {
			action: 'csds_lost_password_form_ajax',
			username: uname,
			csds_ura_lost_password_ajax_nonce: csds_userRegAide_ajax_vars.csds_ura_lost_password_ajax_nonce
		},
		
		function(response){
			$('#csds_ura_lpf_ajax').html(response);
			$('#wp-submit').attr('disabled', false);
			
		});
		//return false;,
		/*
		csds_userRegAide_ajax_vars.ajaxurl,
		{
		action: 'csds_lost_password_form_ajax',
			action: 'csds_lost_password_form_ajax',
			username: uname,
			csds_ura_lost_password_ajax_nonce: csds_userRegAide_ajax_vars.csds_ura_lost_password_ajax_nonce
		}
		function(response){
			alert(response);
		}
		*/
	});
});




/*jQuery(document).ready(function($) {
	$('#csds_userRegAidePro_select_role_submit').click(function(){
		var selectedRole = $('#csds_userRegAidePro_selectRoles').val();
		$('#csds_userRegAidePro_loading_role').show();
		$('#csds_userRegAidePro_select_role_submit').attr('disabled', true);
		data = {
			action: 'csds_userRegAidePro_ajaxRoleEditor_getResults',
			roleSelected: selectedRole,
			csds_userRegAidePro_roleEditorAjaxNonce: csds_userRegAidePro_vars.csds_userRegAidePro_roleEditorAjaxNonce
		};
		
		$.post(ajaxurl, data, function(response){
			$('#csds_userRegAidePro_ajaxTestResults_RoleEditor').html(response);
			$('#csds_userRegAidePro_loading_role').hide();
			$('#csds_userRegAidePro_select_role_submit').attr('disabled', false);
			
		});
		return false;
	});	*/