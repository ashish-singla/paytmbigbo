<?php

function wds_update($version) {
  global $wpdb;
  if (version_compare($version, '1.0.2') == -1) {
   // Add spider uploader option.
   $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `spider_uploader` tinyint(1) NOT NULL DEFAULT 0");
  }
   if (version_compare($version, '1.0.4') == -1) {
    // Add stop animation on hover and link target options.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `stop_animation` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslide ADD `target_attr_slide` tinyint(1) NOT NULL DEFAULT 1");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `target_attr_layer` tinyint(1) NOT NULL DEFAULT 1");
  }
  if (version_compare($version, '1.0.5') == -1) {
    // Add right/left button image/hover image url.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `right_butt_url` varchar(255) NOT NULL DEFAULT '" . WD_S_URL . '/images/arrow/arrow11/1/2.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `left_butt_url` varchar(255) NOT NULL DEFAULT '" . WD_S_URL . '/images/arrow/arrow11/1/1.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `right_butt_hov_url` varchar(255) NOT NULL DEFAULT '" . WD_S_URL . '/images/arrow/arrow11/1/4.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `left_butt_hov_url` varchar(255) NOT NULL DEFAULT '" . WD_S_URL . '/images/arrow/arrow11/1/3.png' . "'");
    // Whether to display right/left buttons by image or not.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `rl_butt_img_or_not` varchar(8) NOT NULL DEFAULT 'style'");
    // Add bullets image/hover image url.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bullets_img_main_url` varchar(255) NOT NULL DEFAULT '" . WD_S_URL . '/images/bullet/bullet1/1/1.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bullets_img_hov_url` varchar(255) NOT NULL DEFAULT '" . WD_S_URL . '/images/bullet/bullet1/1/2.png' . "'");
    // Whether to display bullets by image or not.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bull_butt_img_or_not` varchar(8) NOT NULL DEFAULT 'style'");
  }
  if (version_compare($version, '1.0.6') == -1) {
    // Add play/pause button image/hover image url.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `play_butt_url` varchar(255) NOT NULL DEFAULT '" . WD_S_URL . '/images/button/button4/1/1.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `paus_butt_url` varchar(255) NOT NULL DEFAULT '" . WD_S_URL . '/images/button/button4/1/3.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `play_butt_hov_url` varchar(255) NOT NULL DEFAULT '" . WD_S_URL . '/images/button/button4/1/2.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `paus_butt_hov_url` varchar(255) NOT NULL DEFAULT '" . WD_S_URL . '/images/button/button4/1/4.png' . "'");
    // Whether to display play/pause buttons by image or not.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `play_paus_butt_img_or_not` varchar(8) NOT NULL DEFAULT 'style'");
  }
  if (version_compare($version, '1.0.8') == -1) {
    // Start slider with slide.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `start_slide_num` int(4) NOT NULL DEFAULT 1");
    // Transition effect duration.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `effect_duration` int(6) NOT NULL DEFAULT 800");
  }
  if (version_compare($version, '1.0.11') == -1) {
    // Carousel view options.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_image_counts` int(4) NOT NULL DEFAULT 7");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_image_parameters` varchar(8) NOT NULL DEFAULT 0.85");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_fit_containerWidth` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_width` int(4) NOT NULL DEFAULT 1000");
  }
   if (version_compare($version, '1.0.23') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_width` int(4) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_fbgcolor`  varchar(8) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_border_width` int(4) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_border_style` varchar(16) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_border_color` varchar(8) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_border_radius` varchar(32) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_text_position` varchar(6) NOT NULL");
  }
  if (version_compare($version, '1.0.24') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslide` CHANGE `type` `type` varchar(128)");
  }
  if (version_compare($version, '1.0.26') == -1) {
    // Parallax_effect.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `parallax_effect` tinyint(1) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.0.32') == -1) {
    // Mouse swipe navigation.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `mouse_swipe_nav` tinyint(1) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.0.33') == -1) {
    // Show bullets on hover.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bull_hover` int(1) NOT NULL DEFAULT 1");
  }
  if (version_compare($version, '1.0.34') == -1) {
    // Google fonts.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `google_fonts` int(1) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.0.40') == -1) {
    // Navigation methods.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `touch_swipe_nav` tinyint(1) NOT NULL DEFAULT 1");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `mouse_wheel_nav` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `keyboard_nav` tinyint(1) NOT NULL DEFAULT 0");   
  }
  if (version_compare($version, '1.0.41') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslide` CHANGE `title` `title` longtext");
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` CHANGE `title` `title` longtext");
  }
  if (version_compare($version, '1.0.42') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `possib_add_ffamily` varchar(255) NOT NULL DEFAULT ''");
  }
  if (version_compare($version, '1.0.43') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `show_thumbnail` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `thumb_size` varchar(8) NOT NULL DEFAULT '0.2'");
  }
  if (version_compare($version, '1.0.47') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `fixed_bg` tinyint(1) NOT NULL DEFAULT 0");
  }
  return;
}

?>