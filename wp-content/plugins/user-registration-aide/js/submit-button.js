// Lost Password Form Submit Button Hide/Show

jQuery(document).ready(function($) {

	//function checkUserName(){
	
		//$('#wp-submit').attr('disabled', true);
		var sq = $('#sec_question').val();
		
		if(sq == '1'){
			//$('#wp-submit').attr('disabled', true);
		}
		
		$('#user_login').focusout(function(){
			//var uname = $('#user_login').val();
			//alert(uname);
		});
			//var uname = $(this).val();
			//$('#sec_question').text("wtf");
			//$('#wp-submit').attr('disabled', true);
		
		
	//}
});