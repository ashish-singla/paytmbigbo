<?php

class WDSControllerSliders_wds {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    $task = WDW_S_Library::get('task');
    $id = WDW_S_Library::get('current_id', 0);
    $message = WDW_S_Library::get('message');
    echo WDW_S_Library::message_id($message);
    if (method_exists($this, $task)) {
      check_admin_referer('nonce_wd', 'nonce_wd');
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_S_DIR . "/admin/models/WDSModelSliders_wds.php";
    $model = new WDSModelSliders_wds();

    require_once WD_S_DIR . "/admin/views/WDSViewSliders_wds.php";
    $view = new WDSViewSliders_wds($model);
    $view->display();
  }

  public function add() {
    require_once WD_S_DIR . "/admin/models/WDSModelSliders_wds.php";
    $model = new WDSModelSliders_wds();

    require_once WD_S_DIR . "/admin/views/WDSViewSliders_wds.php";
    $view = new WDSViewSliders_wds($model);
    $view->edit(0);
  }

  public function edit() {
    require_once WD_S_DIR . "/admin/models/WDSModelSliders_wds.php";
    $model = new WDSModelSliders_wds();

    require_once WD_S_DIR . "/admin/views/WDSViewSliders_wds.php";
    $view = new WDSViewSliders_wds($model);
    $id = ((isset($_POST['current_id']) && esc_html(stripslashes($_POST['current_id'])) != '') ? esc_html(stripslashes($_POST['current_id'])) : 0);
    $view->edit($id);
  }

  public function save() {
    $page = WDW_S_Library::get('page');
    WDW_S_Library::spider_redirect(add_query_arg(array('page' => $page, 'task' => 'display', 'message' => 1), admin_url('admin.php')));
  }

  public function apply() {
    $this->save_slider_db();
    $this->save_slide_db();
    $this->edit();
  }

  // public function save_slide() {
    // $this->save_slide_db();
    // $this->edit();
  // }
  
  public function save_slider_db() {
    global $wpdb;
    $del_slide_ids_string = (isset($_POST['del_slide_ids_string']) ? substr(esc_html(stripslashes($_POST['del_slide_ids_string'])), 0, -1) : '');
    if ($del_slide_ids_string) {
      $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'wdsslide WHERE id IN (' . $del_slide_ids_string . ')');
    }
    if (get_option("wds_theme_version")) {
      $allow = FALSE;
    }
    else {
      $allow = TRUE;
    }
    $slider_id = (isset($_POST['current_id']) ? (int) $_POST['current_id'] : 0);
    $name = ((isset($_POST['name'])) ? esc_html(stripslashes($_POST['name'])) : '');
    $published = ((isset($_POST['published'])) ? (int) esc_html(stripslashes($_POST['published'])) : 1);
    $full_width = ((isset($_POST['full_width'])) ? (int) esc_html(stripslashes($_POST['full_width'])) : 0);
    $spider_uploader = ((isset($_POST['spider_uploader'])) ? (int) esc_html(stripslashes($_POST['spider_uploader'])) : 0);
    $width = ((isset($_POST['width'])) ? (int) esc_html(stripslashes($_POST['width'])) : 800);
    $height = ((isset($_POST['height'])) ? (int) esc_html((stripslashes($_POST['height']))) : 300);
    $bg_fit = ((isset($_POST['bg_fit'])) ? esc_html(stripslashes($_POST['bg_fit'])) : 'cover');
    $align = ((isset($_POST['align'])) ? esc_html(stripslashes($_POST['align'])) : 'center');
    $effect = ((isset($_POST['effect'])) ? esc_html(stripslashes($_POST['effect'])) : 'fade');
    $time_intervval = ((isset($_POST['time_intervval'])) ? (int) esc_html(stripslashes($_POST['time_intervval'])) : 5);
    $autoplay = ((isset($_POST['autoplay'])) ? (int) esc_html(stripslashes($_POST['autoplay'])) : 0);
    $shuffle = ((isset($_POST['shuffle'])) ? (int) esc_html(stripslashes($_POST['shuffle'])) : 0);
    $music = ((isset($_POST['music'])) ? (int) esc_html(stripslashes($_POST['music'])) : 0);	
    $music_url = ((isset($_POST['music_url'])) ? esc_html(stripslashes($_POST['music_url'])) : '');
    $preload_images = ((isset($_POST['preload_images'])) ? (int) esc_html(stripslashes($_POST['preload_images'])) : 1);
    $background_color = ((isset($_POST['background_color'])) ? esc_html(stripslashes($_POST['background_color'])) : '000000');
    $background_transparent = ((isset($_POST['background_transparent'])) ? esc_html(stripslashes($_POST['background_transparent'])) : 100);
    $glb_border_width = ((isset($_POST['glb_border_width'])) ? (int) esc_html(stripslashes($_POST['glb_border_width'])) : 0);
    $glb_border_style = ((isset($_POST['glb_border_style'])) ? esc_html(stripslashes($_POST['glb_border_style'])) : 'none');	
    $glb_border_color = ((isset($_POST['glb_border_color'])) ? esc_html(stripslashes($_POST['glb_border_color'])) : '000000');
    $glb_border_radius = ((isset($_POST['glb_border_radius'])) ? esc_html(stripslashes($_POST['glb_border_radius'])) : '');
    $glb_margin = ((isset($_POST['glb_margin'])) ? (int) esc_html(stripslashes($_POST['glb_margin'])) : 0);
    $glb_box_shadow = ((isset($_POST['glb_box_shadow'])) ? esc_html(stripslashes($_POST['glb_box_shadow'])) : '');
    $image_right_click = ((isset($_POST['image_right_click'])) ? (int) esc_html(stripslashes($_POST['image_right_click'])) : 0);
    $layer_out_next = ((isset($_POST['layer_out_next'])) ? (int) esc_html(stripslashes($_POST['layer_out_next'])) : 0);
    $prev_next_butt = ((isset($_POST['prev_next_butt'])) ? (int) esc_html(stripslashes($_POST['prev_next_butt'])) : 1);	
    $play_paus_butt = ((isset($_POST['play_paus_butt'])) ? (int) esc_html(stripslashes($_POST['play_paus_butt'])) : 0);
    $navigation = ((isset($_POST['navigation'])) ? esc_html(stripslashes($_POST['navigation'])) : 'hover');
    $rl_butt_style = ((isset($_POST['rl_butt_style']) && $allow) ? esc_html(stripslashes($_POST['rl_butt_style'])) : 'fa-angle');
    $rl_butt_size = ((isset($_POST['rl_butt_size']) && $allow) ? (int) esc_html(stripslashes($_POST['rl_butt_size'])) : 40);
    $pp_butt_size = ((isset($_POST['pp_butt_size']) && $allow) ? (int) esc_html(stripslashes($_POST['pp_butt_size'])) : 40);	
    $butts_color = ((isset($_POST['butts_color']) && $allow) ? esc_html(stripslashes($_POST['butts_color'])) : '000000');
    $butts_transparent = ((isset($_POST['butts_transparent']) && $allow) ? (int) esc_html(stripslashes($_POST['butts_transparent'])) : 100);
    $hover_color = ((isset($_POST['hover_color']) && $allow) ? esc_html(stripslashes($_POST['hover_color'])) : '000000');
    $nav_border_width = ((isset($_POST['nav_border_width']) && $allow) ? (int) esc_html(stripslashes($_POST['nav_border_width'])) : 0);
    $nav_border_style = ((isset($_POST['nav_border_style']) && $allow) ? esc_html(stripslashes($_POST['nav_border_style'])) : 'none');
    $nav_border_color = ((isset($_POST['nav_border_color'])) ? esc_html(stripslashes($_POST['nav_border_color'])) : 'FFFFFF');	
    $nav_border_radius = ((isset($_POST['nav_border_radius']) && $allow) ? esc_html(stripslashes($_POST['nav_border_radius'])) : '20px');
    $nav_bg_color = ((isset($_POST['nav_bg_color']) && $allow) ? esc_html(stripslashes($_POST['nav_bg_color'])) : 'FFFFFF');
    $bull_position = ((isset($_POST['bull_position'])) ? esc_html(stripslashes($_POST['bull_position'])) : 'bottom');
    if (isset($_POST['enable_bullets']) && (esc_html(stripslashes($_POST['enable_bullets'])) == 0)) {
      $bull_position = 'none';
    }
    $bull_style = ((isset($_POST['bull_style']) && $allow) ? esc_html(stripslashes($_POST['bull_style'])) : 'fa-square-o');
    $bull_size = ((isset($_POST['bull_size']) && $allow) ? (int) esc_html(stripslashes($_POST['bull_size'])) : 20);
    $bull_color = ((isset($_POST['bull_color']) && $allow) ? esc_html(stripslashes($_POST['bull_color'])) : 'FFFFFF');	
    $bull_act_color = ((isset($_POST['bull_act_color']) && $allow) ? esc_html(stripslashes($_POST['bull_act_color'])) : 'FFFFFF');
    $bull_margin = ((isset($_POST['bull_margin']) && $allow) ? (int) esc_html(stripslashes($_POST['bull_margin'])) : 3);
    $film_pos = ((isset($_POST['film_pos'])) ? esc_html(stripslashes($_POST['film_pos'])) : 'none');
    if (isset($_POST['enable_filmstrip']) && (esc_html(stripslashes($_POST['enable_filmstrip'])) == 0)) {
      $film_pos = 'none';
    }
    $film_thumb_width = ((isset($_POST['film_thumb_width'])) ? (int) esc_html(stripslashes($_POST['film_thumb_width'])) : 100);
    $film_thumb_height = ((isset($_POST['film_thumb_height'])) ? (int) esc_html(stripslashes($_POST['film_thumb_height'])) : 50);
    $film_bg_color = ((isset($_POST['film_bg_color'])) ? esc_html(stripslashes($_POST['film_bg_color'])) : '000000');	
    $film_tmb_margin = ((isset($_POST['film_tmb_margin'])) ? (int) esc_html(stripslashes($_POST['film_tmb_margin'])) : 0);
    $film_act_border_width = ((isset($_POST['film_act_border_width'])) ? (int) esc_html(stripslashes($_POST['film_act_border_width'])) : 0);
    $film_act_border_style = ((isset($_POST['film_act_border_style'])) ? esc_html(stripslashes($_POST['film_act_border_style'])) : 'none');
    $film_act_border_color = ((isset($_POST['film_act_border_color'])) ? esc_html(stripslashes($_POST['film_act_border_color'])) : 'FFFFFF');
    $film_dac_transparent = ((isset($_POST['film_dac_transparent'])) ? (int) esc_html(stripslashes($_POST['film_dac_transparent'])) : 50);	
    $built_in_watermark_type = (isset($_POST['built_in_watermark_type']) ? esc_html(stripslashes($_POST['built_in_watermark_type'])) : 'none');
    $built_in_watermark_position = (isset($_POST['built_in_watermark_position']) ? esc_html(stripslashes($_POST['built_in_watermark_position'])) : 'middle-center');
    $built_in_watermark_size = (isset($_POST['built_in_watermark_size']) ? esc_html(stripslashes($_POST['built_in_watermark_size'])) : 15);
    $built_in_watermark_url = (isset($_POST['built_in_watermark_url']) ? esc_html(stripslashes($_POST['built_in_watermark_url'])) : '');
    $built_in_watermark_text = (isset($_POST['built_in_watermark_text']) ? esc_html(stripslashes($_POST['built_in_watermark_text'])) : 'web-dorado.com');
    $built_in_watermark_opacity = (isset($_POST['built_in_watermark_opacity']) ? esc_html(stripslashes($_POST['built_in_watermark_opacity'])) : 70);
    $built_in_watermark_font_size = (isset($_POST['built_in_watermark_font_size']) ? esc_html(stripslashes($_POST['built_in_watermark_font_size'])) : 20);
    $built_in_watermark_font = (isset($_POST['built_in_watermark_font']) ? esc_html(stripslashes($_POST['built_in_watermark_font'])) : '');
    $built_in_watermark_color = (isset($_POST['built_in_watermark_color']) ? esc_html(stripslashes($_POST['built_in_watermark_color'])) : 'FFFFFF');
    $css = (isset($_POST['css']) ? htmlspecialchars_decode((stripslashes($_POST['css'])), ENT_QUOTES) : '');
    $timer_bar_type = (isset($_POST['timer_bar_type']) ? esc_html(stripslashes($_POST['timer_bar_type'])) : 'top');
    if (isset($_POST['enable_time_bar']) && (esc_html(stripslashes($_POST['enable_time_bar'])) == 0)) {
      $timer_bar_type = 'none';
    }
    $timer_bar_size = (isset($_POST['timer_bar_size']) ? esc_html(stripslashes($_POST['timer_bar_size'])) : 5);
    $timer_bar_color = (isset($_POST['timer_bar_color']) ? esc_html(stripslashes($_POST['timer_bar_color'])) : 'BBBBBB');
    $timer_bar_transparent = (isset($_POST['timer_bar_transparent']) ? esc_html(stripslashes($_POST['timer_bar_transparent'])) : 50);
    $stop_animation = ((isset($_POST['stop_animation'])) ? (int) esc_html(stripslashes($_POST['stop_animation'])) : 0);
    $right_butt_url = (isset($_POST['right_butt_url']) ? esc_html(stripslashes($_POST['right_butt_url'])) : '');
    $left_butt_url = (isset($_POST['left_butt_url']) ? esc_html(stripslashes($_POST['left_butt_url'])) : '');
    $right_butt_hov_url = (isset($_POST['right_butt_hov_url']) ? esc_html(stripslashes($_POST['right_butt_hov_url'])) : '');
    $left_butt_hov_url = (isset($_POST['left_butt_hov_url']) ? esc_html(stripslashes($_POST['left_butt_hov_url'])) : '');
    $rl_butt_img_or_not = (isset($_POST['rl_butt_img_or_not']) ? esc_html(stripslashes($_POST['rl_butt_img_or_not'])) : 'style');
    $bullets_img_main_url = (isset($_POST['bullets_img_main_url']) ? esc_html(stripslashes($_POST['bullets_img_main_url'])) : '');
    $bullets_img_hov_url = (isset($_POST['bullets_img_hov_url']) ? esc_html(stripslashes($_POST['bullets_img_hov_url'])) : '');
    $bull_butt_img_or_not = (isset($_POST['bull_butt_img_or_not']) ? esc_html(stripslashes($_POST['bull_butt_img_or_not'])) : 'style');
    $play_paus_butt_img_or_not = (isset($_POST['play_paus_butt_img_or_not']) ? esc_html(stripslashes($_POST['play_paus_butt_img_or_not'])) : 'style');
    $play_butt_url = (isset($_POST['play_butt_url']) ? esc_html(stripslashes($_POST['play_butt_url'])) : '');
    $play_butt_hov_url = (isset($_POST['play_butt_hov_url']) ? esc_html(stripslashes($_POST['play_butt_hov_url'])) : '');
    $paus_butt_url = (isset($_POST['paus_butt_url']) ? esc_html(stripslashes($_POST['paus_butt_url'])) : '');
    $paus_butt_hov_url = (isset($_POST['paus_butt_hov_url']) ? esc_html(stripslashes($_POST['paus_butt_hov_url'])) : '');
    $start_slide_num = ((isset($_POST['start_slide_num'])) ? (int) stripslashes($_POST['start_slide_num']) : 1);
    $effect_duration = ((isset($_POST['effect_duration'])) ? (int) stripslashes($_POST['effect_duration']) : 800);
    $carousel = 0;
    $carousel_image_counts = 7;
    $carousel_image_parameters = 0.85;
    $carousel_fit_containerWidth = 0;
    $carousel_width = 1000;
    $parallax_effect = 0;
    $mouse_swipe_nav = ((isset($_POST['mouse_swipe_nav'])) ? (int) esc_html(stripslashes($_POST['mouse_swipe_nav'])) : 0);
    $bull_hover = ((isset($_POST['bull_hover'])) ? (int) esc_html(stripslashes($_POST['bull_hover'])) : 1);
    $touch_swipe_nav = ((isset($_POST['touch_swipe_nav'])) ? (int) esc_html(stripslashes($_POST['touch_swipe_nav'])) : 1);
    $mouse_wheel_nav = ((isset($_POST['mouse_wheel_nav'])) ? (int) esc_html(stripslashes($_POST['mouse_wheel_nav'])) : 0);
    $keyboard_nav = ((isset($_POST['keyboard_nav'])) ? (int) esc_html(stripslashes($_POST['keyboard_nav'])) : 0);
    $possib_add_ffamily = ((isset($_POST['possib_add_ffamily'])) ? esc_html(stripslashes($_POST['possib_add_ffamily'])) : '');
    $show_thumbnail = ((isset($_POST['show_thumbnail'])) ? (int) esc_html(stripslashes($_POST['show_thumbnail'])) : 0);
    $thumb_size = ((isset($_POST['thumb_size'])) ? esc_html(stripslashes($_POST['thumb_size'])) : '0.2');
    $fixed_bg = ((isset($_POST['fixed_bg'])) ? (int) esc_html(stripslashes($_POST['fixed_bg'])) : 0);

    $data = array(
      'name' => $name,
      'published' => $published,
      'full_width' => $full_width,
      'width' => $width,
      'height' => $height,
      'bg_fit' => $bg_fit,
      'align' => $align,
      'effect' => $effect,
      'time_intervval' => $time_intervval,
      'autoplay' => $autoplay,
      'shuffle' => $shuffle,
      'music' => $music,
      'music_url' => $music_url,
      'preload_images' => $preload_images, 
      'background_color' => $background_color,
      'background_transparent' => $background_transparent,
      'glb_border_width' => $glb_border_width,
      'glb_border_style' => $glb_border_style,
      'glb_border_color' => $glb_border_color,
      'glb_border_radius' => $glb_border_radius,
      'glb_margin' => $glb_margin,
      'glb_box_shadow' => $glb_box_shadow,
      'image_right_click' => $image_right_click,
      'prev_next_butt' => $prev_next_butt,	
      'play_paus_butt' => $play_paus_butt,
      'navigation' => $navigation,
      'rl_butt_style' => $rl_butt_style,
      'rl_butt_size' => $rl_butt_size,
      'pp_butt_size' => $pp_butt_size,	
      'butts_color' => $butts_color,
      'butts_transparent' => $butts_transparent,
      'hover_color' => $hover_color,
      'nav_border_width' => $nav_border_width,
      'nav_border_style' => $nav_border_style,
      'nav_border_color' => $nav_border_color,
      'nav_border_radius' => $nav_border_radius,
      'nav_bg_color' => $nav_bg_color,
      'bull_position' => $bull_position,
      'bull_style' => $bull_style,
      'bull_size' => $bull_size,
      'bull_color' => $bull_color,
      'bull_act_color' => $bull_act_color,
      'bull_margin' => $bull_margin,
      'film_pos' => $film_pos,
      'film_thumb_width' => $film_thumb_width,
      'film_thumb_height' => $film_thumb_height,
      'film_bg_color' => $film_bg_color,
      'film_tmb_margin' => $film_tmb_margin,
      'film_act_border_width' => $film_act_border_width,
      'film_act_border_style' => $film_act_border_style,
      'film_act_border_color' => $film_act_border_color,
      'film_dac_transparent' => $film_dac_transparent,	
      'built_in_watermark_type' => $built_in_watermark_type,
      'built_in_watermark_position' => $built_in_watermark_position,
      'built_in_watermark_size' => $built_in_watermark_size,
      'built_in_watermark_url' => $built_in_watermark_url,
      'built_in_watermark_text' => $built_in_watermark_text,
      'built_in_watermark_opacity' => $built_in_watermark_opacity,		
      'built_in_watermark_font_size' => $built_in_watermark_font_size,
      'built_in_watermark_font' => $built_in_watermark_font,
      'built_in_watermark_color' => $built_in_watermark_color,
      'css' => $css,
      'timer_bar_type' => $timer_bar_type,
      'timer_bar_size' => $timer_bar_size,
      'timer_bar_color' => $timer_bar_color,
      'timer_bar_transparent' => $timer_bar_transparent,
      'layer_out_next' => $layer_out_next,
      'spider_uploader' => $spider_uploader,
      'stop_animation' => $stop_animation,
      'right_butt_url' => $right_butt_url,
      'left_butt_url' => $left_butt_url,
      'right_butt_hov_url' => $right_butt_hov_url,
      'left_butt_hov_url' => $left_butt_hov_url,
      'rl_butt_img_or_not' => $rl_butt_img_or_not,
      'bullets_img_main_url' => $bullets_img_main_url,
      'bullets_img_hov_url' => $bullets_img_hov_url,
      'bull_butt_img_or_not' => $bull_butt_img_or_not,
      'play_paus_butt_img_or_not' => $play_paus_butt_img_or_not,
      'play_butt_url' => $play_butt_url,
      'play_butt_hov_url' => $play_butt_hov_url,
      'paus_butt_url' => $paus_butt_url,
      'paus_butt_hov_url' => $paus_butt_hov_url,
      'start_slide_num' => $start_slide_num,
      'effect_duration' => $effect_duration,
      'carousel' => $carousel,
      'carousel_image_counts' => $carousel_image_counts,
      'carousel_image_parameters' => $carousel_image_parameters,
      'carousel_fit_containerWidth' => $carousel_fit_containerWidth,
      'carousel_width' => $carousel_width,
      'parallax_effect' => $parallax_effect,
      'mouse_swipe_nav' => $mouse_swipe_nav,
      'bull_hover' => $bull_hover,
      'touch_swipe_nav' => $touch_swipe_nav,
      'mouse_wheel_nav' => $mouse_wheel_nav,
      'keyboard_nav' => $keyboard_nav,
      'possib_add_ffamily' => $possib_add_ffamily,
      'show_thumbnail' => $show_thumbnail,
      'thumb_size' => $thumb_size,
      'fixed_bg' => $fixed_bg,
    );

    if (!$slider_id) {
      $save = $wpdb->insert($wpdb->prefix . 'wdsslider', $data);
      $_POST['current_id'] = (int) $wpdb->insert_id;
    }
    else {
      $save = $wpdb->update($wpdb->prefix . 'wdsslider', $data, array('id' => $slider_id));
    }
    if ($save !== FALSE) {
      return 1;
    }
    else {
      return 2;
    }
  }

  public function save_slide_db() {
    global $wpdb;
    $slider_id = (isset($_POST['current_id']) ? (int) $_POST['current_id'] : 0);
    if (!$slider_id) {
      $slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslider');
    }
    $slide_ids_string = (isset($_POST['slide_ids_string']) ? esc_html(stripslashes($_POST['slide_ids_string'])) : '');
    $slide_id_array = explode(',', $slide_ids_string);
    if (get_option("wds_theme_version")) {
      $allow = FALSE;
    }
    else {
      $allow = TRUE;
    }
    foreach ($slide_id_array as $slide_id) {
      if ($slide_id) {
        $del_layer_ids_string = (isset($_POST['slide' . $slide_id . '_del_layer_ids_string']) ? substr(esc_html(stripslashes($_POST['slide' . $slide_id . '_del_layer_ids_string'])), 0, -1) : '');
        if ($del_layer_ids_string) {
          $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'wdslayer WHERE id IN (' . $del_layer_ids_string . ')');
        }
        $title = ((isset($_POST['title' . $slide_id])) ? esc_html(stripslashes($_POST['title' . $slide_id])) : '');
        $type = ((isset($_POST['type' . $slide_id])) ? esc_html(stripslashes($_POST['type' . $slide_id])) : '');
        $order = ((isset($_POST['order' . $slide_id])) ? esc_html(stripslashes($_POST['order' . $slide_id])) : '');
        $published = ((isset($_POST['published' . $slide_id])) ? esc_html(stripslashes($_POST['published' . $slide_id])) : '');
        $target_attr_slide = ((isset($_POST['target_attr_slide' . $slide_id])) ? esc_html(stripslashes($_POST['target_attr_slide' . $slide_id])) : 0);
        $link = ((isset($_POST['link' . $slide_id])) ? esc_html(stripslashes($_POST['link' . $slide_id])) : '');
        $image_url = ((isset($_POST['image_url' . $slide_id])) ? esc_html(stripslashes($_POST['image_url' . $slide_id])) : '');
        $thumb_url = ((isset($_POST['thumb_url' . $slide_id])) ? esc_html(stripslashes($_POST['thumb_url' . $slide_id])) : '');
        if (strpos($slide_id, 'pr') !== FALSE) {
          $save = $wpdb->insert($wpdb->prefix . 'wdsslide', array(
            'slider_id' => $slider_id,
            'title' => $title,
            'type' => $type,
            'order' => $order,
            'published' => $published,
            'link' => $link,
            'image_url' => $image_url,
            'thumb_url' => $thumb_url,
            'target_attr_slide' => $target_attr_slide,
          ), array(
            '%d',
            '%s',
            '%s',
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%d',
          ));
          if ($allow) {
            $slide_id_pr = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslide');
            $this->save_layer_db($slide_id, $slide_id_pr);
          }
        }
        else {
          $save = $wpdb->update($wpdb->prefix . 'wdsslide', array(
            'slider_id' => $slider_id,
            'title' => $title,
            'type' => $type,
            'order' => $order,
            'published' => $published,
            'link' => $link,
            'image_url' => $image_url,
            'thumb_url' => $thumb_url,
            'target_attr_slide' => $target_attr_slide,
          ), array('id' => $slide_id));
          if ($allow) {
            $this->save_layer_db($slide_id, $slide_id);
          }
        }
      }
    }
  }

  public function save_layer_db($slide_id, $slide_id_pr) {
    global $wpdb;
    $layer_ids_string = (isset($_POST['slide' . $slide_id . '_layer_ids_string']) ? esc_html(stripslashes($_POST['slide' . $slide_id . '_layer_ids_string'])) : '');
    $layer_id_array = explode(',', $layer_ids_string);
    foreach ($layer_id_array as $layer_id) {
      if ($layer_id) {
        $prefix = 'slide' . $slide_id . '_layer' . $layer_id;
        $json_string = (isset($_POST[$prefix . '_json']) ? stripslashes($_POST[$prefix . '_json']) : '');
        $params_array = json_decode($json_string, TRUE);
        $title = ((isset($params_array['title'])) ? esc_html(stripslashes($params_array['title'])) : '');
        $type = ((isset($params_array['type'])) ? esc_html(stripslashes($params_array['type'])) : '');
        $depth = ((isset($params_array['depth'])) ? esc_html(stripslashes($params_array['depth'])) : '');
        $text = ((isset($params_array['text'])) ? stripslashes($params_array['text']) : '');
        $link = ((isset($params_array['link'])) ? esc_html(stripslashes($params_array['link'])) : '');
        $target_attr_layer = ((isset($params_array['target_attr_layer'])) ? esc_html(stripslashes($params_array['target_attr_layer'])) : 0);
        $left = ((isset($params_array['left'])) ? esc_html(stripslashes($params_array['left'])) : '');
        $top = ((isset($params_array['top'])) ? esc_html(stripslashes($params_array['top'])) : '');
        $start = ((isset($params_array['start'])) ? esc_html(stripslashes($params_array['start'])) : '');
        $end = ((isset($params_array['end'])) ? esc_html(stripslashes($params_array['end'])) : '');
        $published = ((isset($params_array['published'])) ? esc_html(stripslashes($params_array['published'])) : '');
        $color = ((isset($params_array['color'])) ? esc_html(stripslashes($params_array['color'])) : '');
        $size = ((isset($params_array['size'])) ? esc_html(stripslashes($params_array['size'])) : '');
        $ffamily = ((isset($params_array['ffamily'])) ? esc_html(stripslashes($params_array['ffamily'])) : '');
        $fweight = ((isset($params_array['fweight'])) ? esc_html(stripslashes($params_array['fweight'])) : '');
        $padding = ((isset($params_array['padding'])) ? esc_html(stripslashes($params_array['padding'])) : '');
        $fbgcolor = ((isset($params_array['fbgcolor'])) ? esc_html(stripslashes($params_array['fbgcolor'])) : '');
        $transparent = ((isset($params_array['transparent'])) ? esc_html(stripslashes($params_array['transparent'])) : '');
        $border_width = ((isset($params_array['border_width'])) ? esc_html(stripslashes($params_array['border_width'])) : '');
        $border_style = ((isset($params_array['border_style'])) ? esc_html(stripslashes($params_array['border_style'])) : '');
        $border_color = ((isset($params_array['border_color'])) ? esc_html(stripslashes($params_array['border_color'])) : '');
        $border_radius = ((isset($params_array['border_radius'])) ? esc_html(stripslashes($params_array['border_radius'])) : '');
        $shadow = ((isset($params_array['shadow'])) ? esc_html(stripslashes($params_array['shadow'])) : '');
        $image_url = ((isset($params_array['image_url'])) ? esc_html(stripslashes($params_array['image_url'])) : '');
        $image_width = ((isset($params_array['image_width'])) ? esc_html(stripslashes($params_array['image_width'])) : '');
        $image_height = ((isset($params_array['image_height'])) ? esc_html(stripslashes($params_array['image_height'])) : '');
        $image_scale = ((isset($params_array['image_scale'])) ? esc_html(stripslashes($params_array['image_scale'])) : '');
        $alt = ((isset($params_array['alt'])) ? esc_html(stripslashes($params_array['alt'])) : '');
        $imgtransparent = ((isset($params_array['imgtransparent'])) ? esc_html(stripslashes($params_array['imgtransparent'])) : '');
        $social_button = ((isset($params_array['social_button'])) ? esc_html(stripslashes($params_array['social_button'])) : '');
        $hover_color = ((isset($params_array['hover_color'])) ? esc_html(stripslashes($params_array['hover_color'])) : '');
        $layer_effect_in = ((isset($params_array['layer_effect_in'])) ? esc_html(stripslashes($params_array['layer_effect_in'])) : ''); 
        $layer_effect_out = ((isset($params_array['layer_effect_out'])) ? esc_html(stripslashes($params_array['layer_effect_out'])) : '');
        $duration_eff_in = ((isset($params_array['duration_eff_in'])) ? esc_html(stripslashes($params_array['duration_eff_in'])) : 3);
        $duration_eff_out = ((isset($params_array['duration_eff_out'])) ? esc_html(stripslashes($params_array['duration_eff_out'])) : 3);

		$hotp_width = ((isset($params_array['hotp_width'])) ? esc_html(stripslashes($params_array['hotp_width'])) : '');
		$hotp_fbgcolor = ((isset($params_array['hotp_fbgcolor'])) ? esc_html(stripslashes($params_array['hotp_fbgcolor'])) : '');
		$hotp_border_width = ((isset($params_array['hotp_border_width'])) ? esc_html(stripslashes($params_array['hotp_border_width'])) : '');
		$hotp_border_style = ((isset($params_array['hotp_border_style'])) ? esc_html(stripslashes($params_array['hotp_border_style'])) : '');
		$hotp_border_color = ((isset($params_array['hotp_border_color'])) ? esc_html(stripslashes($params_array['hotp_border_color'])) : '');
		$hotp_border_radius = ((isset($params_array['hotp_border_radius'])) ? esc_html(stripslashes($params_array['hotp_border_radius'])) : '');
        $hotp_text_position = ((isset($params_array['hotp_text_position'])) ? esc_html(stripslashes($params_array['hotp_text_position'])) : '');
    $google_fonts = ((isset($params_array['google_fonts'])) ? esc_html(stripslashes($params_array['google_fonts'])) : 0);
        if ($title) {
          if (strpos($layer_id, 'pr_') !== FALSE) {
            $save = $wpdb->insert($wpdb->prefix . 'wdslayer', array(
              'slide_id' => $slide_id_pr,
              'title' => $title,
              'type' => $type,
              'depth' => $depth,
              'text' => $text,
              'link' => $link,
              'left' => $left,
              'top' => $top,
              'start' => $start,
              'end' => $end,
              'published' => $published,
              'color' => $color,
              'size' => $size,
              'ffamily' => $ffamily,
              'fweight' => $fweight,
              'padding' => $padding,
              'fbgcolor' => $fbgcolor,
              'transparent' => $transparent,
              'border_width' => $border_width,
              'border_style' => $border_style,
              'border_color' => $border_color,
              'border_radius' => $border_radius,
              'shadow' => $shadow,
              'image_url' => $image_url,
              'image_width' => $image_width,
              'image_height' => $image_height,
              'image_scale' => $image_scale,
              'alt' => $alt,
              'imgtransparent' => $imgtransparent,
              'social_button' => $social_button,
              'hover_color' => $hover_color,
              'layer_effect_in' => $layer_effect_in,
              'layer_effect_out' => $layer_effect_out,
              'duration_eff_in' => $duration_eff_in,
              'duration_eff_out' => $duration_eff_out,
              'target_attr_layer' => $target_attr_layer,
			  'hotp_width' => $hotp_width,
			  'hotp_fbgcolor' => $hotp_fbgcolor,
			  'hotp_border_width' => $hotp_border_width,
			  'hotp_border_style' => $hotp_border_style,
			  'hotp_border_color' => $hotp_border_color,
			  'hotp_border_radius' => $hotp_border_radius,
			  'hotp_text_position' => $hotp_text_position,
        'google_fonts' => $google_fonts,
            ));
          }
          else {
            $save = $wpdb->update($wpdb->prefix . 'wdslayer', array(
              'title' => $title,
              'type' => $type,
              'depth' => $depth,
              'text' => $text,
              'link' => $link,
              'left' => $left,
              'top' => $top,
              'start' => $start,
              'end' => $end,
              'published' => $published,
              'color' => $color,
              'size' => $size,
              'ffamily' => $ffamily,
              'fweight' => $fweight,
              'padding' => $padding,
              'fbgcolor' => $fbgcolor,
              'transparent' => $transparent,
              'border_width' => $border_width,
              'border_style' => $border_style,
              'border_color' => $border_color,
              'border_radius' => $border_radius,
              'shadow' => $shadow,
              'image_url' => $image_url,
              'image_width' => $image_width,
              'image_height' => $image_height,
              'image_scale' => $image_scale,
              'alt' => $alt,
              'imgtransparent' => $imgtransparent,
              'social_button' => $social_button,
              'hover_color' => $hover_color,
              'layer_effect_in' => $layer_effect_in,
              'layer_effect_out' => $layer_effect_out,
              'duration_eff_in' => $duration_eff_in,
              'duration_eff_out' => $duration_eff_out,
              'target_attr_layer' => $target_attr_layer,
			  'hotp_width' => $hotp_width,
			  'hotp_fbgcolor' => $hotp_fbgcolor,
			  'hotp_border_width' => $hotp_border_width,
			  'hotp_border_style' => $hotp_border_style,
			  'hotp_border_color' => $hotp_border_color,
			  'hotp_border_radius' => $hotp_border_radius,
              'hotp_text_position' => $hotp_text_position,
              'google_fonts' => $google_fonts,
            ), array('id' => $layer_id));
          }
        }
      }
    }
  }

  public function set_watermark() {
    global $wpdb;
    $slider_id = WDW_S_Library::get('current_id', 0);
    if (!$slider_id) {
      $slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslider');
    }

    $slider_images = $wpdb->get_col($wpdb->prepare('SELECT image_url FROM ' . $wpdb->prefix . 'wdsslide WHERE `slider_id`="%d"', $slider_id));
    $slider = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslider WHERE `id`="%d"', $slider_id));

    switch ($slider->built_in_watermark_type) {
      case 'text': {
        foreach ($slider_images as $slider_image) {
          if ($slider_image) {
            $slider_image_dir = str_replace(site_url() . '/', ABSPATH, $slider_image);
            $last_slash_pos = strrpos($slider_image_dir, '/') + 1;
            $dest_dir = substr($slider_image_dir, 0, $last_slash_pos);
            $image_name = substr($slider_image_dir, $last_slash_pos);
            $new_image = $dest_dir . '.original/' . $image_name;
            if (!is_dir($dest_dir . '.original')) {
              mkdir($dest_dir . '.original', 0777);
            }
            if (!file_exists($new_image)) {
              copy($slider_image_dir, $new_image);
            }
            $this->set_text_watermark($slider_image_dir, $slider_image_dir, $slider->built_in_watermark_text, $slider->built_in_watermark_font, $slider->built_in_watermark_font_size, '#' . $slider->built_in_watermark_color, $slider->built_in_watermark_opacity, $slider->built_in_watermark_position);
          }
        }
        break;
      }
      case 'image': {
        foreach ($slider_images as $slider_image) {
          if ($slider_image) {
            $slider_image_dir = str_replace(site_url() . '/', ABSPATH, $slider_image);
            $last_slash_pos = strrpos($slider_image_dir, '/') + 1;
            $dest_dir = substr($slider_image_dir, 0, $last_slash_pos);
            $image_name = substr($slider_image_dir, $last_slash_pos);
            $new_image = $dest_dir . '.original/' . $image_name;
            if (!is_dir($dest_dir . '.original')) {
              mkdir($dest_dir . '.original', 0777);
            }
            if (!file_exists($new_image)) {
              copy($slider_image_dir, $new_image);
            }
            $watermark_image_dir = str_replace(site_url() . '/', ABSPATH, $slider->built_in_watermark_url);
            $this->set_image_watermark($slider_image_dir, $slider_image_dir, $watermark_image_dir, $slider->built_in_watermark_size, $slider->built_in_watermark_size, $slider->built_in_watermark_position);
          }
        }
        break;
      }
      default: {
        break;
      }
    }
  }

  public function reset_watermark() {
    global $wpdb;
    $slider_id = WDW_S_Library::get('current_id', 0);
    if (!$slider_id) {
      $slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslider');
    }
    $slider_images = $wpdb->get_col($wpdb->prepare('SELECT image_url FROM ' . $wpdb->prefix . 'wdsslide WHERE `slider_id`="%d"', $slider_id));
    foreach ($slider_images as $slider_image) {
      if ($slider_image) {
        $slider_image_dir = str_replace(site_url() . '/', ABSPATH, $slider_image);
        $last_slash_pos = strrpos($slider_image_dir, '/') + 1;
        $dest_dir = substr($slider_image_dir, 0, $last_slash_pos);
        $image_name = substr($slider_image_dir, $last_slash_pos);
        $new_image = $dest_dir . '.original/' . $image_name;
        if (file_exists($new_image)) {
          copy($new_image, $slider_image_dir);
        }
        else {
          // For 1.0.1 version.
          $last_dot_pos = strrpos($slider_image_dir, '.');
          $base_name = substr($slider_image_dir, 0, $last_dot_pos);
          $ext = substr($slider_image_dir, strlen($base_name));
          $new_image = $base_name . '-original' . $ext;
          if (file_exists($new_image)) {
            copy($new_image, $slider_image_dir);
          }
        }
      }
    }
  }

  public function reset() {
    global $wpdb;
    $slider_id = WDW_S_Library::get('current_id', 0);
    if (!$slider_id) {
      $slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslider');
    }

    require_once WD_S_DIR . "/admin/models/WDSModelSliders_wds.php";
    $model = new WDSModelSliders_wds();

    require_once WD_S_DIR . "/admin/views/WDSViewSliders_wds.php";
    $view = new WDSViewSliders_wds($model);
    echo WDW_S_Library::message('Changes must be saved.', 'wd_error');
    $view->edit($slider_id, TRUE);
  }
  public function duplicate() {
    $slider_id = WDW_S_Library::get('current_id', 0);
    $new_slider_id = $this->duplicate_tabels($slider_id);
    require_once WD_S_DIR . "/admin/models/WDSModelSliders_wds.php";
    $model = new WDSModelSliders_wds();
    require_once WD_S_DIR . "/admin/views/WDSViewSliders_wds.php";
    $view = new WDSViewSliders_wds($model);
    echo WDW_S_Library::message('Item Succesfully Duplicated.', 'wd_updated');
    $view->edit($new_slider_id);
  }

  public function duplicate_all($id) {
    global $wpdb;
    $sliders_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wdsslider');
    foreach ($sliders_ids_col as $slider_id) {
      if (isset($_POST['check_' . $slider_id])) {
        $this->duplicate_tabels($slider_id);
      }
    }
    echo WDW_S_Library::message('Items Succesfully Duplicated.', 'wd_updated');
    $this->display();
  }

  public function duplicate_tabels($slider_id) {
    global $wpdb;
    if ($slider_id) {
      $slider_row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslider where id="%d"', $slider_id));    
    }      
    if ($slider_row) {
      $save = $wpdb->insert($wpdb->prefix . 'wdsslider', array(
        'name' => $slider_row->name,
        'published' => $slider_row->published,
        'full_width' => $slider_row->full_width,
        'width' => $slider_row->width,
        'height' => $slider_row->height,
        'bg_fit' => $slider_row->bg_fit,
        'align' => $slider_row->align,
        'effect' => $slider_row->effect,
        'time_intervval' => $slider_row->time_intervval,
        'autoplay' => $slider_row->autoplay,
        'shuffle' => $slider_row->shuffle,
        'music' => $slider_row->music,
        'music_url' => $slider_row->music_url,
        'preload_images' => $slider_row->preload_images,
        'background_color' => $slider_row->background_color,
        'background_transparent' =>$slider_row-> background_transparent,
        'glb_border_width' => $slider_row->glb_border_width,
        'glb_border_style' => $slider_row->glb_border_style,
        'glb_border_color' => $slider_row->glb_border_color,
        'glb_border_radius' => $slider_row->glb_border_radius,
        'glb_margin' => $slider_row->glb_margin,
        'glb_box_shadow' => $slider_row->glb_box_shadow,
        'image_right_click' => $slider_row->image_right_click,
        'prev_next_butt' => $slider_row->prev_next_butt,	
        'play_paus_butt' => $slider_row->play_paus_butt,
        'navigation' => $slider_row->navigation,
        'rl_butt_style' => $slider_row->rl_butt_style,
        'rl_butt_size' => $slider_row->rl_butt_size,
        'pp_butt_size' => $slider_row->pp_butt_size,	
        'butts_color' => $slider_row->butts_color,
        'butts_transparent' => $slider_row->butts_transparent,
        'hover_color' => $slider_row->hover_color,
        'nav_border_width' => $slider_row->nav_border_width,
        'nav_border_style' => $slider_row->nav_border_style,
        'nav_border_color' => $slider_row->nav_border_color,
        'nav_border_radius' => $slider_row->nav_border_radius,
        'nav_bg_color' => $slider_row->nav_bg_color,
        'bull_position' => $slider_row->bull_position,
        'bull_style' => $slider_row->bull_style,
        'bull_size' => $slider_row->bull_size,
        'bull_color' => $slider_row->bull_color,
        'bull_act_color' => $slider_row->bull_act_color,
        'bull_margin' => $slider_row->bull_margin,
        'film_pos' => $slider_row->film_pos,
        'film_thumb_width' => $slider_row->film_thumb_width,
        'film_thumb_height' => $slider_row->film_thumb_height,
        'film_bg_color' => $slider_row->film_bg_color,
        'film_tmb_margin' => $slider_row->film_tmb_margin,
        'film_act_border_width' => $slider_row->film_act_border_width,
        'film_act_border_style' => $slider_row->film_act_border_style,
        'film_act_border_color' => $slider_row->film_act_border_color,
        'film_dac_transparent' => $slider_row->film_dac_transparent,	
        'built_in_watermark_type' => $slider_row->built_in_watermark_type,
        'built_in_watermark_position' => $slider_row->built_in_watermark_position,
        'built_in_watermark_size' => $slider_row->built_in_watermark_size,
        'built_in_watermark_url' => $slider_row->built_in_watermark_url,
        'built_in_watermark_text' => $slider_row->built_in_watermark_text,
        'built_in_watermark_opacity' => $slider_row->built_in_watermark_opacity,		
        'built_in_watermark_font_size' => $slider_row->built_in_watermark_font_size,
        'built_in_watermark_font' => $slider_row->built_in_watermark_font,
        'built_in_watermark_color' => $slider_row->built_in_watermark_color,
        'css' => $slider_row->css,
        'timer_bar_type' => $slider_row->timer_bar_type,
        'timer_bar_size' => $slider_row->timer_bar_size,
        'timer_bar_color' => $slider_row->timer_bar_color,
        'timer_bar_transparent' => $slider_row->timer_bar_transparent,
        'layer_out_next' => $slider_row->layer_out_next,
        'spider_uploader' => $slider_row->spider_uploader,
        'stop_animation' => $slider_row->stop_animation,
        'right_butt_url' => $slider_row->right_butt_url,
        'left_butt_url' => $slider_row->left_butt_url,
        'right_butt_hov_url' => $slider_row->right_butt_hov_url,
        'left_butt_hov_url' => $slider_row->left_butt_hov_url,
        'rl_butt_img_or_not' => $slider_row->rl_butt_img_or_not,
        'bullets_img_main_url' => $slider_row->bullets_img_main_url,
        'bullets_img_hov_url' => $slider_row->bullets_img_hov_url,
        'bull_butt_img_or_not' => $slider_row->bull_butt_img_or_not,
        'play_paus_butt_img_or_not' => $slider_row->play_paus_butt_img_or_not,
        'play_butt_url' => $slider_row->play_butt_url,
        'play_butt_hov_url' => $slider_row->play_butt_hov_url,
        'paus_butt_url' => $slider_row->paus_butt_url,
        'paus_butt_hov_url' => $slider_row->paus_butt_hov_url,
        'start_slide_num' => $slider_row->start_slide_num,
        'effect_duration' => $slider_row->effect_duration,
        'carousel' => $slider_row->carousel,
        'carousel_image_counts' => $slider_row->carousel_image_counts,
        'carousel_image_parameters' => $slider_row->carousel_image_parameters,
        'carousel_fit_containerWidth' => $slider_row->carousel_fit_containerWidth,
        'carousel_width' => $slider_row->carousel_width,
	'parallax_effect' => $slider_row->parallax_effect,
      ), array(
        '%s',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s',
        '%s',
        '%s',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s',
        '%d',
        '%s',
        '%d',
        '%d',
        '%s',
        '%s',
        '%s',
        '%d',
        '%s',
        '%d',
        '%d',
        '%d',
        '%s',
        '%s',
        '%d',
        '%d',
        '%s',
        '%d',
        '%s',
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
        '%s',
        '%s',
        '%d',
        '%s',
        '%d',
        '%d',
        '%s',
        '%d',
        '%d',
        '%s',
        '%s',
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
        '%s',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s',
        '%d',
        '%d',
	'%d',
      ));
      $new_slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslider');

      $slider_slides = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslide where slider_id="%d"', $slider_id));
      if ($slider_slides) {
        foreach ($slider_slides as $single_slide) {
          $save = $wpdb->insert($wpdb->prefix . 'wdsslide', array(
            'slider_id' => $new_slider_id,
            'title' => $single_slide->title,
            'type' => $single_slide->type,
            'order' => $single_slide->order,
            'published' => $single_slide->published,
            'link' => $single_slide->link,
            'image_url' => $single_slide->image_url,
            'thumb_url' => $single_slide->thumb_url,
            'target_attr_slide' => $single_slide->target_attr_slide,
          ), array(
            '%d',
            '%s',
            '%s',
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%d',
          ));
          $new_slide_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslide');
          $slider_layer = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdslayer where slide_id="%d"', $single_slide->id));
          if ($slider_layer){
            foreach ($slider_layer as $layer_id) {
              if ($layer_id) {            
                $save = $wpdb->insert($wpdb->prefix . 'wdslayer', array(
                'slide_id' => $new_slide_id,
                'title' => $layer_id->title,
                'type' => $layer_id->type,
                'depth' => $layer_id->depth,
                'text' => $layer_id->text,
                'link' => $layer_id->link,
                'left' => $layer_id->left,
                'top' => $layer_id->top,
                'start' => $layer_id->start,
                'end' => $layer_id->end,
                'published' => $layer_id->published,
                'color' => $layer_id->color,
                'size' => $layer_id->size,
                'ffamily' => $layer_id->ffamily,
                'fweight' => $layer_id->fweight,
                'padding' => $layer_id->padding,
                'fbgcolor' => $layer_id->fbgcolor,
                'transparent' => $layer_id->transparent,
                'border_width' => $layer_id->border_width,
                'border_style' => $layer_id->border_style,
                'border_color' => $layer_id->border_color,
                'border_radius' => $layer_id->border_radius,
                'shadow' => $layer_id->shadow,
                'image_url' => $layer_id->image_url,
                'image_width' => $layer_id->image_width,
                'image_height' => $layer_id->image_height,
                'image_scale' => $layer_id->image_scale,
                'alt' => $layer_id->alt,
                'imgtransparent' => $layer_id->imgtransparent,
                'social_button' => $layer_id->social_button,
                'hover_color' => $layer_id->hover_color,
                'layer_effect_in' => $layer_id->layer_effect_in,
                'layer_effect_out' => $layer_id->layer_effect_out,
                'duration_eff_in' => $layer_id->duration_eff_in,
                'duration_eff_out' => $layer_id->duration_eff_out,
                'target_attr_layer' => $layer_id->target_attr_layer,
			        	'hotp_width' => $layer_id->hotp_width,
			          'hotp_fbgcolor' => $layer_id->hotp_fbgcolor,
			          'hotp_border_width' => $layer_id->hotp_border_width,
			          'hotp_border_style' => $layer_id->hotp_border_style,
			          'hotp_border_color' => $layer_id->hotp_border_color,
			          'hotp_border_radius' => $layer_id->hotp_border_radius,
                'hotp_text_position' => $layer_id->hotp_text_position,
                'google_fonts' => $layer_id->google_fonts,
              ));
                         
            }
          }
          }
        }
      }
      
    }
    return $new_slider_id;
  }

  function bwg_hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);
    if (strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    }
    else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
    }
    $rgb = array($r, $g, $b);
    return $rgb;
  }

  function bwg_imagettfbboxdimensions($font_size, $font_angle, $font, $text) {
    $box = @ImageTTFBBox($font_size, $font_angle, $font, $text) or die;
    $max_x = max(array($box[0], $box[2], $box[4], $box[6]));
    $max_y = max(array($box[1], $box[3], $box[5], $box[7]));
    $min_x = min(array($box[0], $box[2], $box[4], $box[6]));
    $min_y = min(array($box[1], $box[3], $box[5], $box[7]));
    return array(
      "width"  => ($max_x - $min_x),
      "height" => ($max_y - $min_y)
    );
  }

  function set_text_watermark($original_filename, $dest_filename, $watermark_text, $watermark_font, $watermark_font_size, $watermark_color, $watermark_transparency, $watermark_position) {
    $original_filename = htmlspecialchars_decode($original_filename, ENT_COMPAT | ENT_QUOTES);
    $dest_filename = htmlspecialchars_decode($dest_filename, ENT_COMPAT | ENT_QUOTES);

    $watermark_transparency = 127 - ((100 - $watermark_transparency) * 1.27);
    list($width, $height, $type) = getimagesize($original_filename);
    $watermark_image = imagecreatetruecolor($width, $height);

    $watermark_color = $this->bwg_hex2rgb($watermark_color);
    $watermark_color = imagecolorallocatealpha($watermark_image, $watermark_color[0], $watermark_color[1], $watermark_color[2], $watermark_transparency);
    $watermark_font = WD_S_DIR . '/fonts/' . $watermark_font;
    $watermark_font_size = ($height * $watermark_font_size / 500);
    $watermark_position = explode('-', $watermark_position);
    $watermark_sizes = $this->bwg_imagettfbboxdimensions($watermark_font_size, 0, $watermark_font, $watermark_text);

    $top = $height - 5;
    $left = $width - $watermark_sizes['width'] - 5;
    switch ($watermark_position[0]) {
      case 'top':
        $top = $watermark_sizes['height'] + 5;
        break;
      case 'middle':
        $top = ($height + $watermark_sizes['height']) / 2;
        break;
    }
    switch ($watermark_position[1]) {
      case 'left':
        $left = 5;
        break;
      case 'center':
        $left = ($width - $watermark_sizes['width']) / 2;
        break;
    }
    @ini_set('memory_limit', '-1');
    if ($type == 2) {
      $image = imagecreatefromjpeg($original_filename);
      imagettftext($image, $watermark_font_size, 0, $left, $top, $watermark_color, $watermark_font, $watermark_text);
      imagejpeg ($image, $dest_filename, 100);
      imagedestroy($image);  
    }
    elseif ($type == 3) {
      $image = imagecreatefrompng($original_filename);
      imagettftext($image, $watermark_font_size, 0, $left, $top, $watermark_color, $watermark_font, $watermark_text);
      imageColorAllocateAlpha($image, 0, 0, 0, 127);
      imagealphablending($image, FALSE);
      imagesavealpha($image, TRUE);
      imagepng($image, $dest_filename, 9);
      imagedestroy($image);
    }
    elseif ($type == 1) {
      $image = imagecreatefromgif($original_filename);
      imageColorAllocateAlpha($watermark_image, 0, 0, 0, 127);
      imagecopy($watermark_image, $image, 0, 0, 0, 0, $width, $height);
      imagettftext($watermark_image, $watermark_font_size, 0, $left, $top, $watermark_color, $watermark_font, $watermark_text);
      imagealphablending($watermark_image, FALSE);
      imagesavealpha($watermark_image, TRUE);
      imagegif($watermark_image, $dest_filename);
      imagedestroy($image);
    }
    imagedestroy($watermark_image);
    @ini_restore('memory_limit');
  }

  function set_image_watermark($original_filename, $dest_filename, $watermark_url, $watermark_height, $watermark_width, $watermark_position) {
    $original_filename = htmlspecialchars_decode($original_filename, ENT_COMPAT | ENT_QUOTES);
    $dest_filename = htmlspecialchars_decode($dest_filename, ENT_COMPAT | ENT_QUOTES);
    $watermark_url = htmlspecialchars_decode($watermark_url, ENT_COMPAT | ENT_QUOTES);

    list($width, $height, $type) = getimagesize($original_filename);
    list($width_watermark, $height_watermark, $type_watermark) = getimagesize($watermark_url);

    $watermark_width = $width * $watermark_width / 100;
    $watermark_height = $height_watermark * $watermark_width / $width_watermark;
        
    $watermark_position = explode('-', $watermark_position);
    $top = $height - $watermark_height - 5;
    $left = $width - $watermark_width - 5;
    switch ($watermark_position[0]) {
      case 'top':
        $top = 5;
        break;
      case 'middle':
        $top = ($height - $watermark_height) / 2;
        break;
    }
    switch ($watermark_position[1]) {
      case 'left':
        $left = 5;
        break;
      case 'center':
        $left = ($width - $watermark_width) / 2;
        break;
    }
    @ini_set('memory_limit', '-1');
    if ($type_watermark == 2) {
      $watermark_image = imagecreatefromjpeg($watermark_url);        
    }
    elseif ($type_watermark == 3) {
      $watermark_image = imagecreatefrompng($watermark_url);
    }
    elseif ($type_watermark == 1) {
      $watermark_image = imagecreatefromgif($watermark_url);      
    }
    else {
      return false;
    }

    $watermark_image_resized = imagecreatetruecolor($watermark_width, $watermark_height);
    imagecolorallocatealpha($watermark_image_resized, 255, 255, 255, 127);
    imagealphablending($watermark_image_resized, FALSE);
    imagesavealpha($watermark_image_resized, TRUE);
    imagecopyresampled ($watermark_image_resized, $watermark_image, 0, 0, 0, 0, $watermark_width, $watermark_height, $width_watermark, $height_watermark);
        
    if ($type == 2) {
      $image = imagecreatefromjpeg($original_filename);
      imagecopy($image, $watermark_image_resized, $left, $top, 0, 0, $watermark_width, $watermark_height);
      if ($dest_filename <> '') {
        imagejpeg ($image, $dest_filename, 100); 
      } else {
        header('Content-Type: image/jpeg');
        imagejpeg($image, null, 100);
      };
      imagedestroy($image);  
    }
    elseif ($type == 3) {
      $image = imagecreatefrompng($original_filename);
      imagecopy($image, $watermark_image_resized, $left, $top, 0, 0, $watermark_width, $watermark_height);
      imagealphablending($image, FALSE);
      imagesavealpha($image, TRUE);
      imagepng($image, $dest_filename, 9);
      imagedestroy($image);
    }
    elseif ($type == 1) {
      $image = imagecreatefromgif($original_filename);
      $tempimage = imagecreatetruecolor($width, $height);
      imagecopy($tempimage, $image, 0, 0, 0, 0, $width, $height);
      imagecopy($tempimage, $watermark_image_resized, $left, $top, 0, 0, $watermark_width, $watermark_height);
      imagegif($tempimage, $dest_filename);
      imagedestroy($image);
      imagedestroy($tempimage);
    }
    imagedestroy($watermark_image);
    @ini_restore('memory_limit');
  }

  public function delete($id) {
    global $wpdb;
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $id);
    if ($wpdb->query($query)) {
      $query_image = $wpdb->prepare('DELETE t1.*, t2.* FROM ' . $wpdb->prefix . 'wdsslide as t1 LEFT JOIN ' . $wpdb->prefix . 'wdslayer as t2 ON t1.id=t2.slide_id WHERE t1.slider_id="%d"', $id);
      $wpdb->query($query_image);
      echo WDW_S_Library::message('Item Succesfully Deleted.', 'wd_updated');
    }
    else {
      echo WDW_S_Library::message('Error. Please install plugin again.', 'wd_error');
    }
    $this->display();
  }
  
  public function delete_all() {
    global $wpdb;
    $flag = FALSE;
    $sliders_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wdsslider');
    foreach ($sliders_ids_col as $slider_id) {
      if (isset($_POST['check_' . $slider_id]) || isset($_POST['check_all_items'])) {
        $flag = TRUE;
        $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $slider_id);
        $wpdb->query($query);
        $query_image = $wpdb->prepare('DELETE t1.*, t2.* FROM ' . $wpdb->prefix . 'wdsslide as t1 LEFT JOIN ' . $wpdb->prefix . 'wdslayer as t2 ON t1.id=t2.slide_id WHERE t1.slider_id="%d"', $slider_id);
        $wpdb->query($query_image);
      }
    }
    if ($flag) {
      echo WDW_S_Library::message('Items Succesfully Deleted.', 'wd_updated');
    }
    else {
      echo WDW_S_Library::message('You must select at least one item.', 'wd_error');
    }
    $this->display();
  }

  public function publish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'wdsslider', array('published' => 1), array('id' => $id));
    if ($save !== FALSE) {
      echo WDW_S_Library::message('Item Succesfully Published.', 'wd_updated');
    }
    else {
      echo WDW_S_Library::message('Error. Please install plugin again.', 'wd_error');
    }
    $this->display();
  }
  
  public function publish_all() {
    global $wpdb;
    $flag = FALSE;
    if (isset($_POST['check_all_items'])) {
      $wpdb->query('UPDATE ' .  $wpdb->prefix . 'wdsslider SET published=1');
      $flag = TRUE;
    }
    else {
      $sliders_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wdsslider');
      foreach ($sliders_ids_col as $slider_id) {
        if (isset($_POST['check_' . $slider_id])) {
          $flag = TRUE;
          $wpdb->update($wpdb->prefix . 'wdsslider', array('published' => 1), array('id' => $slider_id));
        }
      }
    }
    if ($flag) {
      echo WDW_S_Library::message('Items Succesfully Published.', 'wd_updated');
    }
    else {
      echo WDW_S_Library::message('You must select at least one item.', 'wd_error');
    }
    $this->display();
  }

  public function unpublish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'wdsslider', array('published' => 0), array('id' => $id));
    if ($save !== FALSE) {
      echo WDW_S_Library::message('Item Succesfully Unpublished.', 'wd_updated');
    }
    else {
      echo WDW_S_Library::message('Error. Please install plugin again.', 'wd_error');
    }
    $this->display();
  }
  
  public function unpublish_all() {
    global $wpdb;
    $flag = FALSE;
    if (isset($_POST['check_all_items'])) {
      $wpdb->query('UPDATE ' .  $wpdb->prefix . 'wdsslider SET published=0');
      $flag = TRUE;
    }
    else {
      $sliders_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wdsslider');
      foreach ($sliders_ids_col as $slider_id) {
        if (isset($_POST['check_' . $slider_id])) {
          $flag = TRUE;
          $wpdb->update($wpdb->prefix . 'wdsslider', array('published' => 0), array('id' => $slider_id));
        }
      }
    }
    if ($flag) {
      echo WDW_S_Library::message('Items Succesfully Unpublished.', 'wd_updated');
    }
    else {
      echo WDW_S_Library::message('You must select at least one item.', 'wd_error');
    }
    $this->display();
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}