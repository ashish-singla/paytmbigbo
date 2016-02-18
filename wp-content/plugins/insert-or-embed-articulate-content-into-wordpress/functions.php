<?php

function get_slug_from_string($string) {
  $string=strtolower($string);
  $filtered_string = preg_replace('/[^a-zA-Z0-9\s]/', "", $string);
  $filtered_string=str_replace(" ","_",$filtered_string);
  return $filtered_string;
}


#********************************************************************************************************************************
function print_page_navi($num_records)
{
				//$num_records;	#holds total number of record
				$page_size;		#holds how many items per page
				$page;			#holds the curent page index
				$num_pages; 	#holds the total number of pages
				$page_size = 15;
				#get the page index
				if (empty($_GET['npage']) || !is_numeric($_GET['npage']))
				{$page = 1;}
				else
				{$page = $_GET['npage'];}
				
				#caluculate number of pages to display
				if(($num_records%$page_size))
				{
					$num_pages = (floor($num_records/$page_size) + 1);
				}else{
					$num_pages = (floor($num_records/$page_size));
				}
		
				if ($num_pages != 1)
				{
					for ($i = 1; $i <= $num_pages; ++$i)
					{
						#if page is the same as the page being written to screen, don't write the link
						#page navigation logic is developed by "oneTarek" http://onetarek.com
						if ($i == $page)
						{
							echo "$i";	
						}
						else
						{
							echo "<a href=\"media-upload.php?type=upload&tab=quiz&npage=$i\">$i</a>";
			
						}
						if($i != $num_pages)
						{
							echo " | ";
						}
					}
				}
		
				#calculate boundaries for limit query
				$upper_bound = (($page_size * ($page-1)) + $page_size);/*$page_size;*/
				$lower_bound = ($page_size * ($page-1));
				$bound=array($lower_bound,$upper_bound);
				return $bound;
		
}



function print_detail_form($num, $tab="upload", $file_url="", $dirname="")
{
$opt=get_quiz_embeder_options();
$cbox_themes=quiz_embeder_get_colorbox_themes();
?>

	<div id="upload_detail_<?php echo $num ?>" style="display:none; margin-bottom:30px; margin-top:20px;">
		<input type="hidden" size="40" name="file_url_<?php echo $num ?>" id="file_url_<?php echo $num ?>" value="<?php echo $file_url ?>" />
		<input type="hidden" size="40" name="dir_name_<?php echo $num ?>" id="dir_name_<?php echo $num ?>" value="<?php echo $dirname ?>" />
		<?php if($tab=='upload'){ ?> 
		<input type="hidden" name="file_name_<?php echo $num ?>" id="file_name_<?php echo $num ?>" value="" />
		<br /><label for="title"><strong>Title:</strong></label> <input type="text" size="20" name="title" id="title" value="" /><br /><br />
		<?php }?>		
		<strong>Insert As:</strong><br />

		<input type="radio" name="insert_as_<?php echo $num ?>" value="1" checked="checked" onclick="insert_as_clicked(<?php echo $num ?>)" /> iFrame<br />
		<input type="radio" name="insert_as_<?php echo $num ?>" value="2"  onclick="insert_as_clicked(<?php echo $num ?>)" /> Lightbox<br />
		<input type="radio" name="insert_as_<?php echo $num ?>" value="3"  onclick="insert_as_clicked(<?php echo $num ?>)" /> Link that opens in a new window<br />
		<input type="radio" name="insert_as_<?php echo $num ?>" value="4"  onclick="insert_as_clicked(<?php echo $num ?>)" /> Link that opens in the same window<br />
		<br />
		<div id="lightbox_option_box_<?php echo $num ?>" style="display:none">
			<strong>Lightbox options:</strong><br />
			<input type="radio" name="lightbox_option_<?php echo $num ?>" value="1" onclick="lightbox_option_clicked(<?php echo $num ?>)" /> With Title <input type="text"  name="lightbox_title_<?php echo $num ?>" id="lightbox_title_<?php echo $num ?>" size="30" style="display:none;" /><br />
			<input type="radio" name="lightbox_option_<?php echo $num ?>" value="2"  checked="checked"  onclick="lightbox_option_clicked(<?php echo $num ?>)" /> No Title<br />
			<br />
			<strong>More Lightbox options:</strong><br />
			<input type="radio" name="more_lightbox_option_<?php echo $num ?>" value="1"  onclick="more_lightbox_option_clicked(<?php echo $num ?>)"/> Link Text <input type="text"  name="lightbox_link_text_<?php echo $num ?>" id="lightbox_link_text_<?php echo $num ?>" size="30" style="display:none;" /><br />
			<input type="radio" name="more_lightbox_option_<?php echo $num ?>" value="2"  checked="checked"   onclick="more_lightbox_option_clicked(<?php echo $num ?>)"/> Use 'Launch Presentation' button<br />
			<input type="radio" name="more_lightbox_option_<?php echo $num ?>" value="3"  onclick="more_lightbox_option_clicked(<?php echo $num ?>)"/> Use custom image<br />
            
			<div id="custom_button_area_<?php echo $num ?>" style="display:none;">
				<select name="buttons_<?php echo $num ?>" id="buttons_<?php echo $num ?>" onchange="show_button(<?php echo $num ?>)">
					<option value="0">Select Button</option>
					<?php $nn=0; foreach($opt['buttons'] as $btn){$nn++;?>
					<option value="<?php echo $btn;?>"><?php echo $nn;?></option>
					<?php }?>
				</select>
				<div id="button_view_<?php echo $num ?>"></div>
 			</div>
			<br/>
			<strong>Scrollbar options:</strong><br />
			<input type="radio" name="scrollbar_<?php echo $num ?>" value="yes" id="scrollbar_yes" checked="checked" /> Default - display if needed<br />
			<input type="radio" name="scrollbar_<?php echo $num ?>" value="no" id="scrollbar_no" /> Disabled
			<br />
		<!--More lightbox options	-->
			<?php if($opt['lightbox_script']=="colorbox"){?>
			<label  style="width:78px; display:inline-block;">Theme</label>
			<select name="colorbox_theme_<?php echo $num ?>" id="colorbox_theme_<?php echo $num ?>">
				<option value="default_from_dashboard">Default From Dashboard</option>
				<?php 
				foreach($cbox_themes as $key=>$tm)
				{?>
				<option value="<?php echo $key ?>"><?php echo $tm["text"];?></option>		
				<?php }?>
			</select>
			<br />
			<?php }?>	
			<label style="width:78px; display:inline-block;">Size Options</label>
			<select name="size_opt_<?php echo $num ?>" id="size_opt_<?php echo $num ?>" onchange="show_hide_custom_size_area(<?php echo $num ?>)">
				<option value="lightebox_default">Lightbox Default</option>
				<option value="custom_form_dashboard">Custom from dashboard</option>
				<option value="custom">Custom for this item</option>
			</select>			
			
			<div id="custom_size_area_<?php echo $num ?>" style="display:none;">
			<label style="width:80px; display:inline-block;" >Height</label><input style="padding:3px 5px" type="text" name="height_<?php echo $num ?>" id="height_<?php echo $num ?>" size="3" maxlength="4" value="" /><select name="height_type_<?php echo $num ?>" id="height_type_<?php echo $num ?>"><option value="px">px</option><option value="%">%</option></select><br />
			<label style="width:80px; display:inline-block;" >Width</label><input  style="padding:3px 5px" type="text" name="width_<?php echo $num ?>" id="width_<?php echo $num ?>" size="3" maxlength="4" value="" /><select name="width_type_<?php echo $num ?>" id="width_type_<?php echo $num ?>"><option value="px">px</option><option value="%">%</option></select><br />
			</div>
			
			
		</div><!--end lightbox options-->
	
		<div id="new_window_option_box_<?php echo $num ?>" style="display:none">
		<strong>Link that opens in a new window options:</strong><br />
		<input type="radio" name="open_new_window_option_<?php echo $num ?>" value="1" onclick="open_new_window_option_clicked(<?php echo $num ?>)" /> Link Text <input type="text"  name="open_new_window_link_text_<?php echo $num ?>" id="open_new_window_link_text_<?php echo $num ?>" size="30"  style="display:none;"/><br />
		<input type="radio" name="open_new_window_option_<?php echo $num ?>" value="2"  checked="checked"  onclick="open_new_window_option_clicked(<?php echo $num ?>)" /> Use 'Launch Presentation' button<br />
        <br />
		</div>
		<div id="same_window_option_box_<?php echo $num ?>" style="display:none">
		<strong>Link that opens in  same window options:</strong><br />
		<input type="radio" name="open_same_window_option_<?php echo $num ?>" value="1" onclick="open_same_window_option_clicked(<?php echo $num ?>)" /> Link Text <input type="text"  name="open_same_window_link_text_<?php echo $num ?>" id="open_same_window_link_text_<?php echo $num ?>" size="30" style="display:none;" /><br />
		<input type="radio" name="open_same_window_option_<?php echo $num ?>" value="2"  checked="checked" onclick="open_same_window_option_clicked(<?php echo $num ?>)" /> Use 'Launch Presentation' button<br />
        <br />
		</div>
		<br />

		
		<div>
			<input type="button" class="button" name="insert_<?php echo $num ?>" id="insert_<?php echo $num ?>" value="Insert Into Post" onclick="add_to_post(<?php echo $num ?>)" /> 
			<span id="delete_<?php echo $num ?>" onclick="delete_dir(<?php echo $num ?>)"  style="text-decoration:underline; cursor:pointer; color:#0000FF; margin-left:20px;" />Delete</span> &nbsp; &nbsp;
			<span id="insert_msg_<?php echo $num ?>"></span>
		</div>		
	</div>
<?php
}#end print_detail_form()


function printInsertForm()
{
//echo "<h3>Start printInsertForm</h3>";
	$dirs = getDirs();
	if (count($dirs)>0)
	{
	print_js("quiz");
	?>
	<title>Media Library</title>
	<?php
	 $uploadDirUrl=getUploadsUrl();
	 //START PAGIGNATION
	 ?>
	 <div style="text-align:right; padding:5px; padding-right:10px; margin:5px 20px;"> 
	 <?php  $bound= print_page_navi(count($dirs)); // print the pagignation and return upper and lower bound ?>
	 </div>
	 <?php
	 
	  $lower_bound=$bound[0];
	  $upper_bound=$bound[1]; 
	  echo '<div style="text-align:right; margin:5px 20px;padding-right:10px;">Showing Content '.$lower_bound.' - '.$upper_bound.' of '.count($dirs);echo '</div>';
	  //$dirs = array_slice($dirs, $lower_bound, $upper_bound);
	  $dirs = array_slice($dirs, $lower_bound, 15);
	  //END PAGIGNATION
	 	
		echo "<table class='widefat'>";
			foreach ($dirs as $i=>$dir):
				extract($dir);
				$dir1 = str_replace("_"," " ,$dir);


				echo '<tr id="content_item_'.$i.'" class="'; if ($i % 2 == 1)echo 'alternate '; echo 'iedit">
						<td>
						<div>';
						echo $dir1;
						echo '<span style="float:right">';
						echo '<span id="show_button_'.$i.'" flag="1" onclick="show_hide_detail( '.$i.' )" style="text-decoration:underline; color:#000099; cursor:pointer;">Show</span> | ';
						echo '<span id="delete_button_'.$i.'"  onclick="delete_dir( '.$i.' )" style="text-decoration:underline; color:#990000; cursor:pointer;">Delete</span>';
						echo '<span id="loading_box_'.$i.'"></span>';
						echo '</span>';
						echo '</div>';
						print_detail_form($i, "quiz" , $uploadDirUrl.$dir."/".$file, $dir);
						echo '
						</td>
					 </tr>';

			endforeach;
		echo "</table>";
	
	}
	else
	{
	echo "no directories available";
	}
//echo "<h3>End printInsertForm</h3>";	
}

function getUploadsPath()
{
$dir = wp_upload_dir();
return ($dir['basedir'] . "/".WP_QUIZ_EMBEDER_UPLOADS_DIR_NAME."/");
}
function getUploadsUrl()
{
$dir = wp_upload_dir();
return $dir['baseurl'] . "/".WP_QUIZ_EMBEDER_UPLOADS_DIR_NAME."/";
}
function getPluginUrl()
{
//return WP_PLUGIN_URL."/insert-or-embed-articulate-content-into-wordpress/"; #oneTarek says: This line is wrong because you are unable to rename the plugin directory
return plugin_dir_url(__FILE__); #chaned by oneTarek # The URL of the directory that contains the plugin, including a trailing slash ("/")
}
function getDirs()
{
$myDirectory = opendir(getUploadsPath());
$dirArray = array();
$i=0;
// get each entry
while($entryName = readdir($myDirectory)) {
	if ($entryName != "." && $entryName !=".." && is_dir(getUploadsPath().$entryName)):
	$dirArray[$i]['dir'] = $entryName;
	// store the filenames - need to iterate to get story.html or player.html
	$dirArray[$i]['file'] = getFile(getUploadsPath().$entryName);
	$i++;
	endif;
}

// close directory
closedir($myDirectory);

return $dirArray;
}

function getFile($dir)
{
$myDirectory = opendir($dir);
$fileArray = array();
// get each entry
while($entryName = readdir($myDirectory)) {
	if ($entryName != "." && $entryName !="..")
	{
	$f = getUploadsPath().$entryName;
	
	// need to get the filename without the extension
	$fname = pathinfo ($f, PATHINFO_FILENAME);
	// need the extension as well
	$ext = pathinfo ($f,PATHINFO_EXTENSION);
	
	// need to check the filename and only return player.html or story.html
	if (($fname == "player" || $fname == "story" || $fname == "engage" || $fname =="quiz" || $fname =="presentation" || $fname =="interaction" || $fname =="index") && $ext == "html"):
	closedir($myDirectory);
	return $entryName;
	endif;
	}
}
return false;


}



function print_js($tab="upload") #added by oneTarek
{
wp_enqueue_script("jquery");

?>
<link rel="stylesheet" href="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL."css/style.css?var=";echo time(); ?>" />

<script>
	
	jQuery(document).ready(function() { 
	
	/*
	http://stackoverflow.com/questions/1601455/check-file-input-size-with-jquery
		jQuery('#uploadedfile').bind('change', function() {
		  alert(this.files[0].size + " : "+ this.files[0].name);
		});
	*/	
			jQuery("#media_loading").hide();
			
             

			
}); // end jQuery(document).ready()


function show_detail(number)
{
jQuery("#upload_detail_"+number+"").show('slow');
}

function show_hide_detail(number)
{
var flag=jQuery("#show_button_"+number+"").attr("flag");
	if(flag=="1")
	{
	jQuery("#show_button_"+number+"").attr("flag", "2");
	jQuery("#show_button_"+number+"").html("Hide");
	jQuery("#upload_detail_"+number+"").show('slow');
	}
	else
	{
	jQuery("#show_button_"+number+"").attr("flag", "1");
	jQuery("#show_button_"+number+"").html("Show");
	jQuery("#upload_detail_"+number+"").hide('slow');
	
	}

}


function show_box(box, number)
{
jQuery("#"+box+"_"+number+"").show('slow');
}

function hide_box(box, number)
{
jQuery("#"+box+"_"+number+"").hide();
}

function insert_as_clicked(number)
{
var insert_as= parseInt(jQuery('input[name=insert_as_'+number+']:checked').val());
switch(insert_as)
	{
	 case 1:
	  {
	  hide_box("lightbox_option_box", number);
	  hide_box("new_window_option_box", number);
	  hide_box("same_window_option_box", number);
	  break;
	  }
	 case 2:
	  {
	  show_box("lightbox_option_box", number);
	  hide_box("new_window_option_box", number);
	  hide_box("same_window_option_box", number);
	  break;
	  }
	 case 3:
	  {
	  hide_box("lightbox_option_box", number);
	  show_box("new_window_option_box", number);
	  hide_box("same_window_option_box", number);
	  break;
	  }
	 case 4:
	  {
	  hide_box("lightbox_option_box", number);
	  hide_box("new_window_option_box", number);
	  show_box("same_window_option_box", number);
	  break;
	  }	  
	}// end switch
}

function lightbox_option_clicked(number)
{
var lightbox_option= parseInt(jQuery('input[name=lightbox_option_'+number+']:checked').val());
	switch(lightbox_option)
	{
	  case 1:
	  {
	  show_box("lightbox_title", number);
	  break;
	  }
	  case 2:
	  {
	  hide_box("lightbox_title", number);
	  break;
	  }
	}

}

function more_lightbox_option_clicked(number)
{
var more_lightbox_option= parseInt(jQuery('input[name=more_lightbox_option_'+number+']:checked').val());
	switch(more_lightbox_option)
	{
	  case 1:
	  {
	  show_box("lightbox_link_text", number);
	  hide_box("custom_button_area", number);
	  break;
	  }
	  case 2:
	  {
	  hide_box("lightbox_link_text", number);
	  hide_box("custom_button_area", number);
	  break;
	  }
	  case 3:
	  {
	  hide_box("lightbox_link_text", number);
	  show_box("custom_button_area", number);
	  break;
	  }	  
	}
}

function show_button(number)
{
	var btn_src=jQuery("#buttons_"+number).val();
	if(btn_src=='0')
	jQuery("#button_view_"+number).html('');
	else
	jQuery("#button_view_"+number).html('<img src="'+btn_src+'" />');
}

function open_new_window_option_clicked(number)
{
var open_new_window_option= parseInt(jQuery('input[name=open_new_window_option_'+number+']:checked').val());
	switch(open_new_window_option)
	{
	  case 1:
	  {
	  show_box("open_new_window_link_text", number);
	  break;
	  }
	  case 2:
	  {
	  hide_box("open_new_window_link_text", number);
	  break;
	  }
	}

}

function open_same_window_option_clicked(number)
{
var open_same_window_option= parseInt(jQuery('input[name=open_same_window_option_'+number+']:checked').val());
	switch(open_same_window_option)
	{
	  case 1:
	  {
	  show_box("open_same_window_link_text", number);
	  break;
	  }
	  case 2:
	  {
	  hide_box("open_same_window_link_text", number);
	  break;
	  }
	}

}

function show_hide_custom_size_area(number)
{
	var size_opt=jQuery("#size_opt_"+number).val();
	if(size_opt=="custom")
	{
	jQuery("#custom_size_area_"+number).show();
	}
	else
	{
	jQuery("#custom_size_area_"+number).hide();
	}
}


function add_to_post(number)
{
	<?php if($tab=="upload"){?>
	  //rename action will fired.
	  var old_name=jQuery("#dir_name_1").val();
	  var regex = new RegExp('_', 'g');
	  var temp=old_name.replace(regex," ");
	  var new_name=jQuery.trim(jQuery("#title").val());
	  if(new_name!="" && new_name!=temp)
	  {
	  rename_dir(old_name, new_name);
	  }
	  else
	  {
	  insert_into_post(number);
	  }
	<?php }else{?>insert_into_post(number);<?php }?>
	
}


function insert_into_post(number)
{
<?php $opt=get_quiz_embeder_options();?>
var lightbox_script="<?php echo $opt["lightbox_script"]; ?>";
var link_text='';
var uploaded_file_url=jQuery("#file_url_"+number+"").val();
if(uploaded_file_url==""){alert("Please Upload A Zip File"); return;}
var win = window.dialogArguments || opener || parent || top; 
var insert_as= parseInt(jQuery('input[name=insert_as_'+number+']:checked').val());
//alert(insert_as);
	var restrict_access_option=parseInt(jQuery("input[name=restrict_access_option_"+number+"]:checked").val());
	var shortCode="";
	var shortCodeType="";
	var shortCodeOptions="";
	
	
	switch(insert_as)
	{
	 case 1:
	  {
	  //alert("iFrame");
	  shortCodeType="iframe";
	  shortCodeOptions=" width='100%' height='600' frameborder='0' src='"+uploaded_file_url+"'";
	  break;
	  }
	 case 2:
	  {
	  //alert("Lightbox");
	  shortCodeType="lightbox";
	  shortCodeOptions=" href='"+uploaded_file_url+"'";
	  	var more_lightbox_option= parseInt(jQuery('input[name=more_lightbox_option_'+number+']:checked').val());
			if(more_lightbox_option==1)
			{
			  link_text=jQuery('#lightbox_link_text_'+number+'').val();
			  shortCodeOptions=shortCodeOptions+" link_text='"+link_text+"'";
			}
			else if(more_lightbox_option==3)
			{
			  var btn_src=jQuery("#buttons_"+number).val();
			  if(btn_src !='0')
			  shortCodeOptions=shortCodeOptions+" button='"+btn_src+"'";
			}
						
		
			var lightbox_option= parseInt(jQuery('input[name=lightbox_option_'+number+']:checked').val());
			if(lightbox_option==1)
			{
			var lightbox_title= jQuery('#lightbox_title_'+number+'').val();
			shortCodeOptions=shortCodeOptions+" title='"+lightbox_title+"'";
			}
			//MORE NEW SETTINGS
			if(lightbox_script=="colorbox")
			{
				var colorbox_theme=jQuery("#colorbox_theme_"+number).val();
				if(colorbox_theme !="default_from_dashboard")
				shortCodeOptions=shortCodeOptions+" colorbox_theme='"+colorbox_theme+"'";
			}
			
			//SCROLLBAR OPTIONS		
			var scrollbar= jQuery('input[name=scrollbar_'+number+']:checked').val();
			if(scrollbar=='no'){shortCodeOptions=shortCodeOptions+" scrollbar='no'";}
			
			//SIZE OPTIONS
			var size_opt=jQuery("#size_opt_"+number).val();
			shortCodeOptions=shortCodeOptions+" size_opt='"+size_opt+"'";
			if(size_opt=="custom")
			{
				var w=parseInt(jQuery("#width_"+number).val());
				var wt=jQuery("#width_type_"+number).val();
				var h=parseInt(jQuery("#height_"+number).val());
				var ht=jQuery("#height_type_"+number).val();
				var width=""+w+wt; var height=""+h+ht;
				shortCodeOptions=shortCodeOptions+" width='"+width+"'";
				shortCodeOptions=shortCodeOptions+" height='"+height+"'";
			}
			
			
	  break;
	  }
	 case 3:
	  {
	  	//alert("Link that opens in a new window");
	  shortCodeType="open_link_in_new_window";
	  shortCodeOptions=" href='"+uploaded_file_url+"'";
	  
	  	var open_new_window_option= parseInt(jQuery('input[name=open_new_window_option_'+number+']:checked').val());
			if(open_new_window_option==1)
			{
			  link_text=jQuery('#open_new_window_link_text_'+number+'').val();
			  shortCodeOptions=shortCodeOptions+" link_text='"+link_text+"'";
			  
			}
	  break;
	  }
	 case 4:
	  {
	 // alert("Link that opens in the same window");
	 shortCodeType="open_link_in_same_window";
	 shortCodeOptions=" href='"+uploaded_file_url+"'";
	  	var open_same_window_link_text= parseInt(jQuery('input[name=open_same_window_option_'+number+']:checked').val());
		//var link_text="";
			if(open_same_window_link_text==1)
			{
			
			  link_text=jQuery('#open_same_window_link_text_'+number+'').val();
			  shortCodeOptions=shortCodeOptions+" link_text='"+link_text+"'";
			}

		
	  break;
	  }	  
	}
	
	shortCode="[iframe_loader type='"+shortCodeType+"' " +shortCodeOptions+"]";
	win.send_to_editor(shortCode);

}// end insert_into_post()

function rename_dir(old_name, new_name)
{

var loading_text='<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL;?>loading_16x16.gif" alt="Loading" /> Saving....';
	jQuery('#insert_msg_1').html(loading_text);	
		
			
			jQuery.getJSON("<?PHP bloginfo('url') ?>/wp-admin/admin-ajax.php?action=rename_dir&dir_name="+old_name+"&title="+new_name, function(data) {
			
				//alert(data[0]); 
				if(data[0]=="success")
				{
				 var new_renamed_dir_name=data[1];
				 var old_file_name = jQuery('#file_name_1').val();
				 jQuery('#file_url_1').val("<?php echo getUploadsUrl();?>"+new_renamed_dir_name+"/"+old_file_name);	
				 jQuery('#insert_msg_1').html("");
				 insert_into_post(1);
				}
				else
				{
				jQuery('#insert_msg_1').html("");
				alert(data[1])
				}
			});

}

function delete_dir(number)
{

	var dir_name=jQuery("#dir_name_"+number+"").val();
	var loading_image='&nbsp;&nbsp;<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL;?>loading_16x16.gif" alt="Launch Presentation" />&nbsp;Deleting..'
	var loading_text='<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL;?>loading_16x16.gif" alt="Loading" /> Deleting....';
	
	
	if(dir_name!="")
	{			
		if (confirm("Are you sure?"))
		{
		jQuery("#delete_button_"+number+"").hide();
		jQuery("#loading_box_"+number+"").html(loading_image);
		jQuery("#insert_msg_"+number+"").html(loading_text);
		
		jQuery.post("admin-ajax.php",{dir : dir_name,action:'del_dir'},function(data){
			//alert("Deleted");
			<?php if($tab=="upload"){?>
			jQuery("#insert_msg_"+number+"").html("");
			jQuery("#upload_detail_"+number+"").remove();
			location.reload();
			<?php }else{?>
			jQuery("#content_item_"+number+"").remove();
			<?php }?>
				
			
			});
		}
	}else{alert("No Data Found To Delete");}
					
}// end delete_dir()


		
</script>
<?php
}#end function print_js()

function print_upload()
{
// echo "<h3>Start print_upload</h3>";
print_js();
?>
<?PHP 
#following codes SOURCE wp-admin/includes/media.php
$upload_size_unit = $max_upload_size = wp_max_upload_size();
$sizes = array( 'KB', 'MB', 'GB' );

for ( $u = -1; $upload_size_unit > 1024 && $u < count( $sizes ) - 1; $u++ ) {
	$upload_size_unit /= 1024;
}

if ( $u < 0 ) {
	$upload_size_unit = 0;
	$u = 0;
} else {
	$upload_size_unit = (int) $upload_size_unit;
}
$dirs = getDirs();
if (count($dirs)> quiz_embeder_count())
{
echo '<span style="color:#ff0000; font-size:15px;">The trial version only supports three uploads. Please purchase the full version with unlimited uploads at <a href="http://www.elearningfreak.com/presenter/insert-or-embed-articulate-content-into-wordpress-plugin-premium/" target="_blank">www.elearningfreak.com</a></span>';	
}
else
{
?>
<form enctype="multipart/form-data" id="myForm1" action="admin-ajax.php" method="POST">
<div id="container">
    <a id="pickfiles" href="javascript:;" class="button">Choose your zip file</a>
    <a id="uploadfiles" href="javascript:;" class="button button-primary">Upload!</a>
</div>
<div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
 <div id="fileerror"></div>
 
<br />
<pre id="console"></pre>


</form>
 
<script type="text/javascript">
// Custom example logic
 
var art_uploader = new plupload.Uploader({
    runtimes : 'html5,flash,silverlight,html4',
    url: '<?php echo admin_url('admin-ajax.php'); ?>',
	chunk_size: '950kb',
	 multi_selection: false,
	'file_data_name':  'async-upload' ,
	multipart_params : {
        "_ajax_nonce" : "<?php echo wp_create_nonce('articulate_upload_file'); ?>",
        "action" : "articulate_upload_file"
    },
    browse_button : 'pickfiles', // you can pass in id...
    container: document.getElementById('container'), // ... or DOM Element itself
   
     
    filters : {
        max_file_size : '0',
        mime_types: [
        
            {title : "Zip files", extensions : "zip"}
        ]
    },
 
    // Flash settings
    flash_swf_url : '<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>js/plupload/js/Moxie.swf',
 
    // Silverlight settings
    silverlight_xap_url : '<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>js/plupload/js/Moxie.xap',
     

    init: {
        PostInit: function() {
            document.getElementById('filelist').innerHTML = '';
 
            document.getElementById('uploadfiles').onclick = function() {
                art_uploader.start();
                return false;
            };
        },
 
        FilesAdded: function(up, files) {
            plupload.each(files, function(file) {
                
				
            });
        },
 
        UploadProgress: function(up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },
 
        Error: function(up, err) {
            document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
        }
    }
});
 
art_uploader.init();
 
jQuery(function($) { 
 
	 art_uploader.bind('FilesAdded', function(up, files) {
		
		$("#filelist").show();
		$("#filelist").removeClass('uploaded_file');
		if (art_uploader.files.length > 1) {
			art_uploader.removeFile(art_uploader.files[0]);
		}
		 
		$.each(files, function(i, file) {
			$('#filelist').html(
				'<div id="' + file.id + '">' +
				file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
			'</div>');
		});
	 
		console.log('art_uploader.files.length:',art_uploader.files.length);
		up.refresh(); // Reposition Flash/Silverlight
	});
	
	
	art_uploader.bind('FileUploaded', function(upldr, file, object) {
var upload_response = jQuery.parseJSON(object.response);

	console.log(upload_response);
	if(upload_response.OK == 1){
				$("#filelist").addClass('uploaded_file');
				$("#filelist").append(' <span>Upload Complete!</span>');
					dir = upload_response.path;
					var uploaded_dir_neme=upload_response.folder;
					var win = window.dialogArguments || opener || parent || top; 
					jQuery("#file_url_1").val(dir);
					jQuery("#dir_name_1").val(uploaded_dir_neme);
					jQuery("#file_name_1").val(upload_response.name);
				
					var regex = new RegExp('_', 'g');
					jQuery("#title").val(uploaded_dir_neme.replace(regex," "));
			
					show_detail(1);
	
	
	}else{
		$('#fileerror').show();
	$('#fileerror').html(upload_response.info);	
	}
});
	 
     
});
</script>
<p><i>Please choose a .zip file that you published with the Articulate software </i></p>
<img id="media_loading" style='display:none;' src= "<?php echo getPluginUrl() . "loading.gif" ;?>" /><br />
<?php 
 	print_detail_form(1);
}
?>



<p><b>Need help?  See the screencast below:</b></p>
<iframe src="https://www.youtube.com/embed/AwcIsxpkvM4" width="600" height="366" frameborder="0"></iframe>
<p/>
<p/>
<?php if(!function_exists('quiz_embeder_count_premium')){ echo '<iframe src="http://www.elearningfreak.com/wordpresspluginlatest.html" width="600px" frameborder="0">
</iframe>'; } ?>
<p/>


<?php
 //echo "<h3>END print_upload</h3>";
}


// handle uploaded file here
add_action('wp_ajax_articulate_upload_file','articulate_upload_ajax_file');
function articulate_upload_ajax_file(){

  check_ajax_referer('articulate_upload_file');
$count_dirs = getDirs();
if (count($count_dirs) <= quiz_embeder_count())
{

  // you can use WP's wp_handle_upload() function:
  $file = $_FILES['async-upload'];

$upload_dir = wp_upload_dir();
	$dir = '' . $upload_dir['basedir'] . '/articulate_uploads';
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }


if (empty($_FILES) || $_FILES['async-upload']['error']) {
  die('{"OK": 0, "info": "Failed to move uploaded file.  Please check if the folder has write permissions."}');
}
 
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
 
$fileName = isset($_REQUEST["name"]) ? sanitize_file_name($_REQUEST["name"]) : sanitize_file_name($_FILES["async-upload"]["name"]);
$filePath = "".$dir."/".sanitize_file_name($fileName)."";
 
 
// Open temp file
$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
if ($out) {
  // Read binary input stream and append it to temp file
  $in = @fopen($_FILES['async-upload']['tmp_name'], "rb");
 
  if ($in) {
    while ($buff = fread($in, 4096))
      fwrite($out, $buff);
  } else
    die('{"OK": 0, "info": "Failed to open input stream. Please check if the folder has write permissions"}');
 
  @fclose($in);
  @fclose($out);
 
  @unlink($_FILES['async-upload']['tmp_name']);
} else
  die('{"OK": 0, "info": "Failed to open output stream.  Please check if the folder has write permissions"}');
 
 
// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
  // Strip the temp .part suffix off
  rename("{$filePath}.part", $filePath);
 
  #start extracting
 
  			#unzip file
		$dir = explode(".",$fileName);
		$dir[0] = str_replace(" ","_",$dir[0]);		
			
			$target = getUploadsPath().$dir[0];
			$file = $filePath ;			
			

 			while(file_exists($target))
			{
			$r = rand(1,10);
			$target .= $r;
			$dir[0] .= $r;
			}
			
			
			$arr = extractZip($file,$target,$dir[0]);
  			unlink($filePath);
			 die('{"OK": 1, "info": "Upload successful.", "folder" : '.json_encode($arr[2]).', "path" : '.json_encode($arr[1]).', "name" : '.json_encode($arr[3]).', "target": "'.$target.'"}');
  
}else{
	die('{"OK": 1, "info": "Uploading chunks!"}');
}
 

}else{
	die('{"OK": 0, "info": "<span style=\'color:#ff0000; font-size:15px;\'>The trial version only supports three uploads. Please purchase the full version with unlimited uploads at <a href=\'http://www.elearningfreak.com/presenter/insert-or-embed-articulate-content-into-wordpress-plugin-premium/\' target=\'_blank\'>www.elearningfreak.com</a></span>"}');
}

  exit;
};

function wp_ajax_del_dir()
{
$dir = getUploadsPath().$_POST['dir'];
rrmdir($dir);
die();
}


function wp_ajax_rename_dir()
{
$arr=array();
	if(isset($_REQUEST['dir_name']) && $_REQUEST['dir_name']!="")
	{
	$target = getUploadsPath().$_REQUEST['dir_name'];
		if(file_exists($target))
		{
			
			if(isset($_REQUEST['title']) && $_REQUEST['title']!="")
			{
			$title=trim($_REQUEST['title']);
				if($title)
				{   
					$title=str_replace(" ","_" , $title);
					$new_file=getUploadsPath().$title;
					while(file_exists($new_file))
					{
					$r = rand(1,10);
					$new_file .= $r;
					$title .= $r;
					}
					rename($target, $new_file);
					$arr[0]="success";
					$arr[1]=$title;
				}
				else
				{
				$arr[0]="error";
				$arr[1]="Failed: New Title Was Not Given";
				}
			}
			else
			{
			$arr[0]="error";
			$arr[1]="Failed: New Title Was Not Given";
			}
		}
		else
		{
		$arr[0]="error";
		$arr[1]="Failed: Given File is Not Exits";
		}
	}
	else
	{
	$arr[0]="error";
	$arr[1]="Failed: Targeted Directory Name Was Not Given";
	}
echo json_encode($arr);	
	
die();
}
#check if quiz is in a folder as many times as it takes    @anthonysbrown
function wp_ajax_quiz_check_folder($dir){
	
	$arr = scandir($dir);
	foreach($arr as $key=>$folder){
		
		if($folder != '.' && $folder != '..'){
			$structure[] = $folder;
		}
	}
	
	if(count($structure) == 1){
		$sub_folder = $dir.'/'.$structure[0].'/';
		rename($dir, $dir.'_temp');
		rename($dir.'_temp/'.$structure[0].'/', $dir);
		rmdir($dir.'_temp');
		
		wp_ajax_quiz_check_folder($dir);
	}
	
}

function extractZip($fileName,$target,$dir)
{
		 $arr = array();
 $zip = new ZipArchive;
     $res = $zip->open($fileName);
     if ($res === TRUE) {
         $zip->extractTo($target);
         $zip->close();
#check if quiz is in a folder as many times as it takes    @anthonysbrown
		 wp_ajax_quiz_check_folder($target);
		 $file = getFile($target);

		 $arr[0] = 'uploaded'; 
		 $arr[1] = getUploadsUrl().$dir."/".$file; 
		 $arr[2] = $dir;
		 $arr[3] =$file;
         
     } else {
		$arr[0] ="file upload failed";
      
     }
	 return  $arr;

}

function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
 } 

/*-----added by oneTarek-----*/

function quiz_embeder_wp_head()
{
$opt=get_quiz_embeder_options();
global $quiz_embeder_size_opt;
global $quiz_embeder_width;
global $quiz_embeder_height;
global $quiz_embeder_scrollbar;
if(isset($quiz_embeder_size_opt))
	{
	if($quiz_embeder_size_opt=="custom_form_dashboard"){$quiz_embeder_width=$opt['width'].$opt['width_type']; $quiz_embeder_height=$opt['height'].$opt['height_type'];}
	}

?>
	<!--QUIZ_EMBEDER START-->
<?php 	
	if($opt["lightbox_script"]=="nivo_lightbox")
	{
?>
	<!--nivo lite box-->
	<link rel="stylesheet" href="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL?>nivo-lightbox/nivo-lightbox.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL?>nivo-lightbox/themes/default/default.css" type="text/css" />
	<?php if($quiz_embeder_size_opt!="lightebox_default"){ ?>
	<style type="text/css">
	.nivo-lightbox-content {
	width: <?php echo $quiz_embeder_width; ?>;
	height: <?php echo $quiz_embeder_height; ?>;
	margin:auto;
	}
		<?php if(isset($quiz_embeder_scrollbar) && $quiz_embeder_scrollbar=="no"){?>
		.nivo-lightbox-content iframe{
		width: <?php echo $quiz_embeder_width; ?>;
		height: <?php echo $quiz_embeder_height; ?>;
		overflow:hidden;
		}
		<?php }?>
	</style>
	<?php }?>
	<script src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL?>nivo-lightbox/nivo-lightbox.min.js"></script>	
	<!--end nivo lite box-->	
<?php } else{
	global $quiz_embeder_colorbox_theme; if(!isset($quiz_embeder_colorbox_theme) || $quiz_embeder_colorbox_theme==""){$quiz_embeder_colorbox_theme=$opt["colorbox_theme"];}
	if($quiz_embeder_size_opt!="custom_form_dashboard" && $quiz_embeder_size_opt!="custom"){ $quiz_embeder_width="80%"; $quiz_embeder_height="80%";}//default
?>
	<!--QUIZ_EMBEDER START-->
	<link rel="stylesheet" href="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL."colorbox/themes/".$quiz_embeder_colorbox_theme."/colorbox.css" ;?>" />
	<script type="text/javascript" src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL."colorbox/jquery.colorbox-min.js" ;?>" ></script>
<?php } ?>

	<script type="text/javascript">
		jQuery(document).ready(function($){
			<?php if($opt["lightbox_script"]=="nivo_lightbox"){?>			
			$('.nivo_lightbox_iframe').nivoLightbox({
				effect: '<?php echo $opt['nivo_lightbox_effect'] ?>',                             // The effect to use when showing the lightbox
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
			<?php }else{?>
			//Examples of how to assign the ColorBox event to elements
			$(".colorbox_iframe").colorbox({iframe:true, transition:"<?php echo $opt['colorbox_transition']?>", width:"<?php echo $quiz_embeder_width ?>", height:"<?php echo $quiz_embeder_height ?>", scrolling:<?php echo($quiz_embeder_scrollbar=="no")?'false':'true'; ?>});
			<?php }?>			
						
		});
	</script>	
	<!--QUIZ_EMBEDER END-->
<?php 
}
	
function quiz_embeder_get_colorbox_themes()
{
	$themes=array(
		"default"=>array("dir"=>"default", "text"=>"Default"),
		"vintage"=>array("dir"=>"vintage", "text"=>"Vintage"),
		"dark-rimmed"=>array("dir"=>"dark-rimmed", "text"=>"Dark Rimmed"),
		"fancy-overlay"=>array("dir"=>"fancy-overlay", "text"=>"Fancy Overlay"),
		"minimal"=>array("dir"=>"minimal", "text"=>"Minimal"),
		"minimal-circles"=>array("dir"=>"minimal-circles", "text"=>"Minimal Circles"),
		"sketch-toon"=>array("dir"=>"sketch-toon", "text"=>"Sketch / Toon"),
		"noimage"=>array("dir"=>"noimage", "text"=>"No Image"),
		"noimage-rounded"=>array("dir"=>"noimage-rounded", "text"=>"No Image Rounded"),
		"noimage-blue"=>array("dir"=>"noimage-blue", "text"=>"No Image Blue"),
		"noimage-polaroid"=>array("dir"=>"noimage-polaroid", "text"=>"No Image Polaroid"),
		"shadow"=>array("dir"=>"shadow", "text"=>"Shadow"),
		"wood-table"=>array("dir"=>"wood-table", "text"=>"Wood Table") 
	);
 return $themes;

}

function quiz_embeder_admin_scripts()
 {
	 if (isset($_GET['page']) && $_GET['page'] == 'articulate_content')
	 {
	 wp_enqueue_script('jquery');
	 wp_enqueue_media();
	 wp_register_script('quiz_embeder_upload', WP_QUIZ_EMBEDER_PLUGIN_URL.'js/admin.js');
	 wp_enqueue_script('quiz_embeder_upload');
	 }
 }
 add_action( 'admin_enqueue_scripts', 'quiz_embeder_admin_scripts' );
?>