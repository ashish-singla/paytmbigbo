<?php
/**
 * Author: Rob
 * Date: 6/24/13
 * Time: 11:48 AM
 */

class FilemanagerView {
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
    private $model;

    ////////////////////////////////////////////////////////////////////////////////////////
    // Constructor & Destructor                                                           //
    ////////////////////////////////////////////////////////////////////////////////////////
    public function __construct($controller, $model) {
      $this->controller = $controller;
      $this->model = $model;
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    // Public Methods                                                                     //
    ////////////////////////////////////////////////////////////////////////////////////////
    public function display() {
      if (isset($_GET['filemanager_msg']) && esc_html($_GET['filemanager_msg']) != '') {
        ?>
        <div id="file_manager_message" style="height:40px;">
          <div  style="background-color: #FFEBE8; border: 1px solid #CC0000; margin: 5px 15px 2px; padding: 5px 10px;">
            <strong style="font-size:14px"><?php echo esc_html(stripslashes($_GET['filemanager_msg'])); ?></strong>
          </div>
        </div>
        <?php
        $_GET['filemanager_msg'] = '';
      }
      $file_manager_data = $this->model->get_file_manager_data();
      $items_view = $file_manager_data['session_data']['items_view'];
      $sort_by = $file_manager_data['session_data']['sort_by'];
      $sort_order = $file_manager_data['session_data']['sort_order'];
      $clipboard_task = $file_manager_data['session_data']['clipboard_task'];
      $clipboard_files = $file_manager_data['session_data']['clipboard_files'];
      $clipboard_src = $file_manager_data['session_data']['clipboard_src'];
      $clipboard_dest = $file_manager_data['session_data']['clipboard_dest'];
      $icons_dir_url = WD_S_URL . '/filemanager/images/file_icons';
      $sort_icon = $icons_dir_url . '/' . $sort_order;
      wp_print_scripts('jquery');
      wp_print_scripts('jquery-ui-widget');
      ?>
      <script src="<?php echo WD_S_URL; ?>/filemanager/js/jq_uploader/jquery.iframe-transport.js"></script>
      <script src="<?php echo WD_S_URL; ?>/filemanager/js/jq_uploader/jquery.fileupload.js"></script>
      <script>
        var DS = "<?php echo addslashes('/'); ?>";

        var errorLoadingFile = "<?php echo 'File loading failed'; ?>";

        var warningRemoveItems = "<?php echo 'Are you sure you want to permanently remove selected items?'; ?>";
        var warningCancelUploads = "<?php echo 'This will cancel uploads. Continue?'; ?>";

        var messageEnterDirName = "<?php echo 'Enter directory name'; ?>";
        var messageEnterNewName = "<?php echo 'Enter new name'; ?>";
        var messageFilesUploadComplete = "<?php echo 'Files upload complete'; ?>";

        var root = "<?php echo addslashes($this->controller->get_uploads_dir()); ?>";
        var dir = "<?php echo (isset($_REQUEST['dir']) ? addslashes(esc_html($_REQUEST['dir'])) : ''); ?>"; 
        var dirUrl = "<?php echo $this->controller->get_uploads_url() . (isset($_REQUEST['dir']) ? esc_html($_REQUEST['dir']) . '/' : ''); ?>";
        var callback = "<?php echo (isset($_REQUEST['callback']) ? esc_html($_REQUEST['callback']) : ''); ?>";
        var image_for = "<?php echo (isset($_REQUEST['image_for']) ? esc_html($_REQUEST['image_for']) : ''); ?>";
        var slide_id = "<?php echo (isset($_REQUEST['slide_id']) ? esc_html($_REQUEST['slide_id']) : ''); ?>";
        var layer_id = "<?php echo (isset($_REQUEST['layer_id']) ? esc_html($_REQUEST['layer_id']) : ''); ?>";
        var sortBy = "<?php echo $sort_by; ?>";
        var sortOrder = "<?php echo $sort_order; ?>";
        jQuery(document).ready(function () {
          jQuery("#search_by_name .search_by_name").on("input keyup", function() {
            var search_by_name = jQuery(this).val();
            jQuery("#explorer_body .explorer_item").each(function() {
            jQuery(this).hide();
            if (jQuery(this).find(".item_name").html().trim().toLowerCase().indexOf(search_by_name) !== -1) {
              jQuery(this).show();
            }
            });
          });
        });
      </script>
      <script src="<?php echo WD_S_URL; ?>/filemanager/js/default.js?ver=<?php echo get_option("wd_bwg_version"); ?>"></script>
      <link href="<?php echo WD_S_URL; ?>/filemanager/css/default.css?ver=<?php echo get_option("wd_bwg_version"); ?>" type="text/css" rel="stylesheet">
      <link href="<?php echo WD_S_URL; ?>/css/wds_tables_320.css?ver=<?php echo get_option("wd_bwg_version"); ?>" type="text/css" rel="stylesheet">
      <?php
      switch ($items_view) {
        case 'list':
          ?>
          <link href="<?php echo WD_S_URL; ?>/filemanager/css/default_view_list.css?ver=<?php echo get_option("wd_bwg_version"); ?>" type="text/css" rel="stylesheet">
          <?php
          break;
        case 'thumbs':
          ?>
          <link href="<?php echo WD_S_URL; ?>/filemanager/css/default_view_thumbs.css?ver=<?php echo get_option("wd_bwg_version"); ?>" type="text/css" rel="stylesheet">
          <?php
          break;
      }
      $i = 0;
      ?>

      <form id="adminForm" name="adminForm" action="" method="post">
        <div id="wrapper">
          <div id="opacity_div" style="background-color: rgba(0, 0, 0, 0.2); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99998;"></div>
          <div id="loading_div" style="text-align: center; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999;">
            <img src="<?php echo WD_S_URL . '/images/ajax_loader.gif'; ?>" class="spider_ajax_loading" style="margin-top: 200px; width:50px;">
          </div>
          <div id="file_manager">
            <div class="ctrls_bar ctrls_bar_header">
              <div class="ctrls_left">
                <a class="ctrl_bar_btn btn_up" onclick="onBtnUpClick(event, this);" title="<?php echo 'Up'; ?>"></a>
                <a class="ctrl_bar_btn btn_make_dir" onclick="onBtnMakeDirClick(event, this);" title="<?php echo 'Make a directory'; ?>"></a>
                <a class="ctrl_bar_btn btn_rename_item" onclick="onBtnRenameItemClick(event, this);" title="<?php echo 'Rename item'; ?>"></a>
                <span class="ctrl_bar_divider"></span>
                <a class="ctrl_bar_btn btn_copy" onclick="onBtnCopyClick(event, this);" title="<?php echo 'Copy'; ?>"></a>
                <a class="ctrl_bar_btn btn_cut" onclick="onBtnCutClick(event, this);" title="<?php echo 'Cut'; ?>"></a>
                <a class="ctrl_bar_btn btn_paste" onclick="onBtnPasteClick(event, this);" title="<?php echo 'Paste'; ?>"> </a>
                <a class="ctrl_bar_btn btn_remove_items" onclick="onBtnRemoveItemsClick(event, this);" title="<?php echo 'Remove items'; ?>"></a>
                <span class="ctrl_bar_divider"></span>
								<span class="ctrl_bar_search_upload">
                <span class="ctrl_bar_btn btn_primary">
                  <a class="ctrl_bar_btn btn_upload_files" onclick="onBtnShowUploaderClick(event, this);"><?php echo 'Upload files'; ?></a>
                </span>
		<span class="ctrl_bar_divider"></span>
                <span id="search_by_name" class="ctrl_bar_btn">
                  <input type="search" placeholder="Search" class="ctrl_bar_btn search_by_name">
									</span>
                </span>
              </div>
              <div class="ctrls_right">
                <a class="ctrl_bar_btn btn_view_thumbs" onclick="onBtnViewThumbsClick(event, this);" title="<?php echo 'View thumbs'; ?>"></a>
                <a class="ctrl_bar_btn btn_view_list" onclick="onBtnViewListClick(event, this);" title="<?php echo 'View list'; ?>"></a>
              </div>
            </div>
            <div id="path">
              <?php
              foreach ($file_manager_data['path_components'] as $key => $path_component) {
                ?>
                <a class="path_component path_dir"
                   onclick="onPathComponentClick(event, this, '<?php echo addslashes($path_component['path']); ?>');">
                    <?php echo $path_component['name']; ?></a>
                <a class="path_component path_separator"><?php echo '/'; ?></a>
                <?php
              }
              ?>
            </div>
            <div id="explorer">
              <div id="explorer_header_wrapper">
                <div id="explorer_header_container">
                  <div id="explorer_header">
                    <span class="item_numbering">#</span>
                    <span class="item_icon"></span>
                    <span class="item_name">
                      <span class="clickable" onclick="onNameHeaderClick(event, this);">
                          <?php
                          echo 'Name';
                          if ($sort_by == 'name') {
                            ?>
                            <span class="sort_order_<?php echo $sort_order; ?>"></span>
                            <?php
                          }
                          ?>
                      </span>
                    </span>
                    <span class="item_size">
                      <span class="clickable" onclick="onSizeHeaderClick(event, this);">
                        <?php
                        echo 'Size';
                        if ($sort_by == 'size') {
                          ?>
                          <span class="sort_order_<?php echo $sort_order; ?>"></span>
                          <?php
                        }
                        ?>
                      </span>
                    </span>
                    <span class="item_date_modified">
                      <span class="clickable" onclick="onDateModifiedHeaderClick(event, this);">
                        <?php
                        echo 'Date modified';
                        if ($sort_by == 'date_modified') {
                          ?>
                          <span class="sort_order_<?php echo $sort_order; ?>"></span>
                          <?php
                        }
                        ?>
                      </span>
                    </span>
                    <span class="scrollbar_filler"></span>
                  </div>
                </div>
              </div>
              <div id="explorer_body_wrapper">
                <div id="explorer_body_container">
                  <div id="explorer_body">
                    <?php
                    foreach ($file_manager_data['files'] as $file) {
                      $file['name'] = esc_html($file['name']);
                      $file['filename'] = esc_html($file['filename']);
                      $file['thumb'] = esc_html($file['thumb']);
                      ?>
                      <div class="explorer_item" draggable="true"
                           name="<?php echo $file['name']; ?>"
                           filename="<?php echo $file['filename']; ?>"
                           filethumb="<?php echo $file['thumb']; ?>"
                           filesize="<?php echo $file['size']; ?>"
                           filetype="<?php echo strtoupper($file['type']); ?>"
                           date_modified="<?php echo $file['date_modified']; ?>"
                           fileresolution="<?php echo $file['resolution']; ?>"
                           onmouseover="onFileMOver(event, this);"
                           onmouseout="onFileMOut(event, this);"
                           onclick="onFileClick(event, this);"
                           ondblclick="onFileDblClick(event, this);"
                           ondragstart="onFileDragStart(event, this);"
                          <?php
                          if ($file['is_dir'] == true) {
                            ?>
                          ondragover="onFileDragOver(event, this);"
                          ondrop="onFileDrop(event, this);"
                            <?php
                          }
                          ?>
                          isDir="<?php echo $file['is_dir'] == true ? 'true' : 'false'; ?>">
                        <span class="item_numbering"><?php echo ++$i; ?></span>
                        <span class="item_thumb">
                          <img src="<?php echo $file['thumb']; ?>"/>
                        </span>
                        <span class="item_icon">
                          <img src="<?php echo $file['icon']; ?>"/>
                        </span>
                        <span class="item_name">
                          <?php echo $file['name']; ?>
                        </span>
                        <span class="item_size">
                          <?php echo $file['size']; ?>
                        </span>
                        <span class="item_date_modified">
                          <?php echo $file['date_modified']; ?>
                        </span>
                      </div>
                      <?php
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="ctrls_bar ctrls_bar_footer">
              <div class="ctrls_left">
                <a class="ctrl_bar_btn btn_open btn_primary none_select" onclick="onBtnSelectAllClick();"><?php echo 'Select All'; ?></a>
              </div>
              <div class="ctrls_right">
                <span id="file_names_span">
                  <span>
                  </span>
                </span>
                <a class="ctrl_bar_btn btn_open btn_primary none_select" onclick="onBtnOpenClick(event, this);"><?php echo ((isset($_REQUEST['image_for']) && esc_html($_REQUEST['image_for']) == 'add_slides') ? 'Add selected images to slider' : 'Add image to slider'); ?></a>
                <span class="ctrl_bar_empty_devider"></span>
                <a class="ctrl_bar_btn btn_cancel btn_secondary none_select" onclick="onBtnCancelClick(event, this);"><?php echo 'Cancel'; ?></a>
              </div>
            </div>
          </div>
          <div id="uploader">
            <div id="uploader_bg"></div>
            <div class="ctrls_bar ctrls_bar_header">
              <div class="ctrls_left upload_thumb">
                Thumbnail Maximum Dimensions:
                <input type="text" class="upload_thumb_dim" name="upload_thumb_width" id="upload_thumb_width" value="<?php echo /*$bwg_options->upload_thumb_width;*/ 300; ?>" /> x 
                <input type="text" class="upload_thumb_dim" name="upload_thumb_height" id="upload_thumb_height" value="<?php echo /*$bwg_options->upload_thumb_height;*/ 300; ?>" /> px
              </div>
              <div class="ctrls_right">
                <a class="ctrl_bar_btn btn_back" onclick="onBtnBackClick(event, this);" title="<?php echo 'Back'; ?>"></a>
              </div>
              <div class="ctrls_right_img upload_thumb">
                Image Maximum Dimensions:
                <input type="text" class="upload_thumb_dim" name="upload_img_width" id="upload_img_width" value="<?php echo /*$bwg_options->upload_img_width;*/ 1200; ?>" /> x 
                <input type="text" class="upload_thumb_dim" name="upload_img_height" id="upload_img_height" value="<?php echo /*$bwg_options->upload_img_height;*/ 1200; ?>" /> px
              </div>
            </div>
            <label for="jQueryUploader">
              <div id="uploader_hitter">
                <div id="drag_message">
                  <span><?php echo 'Drag files here or click the button below' . '<br />' . 'to upload files' ?></span>
                </div>
                <div id="btnBrowseContainer">
                  <input id="jQueryUploader" type="file" name="files[]"
                         data-url="<?php echo add_query_arg(array('action' => 'wds_UploadHandler', 'dir' => $this->controller->get_uploads_dir() . '/' . (isset($_REQUEST['dir']) ? esc_html($_REQUEST['dir']) : '') . '/'), admin_url('admin-ajax.php')); ?>"
                         multiple>
                </div>
                <script>
                  jQuery("#jQueryUploader").fileupload({
                    dataType: "json",
                    dropZone: jQuery("#uploader_hitter"),
                    submit: function (e, data) {
                      jQuery("#uploader_progress_text").removeClass("uploader_text");
                      isUploading = true;
                      jQuery("#uploader_progress_bar").fadeIn();
                    },
                    progressall: function (e, data) {
                      var progress = parseInt(data.loaded / data.total * 100, 10);
                      jQuery("#uploader_progress_text").text("Progress " + progress + "%");
                      jQuery("#uploader_progress div div").css({width: progress + "%"});
                      if (data.loaded == data.total) {
                        isUploading = false;
                        jQuery("#uploader_progress_bar").fadeOut(function () {
                          jQuery("#uploader_progress_text").text(messageFilesUploadComplete);
                          jQuery("#uploader_progress_text").addClass("uploader_text");
                        });
                      }
                    },
                    stop: function (e, data) {
                      onBtnBackClick();
                    },
                    done: function (e, data) {
                      jQuery.each(data.result.files, function (index, file) {
                        if (file.error) {
                          alert(errorLoadingFile + ' :: ' + file.error);
                        }
                        if (file.error) {
                          jQuery("#uploaded_files ul").prepend(jQuery("<li class=uploaded_item_failed>" + "<?php echo 'Upload failed' ?> :: " + file.error + "</li>"));
                        }
                        else {
                          jQuery("#uploaded_files ul").prepend(jQuery("<li class=uploaded_item>" + file.name + " (<?php echo 'Uploaded' ?>)" + "</li>"));
                        }
                      });
                    }
                  });
                  jQuery(window).load(function () { 
                    jQuery("#opacity_div").hide();
                    jQuery("#loading_div").hide();
                  })
                </script>
              </div>
            </label>
            <div id="uploaded_files">
              <ul></ul>
            </div>
            <div id="uploader_progress">
              <div id="uploader_progress_bar">
                <div></div>
              </div>
              <span id="uploader_progress_text" class="uploader_text">
                <?php echo 'No files to upload'; ?>
              </span>
            </div>
          </div>
        </div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="extensions" value="<?php echo (isset($_REQUEST['extensions']) ? esc_html($_REQUEST['extensions']) : '*'); ?>" />
        <input type="hidden" name="callback" value="<?php echo (isset($_REQUEST['callback']) ? esc_html($_REQUEST['callback']) : ''); ?>" />
        <input type="hidden" name="image_for" value="<?php echo (isset($_REQUEST['image_for']) ? esc_html($_REQUEST['image_for']) : ''); ?>" />
        <input type="hidden" name="slide_id" value="<?php echo (isset($_REQUEST['slide_id']) ? esc_html($_REQUEST['slide_id']) : ''); ?>" />
        <input type="hidden" name="layer_id" value="<?php echo (isset($_REQUEST['layer_id']) ? esc_html($_REQUEST['layer_id']) : ''); ?>" />
        <input type="hidden" name="sort_by" value="<?php echo $sort_by; ?>" />
        <input type="hidden" name="sort_order" value="<?php echo $sort_order; ?>" />
        <input type="hidden" name="items_view" value="<?php echo $items_view; ?>" />
        <input type="hidden" name="dir" value="<?php echo (isset($_REQUEST['dir']) ? esc_html($_REQUEST['dir']) : ''); ?>" />
        <input type="hidden" name="file_names" value="" />
        <input type="hidden" name="file_namesML" value="" />
        <input type="hidden" name="file_new_name" value="" />
        <input type="hidden" name="new_dir_name" value="" />
        <input type="hidden" name="clipboard_task" value="<?php echo $clipboard_task; ?>" />
        <input type="hidden" name="clipboard_files" value="<?php echo $clipboard_files; ?>" />
        <input type="hidden" name="clipboard_src" value="<?php echo $clipboard_src; ?>" />
        <input type="hidden" name="clipboard_dest" value="<?php echo $clipboard_dest; ?>" />
      </form>
      <?php
      die();
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