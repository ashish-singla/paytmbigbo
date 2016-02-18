<?php
	
function get_quiz_embeder_options()
{
	$default=array(
		"lightbox_script"=>"colorbox",
		"colorbox_transition"=>"elastic",
		"colorbox_theme"=>"default",
		
		"nivo_lightbox_effect"=>"fade",
		"nivo_lightbox_theme"=>"default",
				
		"size_opt"=>"default",
		"height"=>500,
		"width"=>750,	
		"height_type"=>"px",
		"width_type"=>"px",
		"buttons"=>array()
	
	);
	$opt = get_option('quiz_embeder_option', $default);
	if($opt["lightbox_script"]==""){$opt["lightbox_script"]="colorbox";}
	if($opt["colorbox_theme"]==""){$opt["colorbox_theme"]="default";}
	if($opt["size_opt"]==""){$opt["size_opt"]="default";}
	return $opt;
}

function quiz_embeder_options_page()
{
$opt=get_quiz_embeder_options();
$cbox_themes=quiz_embeder_get_colorbox_themes();
if(isset($_POST['save']))
{
	//echo "<pre>"; print_r($_POST); echo "</pre>";
	$opt['lightbox_script']=$_POST['lightbox_script'];
	$opt['colorbox_transition']=$_POST['colorbox_transition'];
	$opt['colorbox_theme']=$_POST['colorbox_theme'];
	$opt['nivo_lightbox_effect']=$_POST['nivo_lightbox_effect'];
	$opt['nivo_lightbox_theme']=$_POST['nivo_lightbox_theme'];	
	$opt['size_opt']=$_POST['size_opt'];

	$opt['height']=intval($_POST['height']);
	$opt['width']=intval($_POST['width']);

	$opt['height_type']=$_POST['height_type'];
	$opt['width_type']=$_POST['width_type'];
	$buttons=array();
	if(isset($_POST['buttons']) && is_array($_POST['buttons']))
	{
	foreach($_POST['buttons'] as $btn){$btn=trim($btn); if($btn!=""){$buttons[]=$btn;}}
	}
	$opt['buttons']=$buttons;
	
	update_option('quiz_embeder_option', $opt);

}


?>
	<script type="text/javascript">
		function show_lightbox_settings()
		{
			var val=jQuery("#lightbox_script").val();
			if(val=="colorbox")
			{
				jQuery("#nivo_lightbox_settings").hide();
				jQuery("#colorbox_settings").show();
			}
			else
			{
				jQuery("#colorbox_settings").hide();
				jQuery("#nivo_lightbox_settings").show();
			}
		
		}
		
		function show_custom_size_options()	{ jQuery("#custom_size_options").show(); }
		function hide_custom_size_options()	{ jQuery("#custom_size_options").hide(); }
		
	
	</script>

	<!--nivo lite box-->
	<link rel="stylesheet" href="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL?>nivo-lightbox/nivo-lightbox.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL?>nivo-lightbox/themes/default/default.css" type="text/css" />
	<script src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL?>nivo-lightbox/nivo-lightbox.min.js"></script>	
	<!--end nivo lite box-->	
	<!--QUIZ_EMBEDER START-->
	<link rel="stylesheet" id="colorbox_css" href="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL."colorbox/themes/default/colorbox.css" ;?>" />
	<script type="text/javascript" src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL."colorbox/jquery.colorbox-min.js" ;?>" ></script>
	<script type="text/javascript">
		function get_colorbox_css_url(theme)
		{
			var url="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL?>colorbox/themes/"+theme+"/colorbox.css";
			return url;
		}
		jQuery(document).ready(function($){		
			$('#nivo_lightbox_preview').nivoLightbox({
				effect: 'fall',                             // The effect to use when showing the lightbox
				theme: 'default',                           // The lightbox theme to use
				keyboardNav: true,                          // Enable/Disable keyboard navigation (left/right/escape)
				clickOverlayToClose: true,                  // If false clicking the "close" button will be the only way to close the lightbox
				onInit: function(){},                       // Callback when lightbox has loaded
				beforeShowLightbox: function(){},           // Callback before the lightbox is shown
				afterShowLightbox: function(lightbox){},    // Callback after the lightbox is shown
				beforeHideLightbox: function(){},           // Callback before the lightbox is hidden
				afterHideLightbox: function(){},            // Callback after the lightbox is hidden
				onPrev: function(element){},                // Callback when the lightbox gallery goes to previous item
				onNext: function(element){},                // Callback when the lightbox gallery goes to next item
				errorMessage: 'The requested content cannot be loaded. Please try again later.' // Error message when content can't be loaded
			});
			//Examples of how to assign the ColorBox event to elements
			$("#colorbox_preview").colorbox({iframe:true, width:"80%", height:"80%"});	
			
			
			$("#colorbox_theme").click(function(){
				var val=$(this).val();
				$("#colorbox_css").remove();
				var url=get_colorbox_css_url(val);
				$("head").append('<link rel="stylesheet" id="colorbox_css" href="'+url+'" />');
			});
						
		});
		
		
		function preview_lightbox()
		{
			
			var val=jQuery("#lightbox_script").val();
			if(val=="colorbox")
			{
				var theme=jQuery("#colorbox_theme").val();
				
				//jQuery("#colorbox_css").attr("href", get_colorbox_css_url(theme)); 
				jQuery("#colorbox_preview").trigger('click');
			}
			else
			{
				jQuery("#nivo_lightbox_preview").trigger('click');

			}
			//jQuery.colorbox({href:"http://www.articulate.com"}); //This method allows you to call Colorbox without having to assign it to an element.
		
		}
		
		
	</script>
<style type="text/css">
	.button_box{ margin-bottom:5px; padding:2px; border:1px solid #FFFFFF;}
	.button_box .close_button{ width:16px; height:16px; position:absolute; background:url(<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL?>/close_16.png) no-repeat; margin-left:369px; cursor:pointer; }
	.button_box:hover{background:#fff;}
	.imgbox{min-height:18px;}
	.imgbox img{max-width:300px;}
	.image_source{width:277px;}
	
</style>
<div style="width: 800px; padding-left: 10px;" class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2>Insert or Embed Articulate Content into Wordpress</h2>
			

    <form action="" method="post" >
	<div style="width:400px; float:left; border-right:1px solid #ffffff;">
	<table class="widefat"><tr><td>
	<label style="width:90px; display:inline-block;" >Lightbox Style</label>
	<select name="lightbox_script" id="lightbox_script" onchange="show_lightbox_settings()">
		<option value="colorbox" <?php echo ($opt["lightbox_script"]=="colorbox")?' selected="selected"':""?> >Color Box</option>
		<option value="nivo_lightbox" <?php echo ($opt["lightbox_script"]=="nivo_lightbox")?' selected="selected"':""?>>Nivo Lightbox</option>
	</select>
	<h3>Lightbox Settings</h3>
	<div id="colorbox_settings" <?php echo ($opt["lightbox_script"]=="colorbox")?' style="display:block"':'style="display:none"'?>>
	<label style="width:90px; display:inline-block;" >Transition</label>
	<select name="colorbox_transition">
		<option value="elastic" <?php echo ($opt["colorbox_transition"]=="elastic")?' selected="selected"':""?>>Elastic</option>
		<option value="fade" <?php echo ($opt["colorbox_transition"]=="fade")?' selected="selected"':""?>>Fade</option>
		<option value="none" <?php echo ($opt["colorbox_transition"]=="none")?' selected="selected"':""?>>None</option>
	</select>	
	<br />
	<label  style="width:90px; display:inline-block;" >Theme</label>

	<select name="colorbox_theme" id="colorbox_theme">
		<?php 
		foreach($cbox_themes as $key=>$tm)
		{?>
		<option value="<?php echo $key ?>" <?php echo ($opt["colorbox_theme"]==$key)?' selected="selected"':""?>><?php echo $tm["text"];?></option>		
		<?php }?>
	</select>
	</div>
	<div id="nivo_lightbox_settings" <?php echo ($opt["lightbox_script"]=="nivo_lightbox")?' style="display:block"':'style="display:none"'?>>
	<label style="width:90px; display:inline-block;" >Effect</label>
	<select name="nivo_lightbox_effect">
		<option value="fade" <?php echo ($opt["nivo_lightbox_effect"]=="fade")?' selected="selected"':""?>>Fade</option>
		<option value="fadeScale" <?php echo ($opt["nivo_lightbox_effect"]=="fadeScale")?' selected="selected"':""?>>FadeScale</option>
		<option value="slideLeft" <?php echo ($opt["nivo_lightbox_effect"]=="slideLeft")?' selected="selected"':""?>>SlideLeft</option>
		<option value="slideRight" <?php echo ($opt["nivo_lightbox_effect"]=="slideRight")?' selected="selected"':""?>>SlideRight</option>
		<option value="slideUp" <?php echo ($opt["nivo_lightbox_effect"]=="slideUp")?' selected="selected"':""?>>SlideUp</option>
		<option value="slideDown" <?php echo ($opt["nivo_lightbox_effect"]=="slideDown")?' selected="selected"':""?>>SlideDown</option>
		<option value="fall" <?php echo ($opt["nivo_lightbox_effect"]=="fall")?' selected="selected"':""?>>Fall</option>
	</select>	
	<br />
	<label  style="width:90px; display:inline-block;" >Theme</label>
	<select name="nivo_lightbox_theme">
		<option value="default" <?php echo ($opt["nivo_lightbox_theme"]=="default")?' selected="selected"':""?>>Default</option>
	</select>	
	</div>
	
		
	<h3>Size Options</h3>
	<input type="radio" name="size_opt" id="size_opt_default" value="default" <?php echo ($opt["size_opt"]=="default")?' checked="checked"':""?> onclick="hide_custom_size_options()" /><label for="size_opt_default" >Default</label><br />
	<input type="radio" name="size_opt" id="size_opt_custom"  value="custom" <?php echo ($opt["size_opt"]=="custom")?' checked="checked"':""?> onclick="show_custom_size_options()" /><label for="size_opt_custom" >Custom</label><br />
	<div id="custom_size_options" <?php echo ($opt["size_opt"]=="custom")?' style="display:block"':'style="display:none"'?>>
	<label  style="width:90px; display:inline-block;" >Height</label><input type="text" name="height" size="3" maxlength="4" value="<?php echo (intval($opt["height"]))?>" /><select style="margin-top:-2px;" name="height_type"><option value="px" <?php echo ($opt["height_type"]=="px")?' selected="selected"':""?>>px</option><option value="%" <?php echo ($opt["height_type"]=="%")?' selected="selected"':""?> >%</option></select><br />
	<label  style="width:90px; display:inline-block;" >Width</label><input type="text" name="width" size="3" maxlength="4" value="<?php echo (intval($opt["width"]))?>" /><select style="margin-top:-2px;" name="width_type"><option value="px" <?php echo ($opt["width_type"]=="px")?' selected="selected"':""?>>px</option><option value="%" <?php echo ($opt["width_type"]=="%")?' selected="selected"':""?>>%</option></select><br />
	</div>
	<br /><br />
	<a href="https://www.articulate.com" id="nivo_lightbox_preview" data-lightbox-type="iframe" title="Lightbox Preview: Sample Title (optional)" style="display:none;">Nivo Preview</a>
	<a href="https://www.articulate.com" id="colorbox_preview"  style="display:none;"  title="Lightbox Preview: Sample Title (optional)">Colorbox Preview</a>
	<input type="button" value="Preview Lightbox" class="button-primary" onclick="preview_lightbox()" /> &nbsp;&nbsp;
    <input type="submit" value="Save" name="save" class="button-primary" />    
	</td></tr></table>
	</div><!--end of left box-->
	<div style="width:390px; float:right;min-height:400px;">
	<h3>Custom Buttons</h3> 
		<div id="button_area">
		<?php if(is_array($opt['buttons'])){
		
			foreach($opt['buttons'] as $btn){
		 ?>
			<div class="button_box">
			<div class="close_button" title="Delete"></div>
			<div class="imgbox"><img src="<?php echo $btn?>"  /></div>
			<input type="text" size="20" name="buttons[]" class="image_source" value="<?php echo $btn ?>">&nbsp;
			<input class="img_upload_button button" type="button" value="Select Image">
			</div>	
			<?php }}?>
		</div>
		<div><input type="button" class="button" value="Add Button" onclick="add_new_button()" /> &nbsp;&nbsp;
		<input type="submit" value="Save" name="save" class="button-primary" /></div>
	</div><!--end of right box-->
	
	</form>
</div>

<?php 
}//end function 


function quiz_embeder_menu_pages()
{
add_menu_page( "Articulate", "Articulate" ,'manage_options', 'articulate_content', 'quiz_embeder_options_page');
}

add_action('admin_menu', 'quiz_embeder_menu_pages');

function quiz_admin_enqueue_scripts()
	{
		if(strpos($_SERVER['REQUEST_URI'], 'articulate_content')) #to ensure that current plugin page is being shown.
		{
		wp_enqueue_script( 'jquery' );
		$jquery_css_base = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css';
		wp_enqueue_style ( 'jquery-ui-standard-css', $jquery_css_base );
		}   
	}
add_action( 'admin_enqueue_scripts', 'quiz_admin_enqueue_scripts');	
?>