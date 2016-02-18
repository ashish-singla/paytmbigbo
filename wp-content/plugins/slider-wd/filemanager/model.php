<?php
/**
 * Author: Rob
 * Date: 6/24/13
 * Time: 11:31 AM
 */

$p_dir;
$s_order;

class FilemanagerModel {
    ////////////////////////////////////////////////////////////////////////////////////////
    // Events                                                                             //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Constants                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Variables                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
    private $controller;


    ////////////////////////////////////////////////////////////////////////////////////////
    // Constructor & Destructor                                                           //
    ////////////////////////////////////////////////////////////////////////////////////////
    public function __construct($controller) {
      $this->controller = $controller;
    }


    ////////////////////////////////////////////////////////////////////////////////////////
    // Public Methods                                                                     //
    ////////////////////////////////////////////////////////////////////////////////////////
    public function get_file_manager_data() {
      $session_data = array();
      $session_data['sort_by'] = $this->get_from_session('sort_by', 'name');
      $session_data['sort_order'] = $this->get_from_session('sort_order', 'asc');
      $session_data['items_view'] = $this->get_from_session('items_view', 'thumbs');
      $session_data['clipboard_task'] = $this->get_from_session('clipboard_task', '');
      $session_data['clipboard_files'] = $this->get_from_session('clipboard_files', '');
      $session_data['clipboard_src'] = $this->get_from_session('clipboard_src', '');
      $session_data['clipboard_dest'] = $this->get_from_session('clipboard_dest', '');

      $data = array();
      $data['session_data'] = $session_data;
      $data['path_components'] = $this->get_path_components();
      $data['dir'] = (isset($_REQUEST['dir']) ? esc_html($_REQUEST['dir']) : '');
      $data['files'] = $this->get_files($session_data['sort_by'], $session_data['sort_order']);
      $data['extensions'] = (isset($_REQUEST['extensions']) ? esc_html($_REQUEST['extensions']) : '');
      $data['callback'] = (isset($_REQUEST['callback']) ? esc_html($_REQUEST['callback']) : '');
      $data['image_for'] = (isset($_REQUEST['image_for']) ? esc_html($_REQUEST['image_for']) : '');
      $data['slide_id'] = (isset($_REQUEST['slide_id']) ? esc_html($_REQUEST['slide_id']) : '');

      return $data;
    }


    ////////////////////////////////////////////////////////////////////////////////////////
    // Getters & Setters                                                                  //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Private Methods                                                                    //
    ////////////////////////////////////////////////////////////////////////////////////////
    private function get_from_session($key, $default) {
      if (isset($_REQUEST[$key])) {
        $_REQUEST[$key] = stripslashes($_REQUEST[$key]);
      }
      else {
        $_REQUEST[$key] = stripslashes($default);
      }
      return esc_html(stripslashes($_REQUEST[$key]));
    }

    public function get_path_components() {
      $dir_names = explode('/', (isset($_REQUEST['dir']) ? esc_html($_REQUEST['dir']) : ''));
      $path = '';

      $components = array();
      $component = array();
      global $WD_S_UPLOAD_DIR;
      $component['name'] = $WD_S_UPLOAD_DIR;
      $component['path'] = $path;
      $components[] = $component;
      for ($i = 0; $i < count($dir_names); $i++) {
        $dir_name = $dir_names[$i];
        if ($dir_name == '') {
            continue;
        }
        $path .= (($path == '') ? $dir_name : '/' . $dir_name);
        $component = array();
        $component['name'] = $dir_name;
        $component['path'] = $path;
        $components[] = $component;
      }
      return $components;
    }

    function get_files($sort_by, $sort_order) {
      $icons_dir_path = WD_S_DIR . '/filemanager/images/file_icons';
      $icons_dir_url = WD_S_URL . '/filemanager/images/file_icons';
      $valid_types = explode(',', isset($_REQUEST['extensions']) ? strtolower(esc_html($_REQUEST['extensions'])) : '*');
      $image_for = isset($_REQUEST['image_for']) ? esc_html($_REQUEST['image_for']) : '';
      $parent_dir = $this->controller->get_uploads_dir() . (isset($_REQUEST['dir']) ? '/' . esc_html($_REQUEST['dir']) : '');
      $parent_dir_url = $this->controller->get_uploads_url() . (isset($_REQUEST['dir']) ? '/' . esc_html($_REQUEST['dir']) : '');
      
      if ($image_for == 'nav_right_but' || $image_for == 'nav_right_hov_but' || $image_for == 'nav_left_but' || $image_for == 'nav_left_hov_but') {
        if (!is_dir($parent_dir)) {
          mkdir($parent_dir, 0777);
        }
      }
      $file_names = $this->get_sorted_file_names($parent_dir, $sort_by, $sort_order);

      $dirs = array();
      $files = array();
      foreach ($file_names as $file_name) {
        if (($file_name == '.') || ($file_name == '..') || ($file_name == 'thumb') || ($file_name == '.original')) {
          continue;
        }
        if (is_dir($parent_dir . '/' . $file_name) == TRUE) {
          $file = array();
          $file['is_dir'] = TRUE;
          $file['name'] = $file_name;
          $file['filename'] = $file_name;
          $file['type'] = '';
          $file['thumb'] = $icons_dir_url . '/dir.png';
          $file['icon'] = $icons_dir_url . '/dir.png';
          $file['size'] = '';
          $file['date_modified'] = '';
          $file['resolution'] = '';
          $dirs[] = $file;
        }
        else {
          $file = array();
          $file['is_dir'] = FALSE;
          $file['name'] = $file_name;
          $file['filename'] = substr($file_name, 0, strrpos($file_name, '.'));
          $file_extension = explode('.', $file_name);
          $file['type'] = strtolower(end($file_extension));
          $icon = $icons_dir_url . '/' . $file['type'] . '.png';
          if (file_exists($icons_dir_path . '/' . $file['type'] . '.png') == FALSE) {
            $icon = $icons_dir_url . '/' . '_blank.png';
          }
          $file['thumb'] = $this->is_img($file['type']) ? $parent_dir_url . '/thumb/' . $file_name : $icon;
          $file['icon'] = $icon;
          if (($valid_types[0] != '*') && (in_array($file['type'], $valid_types) == FALSE)) {
            continue;
          }
          $file_size_kb = (int)(filesize($parent_dir . '/' . $file_name) / 1024);
          // $file_size_mb = (int)($file_size_kb / 1024);
          // $file['size'] = $file_size_kb < 1024 ? (string)$file_size_kb . 'KB' : (string)$file_size_mb . 'MB';
          $file['size'] = $file_size_kb . ' KB';
          $file['date_modified'] = date('d F Y, H:i', filemtime($parent_dir . '/' . $file_name));
          $image_info = getimagesize(htmlspecialchars_decode($parent_dir . '/' . $file_name, ENT_COMPAT | ENT_QUOTES));
          $file['resolution'] = $this->is_img($file['type']) ? $image_info[0]  . ' x ' . $image_info[1] . ' px' : '';
          $files[] = $file;
        }
      }

      $result = $sort_order == 'asc' ? array_merge($dirs, $files) : array_merge($files, $dirs);
      return $result;
    }
    
    /*function get_media_library_files($sort_by, $sort_order) {
      $icons_dir_path = WD_S_DIR . '/filemanager/images/file_icons';
      $icons_dir_url = WD_S_URL . '/filemanager/images/file_icons';
      $valid_types = explode(',', isset($_REQUEST['extensions']) ? strtolower(esc_html($_REQUEST['extensions'])) : '*');
      $upload_dir = wp_upload_dir();
      $parent_dir = $upload_dir['basedir'];
      $parent_dir_url = $upload_dir['baseurl'];

      $query_images_args = array(
          'post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => -1,
      );

      $query_images = new WP_Query( $query_images_args );

      $files = array();
      $upload_dir = wp_upload_dir();

      foreach ($query_images->posts as $image) {
        $file_meta = wp_get_attachment_metadata($image->ID);
        if (isset($file_meta['file'])) {
          $file = array();
          $file['is_dir'] = FALSE;
          $file_name_array = explode('/', $file_meta['file']);
          $file_name = end($file_name_array);
          $file['name'] = $file_name;
          $file['path'] = $file_meta['file'];
          $file['filename'] = substr($file_name, 0, strrpos($file_name, '.'));
          $file_type_array = explode('.', $file_name);
          $file['type'] = strtolower(end($file_type_array));
          // $file['thumb'] = wp_get_attachment_thumb_url($image->ID);
          if (!empty($file_meta['sizes']) && $file_meta['sizes']['thumbnail']['file']) {
            $file_pos = strrpos($file_meta['file'], '/');
            $sub_folder = substr($file_meta['file'], 0, $file_pos); 
            $file['thumb'] = $upload_dir['baseurl'] . '/' . $sub_folder . '/' . $file_meta['sizes']['thumbnail']['file'];
          }
          else {
            $file['thumb'] = $upload_dir['baseurl'] . '/' . $file_meta['file'];
          }
          $file['icon'] = $file['thumb'];
          if (($valid_types[0] != '*') && (in_array($file['type'], $valid_types) == FALSE)) {
            continue;
          }
          $file_size_kb = (int)(@filesize($parent_dir . '/' . $file_meta['file']) / 1024);
          if (!$file_size_kb) continue;
          $file['size'] = $file_size_kb . ' KB';
          $file['date_modified'] = date('d F Y, H:i', filemtime($parent_dir . '/' . $file_meta['file']));
          // $image_info = getimagesize(htmlspecialchars_decode($parent_dir . '/' . $file_meta['file'], ENT_COMPAT | ENT_QUOTES));
          $file['resolution'] = $this->is_img($file['type']) ? $file_meta['width']  . ' x ' . $file_meta['height'] . ' px' : '';
          $files[] = $file;
        }
      }
      return $files;
    }*/

    private function get_sorted_file_names($parent_dir, $sort_by, $sort_order) {
      $file_names = scandir($parent_dir);

      global $p_dir;
      global $s_order;

      $p_dir = $parent_dir;
      $s_order = $sort_order;

      function sort_by_size ($a, $b) {
        global $p_dir;
        global $s_order;

        $size_of_a = filesize($p_dir . '/' . $a);
        $size_of_b = filesize($p_dir . '/' . $b);
        return $s_order == 'asc' ? $size_of_a > $size_of_b : $size_of_a < $size_of_b;
      }

      function sort_by_date($a, $b) {
        global $p_dir;
        global $s_order;

        $m_time_a = filemtime($p_dir . '/' . $a);
        $m_time_b = filemtime($p_dir . '/' . $b);
        return $s_order == 'asc' ? $m_time_a > $m_time_b : $m_time_a < $m_time_b;
      }

      switch ($sort_by) {
        case 'name':
          natcasesort($file_names);
          if ($sort_order == 'desc') {
              $file_names = array_reverse($file_names);
          }
          break;
        case 'size':
          usort($file_names, 'sort_by_size');
          break;
        case 'date_modified':
          usort($file_names, 'sort_by_date');
          break;
      }
      return $file_names;
    }

    private function is_img($file_type) {
      switch ($file_type) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'bmp':
        case 'gif':
          return true;
          break;
      }
      return false;
    }


    ////////////////////////////////////////////////////////////////////////////////////////
    // Listeners                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
}