<?php
/**
 *
 * CONTACT FORM BY CONTACTUS.COM
 *
 * Initialization Form Placement List View
 * @since 1.0 First time this was introduced into plugin.
 * @author ContactUs.com <support@contactus.com>
 * @copyright 2014 ContactUs.com Inc.
 * Company      : contactus.com
 * Updated  	: 20140127
 * */
?>

<?php

/*
 * DEEP LINKS
 */

$default_deep_link = $cUsCF_api->parse_deeplink ( $default_deep_link );

$createform = $default_deep_link.'?pageID=81&id=0&do=addnew&formType=';

?>
<div class="row">
    <div class="col-md-8"><h2>Form/Page Selection</h2></div>
    <div class="col-md-4">
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                + Create a New Form <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>contact_us" target="_blank">Add a Contact Form</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>newsletter" target="_blank">Add a Newsletter Form</a></li>
                <li><a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>donation" target="_blank">Add a Donation Form</a></li>
            </ul>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12"><p>All of your WordPress Pages are listed below. <strong>If you decide not to place the Call Out Tab + Form across your entire site</strong>, you can set one form for each specific page. Set them as Inline and/or as with a callout tab.</p><p>If you want to add more than one form per page, use <a href="http://help.contactus.com/hc/en-us/articles/200873578-Contact-Form-Shortcodes-Snippets-and-Widgets-Overview" target="_blank">shortcodes</a>.</p></div>
</div>

<!--   TABLE FORMS -->

<table class="table table-hover">
    <thead>
        <tr><th width="15%">Form</th><th width="30%"></th><th width="15%">INLINE</th><th width="15%">TAB</th><th width="25%">PAGE TITLE</th></tr>
    </thead>
    <tbody>

        <!-- FORM TR -->
        <tr>
            <?php
            $homeSettings    = get_option('cUsCF_HOME_settings');
            ?>
            <form name="page-form-home" id="page-form-home">
                <td>
                    <div class="row">
                        <div class="col-md-11">
                            <img src="" alt="" class="img-responsive tooltips form-thumb-home">
                        </div>
                    </div>
                </td>
                <td>
                    <strong>Choose Form</strong><br>
                    <select class="form-control form_key_byPage" name="form-key" data-pageid="home">
                        <?php
                        /*
                         * Render Forms Data
                         */

                        if ($cUsCF_API_getFormKeys) {

                            $cUs_json = json_decode($cUsCF_API_getFormKeys);
                            $formThum ='';

                            switch ($cUs_json->status) {
                                case 'success':
                                    $i = 1;
                                    foreach ($cUs_json->data as $oForms => $oForm) {
                                        if (cUsCF_allowedFormType($oForm->form_type)) {
                                            if(empty($homeSettings) && $i==1){
                                                $formThum = $oForm->template_desktop_form_thumbnail;
                                                $formName = $oForm->form_name;
                                                $formKey = $oForm->form_key;
                                            }elseif($homeSettings['form_key'] == $oForm->form_key){
                                                $formThum = $oForm->template_desktop_form_thumbnail;
                                                $formName = $oForm->form_name;
                                                $formKey = $oForm->form_key;
                                            }

                                            $i++;

                                            ?>
                                            <option value="<?php echo $oForm->form_key ?>" <?php echo(!empty($homeSettings) && $homeSettings['form_key'] == $oForm->form_key) ? 'selected' :'';?> ><?php echo $oForm->form_name ?></option>
                                        <?php }

                                    }
                                    break;
                            }

                        }
                        ?>
                    </select>
                    <script>
                        jQuery(document).ready(function($) {
                            $('.form-thumb-home').attr({src:'<?php echo $formThum; ?>', title:'Form Key: <?php echo $formKey; ?>'});
                        });
                    </script>
                </td>
                <td></td>
                <td>
                    <strong>Tab</strong><br><input type="checkbox" class="custom-home-checkbox" name="checkbox_tab_home" data-pageid="home" data-version="tab" <?php echo @( !empty($homeSettings) && $homeSettings['tab_user'] ) ? 'checked': ''; ?>></td>
                <td>
                    <strong>Home / Front Page</strong><br><div class="btn btn-warning"><a href="<?php echo get_option('home'); ?>" title="Preview Home page" target="_blank" class="setLabels"> <i class="icon-window"></i> View this page</a></div>
                </td>
                <input type="hidden" name="action" value="cUsCF_setPageSettingsHome">
            </form>
        </tr>
        <!-- FORM TR END -->

    <?php
        $mypages = get_pages(array('parent' => 0, 'sort_column' => 'post_date', 'sort_order' => 'desc'));

        if (is_array($mypages)) {
            foreach ($mypages as $page) {
                $pageSettings = get_post_meta($page->ID, 'cUsCF_FormByPage_settings', true);
                ?>

                <!-- FORM TR -->
                <tr class="form_placement_item">


                    <form name="page-form-<?php echo $page->ID; ?>" id="page-form-<?php echo $page->ID; ?>">
                        <td>
                            <?php //print_r($pageSettings);?>
                            <div class="row">
                                <div class="col-md-11">
                                    <img src="" alt="" class="img-responsive tooltips form-thumb-<?php echo $page->ID; ?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong>Choose Form</strong><br>
                            <select class="form-control form_key_byPage" name="form-key" data-pageid="<?php echo $page->ID; ?>">
                                <!-- option>Forms Dropdown</option -->

                                <?php
                                /*
                                 * Render Forms Data
                                 */

                                if ($cUsCF_API_getFormKeys) {

                                    $cUs_json = json_decode($cUsCF_API_getFormKeys);
                                    $formThum ='';
                                    $aryFormThumbs = array();
                                    switch ($cUs_json->status) {
                                        case 'success':
                                            $i = 1;
                                            foreach ($cUs_json->data as $oForms => $oForm) {
                                                if (cUsCF_allowedFormType($oForm->form_type)) {
                                                    if(empty($pageSettings) && $i==1){
                                                        $formThum = $oForm->template_desktop_form_thumbnail;
                                                        $formName = $oForm->form_name;
                                                        $formKey = $oForm->form_key;
                                                    }elseif($pageSettings['form_key'] == $oForm->form_key){
                                                        $formThum = $oForm->template_desktop_form_thumbnail;
                                                        $formName = $oForm->form_name;
                                                        $formKey = $oForm->form_key;
                                                    }

                                                    $aryFormThumbs[ $oForm->form_key ] = array(
                                                       'form_key' => $oForm->form_key,
                                                        'thumb'  => $oForm->template_desktop_form_thumbnail
                                                    );

                                                    $i++;

                                                    ?>
                                                    <option value='<?php echo $oForm->form_key; ?>' <?php echo(!empty($pageSettings) && $pageSettings['form_key'] == $oForm->form_key)?'selected':'';?> ><?php echo $oForm->form_name ?></option>
                                                <?php }

                                            }
                                            break;
                                    }
                                    update_option('cUsCF_settings_form_thumbs', $aryFormThumbs );//UPDATE FORM SETTINGS
                                }
                                ?>
                            </select>
                            <script>
                                jQuery(document).ready(function($) {
                                    $('.form-thumb-<?php echo $page->ID; ?>').attr({src:'<?php echo $formThum; ?>', title:'Form Key: <?php echo $formKey; ?>'});
                                });
                            </script>
                        </td>
                        <td><strong>Inline</strong><br><input type="checkbox" class="custom-page-checkbox" name="checkbox_inline_page_<?php echo $page->ID; ?>" data-pageid="<?php echo $page->ID; ?>" data-version="inline" <?php echo @( ( !empty($pageSettings) && $pageSettings['form_status_inline'] ) || ( $pageSettings['cus_version'] == 'inline' ) ) ? 'checked': ''; ?> ></td>
                        <td><strong>Tab</strong><br><input type="checkbox" class="custom-page-checkbox" name="checkbox_tab_page_<?php echo $page->ID; ?>" data-pageid="<?php echo $page->ID; ?>" data-version="tab" <?php echo @( ( !empty($pageSettings) && $pageSettings['form_status'] ) || ( $pageSettings['cus_version'] == 'tab' && $pageSettings['tab_user'] ) ) ? 'checked': ''; ?> ></td>
                        <td>
                            <strong><?php echo $page->post_title; ?></strong><br>
                            <div class="btn btn-warning"><a target="_blank" href="<?php echo get_permalink($page->ID); ?>" title="Preview <?php echo $page->post_title; ?> page" class="setLabels"> <i class="icon-window"></i> View this page</a></div>
                        </td>
                        <input type="hidden" name="action" value="cUsCF_setPageSettings">

                    </form>

                </tr>
                <!-- FORM TR END -->

            <?php } //endforeach; ?>
    <?php } //END WP PAGES LOOP?>

    </tbody>
</table>

<script>
    //PLUGIN cUsCF_myjq ENVIROMENT (cUsCF_myjq)
    var cUsCF_myjq = jQuery.noConflict();

    //ON READY DOM LOADED
    cUsCF_myjq(document).ready(function($) {

        $(".custom-page-checkbox").bootstrapSwitch({
            onColor: 'success',
            offColor: 'danger',
            size: 'small',
            onSwitchChange: function(event, state) {
                var status = (state) ? 1 : 0 ;
                var pageId = $(this).attr('data-pageid');
                var formVersion = $(this).attr('data-version');
                var oFormData = $('#page-form-' + pageId).serialize();
                oFormData += '&form_status=' + status;
                oFormData += '&tab_user=' + status;
                oFormData += '&form_version=' + formVersion;
                oFormData += '&cus_version=' + formVersion;
                oFormData += '&page_id=' + pageId;

                //AJAX POST CALL
                $.ajax({ type: "POST", dataType:'json', url: ajax_object.ajax_url, data: oFormData,
                    success: function(data) {

                        switch(data.status){

                            //USER CRATED SUCCESS
                            case 1:

                                var message = '<p><strong>Settings saved successfully....</strong></p>';

                                $('.save_message_placement').html(message).slideUp();
                                setTimeout(function(){
                                    $('.save_message_placement').slideToggle();
                                },3000);

                                break;
                        }


                    },
                    fail: function(){ //AJAX FAIL
                        message = '<p>Unfortunately there has being an error during the application. If the problem continues, contact us at support@contactus.com.</a></p>';
                    },
                    async: false
                });


            }
        });

        $(".custom-home-checkbox").bootstrapSwitch({
            onColor: 'success',
            offColor: 'danger',
            size: 'small',
            onSwitchChange: function(event, state) {
                var status = (state) ? 1 : 0 ;
                var pageId = $(this).attr('data-pageid');
                var formVersion = $(this).attr('data-version');
                var oFormData = $('#page-form-home').serialize();
                oFormData += '&form_status=' + status;
                oFormData += '&tab_user=' + status;
                oFormData += '&form_version=' + formVersion;
                oFormData += '&cus_version=' + formVersion;
                oFormData += '&page_id=' + pageId;

                console.log(oFormData);
                //break;

                //AJAX POST CALL
                $.ajax({ type: "POST", dataType:'json', url: ajax_object.ajax_url, data: oFormData,
                    success: function(data) {

                        switch(data.status){

                            //USER CRATED SUCCESS
                            case 1:

                                var message = '<p><strong>Settings saved successfully....</strong></p>';

                                $('.save_message_placement').html(message).slideUp();
                                setTimeout(function(){
                                    $('.save_message_placement').slideToggle();
                                },3000);

                                break;
                        }

                        $('.loadingMessage').fadeOut();


                    },
                    fail: function(){ //AJAX FAIL
                        message = '<p>Unfortunately there has being an error during the application. If the problem continues, contact us at support@contactus.com.</a></p>';
                    },
                    async: false
                });


            }
        });

        $('.form_key_byPage').change(function(){
            var pageId = $(this).attr('data-pageid');
            var formKey = $(this).val();
            var oFormData = 'action=cUsCF_setFormKeyByPage';
            var formThumb = $(this).attr('data-thumb');
            oFormData += '&pageID=' + pageId;
            oFormData += '&form_key=' + formKey;

            //AJAX POST CALL
            $.ajax({ type: "POST", dataType:'json', url: ajax_object.ajax_url, data: oFormData,
                success: function(data) {

                    switch(data.status){

                        //USER CRATED SUCCESS
                        case 1:

                            //console.log(formThumb);
                            $('.form-thumb-' + pageId).attr({src:''+ data.thumb + ''});
                            var message = '<p><strong>Form Key changed. Settings saved successfully...</strong></p>';

                            $('.save_message_placement').html(message).slideUp();
                            setTimeout(function(){
                                $('.save_message_placement').slideToggle();
                            },3000);

                            break;
                    }

                    $('.loadingMessage').fadeOut();


                },
                fail: function(){ //AJAX FAIL
                    message = '<p>Unfortunately there has being an error during the application. If the problem continues, contact us at support@contactus.com.</a></p>';
                },
                async: false
            });

        });

    });//ready
</script>