<?php

//GRANT ADMIN OPTIONS 
//if ( current_user_can( 'manage_options' ) ) {

// loginAlreadyUser handler function...
/*
 * Method in charge to login user via ajax post request vars
 * @since 1.0
 * @return array jSon encoded array
 */
add_action('wp_ajax_cUsCF_loginAlreadyUser', 'cUsCF_loginAlreadyUser_callback');
function cUsCF_loginAlreadyUser_callback() {
    
    $cUsCF_api = new cUsComAPI_CF();
    $cUs_email = filter_input(INPUT_POST, 'username',FILTER_SANITIZE_STRING);
    $cUs_pass = filter_input(INPUT_POST, 'password',FILTER_SANITIZE_STRING);
    
    //API CALL TO getAPICredentials
    $cUsCF_API_credentials = $cUsCF_api->getAPICredentials($cUs_email, $cUs_pass); //api hook;

    //print_r($cUsCF_API_credentials);
    
    if($cUsCF_API_credentials){
        $cUs_json = json_decode($cUsCF_API_credentials);
        
        //SWITCH API STATUS RESPONSE
        switch ( $cUs_json->status  ) {
            case 'success':
                
                $cUs_API_Account    = $cUs_json->api_account;
                $cUs_API_Key        = $cUs_json->api_key;
                
                if(strlen(trim($cUs_API_Account)) && strlen(trim($cUs_API_Key))){
                    
                    $aryUserCredentials = array(
                        'API_Account' => $cUs_API_Account,
                        'API_Key'     => $cUs_API_Key
                    );
                    
                    
                    $cUsCF_API_getKeysResult = $cUsCF_api->getFormKeysData($cUs_API_Account, $cUs_API_Key); //api hook;

                    //print_r($cUsCF_API_getKeysResult);
                    
                    //$old_options = get_option('contactus_settings'); //GET THE OLD OPTIONS
                    
                    $cUs_jsonKeys = json_decode($cUsCF_API_getKeysResult);
                
                    if($cUs_jsonKeys->status == 'success' ){
                        
                        $postData = array( 'email' => $cUs_email);
                        update_option('cUsCF_settings_userData', $postData);
                        
                        $cUsCF_deeplinkview = $cUsCF_api->get_deeplink( $cUs_jsonKeys->data );
                        
                        // get a default deeplink
                        update_option('cUsCF_settings_default_deep_link_view', $cUsCF_deeplinkview ); // DEFAULT FORM KEYS
                        
                        foreach ($cUs_jsonKeys->data as $oForms => $oForm) {
                            if ($oForm->default == 1 && cUsCF_allowedFormType($oForm->form_type)){ //GET DEFAULT FORM KEY
                               $defaultFormKey = $oForm->form_key;
                               $deeplinkview   = $oForm->deep_link_view;
                               $defaultFormId  = $oForm->form_id;
                               break;
                            }
                        } 
                            
                        if(!strlen($defaultFormKey)){
                                //echo 2; //NO ONE CONTACT FORM 
                                
                                $aryResponse = array(
                                    'status' => 2,
                                    'cUs_API_Account' 	=> $cUs_API_Account,
                                    'cUs_API_Key' 	=> $cUs_API_Key,
                                    'deep_link_view'	=> $cUsCF_deeplinkview
                                );
                                
                               
                        }else{
                            
                            $aryFormOptions = array('tab_user' => 1,'cus_version' => 'tab'); //DEFAULT SETTINGS / FIRST TIME
                            
                            update_option('cUsCF_settings_FORM', $aryFormOptions );//UPDATE FORM SETTINGS
                            update_option('cUsCF_settings_form_key', $defaultFormKey);//DEFAULT FORM KEYS
                            update_option('cUsCF_settings_form_keys', $cUs_jsonKeys); // ALL FORM KEYS
                            update_option('cUsCF_settings_form_id', $defaultFormId); // DEFAULT FORM KEYS
                            update_option('cUsCF_settings_default_deep_link_view', $deeplinkview); // DEFAULT FORM KEYS
                            update_option('cUsCF_settings_userCredentials', $aryUserCredentials);
                            delete_option('cUsCF_settings_userData');
                            
                            $aryResponse = array('status' => 1);
                            
                        }

                            //echo 1;
                        
                    }else{
                        //{"status":"error","error":"No valid form keys"}
                        $aryResponse = array('status' => 3, 'message' => $cUs_jsonKeys->error);
                    } 
                    
                }else{
                    $aryResponse = array('status' => 3, 'message' => $cUs_json->error);
                }
                
                break;

            case 'error':
                $aryResponse = array('status' => 3, 'message' => $cUs_json->error);
                break;
        }
    }
    
    echo json_encode($aryResponse);
    
    die();
}


// cUsCF_verifyCustomerEmail handler function...
/*
 * Method in charge to verify if the email exist via ajax post request vars
 * @since 2.0
 * @return string Value status to switch
 */
add_action('wp_ajax_cUsCF_verifyCustomerEmail', 'cUsCF_verifyCustomerEmail_callback');
function cUsCF_verifyCustomerEmail_callback() {
    
    if ( !strlen(filter_input(INPUT_POST, 'email',FILTER_VALIDATE_EMAIL)) ){      echo 'Missing/Invalid Email, is required field';   die();
    }else{
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API

        $cUsCF_API_EmailResult = $cUsCF_api->verifyCustomerEmail(filter_input(INPUT_POST, 'email')); //EMAIL VERIFICATION
        if($cUsCF_API_EmailResult) {
            $cUsCF_jsonEmail = json_decode($cUsCF_API_EmailResult);
            
            switch ($cUsCF_jsonEmail->result){
                case 0 :
                    echo 'true';
                    break;
                case 1 :
                    echo 'false';
                    break;
            }
            
        }else{
            echo 'Unfortunately there has being an error during the application, please try again';
            exit();
        }
         
    }

    //echo json_encode($aryResponse);

    die();
}


// cUsCF_createCustomer handler function...
/*
 * Method in charge to create a contactus.com user via ajax post request vars
 * @since 2.0
 * @return string Value status to switch
 */
add_action('wp_ajax_cUsCF_createCustomer', 'cUsCF_createCustomer_callback');
function cUsCF_createCustomer_callback() {
    
    $cUsCF_userData = get_option('cUsCF_settings_userData'); //get the saved user data
    
    if      (  !strlen( filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING) ) ){      echo 'Missing First Name, is required field';      die();
    }elseif  ( !strlen( filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING) ) ){      echo 'Missing Last Name, is required field';       die();
    }elseif  ( !strlen( filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ) ){      echo 'Missing/Invalid Email, is required field';   die();
    }elseif  ( !strlen( filter_input(INPUT_POST, 'website', FILTER_SANITIZE_STRING) ) ){    echo 'Missing Website, is required field';         die();
    }else{
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API
        
        $postData = array(
            'fname' => filter_input(INPUT_POST, 'first_name'),
            'lname' => filter_input(INPUT_POST, 'last_name'),
            'email' => filter_input(INPUT_POST, 'email'),
            'website' => filter_input(INPUT_POST, 'website'),
            'phone' => preg_replace('/[^0-9]+/i', '', filter_input(INPUT_POST, 'phone')),
            'Template_Desktop_Form' => 'genericTemplate3',
            'Template_Desktop_Tab' => 'genericTab1',
            'Main_Category' => filter_input(INPUT_POST, 'CU_category',FILTER_SANITIZE_STRING),
            'Sub_Category' => filter_input(INPUT_POST, 'CU_subcategory',FILTER_SANITIZE_STRING),
            'Goals' => filter_input(INPUT_POST, 'CU_goals',FILTER_SANITIZE_STRING)
        );
        
        $cUsCF_API_result = $cUsCF_api->createCustomer($postData, filter_input(INPUT_POST, 'password'));
        if($cUsCF_API_result) {

            $cUs_json = json_decode($cUsCF_API_result);

            switch ( $cUs_json->status  ) {

                case 'success':
                    
                    echo 1;//GREAT
                    update_option('cUsCF_settings_form_key', $cUs_json->form_key ); //finally get form key form contactus.com // SESSION IN
                    $aryFormOptions = array( //DEFAULT SETTINGS / FIRST TIME
                        'tab_user'          => 1,
                        'cus_version'       => 'tab'
                    ); 
                    update_option('cUsCF_settings_FORM', $aryFormOptions );//UPDATE FORM SETTINGS
                    update_option('cUsCF_settings_userData', $postData);
                    
                    $cUs_API_Account    = $cUs_json->api_account;
                    $cUs_API_Key        = $cUs_json->api_key;
                    
                    $aryUserCredentials = array(
                        'API_Account' => $cUs_API_Account,
                        'API_Key'     => $cUs_API_Key
                    );
                    update_option('cUsCF_settings_userCredentials', $aryUserCredentials);
                    
                    // ********************************
                    // get here the default deeplink after creating customer
                    $cUsCF_API_getKeysResult = $cUsCF_api->getFormKeysData($cUs_API_Account, $cUs_API_Key); //api hook;
                    
                    $cUs_jsonKeys = json_decode( $cUsCF_API_getKeysResult );
                    $cUsCF_deeplinkview = $cUsCF_api->get_deeplink( $cUs_jsonKeys->data );
                    // get the default contact form deeplink
                    if( strlen( $cUsCF_deeplinkview ) ){
                        update_option('cUsCF_settings_default_deep_link_view', $cUsCF_deeplinkview ); // DEFAULT FORM KEYS
                    }
                    // save the form id for this donation new user
                    update_option( 'cUsCF_settings_form_id', $cUs_jsonKeys->data[0]->form_id );

                break;

                case 'error':

                    if($cUs_json->error == 'Email exists'){
                        echo 2;//ALREDY CUS USER
                        //$cUsCF_api->resetData(); //RESET DATA
                    }else{
                        //ANY ERROR
                        echo $cUs_json->error;
                        //$cUsCF_api->resetData(); //RESET DATA
                    }
                    
                break;


            }
            
        }else{
             //echo 3;//API ERROR
             echo $cUsCF_API_result;
             // $cUsCF_api->resetData(); //RESET DATA
        }
        
         
    }
    
    die();
}


// LoadDefaultKey handler function...
/*
 * Method in charge to set default form key by user via ajax post request vars
 * @since 2.0
 * @return array jSon encoded array
 */
add_action('wp_ajax_cUsCF_LoadDefaultKey', 'cUsCF_LoadDefaultKey_callback');
function cUsCF_LoadDefaultKey_callback() {
    
    $cUsCF_api = new cUsComAPI_CF();
    $cUsCF_userData = get_option('cUsCF_settings_userData'); //get the saved user data
    $cUs_email = $cUsCF_userData['email'];
    $cUs_pass = $cUsCF_userData['credential'];
    
    $cUsCF_API_result = $cUsCF_api->getFormKeysData($cUs_email, $cUs_pass); //api hook;
    if($cUsCF_API_result){
        $cUs_json = json_decode($cUsCF_API_result);

        switch ( $cUs_json->status  ) {
            case 'success':
                
                foreach ($cUs_json->data as $oForms => $oForm) {
                    if ($oForms !='status' && $oForm->form_type == 0 && $oForm->default == 1){//GET DEFAULT CONTACT FORM KEY
                       $defaultFormKey = $oForm->form_key;
                    }
                }
                
                update_option('cUsCF_settings_form_key', $defaultFormKey);
                
                echo 1;
                break;

            case 'error':
                echo $cUs_json->error;
                //$cUsCF_api->resetData(); //RESET DATA
                break;
        }
    }
    
    die();
}

// cUsCF_setDefaulFormKey handler function...
/*
 * Method in charge to set default form key in all WP environment via ajax post request vars
 * @since 4.0.1
 * @return atring Status value array
 */
add_action('wp_ajax_cUsCF_setDefaulFormKey', 'cUsCF_setDefaulFormKey_callback');
function cUsCF_setDefaulFormKey_callback() {
    
    if(isset($_REQUEST['formKey'])){
       update_option('cUsCF_settings_form_key', filter_input(INPUT_POST, 'formKey',FILTER_SANITIZE_STRING));
       echo 1;//GREAT
    }
    
    die();
}


/*
 * Method in charge to set default form key in all WP environment via ajax post request vars
 * @since 4.0.1
 * @return atring Status value array
 */
add_action('wp_ajax_cUsCF_setDefaulFormKeyByID', 'cUsCF_setDefaulFormKeyByID_callback');
function cUsCF_setDefaulFormKeyByID_callback() {

    if(isset($_POST['form_id'])){
       unset($_POST['action']);
       update_option('cUsCF_settings_form_'.$_POST['form_id'], $_POST);
        $aryResponse = array('status' => 1);
    }

    echo json_encode($aryResponse);

    die();
}

/*
 * Method in charge to set default form key in all WP environment via ajax post request vars
 * @since 4.0.1
 * @return atring Status value array
 */
add_action('wp_ajax_cUsCF_setPageSettings', 'cUsCF_setPageSettings_callback');
function cUsCF_setPageSettings_callback() {

    if(isset($_POST['page_id'])){
        unset($_POST['action']);

        $pageID = $_POST['page_id'];
        $pageSettings = get_post_meta( $pageID, 'cUsCF_FormByPage_settings', true );

        $pageSettings['form_key'] = filter_input(INPUT_POST, 'form-key',FILTER_SANITIZE_STRING);
        

        if($_REQUEST['cus_version'] == 'tab'){
            $pageSettings['tab_user'] = filter_input(INPUT_POST, 'form_status',FILTER_SANITIZE_STRING);
            $pageSettings['form_status'] = filter_input(INPUT_POST, 'form_status',FILTER_SANITIZE_STRING);
            $pageSettings['cus_version'] = filter_input(INPUT_POST, 'cus_version',FILTER_SANITIZE_STRING);
        }

        if($_REQUEST['cus_version'] == 'inline'){

            $pageSettings['form_status_inline'] = filter_input(INPUT_POST, 'form_status',FILTER_SANITIZE_STRING);
            $pageSettings['cus_version_inline'] = filter_input(INPUT_POST, 'form_version',FILTER_SANITIZE_STRING);

            cUsCF_inline_shortcode_cleaner_by_ID($pageID); //RESET SC
            if(filter_input(INPUT_POST, 'form_status',FILTER_SANITIZE_STRING)){
                cUsCF_inline_shortcode_add($pageID); //ADDING SHORTCODE FOR INLINE PAGES
            }

        }

        update_post_meta($pageID, 'cUsCF_FormByPage_settings', $pageSettings);//SAVE DATA ON POST TYPE PAGE METAS
        $aryResponse = array('status' => 1);
    }

    echo json_encode($aryResponse);

    die();
}

/*
 * Method in charge to set default form key in all WP environment via ajax post request vars
 * @since 4.0.1
 * @return atring Status value array
 */
add_action('wp_ajax_cUsCF_setFormKeyByPage', 'cUsCF_setFormKeyByPage_callback');
function cUsCF_setFormKeyByPage_callback() {

    if(isset($_POST['pageID'])){
        unset($_POST['action']);

        $pageID = $_POST['pageID'];

        //print_r($_POST);
        //exit;



        if(!empty( $_REQUEST['form_key'] )){
            
            $FORMKEY = filter_input(INPUT_POST, 'form_key',FILTER_SANITIZE_STRING);

            if($pageID == 'home'){
                $getHomePage    = get_option('cUsCF_HOME_settings');
                $getHomePage['form_key'] = $FORMKEY;
                update_option('cUsCF_HOME_settings', $getHomePage);
            }else{
                $pageSettings = get_post_meta( $pageID, 'cUsCF_FormByPage_settings', true );
                $pageSettings['form_key'] = $FORMKEY;
                update_post_meta($pageID, 'cUsCF_FormByPage_settings', $pageSettings);//SAVE DATA ON POST TYPE PAGE METAS
            }

            $aryThumbs = get_option('cUsCF_settings_form_thumbs');
            $aryThumbs = $aryThumbs[ $FORMKEY ];
            $thumb = $aryThumbs['thumb'];
        }

        $aryResponse = array('status' => 1, 'thumb' => $thumb);
    }

    echo json_encode($aryResponse);

    die();
}

/*
 * Method in charge to set default form key in all WP environment via ajax post request vars
 * @since 4.0.1
 * @return atring Status value array
 */
add_action('wp_ajax_cUsCF_setPageSettingsHome', 'cUsCF_setPageSettingsHome_callback');
function cUsCF_setPageSettingsHome_callback() {

    if(isset($_POST['page_id'])){
        unset($_POST['action']);

        $pageID = filter_input(INPUT_POST, 'page_id',FILTER_SANITIZE_STRING);
        $getHomePage    = get_option('cUsCF_HOME_settings');
        $thumb = '';

        if(!empty($getHomePage)){
           update_option('cUsCF_HOME_settings', $getHomePage);
           $getHomePage    = get_option('cUsCF_HOME_settings');
        }else{
            $getHomePage = array();
        }

        if(!empty( $_REQUEST['form-key'] )){
            $getHomePage['form_key'] = filter_input(INPUT_POST, 'form-key',FILTER_SANITIZE_STRING);
            $getHomePage['tab_user'] = filter_input(INPUT_POST, 'tab_user',FILTER_SANITIZE_STRING);
            $getHomePage['cus_version'] = filter_input(INPUT_POST, 'cus_version',FILTER_SANITIZE_STRING);

            update_option('cUsCF_HOME_settings', $getHomePage);

            $aryThumbs = get_option('cUsCF_settings_form_thumbs');
            $aryThumbs = $aryThumbs[ filter_input(INPUT_POST, 'form-key', FILTER_SANITIZE_STRING) ];
            $thumb = $aryThumbs['thumb'];
        }

        $aryResponse = array('status' => 1, 'thumb' => $thumb);
    }

    echo json_encode($aryResponse);

    die();
}

// cUsCF_createCustomer handler function...
/*
 * Method in charge to update user form templates via ajax post request vars
 * @since 2.0
 * @return string Value status to switch
 */
add_action('wp_ajax_cUsCF_UpdateTemplates', 'cUsCF_UpdateTemplates_callback');
function cUsCF_UpdateTemplates_callback() {
    
    $cUsCF_userData = get_option('cUsCF_settings_userData'); //get the saved user data
    
    if      ( !strlen($cUsCF_userData['email']) ){      echo 'Missing/Invalid Email, is required field';   die();
    }elseif  ( !strlen($_REQUEST['Template_Desktop_Form']) ){    echo 'Missing Form Template';         die();
    }elseif  ( !strlen($_REQUEST['Template_Desktop_Tab']) ){    echo 'Missing Tab Template';         die();
    }else{
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API
        $form_key       = get_option('cUsCF_settings_form_key');
        $postData = array(
            'email' => $cUsCF_userData['email'],
            'credential' => $cUsCF_userData['credential'],
            'Template_Desktop_Form' => filter_input(INPUT_POST, 'Template_Desktop_Form',FILTER_SANITIZE_STRING),
            'Template_Desktop_Tab' => filter_input(INPUT_POST, 'Template_Desktop_Tab',FILTER_SANITIZE_STRING)
        );
        
        $cUsCF_API_result = $cUsCF_api->updateFormSettings($postData, $form_key);
        if($cUsCF_API_result) {

            $cUs_json = json_decode($cUsCF_API_result);

            switch ( $cUs_json->status  ) {

                case 'success':
                    echo 1;//GREAT

                break;

                case 'error':
                    //ANY ERROR
                    echo $cUs_json->error;
                    //$cUsCF_api->resetData(); //RESET DATA
                break;


            }
            
        }else{
             //echo 3;//API ERROR
             echo $cUs_json->error;
             // $cUsCF_api->resetData(); //RESET DATA
        }
         
    }
    
    die();
}

/*
 * Method in charge to chage user form templates via ajax post request vars
 * @since 2.0
 * @return string Value status to switch
 */
add_action('wp_ajax_cUsCF_changeFormTemplate', 'cUsCF_changeFormTemplate_callback');
function cUsCF_changeFormTemplate_callback() {
    
    $cUsCF_userData = get_option('cUsCF_settings_userCredentials'); //get the saved user data
   
    if      ( !strlen($cUsCF_userData['API_Account']) ){     echo 'Missing API Account';   die();
    }elseif  ( !strlen($cUsCF_userData['API_Key']) ){         echo 'Missing Form Key';         die();
    }elseif  ( !strlen($_REQUEST['Template_Desktop_Form']) ){    echo 'Missing Form Template';         die();
    }elseif  ( !strlen($_REQUEST['form_key']) ){    echo 'Missing Form Key';         die();
    }else{
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API
        $form_key = filter_input(INPUT_POST, 'form_key',FILTER_SANITIZE_STRING);
        
        $postData = array(
            'API_Account'       => $cUsCF_userData['API_Account'],
            'API_Key'           => $cUsCF_userData['API_Key'],
            'Template_Desktop_Form' => filter_input(INPUT_POST, 'Template_Desktop_Form',FILTER_SANITIZE_STRING)
        );
        
        $cUsCF_API_result = $cUsCF_api->updateFormSettings($postData, $form_key);
        if($cUsCF_API_result) {

            $cUs_json = json_decode($cUsCF_API_result);

            switch ( $cUs_json->status  ) {

                case 'success':
                    echo 1;//GREAT

                break;

                case 'error':
                    //ANY ERROR
                    echo $cUs_json->error;
                    //$cUsCF_api->resetData(); //RESET DATA
                break;


            } 
        }else{
             //echo 3;//API ERROR
             echo $cUs_json->error;
             // $cUsCF_api->resetData(); //RESET DATA
        } 
        
         
    } 
    
    die();
}

/*
 * Method in charge to update user tab templates via ajax post request vars
 * @since 2.0
 * @return string Value status to switch
 */
add_action('wp_ajax_cUsCF_changeTabTemplate', 'cUsCF_changeTabTemplate_callback');
function cUsCF_changeTabTemplate_callback() {
    
    $cUsCF_userData = get_option('cUsCF_settings_userCredentials'); //get the saved user data
   
    if       ( !strlen($cUsCF_userData['API_Account']) ){       echo 'Missing API Account';   die();
    }elseif  ( !strlen($cUsCF_userData['API_Key']) ){           echo 'Missing Form Key';      die();
    }elseif  ( !strlen($_REQUEST['Template_Desktop_Tab']) ){    echo 'Missing Tab Template';  die();
    }elseif  ( !strlen($_REQUEST['form_key']) ){                echo 'Missing Form Key';      die();
    }else{
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API
        $form_key = filter_input(INPUT_POST, 'form_key',FILTER_SANITIZE_STRING);
        
        $postData = array(
            'API_Account'       => $cUsCF_userData['API_Account'],
            'API_Key'           => $cUsCF_userData['API_Key'],
            'Template_Desktop_Tab' => filter_input(INPUT_POST, 'Template_Desktop_Tab',FILTER_SANITIZE_STRING)
        );
        
        $cUsCF_API_result = $cUsCF_api->updateFormSettings($postData, $form_key);
        if($cUsCF_API_result) {

            $cUs_json = json_decode($cUsCF_API_result);

            switch ( $cUs_json->status  ) {

                case 'success':
                    echo 1;//GREAT

                break;

                case 'error':
                    //ANY ERROR
                    echo $cUs_json->error;
                    //$cUsCF_api->resetData(); //RESET DATA
                break;


            } 
        }else{
             //echo 3;//API ERROR
             echo $cUs_json->error;
             // $cUsCF_api->resetData(); //RESET DATA
        } 
        
         
    }
    
    die();
}



// save custom selected pages handler function...
/*
 * Method in charge to save form settings via ajax post request vars
 * @since 2.0
 * @return string Value status to switch
 */
add_action('wp_ajax_cUsCF_saveCustomSettings', 'cUsCF_saveCustomSettings_callback');
function cUsCF_saveCustomSettings_callback() {
    
    $aryFormOptions = array( //DEFAULT SETTINGS / FIRST TIME
        'tab_user'          => filter_input(INPUT_POST, 'tab_user',FILTER_SANITIZE_STRING),
        'cus_version'       => filter_input(INPUT_POST, 'cus_version',FILTER_SANITIZE_STRING)
    ); 
    update_option('cUsCF_settings_FORM', $aryFormOptions );//UPDATE FORM SETTINGS
    
    cUsCF_page_settings_cleaner();
    
    delete_option( 'cUsCF_settings_inlinepages' );
    delete_option( 'cUsCF_settings_tabpages' );
   
    
    die();
}

// save custom selected pages handler function...
/*
 * Method in charge to remove page settings via ajax post request vars
 * @since 2.0
 * @return string Value status to switch
 */
add_action('wp_ajax_cUsCF_deletePageSettings', 'cUsCF_deletePageSettings_callback');
function cUsCF_deletePageSettings_callback() {
    
    $pageID = filter_input(INPUT_POST, 'pageID',FILTER_SANITIZE_STRING);
    
    delete_post_meta($pageID, 'cUsCF_FormByPage_settings');//reset values
    cUsCF_inline_shortcode_cleaner_by_ID($pageID); //RESET SC
    
    $aryTabPages = get_option('cUsCF_settings_tabpages');
    $aryTabPages = cUsCF_removePage($pageID,$aryTabPages);
    update_option( 'cUsCF_settings_tabpages', $aryTabPages); //UPDATE OPTIONS
            
    $aryInlinePages = get_option('cUsCF_settings_inlinepages');
    $aryInlinePages = cUsCF_removePage($pageID,$aryInlinePages);
    update_option( 'cUsCF_settings_inlinepages', $aryInlinePages); //UPDATE OPTIONS
    
    die();
}

// save custom selected pages handler function...
/*
 * Method in charge to update user page settings from page selection via ajax post request vars
 * @since 2.0
 * @return string Value status to switch
 */
add_action('wp_ajax_cUsCF_changePageSettings', 'cUsCF_changePageSettings_callback');
function cUsCF_changePageSettings_callback() {
    
    $pageID = filter_input(INPUT_POST, 'pageID',FILTER_SANITIZE_STRING);
    delete_post_meta($pageID, 'cUsCF_FormByPage_settings');//reset values
    cUsCF_inline_shortcode_cleaner_by_ID($pageID); //RESET SC
    $aryTabPages = get_option('cUsCF_settings_tabpages');
    $aryInlinePages = get_option('cUsCF_settings_inlinepages');
    
    switch ($_REQUEST['cus_version']){
        case 'tab':
            
            $tabUser = 1;
            
            $aryTabPages[] = $pageID;
            $aryTabPages = array_unique($aryTabPages);
            update_option('cUsCF_settings_tabpages', $aryTabPages); //UPDATE OPTIONS
            
            if(!empty($aryInlinePages)){
                $aryInlinePages = cUsCF_removePage($pageID,$aryInlinePages);
                update_option( 'cUsCF_settings_inlinepages', $aryInlinePages); //UPDATE OPTIONS
            }
            
            echo 1;
            
            break;
        case 'inline':
            
            $tabUser = 0;
            
            $aryInlinePages[] = $pageID;
            $aryInlinePages = array_unique($aryInlinePages);
            update_option( 'cUsCF_settings_inlinepages', $aryInlinePages); //UPDATE OPTIONS
            
            if(!empty($aryTabPages)){
                $aryTabPages = cUsCF_removePage($pageID,$aryTabPages);
                update_option( 'cUsCF_settings_tabpages', $aryTabPages); //UPDATE OPTIONS
            }
            
            cUsCF_inline_shortcode_add($pageID); //ADDING SHORTCODE FOR INLINE PAGES
            
            echo 1;
            
            break;
    } 
    
    $aryFormOptions = array( //DEFAULT SETTINGS / FIRST TIME
        'tab_user'          => $tabUser,
        'form_key'          => filter_input(INPUT_POST, 'form_key',FILTER_SANITIZE_STRING),   
        'cus_version'       => filter_input(INPUT_POST, 'cus_version',FILTER_SANITIZE_STRING)
    );
    
    if($pageID != 'home'){
        update_post_meta($pageID, 'cUsCF_FormByPage_settings', $aryFormOptions);//SAVE DATA ON POST TYPE PAGE METAS
    }else{
       update_option('cUsCF_HOME_settings', $aryFormOptions );//UPDATE FORM SETTINGS
    }
    
    die();
}

/*
 * Method in charge to remove user guide
 * @since 2.0
 * @return string Value status to switch
 */
add_action('wp_ajax_cUsCF_disable_introjs', 'cUsCF_disable_introjs_callback');
function cUsCF_disable_introjs_callback() {
    update_option( 'cUsCF_settings_intro_hints', 0); //UPDATE OPTIONS

    die();
}

/*
 * Method in charge to remove page settings via ajax post request vars
 * @since 2.0
 * @return string Value status to switch
 */
function cUsCF_removePage($valueToSearch, $arrayToSearch){
    $key = array_search($valueToSearch,$arrayToSearch);
    if($key!==false){
        unset($arrayToSearch[$key]);
    }
    return $arrayToSearch;
}

// logoutUser handler function...
/*
 * Method in charge to remove wp options saved with this plugin via ajax post request vars
 * @since 1.0
 * @return string Value status to switch
 */
add_action('wp_ajax_cUsCF_logoutUser', 'cUsCF_logoutUser_callback');
function cUsCF_logoutUser_callback() {
    
    $cUsCF_api = new cUsComAPI_CF();
    $cUsCF_api->resetData(); //RESET DATA
    
    delete_option( 'cUsCF_settings_api_key' );
    delete_option( 'cUsCF_settings_form_key' );
    delete_option( 'cUsCF_settings_list_Name' );
    delete_option( 'cUsCF_settings_list_ID' );
    
    echo 'Deleted.... User data'; //none list
    
    die();
}

/*
 * Method in charge to switch cus backlink
 * @since 4.0.1
 * @return atring Status value array
 */
add_action('wp_ajax_cUsCF_switch_backlink', 'cUsCF_switch_backlink_callback');
function cUsCF_switch_backlink_callback() {

    if(isset($_REQUEST['link_status'])){
        update_option('cUsCF_switch_backlink', filter_input(INPUT_POST, 'link_status',FILTER_SANITIZE_STRING));
        $aryResponse = array('status' => 1, 'message' => 'Success!!');
    }

    echo json_encode($aryResponse);

    die();
    
    }
//}