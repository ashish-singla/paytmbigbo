(function( $ ){$(document).ready(function(){
 
        $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('#backtotop').fadeIn();
            } else {
                $('#backtotop').fadeOut();
            }
        });
 
        $('#backtotop').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });
 
    });
})( jQuery );