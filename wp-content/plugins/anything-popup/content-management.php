<div class="wrap">
  <?php
 	global $wpdb;
	@$mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=anything-popup/anything-popup.php";
    @$DID=@$_GET["DID"];
    @$AC=@$_GET["AC"];
    @$submittext = "Insert Message";
	if($AC <> "DEL" and trim(@$_POST['pop_title']) <>"")
    {
			if($_POST['pop_id'] == "" )
			{
					$sql = "insert into ".AnythingPopupTable.""
					. " set `pop_title` = '" . mysql_real_escape_string(trim($_POST['pop_title']))
					. "', `pop_width` = '" . $_POST['pop_width']
					. "', `pop_height` = '" . $_POST['pop_height']
					. "', `pop_headercolor` = '" . $_POST['pop_headercolor']
					. "', `pop_bordercolor` = '" . $_POST['pop_bordercolor']
					. "', `pop_header_fontcolor` = '" . $_POST['pop_header_fontcolor']
					. "', `pop_content` = '" . trim($_POST['pop_content'])
					. "', `pop_caption` = '" . trim($_POST['pop_caption'])
					. "'";	
			}
			else
			{
					$sql = "update ".AnythingPopupTable.""
					. " set `pop_title` = '" . mysql_real_escape_string(trim($_POST['pop_title']))
					. "', `pop_width` = '" . $_POST['pop_width']
					. "', `pop_height` = '" . $_POST['pop_height']
					. "', `pop_headercolor` = '" . $_POST['pop_headercolor']
					. "', `pop_bordercolor` = '" . $_POST['pop_bordercolor']
					. "', `pop_header_fontcolor` = '" . $_POST['pop_header_fontcolor']
					. "', `pop_content` = '" . trim($_POST['pop_content'])
					. "', `pop_caption` = '" . trim($_POST['pop_caption'])
					. "' where `pop_id` = '" . $_POST['pop_id'] 
					. "'";	
			}
			$wpdb->get_results($sql);
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".AnythingPopupTable." where pop_id=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        $data = $wpdb->get_results("select * from ".AnythingPopupTable." where pop_id=$DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
           return;
        }
        $data = $data[0];
        if ( !empty($data) ) 
		{
			$pop_id_x = stripslashes($data->pop_id);
			$pop_width_x = stripslashes($data->pop_width);
			$pop_height_x = stripslashes($data->pop_height);
			$pop_headercolor_x = stripslashes($data->pop_headercolor);
			$pop_bordercolor_x = stripslashes($data->pop_bordercolor);
			$pop_header_fontcolor_x = stripslashes($data->pop_header_fontcolor);
			$pop_title_x = htmlspecialchars(stripslashes($data->pop_title));
			$pop_content_x = stripslashes($data->pop_content);
			$pop_caption_x = htmlspecialchars(stripslashes($data->pop_caption));
		} 
        $submittext = "Update Message";
    }
 ?>
  <h2>Anything Popup</h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/anything-popup/setting.js"></script>
  <form name="pop_form" method="post" action="<?php echo $mainurl; ?>" onsubmit="return _pop_submit()"  >
    <table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td width="167">Popup Window Width</td>
        <td width="1105">:
          <input name="pop_width" type="text" id="pop_width" value="<?php echo @$pop_width_x; ?>" size="20" maxlength="3" />
          (Ex: 300)</td>
      </tr>
      <tr>
        <td>Popup Window Height </td>
        <td>:
          <input name="pop_height" type="text" id="pop_height" value="<?php echo @$pop_height_x; ?>" size="20" maxlength="3" />
          (Ex: 250)</td>
      </tr>
      <tr>
        <td>Header Color </td>
        <td>:
          <input name="pop_headercolor" type="text" id="pop_headercolor" value="<?php echo @$pop_headercolor_x; ?>" size="20" maxlength="7" />
          (Ex: #4D4D4D)</td>
      </tr>
      <tr>
        <td>Border Color </td>
        <td>:
          <input name="pop_bordercolor" type="text" id="pop_bordercolor" value="<?php echo @$pop_bordercolor_x; ?>" size="20" maxlength="7" />
          (Ex: #4D4D4D)</td>
      </tr>
      <tr>
        <td>Header Font Color </td>
        <td>:
          <input name="pop_header_fontcolor" type="text" id="pop_header_fontcolor" value="<?php echo @$pop_header_fontcolor_x; ?>" size="20" maxlength="7" />
          (Ex: #FFFFFF)</td>
      </tr>
      <tr>
        <td>Popup Title </td>
        <td>:
          <input name="pop_title" type="text" id="pop_title" value="<?php echo @$pop_title_x; ?>" size="50" maxlength="500" />
        </td>
      </tr>
      <tr>
        <td colspan="2">Popup Link Text/Label/Image </td>
      </tr>
      <tr>
        <td colspan="2">
          <input name="pop_caption" type="text" id="pop_caption" value="<?php echo @$pop_caption_x; ?>" size="150" maxlength="200" />
        </td>
      </tr>
      <tr>
        <td colspan="2">Add The Popup Content (Use below editor):</td>
      </tr>
      <tr>
        <td colspan="2"><?php wp_editor(@$pop_content_x, "pop_content");?></td>
      </tr>
      <tr>
        <td colspan="2"><input name="publish" lang="publish" class="button-primary" value="<?php echo $submittext?>" type="submit" />
          <input name="publish" lang="publish" class="button-primary" onclick="_pop_redirect()" value="Cancel" type="button" />
          <input name="publish" lang="publish" class="button-primary" onclick="_pop_help()" value="Help" type="button" />
        </td>
      </tr>
    </table>
    <input name="pop_id" id="pop_id" type="hidden" value="<?php echo @$pop_id_x; ?>">
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".AnythingPopupTable." order by pop_id");
	if ( empty($data) ) 
	{ 
		echo "<div id='message' class='error'>No data available! use below form to create!</div>";
		return;
	}
	?>
    <form name="pop_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="17%" align="left" scope="col">Short Code</th>
            <th align="left" scope="col">Popup Content</th>
            <th width="8%" align="left" scope="col">Action</th>
          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
		$displayisthere="True";
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle">[ANYTHING-POPUP:<?php echo(stripslashes($data->pop_id)); ?>]</td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->pop_title)); ?></td>
            <td align="left" valign="middle"><a href="options-general.php?page=anything-popup/anything-popup.php&DID=<?php echo($data->pop_id); ?>">Edit</a> &nbsp; <a onClick="javascript:_pop_delete('<?php echo($data->pop_id); ?>')" href="javascript:void(0);">Delete</a></td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
        <?php if($displayisthere<>"True") { ?>
        <tr>
          <td colspan="6" align="center" style="color:#FF0000" valign="middle">No message available</td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
  <?php include_once("help.php"); ?>
</div>
