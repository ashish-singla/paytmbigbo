   
    $thematerjQ=jQuery.noConflict();
        
    function themater_ajax(requests,appendto,loading) 
    {
    	$thematerjQ.ajax({
    		url: "admin-ajax.php",
    		type: "POST",
    		dataType: "html",
    		data: "action=themater_ajax&_ajax_nonce="+themater_nonce+"&"+requests+"",
    		success: function(response){$thematerjQ("#"+appendto+"").html(response);}
    	});
        
        if(loading) {
            themater_loading(appendto);
        }
    	
    }
    
    function themater_loading(appendto) 
    {
    	$thematerjQ("#"+appendto+"").empty();
        $thematerjQ("#"+appendto+"").show();
    	$thematerjQ("#"+appendto+"").append('<p style="padding:4px; margin:4px;"><img src="images/loading.gif" align="absmiddle"> <span style="font-size:11px; color: #999;">Loading, please wait...</span></p>');
    }
    
    
    function themater_form(requests,formname,appendto,loading) 
    {
    	$thematerjQ.ajax({
    		url: "admin-ajax.php?action=themater_ajax&_ajax_nonce="+themater_nonce+"&act="+requests+"",
    		type: "POST",
    		dataType: "html",
    		data: $thematerjQ("#"+formname+"").serialize(),
    		success: function(response){$thematerjQ("#"+appendto+"").html(response);}
    	});
        
        if(loading) {
            themater_loading(appendto);
        }
    	return false;
    }
    
    function themater_savechanges(requests,formname,appendto) 
    {
    	$thematerjQ.ajax({
            url: "admin-ajax.php?action=themater_ajax&_ajax_nonce="+themater_nonce+"&act="+requests+"",
    		type: "POST",
    		dataType: "html",
    		data: $thematerjQ("#"+formname+"").serialize(),
    		success: function(response){
    		    $thematerjQ("#"+appendto+"").empty();
                $thematerjQ("#"+appendto+"").show();
                $thematerjQ("#"+appendto+"").html(response);
                $thematerjQ("#"+appendto+"").fadeIn(5000);
                $thematerjQ("#"+appendto+"").fadeOut(1000);
              }
    	});
        $thematerjQ("#"+appendto+"").empty();
        $thematerjQ("#"+appendto+"").show();
	    $thematerjQ("#"+appendto+"").append('<img src="images/loading.gif" align="absmiddle"> <span style="font-size:11px; color: #999;">Saving changes, please wait...</span>');
        return false;
    }
        
    function themater_showHide(id)
    {
    	if ($thematerjQ("#"+id+"").is(":hidden")) {
            $thematerjQ("#"+id+"").slideDown('fast');
          } else {
        	  $thematerjQ("#"+id+"").slideUp('fast');
          }
    }
    
    function themater_hide(id) 
    {
    	$thematerjQ("#"+id+"").empty();
    }
    
    function themater_remove(id) 
    {
    	$thematerjQ("#"+id+"").remove();
    }
    
    function themater_hoverShow(id) 
    {
    	$thematerjQ("#"+id+"").css("display","inline");
    }
    
    function themater_hoverHide(id) 
    {
    	$thematerjQ("#"+id+"").css("display","none");
    } 
    
    function themater_togle(id)
    {
        $thematerjQ('#'+id).slideToggle('fast');
    }
    
    var themater_sp_id_new = Math.floor(Math.random()*100000);
    function themater_sp_new(id)
    {
        themater_sp_id_new = themater_sp_id_new+1;
        var new_sp_id = themater_sp_id_new;
        var get_new_sp_container = $thematerjQ('#themater_sp_new_'+id+'').html();
        get_new_sp_container = get_new_sp_container.replace(/the__id__/g, ''+id+new_sp_id+'');
        get_new_sp_container = get_new_sp_container.replace(/new__id__/g, ''+new_sp_id+'');
        var new_sp_container = get_new_sp_container.replace(/name_replace_/g, '');
        $thematerjQ('#themater_sp_new_'+id).before(new_sp_container);
    }

    function themater_sp_titles(this_id, temp_id)
    {
       var id = this_id+temp_id;
       var new_title = $thematerjQ('#sp_title_text_'+id).val();
	   $thematerjQ('#sp_title_'+id).text(new_title);
    }

    function themater_sp_delete(id)
    {
        $thematerjQ('#sp_container_'+id+'').remove();
    }
 	
    jQuery(document).ready(function($){
        
        // Navigation Tabs
        $(".tt-menu li").click(function () {
        	$(".tt-menu-active").removeClass("tt-menu-active");
        	$(this).addClass("tt-menu-active");
        	$(".tt-menu-content").hide();
        	var change_content= $(this).attr("id");
        	$("."+change_content).fadeIn();
           
        });
        
        // Image Upload
         $('.tt_imageupload').each(function(){
			
			var clickedObject = $(this);
            var clickedID = $(this).attr('id');
			var getClickedID = clickedID.replace("themater_image_upload_", "");
            	
			new AjaxUpload(clickedID, {
			  action: 'admin-ajax.php?action=themater_ajax&_ajax_nonce='+themater_nonce+'&act=imageupload',
			  name: clickedID,
			  data: { 
				imgname: clickedID
                },
                
			  onChange: function(file, extension){},
              
			  onSubmit: function(file, extension){
					clickedObject.text('Uploading'); 
					this.disable(); 
					interval = window.setInterval(function(){
						var text = clickedObject.text();
						if (text.length < 13){	clickedObject.text(text + '.'); }
						else { clickedObject.text('Uploading'); } 
					}, 200);
			  },
              
			  onComplete: function(file, response) {
			   if(response.search('Upload Error') > -1){
			            window.clearInterval(interval);
    				    clickedObject.text('Upload Now');
                        this.enable(); 
			            $('#'+getClickedID+'_error').text(response);
						$('#'+getClickedID+'_error').show();
					
				} else{
    				window.clearInterval(interval);
    				clickedObject.text('Upload Now');	
    				this.enable(); 
                    $('#'+getClickedID+'_error').hide();
    				$('.'+clickedID+'').val(response);	
    				$('#'+getClickedID+'_reset').show();
                    var previewImage = '<a href="'+response+'" target="_blank"><img src="'+response+'" title="The image might be resized, click for full preview!" alt="" /></a><br /><span>The image might be resized, click for full preview!</span>';
                    $('#'+getClickedID+'_preview').html(previewImage);
                    $('#'+getClickedID+'_preview').show();
                } 
              }
              
			});
		});
        
        // Reset the image filed
        $('.tt_imageupload_reset').click(function(){
			
			var clickedObject = $(this);
			var clickedID = $(this).attr('id');
			var theID = $(this).attr('title');	

			$('.themater_image_upload_'+theID+'').val('');	
			$('#'+clickedID+'').hide();
            $('#'+theID+'_preview').hide();
			return false; 
			
		}); 
        $('#tt-menuwrap ul').hide();
        $('.tt-first-menu').slideDown();
        
    });	 	
    