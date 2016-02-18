// Password strength meter
(function($){

	function ura_check_pass_strength() {
		var pass1 = jQuery('[name=pass1]').val(), user = jQuery('#user_login').val(), pass2 = jQuery('[name=pass2]').val(), strength;

		$('#pass-strength-result').removeClass('empty short bad good strong');
		if ( ! pass1 ) {
			$('#pass-strength-result').html( ura_pwsL10n.empty );
			return;
		}

		strength = ura_passwordStrength(pass1, user, pass2);

		switch ( strength ) {
		case 2:
			jQuery('#pass-strength-result').addClass('short').html( ura_pwsL10n['short'] );
			jQuery('#pass-strength-result').css("background-color", "#FF6600");
			break;
		case 3:
			jQuery('#pass-strength-result').addClass('bad').html( ura_pwsL10n['bad'] );
			jQuery('#pass-strength-result').css("background-color", "#FFCC00");
			break;
		case 4:
			jQuery('#pass-strength-result').addClass('good').html( ura_pwsL10n['good'] );
			jQuery('#pass-strength-result').css("background-color", "#99FF00");
			break;
		case 5:
			jQuery('#pass-strength-result').addClass('strong').html( ura_pwsL10n['strong'] );
			jQuery('#pass-strength-result').css("background-color", "#00CC00");
			break;
		case 6:
			jQuery('#pass-strength-result').addClass('short').html( ura_pwsL10n['mismatch'] );
			jQuery('#pass-strength-result').css("background-color", "#33FFCC");
			break;
		default:
			jQuery('#pass-strength-result').addClass('empty').html( ura_pwsL10n['empty'] );
			jQuery('#pass-strength-result').css("background-color", "#FF0000");
		}
	}

	$(document).ready( function() {
		if (jQuery('[name=pass1]').attr('type') == 'password') {
		jQuery('[name=pass1]').val('').keyup( ura_check_pass_strength );
		jQuery('[name=pass2]').val('').keyup( ura_check_pass_strength );
		
		ura_check_pass_strength();
	}
		
	

});
})(jQuery);



