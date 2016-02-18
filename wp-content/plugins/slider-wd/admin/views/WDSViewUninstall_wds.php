<?php

class WDSViewUninstall_wds {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    ?>
    <form method="post" action="admin.php?page=uninstall_wds" style="width:99%;">
      <?php wp_nonce_field('nonce_wd', 'nonce_wd'); ?>
      <div class="wrap">
        <span class="uninstall_icon"></span>
        <h2>Uninstall Slider WD</h2>
        <p>
          Deactivating Slider WD plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.
        </p>
        <p style="color: red;">
          <strong>WARNING:</strong>
          Once uninstalled, this can't be undone. You should use a Database Backup plugin of WordPress to back up all the data first.
        </p>
        <p style="color: red">
          <strong>The following Database Tables will be deleted:</strong>
        </p>
        <table class="widefat">
          <thead>
            <tr>
              <th>Database Tables</th>
            </tr>
          </thead>
          <tr>
            <td valign="top">
              <ol>
                  <li><?php echo $prefix; ?>wdsslider</li>
                  <li><?php echo $prefix; ?>wdsslide</li>
                  <li><?php echo $prefix; ?>wdslayer</li>              
              </ol>
            </td>
          </tr>
        </table>
        <p style="text-align: center;">
          Do you really want to uninstall Slider WD plugin?
        </p>
        <p style="text-align: center;">
          <input type="checkbox" name="Slider WD" id="check_yes" value="yes" />&nbsp;<label for="check_yes">Yes</label>
        </p>
        <p style="text-align: center;">
          <input type="submit" value="UNINSTALL" class="button-primary" onclick="if (check_yes.checked) { 
                                                                                    if (confirm('You are About to Uninstall Slider WD plugin from WordPress.\nThis Action Is Not Reversible.')) {
                                                                                        spider_set_input_value('task', 'uninstall');
                                                                                    } else {
                                                                                        return false;
                                                                                    }
                                                                                  }
                                                                                  else {
                                                                                    return false;
                                                                                  }" />
        </p>
      </div>
      <input id="task" name="task" type="hidden" value="" />
    </form>
  <?php
  }

  public function uninstall() {
    $flag = TRUE;
    global $wpdb;
    $this->model->delete_db_tables();
    $prefix = $wpdb->prefix;
    $deactivate_url = add_query_arg(array('action' => 'deactivate', 'plugin' => 'slider-wd/slider-wd.php'), admin_url('plugins.php'));
    $deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_slider-wd/slider-wd.php');
    ?>
    <div id="message" class="wd_updated fade">
      <p>The following Database Tables successfully deleted:</p>
      <p><?php echo $prefix; ?>wdsslider,</p>
      <p><?php echo $prefix; ?>wdsslide,</p>
      <p><?php echo $prefix; ?>wdslayer.</p>
    </div>
    <?php
    if (isset($_POST['bwg_delete_files'])) {
    ?>
    <div class="<?php echo ($flag) ? 'wd_updated' : 'wp_error'?>">
      <p><?php echo ($flag) ? 'The folder was successfully deleted.' : 'An error occurred when deleting the folder.'?></p>
    </div>
    <?php
    }
    ?>
    <div class="wrap">
      <h2>Uninstall Slider WD</h2>
      <p><strong><a href="<?php echo $deactivate_url; ?>">Click Here</a> To Finish the Uninstallation and Slider WD will be Deactivated Automatically.</strong></p>
      <input id="task" name="task" type="hidden" value="" />
    </div>
  <?php
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