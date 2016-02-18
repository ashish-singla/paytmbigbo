<?php

class WDW_S_Library {
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
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  public static function get($key, $default_value = '') {
    if (isset($_GET[$key])) {
      $value = $_GET[$key];
    }
    elseif (isset($_POST[$key])) {
      $value = $_POST[$key];
    }
    else {
      $value = '';
    }
    if (!$value) {
      $value = $default_value;
    }
    return esc_html($value);
  }

  public static function message_id($message_id) {
    if ($message_id) {
      switch($message_id) {
        case 1: {
          $message = 'Item Succesfully Saved.';
          $type = 'wd_updated';
          break;

        }
        case 2: {
          $message = 'Error. Please install plugin again.';
          $type = 'wd_error';
          break;

        }
        case 3: {
          $message = 'Item Succesfully Deleted.';
          $type = 'wd_updated';
          break;

        }
        case 4: {
          $message = "You can't delete default theme";
          $type = 'wd_error';
          break;

        }
        case 5: {
          $message = 'Items Succesfully Deleted.';
          $type = 'wd_updated';
          break;

        }
        case 6: {
          $message = 'You must select at least one item.';
          $type = 'wd_error';
          break;

        }
        case 7: {
          $message = 'The item is successfully set as default.';
          $type = 'wd_updated';
          break;

        }
        case 8: {
          $message = 'Options Succesfully Saved.';
          $type = 'wd_updated';
          break;

        }
        case 9: {
          $message = 'Item Succesfully Published.';
          $type = 'wd_updated';
          break;

        }
        case 10: {
          $message = 'Items Succesfully Published.';
          $type = 'wd_updated';
          break;

        }
        case 11: {
          $message = 'Item Succesfully Unpublished.';
          $type = 'wd_updated';
          break;

        }
        case 12: {
          $message = 'Items Succesfully Unpublished.';
          $type = 'wd_updated';
          break;

        }
        case 13: {
          $message = 'Ordering Succesfully Saved.';
          $type = 'wd_updated';
          break;

        }
        case 14: {
          $message = 'A term with the name provided already exists.';
          $type = 'wd_error';
          break;

        }
        case 15: {
          $message = 'Name field is required.';
          $type = 'wd_error';
          break;

        }
        case 16: {
          $message = 'The slug must be unique.';
          $type = 'wd_error';
          break;

        }
        case 17: {
          $message = 'Changes must be saved.';
          $type = 'wd_error';
          break;

        }
        case 18: {
          $message = 'You must set watermark type.';
          $type = 'wd_error';
          break;

        }
        case 19: {
          $message = 'Watermark Succesfully Set.';
          $type = 'wd_updated';
          break;

        }
        case 20: {
          $message = 'Watermark Succesfully Reset.';
          $type = 'wd_updated';
          break;

        }
        case 21: {
          $message = 'Settings Succesfully Reset.';
          $type = 'wd_updated';
          break;

        }
        default: {
          $message = '';
          $type = 'wd_error';
          break;
        }
      }
      return '<div style="width:99%"><div class="' . $type . '"><p><strong>' . $message . '</strong></p></div></div>';
    }
  }

  public static function message($message, $type) {
    return '<div style="width: 99%" class="spider_message"><div class="' . $type . '"><p><strong>' . $message . '</strong></p></div></div>';
  }

  public static function search($search_by, $search_value, $form_id) {
    ?>
    <div class="alignleft actions" style="float:left;">
      <script>
        function spider_search(event) {
          if (typeof event != 'undefined') {
            var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
            if (keyCode != 13) {
              return false;
            }
          }
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
        function spider_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          if (document.getElementById("category_id")) {
            document.getElementById("category_id").value = -1;
          }
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
      </script>
      <div class="alignleft actions" style="">
        <label for="search_value" style="font-size:14px; width:50px; display:inline-block;"><?php echo $search_by; ?>:</label>
        <input type="text"
               id="search_value"
               name="search_value"
               class="spider_search_value"
               value="<?php echo esc_html($search_value); ?>"
               style="box-shadow:none; width: 150px;<?php echo (get_bloginfo('version') > '3.7') ? ' height: 32px;' : ''; ?>"
               onkeypress="spider_search(event)" />
      </div>
      <div class="alignleft actions">
        <input type="button" value="Search" id="wds_search" onclick="spider_search()" class="wds_button-secondary action">
        <input type="button" value="Reset" id="wds_reset" onclick="spider_reset()" class="wds_button-secondary action">
      </div>
    </div>
    <?php
  }

  public static function search_select($search_by, $search_select_id = 'search_select_value', $search_select_value, $playlists, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function spider_search_select() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
      </script>
      <div class="alignleft actions" >
        <label for="<?php echo $search_select_id; ?>" style="font-size:14px; width:50px; display:inline-block;"><?php echo $search_by; ?>:</label>
        <select id="<?php echo $search_select_id; ?>" name="<?php echo $search_select_id; ?>" onchange="spider_search_select();" style="float: none; width: 150px;">
        <?php
          foreach ($playlists as $id => $playlist) {
            ?>
            <option value="<?php echo $id; ?>" <?php echo (($search_select_value == $id) ? 'selected="selected"' : ''); ?>><?php echo $playlist; ?></option>
            <?php
          }
        ?>
        </select>
      </div>
    </div>
    <?php
  }
  
  public static function html_page_nav($count_items, $page_number, $form_id, $items_per_page = 20) {
    $limit = 20;
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function spider_page(x, y) {       
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        document.getElementById('<?php echo $form_id; ?>').submit();
      }
      function check_enter_key(e) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery('#current_page').val() >= items_county) {
           document.getElementById('page_number').value = items_county;
          }
          else {
           document.getElementById('page_number').value = jQuery('#current_page').val();
          }
          document.getElementById('<?php echo $form_id; ?>').submit();
        }
        return true;
      }
    </script>
    <div class="tablenav-pages" style="text-align:right">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo $count_items; ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      if ($count_items > $items_per_page) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="Go to the first page" href="javascript:spider_page(<?php echo $page_number; ?>,-2);">«</a>
        <a class="<?php echo $prev_page; ?>" title="Go to the previous page" href="javascript:spider_page(<?php echo $page_number; ?>,-1);">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event)" title="Go to the page" type="text" size="1" />
        </span> of 
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="Go to the next page" href="javascript:spider_page(<?php echo $page_number; ?>,1);">›</a>
        <a class="<?php echo $last_page ?>" title="Go to the last page" href="javascript:spider_page(<?php echo $page_number; ?>,2);">»</a>
        <?php
      }
      ?>
      </span>
    </div>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo ((isset($_POST['page_number'])) ? (int) $_POST['page_number'] : 1); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo ((isset($_POST['search_or_not'])) ? esc_html($_POST['search_or_not']) : ''); ?>"/>
    <?php
  }

  public static function ajax_search($search_by, $search_value, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function spider_search() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          spider_ajax_save('<?php echo $form_id; ?>');
        }
        function spider_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          spider_ajax_save('<?php echo $form_id; ?>');
        }
      </script>
      <div class="alignleft actions" style="">
        <label for="search_value" style="font-size:14px; width:60px; display:inline-block;"><?php echo $search_by; ?>:</label>
        <input type="text" id="search_value" name="search_value" class="spider_search_value" value="<?php echo esc_html($search_value); ?>" style="width: 150px;<?php echo (get_bloginfo('version') > '3.7') ? ' height: 32px;' : ''; ?>" />
      </div>
      <div class="alignleft actions">
        <input type="button" value="Search" onclick="spider_search()" class="button-secondary action">
        <input type="button" value="Reset" onclick="spider_reset()" class="button-secondary action">
      </div>
    </div>
    <?php
  }

  public static function ajax_html_page_nav($count_items, $page_number, $form_id) {
    $limit = 20;
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function spider_page(x, y) {
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        spider_ajax_save('<?php echo $form_id; ?>');
      }
      function check_enter_key(e) { 	  
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery('#current_page').val() >= items_county) {
           document.getElementById('page_number').value = items_county;
          }
          else {
           document.getElementById('page_number').value = jQuery('#current_page').val();
          }
          spider_ajax_save('<?php echo $form_id; ?>');
          return false;
        }
       return true;		 
      }
    </script>
    <div id="tablenav-pages" class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo $count_items; ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      if ($count_items > $limit) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="Go to the first page" onclick="spider_page(<?php echo $page_number; ?>,-2)">«</a>
        <a class="<?php echo $prev_page; ?>" title="Go to the previous page" onclick="spider_page(<?php echo $page_number; ?>,-1)">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event)" title="Go to the page" type="text" size="1" />
        </span> of 
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="Go to the next page" onclick="spider_page(<?php echo $page_number; ?>,1)">›</a>
        <a class="<?php echo $last_page ?>" title="Go to the last page" onclick="spider_page(<?php echo $page_number; ?>,2)">»</a>
        <?php
      }
      ?>
      </span>
    </div>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo ((isset($_POST['page_number'])) ? (int) $_POST['page_number'] : 1); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo ((isset($_POST['search_or_not'])) ? esc_html($_POST['search_or_not']) : ''); ?>"/>
    <?php
  }

  public static function spider_hex2rgb($colour) {
    if ($colour[0] == '#') {
      $colour = substr( $colour, 1 );
    }
    if (strlen($colour) == 6) {
      list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    }
    else if (strlen($colour) == 3) {
      list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    }
    else {
      return FALSE;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return array('red' => $r, 'green' => $g, 'blue' => $b);
  }

  public static function spider_hex2rgba($colour, $transparent = 1) {
    if ($colour[0] == '#') {
      $colour = substr( $colour, 1 );
    }
    if (strlen($colour) == 6) {
      list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    }
    else if (strlen($colour) == 3) {
      list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    }
    else {
      return FALSE;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . number_format($transparent, 2, ".", "") . ')';
  }

  public static function spider_redirect($url) {
    $url = html_entity_decode(wp_nonce_url($url, 'nonce_wd', 'nonce_wd'));
    ?>
    <script>
      window.location = "<?php echo $url; ?>";
    </script>
    <?php
    exit();
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}