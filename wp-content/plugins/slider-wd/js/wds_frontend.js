function wds_show_thumb(key, wds) {
  var data = window["wds_data_" + wds][key];
  var bg_fit = window["wds_data_" + wds][key]["bg_fit"];
  var full_width = window["wds_data_" + wds][key]["full_width"];
  var bull_position = window["wds_data_" + wds][key]["bull_position"];
  var image_url = data["image_thumb_url"];
  var dot_conteiner_width = jQuery('.wds_slideshow_dots_container_' + wds).width() / 2;
  var dot_conteiner_height = jQuery('.wds_slideshow_dots_container_' + wds).height();
  var wds_bulframe_width = jQuery('.wds_bulframe_' + wds).width() / 2;
  var dot_position = jQuery('#wds_dots_' + key + '_' + wds ).position();
  var dot_width = jQuery('#wds_dots_' + key + '_' + wds ).width() / 2;
  dot_position = dot_position.left;
  var childPos = jQuery('#wds_dots_' + key + '_' + wds ).offset();
  var parentPos = jQuery('.wds_slideshow_dots_thumbnails_' + wds).parent().offset();
  var childOffset = childPos.left - parentPos.left;
  var right_offset = 0;
  var rt = (dot_conteiner_width * 2) - childOffset;
  if (wds_bulframe_width >= rt && rt > 0) {
    right_offset =  wds_bulframe_width - rt;
    dot_width = 0;
  }
  if (full_width == '1') {
    if (wds_bulframe_width >= childOffset) {
      wds_bulframe_width = childOffset - parentPos.left ;  
      dot_width = 0;
    }
  }
  else {
    if (wds_bulframe_width >= childOffset) {
      wds_bulframe_width = childOffset ;  
      dot_width = 0;
    }
  }
  dot_position = childOffset - wds_bulframe_width + dot_width - right_offset;
  jQuery('.wds_bulframe_' + wds ).css({
    'position' : 'absolute',
    'z-index' : '9999',
    'left': dot_position,
    'background-image' :'url("' + image_url + '")',
    'background-size' : bg_fit,
    'background-repeat' : 'no-repeat',
    'background-position' : 'center center'});
  jQuery('.wds_bulframe_' + wds).css(bull_position, dot_conteiner_height);
  jQuery('.wds_bulframe_' + wds).fadeIn();
}

function wds_hide_thumb(wds) {
  jQuery('.wds_bulframe_' + wds).css({'background-image':''});
  jQuery('.wds_bulframe_' + wds).fadeOut();
}