<?php

class WDSModelSliders_wds {
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

  public function get_slides_count($slider_id) {
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM " . $wpdb->prefix . "wdsslide WHERE slider_id='%d' AND image_url<>''", $slider_id));
    return $count;
  }

  public function get_slides_row_data($slider_id) {
    global $wpdb;
    $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "wdsslide WHERE slider_id='%d' ORDER BY `order` ASC", $slider_id));
    if (!$rows) {
      $rows = array();
      $rows[0] = new stdClass();
      $rows[0]->id = 'pr1';
      $rows[0]->title = 'Slide 1';
      $rows[0]->type = 'image';
      $rows[0]->image_url = '';
      $rows[0]->thumb_url = '';
      $rows[0]->published = 1;
      $rows[0]->link = '';
      $rows[0]->order = 1;
      $rows[0]->target_attr_slide = 1;
    }
    else {
      foreach ($rows as $row) {
        if ($row->type == 'image') {
          $row->image_url = $row->image_url ? $row->image_url : WD_S_URL . '/images/no-image.png';
          $row->thumb_url = $row->thumb_url ? $row->thumb_url : WD_S_URL . '/images/no-image.png';
        }
      }
    }
    return $rows;
  }

  public function get_layers_row_data($slide_id) {
    global $wpdb;
    $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "wdslayer WHERE slide_id='%d' ORDER BY `depth` ASC", $slide_id));
    foreach ($rows as $row) {
      if ($row->type == 'image') {
        $row->image_url = $row->image_url ? $row->image_url : WD_S_URL . '/images/no-image.png';
      }
    }
    return $rows;
  }

  public function get_slider_prev_img($slider_id) { 
    global $wpdb;
    $prev_img_url = $wpdb->get_var($wpdb->prepare("SELECT `thumb_url` FROM " . $wpdb->prefix . "wdsslide WHERE slider_id='%d' ORDER BY `order` ASC", $slider_id));
    $prev_img_url = $prev_img_url ? $prev_img_url : WD_S_URL . '/images/no-image.png';
    return $prev_img_url;
  }

  public function get_rows_data() {
    global $wpdb;
    $where = ((isset($_POST['search_value'])) ? 'WHERE name LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"' : '');
    $asc_or_desc = ((isset($_POST['asc_or_desc']) && esc_html($_POST['asc_or_desc']) == 'desc') ? 'desc' : 'asc');
    $order_by_arr = array('id', 'name', 'published');
    $order_by = ((isset($_POST['order_by']) && in_array(esc_html($_POST['order_by']), $order_by_arr)) ? esc_html($_POST['order_by']) : 'id');
    $order_by = ' ORDER BY `' . $order_by . '` ' . $asc_or_desc;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $query = "SELECT * FROM " . $wpdb->prefix . "wdsslider " . $where . $order_by . " LIMIT " . $limit . ",20";
    $rows = $wpdb->get_results($query);
    return $rows;
  }

  public function get_row_data($id, $reset) {
    global $wpdb;
    if ($id != 0 && !$reset) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $id));
      if ($row) {
        $row->enable_bullets = $row->bull_position == 'none' ? 0 : 1;
        $row->enable_filmstrip = $row->film_pos == 'none' ? 0 : 1;
        $row->enable_time_bar = $row->timer_bar_type == 'none' ? 0 : 1;
      }
    }
    else {
      $row = new stdClass();
      if ($reset && $id) {
        $row = $wpdb->get_row($wpdb->prepare('SELECT name FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $id));
      }
      else {
        $row->name = '';
      }
      $row->id = $id;
      $row->width = 800;
      $row->height = 300;
      $row->full_width = 0;
      $row->spider_uploader = get_option("wds_version_1.0.46") ? 0 : 1;	  
      $row->bg_fit = 'cover';
      $row->align = 'center';
      $row->effect = 'fade';
      $row->published = 1;
      $row->time_intervval = 5;
      $row->autoplay = 0;
      $row->shuffle = 0;
      $row->music = 0;
      $row->music_url = '';
      $row->preload_images = 1;
      $row->background_color = '000000';
      $row->background_transparent = 100;
      $row->glb_border_width = 0;
      $row->glb_border_style = 'none';
      $row->glb_border_color = '000000';
      $row->glb_border_radius = '';
      $row->glb_margin = 0;
      $row->glb_box_shadow = '';
      $row->image_right_click = 0;
      $row->layer_out_next = 0;
      $row->prev_next_butt = 1;
      $row->play_paus_butt = 0;
      $row->navigation = 'hover';
      $row->rl_butt_style = 'fa-angle';
      $row->rl_butt_size = 40;
      $row->pp_butt_size = 40;
      $row->butts_color = '000000';
      $row->hover_color = '000000';
      $row->nav_border_width = 0;
      $row->nav_border_style = 'none';
      $row->nav_border_color = 'FFFFFF';
      $row->nav_border_radius = '20px';
      $row->nav_bg_color = 'FFFFFF';
      $row->butts_transparent = 100;
      $row->enable_bullets = 1;
      $row->bull_position = 'bottom';
      $row->bull_style = 'fa-square-o';
      $row->bull_size = 20;
      $row->bull_color = 'FFFFFF';
      $row->bull_act_color = 'FFFFFF';
      $row->bull_margin = 3;
      $row->enable_filmstrip = 0;
      $row->film_pos = 'none';
      $row->film_thumb_width = 100;
      $row->film_thumb_height = 50;
      $row->film_bg_color = '000000';
      $row->film_tmb_margin = 0;
      $row->film_act_border_width = 0;
      $row->film_act_border_style = 'none';
      $row->film_act_border_color = 'FFFFFF';
      $row->film_dac_transparent = 50;
      $row->enable_time_bar = 1;
      $row->timer_bar_type = 'top';
      $row->timer_bar_size = 5;
      $row->timer_bar_color = 'BBBBBB';
      $row->timer_bar_transparent = 50;
      $row->built_in_watermark_type = 'none';
      $row->built_in_watermark_position = 'middle-center';
      $row->built_in_watermark_size = 15;
      $row->built_in_watermark_url = WD_S_URL . '/images/watermark.png';
      $row->built_in_watermark_text = 'web-dorado.com';
      $row->built_in_watermark_font_size = 20;
      $row->built_in_watermark_font = '';
      $row->built_in_watermark_color = 'FFFFFF';
      $row->built_in_watermark_opacity = 70;
      $row->stop_animation = 0;
      $row->css = '';
      $row->right_butt_url = WD_S_URL . '/images/arrow/arrow11/1/2.png';
      $row->left_butt_url = WD_S_URL . '/images/arrow/arrow11/1/1.png';
      $row->right_butt_hov_url = WD_S_URL . '/images/arrow/arrow11/1/4.png';
      $row->left_butt_hov_url = WD_S_URL . '/images/arrow/arrow11/1/3.png';
      $row->rl_butt_img_or_not = 'style';
      $row->bullets_img_main_url = WD_S_URL . '/images/bullet/bullet1/1/1.png';
      $row->bullets_img_hov_url = WD_S_URL . '/images/bullet/bullet1/1/2.png';
      $row->bull_butt_img_or_not = 'style';
      $row->play_paus_butt_img_or_not = 'style';
      $row->play_butt_url = WD_S_URL . '/images/button/button4/1/1.png';
      $row->play_butt_hov_url = WD_S_URL . '/images/button/button4/1/2.png';
      $row->paus_butt_url = WD_S_URL . '/images/button/button4/1/3.png';
      $row->paus_butt_hov_url = WD_S_URL . '/images/button/button4/1/4.png';
      $row->start_slide_num = 1;
      $row->effect_duration = 800;
      $row->carousel = 0;
      $row->carousel_image_counts = 7;
      $row->carousel_image_parameters = 0.85;
      $row->carousel_fit_containerWidth = 0;
      $row->carousel_width = 1000;
      $row->parallax_effect = 0;
      $row->mouse_swipe_nav = 0;
      $row->bull_hover = 1;
      $row->touch_swipe_nav = 1;
      $row->mouse_wheel_nav = 0;
      $row->keyboard_nav = 0;
      $row->possib_add_ffamily = '';
      $row->show_thumbnail = 0;
      $row->thumb_size = '0.2';
      $row->fixed_bg = 0;
    }
    return $row;
  }

  public function page_nav() {
    global $wpdb;
    $where = ((isset($_POST['search_value']) && (esc_html(stripslashes($_POST['search_value'])) != '')) ? 'WHERE name LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"'  : '');
    $total = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "wdsslider " . $where);
    $page_nav['total'] = $total;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $page_nav['limit'] = (int) ($limit / 20 + 1);
    return $page_nav;
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