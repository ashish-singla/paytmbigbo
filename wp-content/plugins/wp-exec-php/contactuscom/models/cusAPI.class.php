<?php

/**
 * Initialization Class for ContactUs.com API (suffix according to plugin _CF)
 * @api
 * @version 0.4
 * @since 2.0 First time this was introduced into Contact Form plugin.
 * @see http://admin.contactus.com/spec/ ContactUs.com API Documentation.
 * @author ContactUs.com <support@contactus.com>
 * @copyright 2013 ContactUs.com Inc.
 * Company      : contactus.com
 * Updated  	: 20131217
 * */

class cUsComAPI_CF {

    var $v = '0.4';

    /**
     * ContactUs.com API URL
     * @api
     */
    var $enviroment = cUsCF_API_ENV;
    var $wpApiAccountKey = cUsCF_API_ACC;
    var $wpApiKey = cUsCF_API_AKY;

    public function _construct() {
        //silence is golden
        $apiVersion = $this->v;
        return TRUE;
    }

    /*
     * Method in charge to retrive ContactUs.com user's Credentials via Client URL Library
     * @param string $cUs_email String requested via Login Form 
     * @param string $cUs_pass String requested via Login Form 
     * @since 3.0
     * @return string jSon String by default
     */

    public function getAPICredentials($cUs_email, $cUs_pass) {

        if( $this->_isCurl() ){

            $strCURLOPT =  $this->enviroment;
            $strCURLOPT .= '?API_Account='.$this->wpApiAccountKey;
            $strCURLOPT .= '&API_Key='.$this->wpApiKey;
            $strCURLOPT .= '&API_Action=getAPICredentials';
            $strCURLOPT .= '&Email=' . urlencode(trim($cUs_email));
            $strCURLOPT .= '&Password=' . urlencode(trim($cUs_pass));

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ContactUs-Request-URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 'X-ContactUs-Signature: wp|5.0.8|' . $this->getIP()));

            $content = curl_exec($ch);
            curl_close($ch);

        }else{
            $content = '{"status":"error","error":"cURL is NOT installed on this server."}';
        }

        return $content;
    }

    /*
     * Method in charge to create a new ContactUs.com user's via Client URL Library
     * @param array $postData Array with all customer data 
     * @param string $credential String requested via Login Form 
     * @since 2.0
     * @return string jSon String by default
     */

    public function createCustomer($postData, $credential) {

        if (!strlen($postData['fname'])) {
            echo ' "Missing First Name, is required field" ';
        } elseif (!strlen($postData['lname'])) {
            echo ' "Missing Last Name, is required field" ';
        } elseif (!strlen($postData['email'])) {
            echo ' "Missing Email, is required field" ';
        } elseif (!strlen($postData['website'])) {
            echo ' "Missing Website, is required field" ';
        } elseif (!filter_var($postData['website'], FILTER_VALIDATE_URL)) {
            echo ' "Invalid Website" ';
        } else {

            if( $this->_isCurl() ){

                $ch = curl_init();

                $strCURLOPT = $this->enviroment;
                $strCURLOPT .= '?API_Account='.$this->wpApiAccountKey;
                $strCURLOPT .= '&API_Key='.$this->wpApiKey;
                $strCURLOPT .= '&API_Action=createSignupCustomer';
                $strCURLOPT .= '&First_Name=' . urlencode(trim($postData['fname']));
                $strCURLOPT .= '&Last_Name=' . urlencode(trim($postData['lname']));
                $strCURLOPT .= '&Email=' . urlencode(trim($postData['email']));
                $strCURLOPT .= '&Phone=' . urlencode(trim($postData['phone']));
                $strCURLOPT .= '&Password=' . urlencode(trim($credential));
                $strCURLOPT .= '&Website=' . esc_url(trim($postData['website']));

                //check each one if exist to avoid error
                if (strlen(trim($postData['Main_Category'])) > 2) {
                    $strCURLOPT .= '&Main_Category=' . urlencode(trim($postData['Main_Category']));
                }

                if (strlen(trim($postData['Sub_Category'])) > 2) {
                    $strCURLOPT .= '&Sub_Category=' . urlencode(trim($postData['Sub_Category']));
                }

                if (strlen($postData['Goals']) > 2) {
                    $g = explode(',', $postData['Goals']);

                    // delete last element of array 
                    array_pop($g);

                    if (is_array($g)) {
                        foreach ($g as $goal) {
                            $strCURLOPT .= '&Goals[]=' . urlencode(trim($goal));
                        }
                    }
                }

                $strCURLOPT .= '&IP_Address=' . $this->getIP();
                $strCURLOPT .= '&Template_Desktop_Form=' . trim($postData['Template_Desktop_Form']);
                $strCURLOPT .= '&Template_Desktop_Tab=' . trim($postData['Template_Desktop_Tab']);
                $strCURLOPT .= '&Auto_Activate=1';
                $strCURLOPT .= '&API_Credentials=1';
                $strCURLOPT .= '&Promotion_Code=WP';
                $strCURLOPT .= '&Version=wp|5.0.8';

                curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'X-ContactUs-Request-URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
                    'X-ContactUs-Signature: wp|5.0.8|' . $this->getIP()
                ));

                $content = curl_exec($ch);
                curl_close($ch);

            }else{
                $content = '{"status":"error","error":"cURL is NOT installed on this server."}';
            }

            return $content;
        }
    }

    /*
     * Method in charge to check existing ContactUs.com user's by email via Client URL Library
     * @param string $cUs_email String requested via Login Form
     * @since 3.1
     * @return string jSon String by default
     */

    public function verifyCustomerEmail($cUs_email) {

        if( $this->_isCurl() ){

            $ch = curl_init();

            $strCURLOPT = $this->enviroment;
            $strCURLOPT .= '?API_Account='.$this->wpApiAccountKey;
            $strCURLOPT .= '&API_Key='.$this->wpApiKey;
            $strCURLOPT .= '&API_Action=verifyCustomerEmail';
            $strCURLOPT .= '&Email=' . urlencode(trim($cUs_email));

            curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ContactUs-Request-URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 'X-ContactUs-Signature: wp|5.0.8|' . $this->getIP()));

            $content = curl_exec($ch);
            curl_close($ch);

        }else{
            $content = '{"status":"error","error":"cURL is NOT installed on this server."}';
        }

        return $content;
    }

    /*
     * Method in charge to retrive ContactUs.com Form templates via Client URL Library
     * @param string $formType String to select plugin form type ex. 'contact_us'
     * @param string $selType String to select form or tab templates ex. 'template_desktop_form' 
     * @since 3.2
     * @return string jSon String by default
     */

    public function getTemplatesDataAll($formType, $selType) {

        if (!strlen($formType) || !strlen($selType))
            return false;

        if( $this->_isCurl() ){
            $ch = curl_init();

            $strCURLOPT = $this->enviroment;
            $strCURLOPT .= '?API_Account='.$this->wpApiAccountKey;
            $strCURLOPT .= '&API_Key='.$this->wpApiKey;
            $strCURLOPT .= '&API_Action=getTemplatesDataAll';
            $strCURLOPT .= '&Form_Type=' . trim($formType);
            $strCURLOPT .= '&Selection_Type=' . trim($selType);

            curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ContactUs-Request-URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 'X-ContactUs-Signature: wp|5.0.8|' . $this->getIP()));
            $content = curl_exec($ch);
            curl_close($ch);
        }else{
            $content = '{"status":"error","error":"cURL is NOT installed on this server."}';
        }

        return $content;
    }

    /*
     * Method in charge to get default form for the user. If Form_Type is present, function returns default Form_Key for the form type via Client URL Library
     * @param string $cUs_API_Account String API credential
     * @param string $cUs_API_Key String API credential
     * @param string $cUs_Form_Type String to select plugin form type ex. 'contact_us'
     * @since 3.2
     * @return string jSon String by default
     */

    public function getFormKeysData($cUs_API_Account, $cUs_API_Key, $cUs_Form_Type = '') {

        if( $this->_isCurl() ){
            $ch = curl_init();

            $strCURLOPT = $this->enviroment;
            $strCURLOPT .= '?API_Account=' . trim($cUs_API_Account);
            $strCURLOPT .= '&API_Key=' . trim($cUs_API_Key);
            $strCURLOPT .= '&API_Action=getFormKeysData';

            if (isset($cUs_Form_Type) && $cUs_Form_Type !=''){
                $strCURLOPT .= '&Form_Type=' . $cUs_Form_Type;
            }

            //echo $strCURLOPT; exit;

            curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ContactUs-Request-URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 'X-ContactUs-Signature: wp|5.0.8|' . $this->getIP()));
            $content = curl_exec($ch);
            curl_close($ch);
        }else{
            $content = '{"status":"error","error":"cURL is NOT installed on this server."}';
        }

        return $content;
    }

    /*
     * Method in charge to This function is used to update form settings via Client URL Library
     * @param array $postData Array with user data
     * @param string $formkey String with requested form key
     * @since 3.0
     * @return string jSon String by default
     */

    public function updateFormSettings($postData, $formkey) {

        if( $this->_isCurl() ){
            $ch = curl_init();

            $strCURLOPT = $this->enviroment;
            $strCURLOPT .= '?API_Account=' . trim($postData['API_Account']);
            $strCURLOPT .= '&API_Key=' . trim($postData['API_Key']);
            $strCURLOPT .= '&API_Action=updateFormSettings';
            $strCURLOPT .= '&Form_Key=' . trim($formkey);

            //CHANGE FORM TEMPLATE
            if (strlen($postData['Template_Desktop_Form']))
                $strCURLOPT .= '&Template_Desktop_Form=' . trim($postData['Template_Desktop_Form']);

            //CHANGE TAB TEMPLATE
            if (strlen($postData['Template_Desktop_Tab']))
                $strCURLOPT .= '&Template_Desktop_Tab=' . trim($postData['Template_Desktop_Tab']);

            curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ContactUs-Request-URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 'X-ContactUs-Signature: wp|5.0.8|' . $this->getIP()));
            $content = curl_exec($ch);
            curl_close($ch);
        }else{
            $content = '{"status":"error","error":"cURL is NOT installed on this server."}';
        }

        return $content;
    }

    /*
     * Method in charge to retrive ContactUs.com Form Templates via Client URL Library
     * @deprecated 0.2 Function has beeen deprecated. Use getTemplatesDataAll instead
     * @param string $formType String to select plugin form type ex. '1 = contact_us'
     * @param string $selType String to select form or tab templates ex. 'template_desktop_form'
     * @since 3.0
     * @return string jSon String by default
     */

    public function getTemplatesAndTabsAll($formType, $selType) {

        if (!strlen($formType) || !strlen($selType))
            return false;

        if( $this->_isCurl() ){
            $ch = curl_init();

            $strCURLOPT = $this->enviroment;
            $strCURLOPT .= '?API_Account='.$this->wpApiAccountKey;
            $strCURLOPT .= '&API_Key='.$this->wpApiKey;
            $strCURLOPT .= '&API_Action=getTemplatesAndTabsAll';
            $strCURLOPT .= '&Form_Type=' . trim($formType);
            $strCURLOPT .= '&Selection_Type=' . trim($selType);

            curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ContactUs-Request-URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 'X-ContactUs-Signature: wp|5.0.8|' . $this->getIP()));
            $content = curl_exec($ch);
            curl_close($ch);
        }else{
            $content = '{"status":"error","error":"cURL is NOT installed on this server."}';
        }

        return $content;
    }

    /*
     * Method in charge to get list of all availabe templates or tabs, including custom, for selected form types for the user via Client URL Library
     * @deprecated 0.2 Function has beeen deprecated. Use getTemplatesDataAll instead
     * @param string $formType String to select plugin form type ex. '1 = contact_us'
     * @param string $selType String to select form or tab templates ex. 'template_desktop_form'
     * @param string $cUs_API_Account String API credential
     * @param string $cUs_API_Key String API credential
     * @since 3.0
     * @return string jSon String by default
     */

    public function getTemplatesAndTabsAllowed($formType, $selType, $cUs_API_Account, $cUs_API_Key) {

        if (!strlen($formType) || !strlen($selType) || !strlen($cUs_API_Account) || !strlen($cUs_API_Key))
            return false;

        if( $this->_isCurl() ){
            $ch = curl_init();

            $strCURLOPT = $this->enviroment;
            $strCURLOPT .= '?API_Account=' . trim($cUs_API_Account);
            $strCURLOPT .= '&API_Key=' . trim($cUs_API_Key);
            $strCURLOPT .= '&API_Action=getTemplatesAndTabsAllowed';
            $strCURLOPT .= '&Form_Type=' . trim($formType);
            $strCURLOPT .= '&Selection_Type=' . trim($selType);

            curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ContactUs-Request-URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 'X-ContactUs-Signature: wp|5.0.8|' . $this->getIP()));
            $content = curl_exec($ch);
            curl_close($ch);
        }else{
            $content = '{"status":"error","error":"cURL is NOT installed on this server."}';
        }

        return $content;
    }

    /*
     * Method in charge to  get default form for the user. If Form_Type is present, function returns default Form_Key for the form type via Client URL Library
     * @deprecated 0.2 Function has beeen deprecated. Use getTemplatesDataAll instead
     * @param string $cUs_API_Account String API credential
     * @param string $cUs_API_Key String API credential
     * @since 3.0
     * @return string jSon String by default
     */

    public function getFormKeysAPI($cUs_API_Account, $cUs_API_Key) {

        if( $this->_isCurl() ){
            $ch = curl_init();

            $strCURLOPT = $this->enviroment;
            $strCURLOPT .= '?API_Account=' . trim($cUs_API_Account);
            $strCURLOPT .= '&API_Key=' . trim($cUs_API_Key);
            $strCURLOPT .= '&API_Action=getFormKeys';

            curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ContactUs-Request-URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 'X-ContactUs-Signature: wp|5.0.8|' . $this->getIP()));
            $content = curl_exec($ch);
            curl_close($ch);
        }else{
            $content = '{"status":"error","error":"cURL is NOT installed on this server."}';
        }

        return $content;
    }

    /*
     * Method used for making lead delivery changes via Client URL Library
     * @param array $postData Array with user data
     * @param string $formkey String with requested form key
     * @since 3.0
     * @return string jSon String by default
     */

    public function updateDeliveryOptions($postData, $formkey) {

        if( $this->_isCurl() ){
            $ch = curl_init();

            $strCURLOPT = $this->enviroment;
            $strCURLOPT .= '?API_Account=' . trim($postData['API_Account']);
            $strCURLOPT .= '&API_Key=' . trim($postData['API_Key']);
            $strCURLOPT .= '&API_Action=updateDeliveryOptions';
            $strCURLOPT .= '&Form_Key=' . trim($postData['Form_Key']);

            curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ContactUs-Request-URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 'X-ContactUs-Signature: wp|5.0.8|' . $this->getIP()));
            $content = curl_exec($ch);
            curl_close($ch);
        }else{
            $content = '{"status":"error","error":"cURL is NOT installed on this server."}';
        }

        return $content;
    }

    /*
     * Method that returns the first part of the deeplink, until brpage.php
     * String form already created in user account
     * @return string with the deeplink base
     * @since 1.9
     */

    public function get_deeplink($forms) {

        // first check if is array of forms we are passing
        if (is_array($forms)) {

            foreach ($forms as $key => $value) {
                if (strlen($value->deep_link_view)) {
                    $deeplink = $value->deep_link_view;
                    break;
                }
            }
        } else {
            $deeplink = $forms; // this is just a string
        }

        $abtest = parse_url($deeplink);
        $link = $abtest['scheme'] . '://' . $abtest['host'] . $abtest['path'];
        return $link;
    }

    /*
     * Method that returns the first part of the deeplink, until brpage.php
     * String form already created in user account
     * @return string with the deeplink base
     * @since 1.9
     */

    public function parse_deeplink($deeplink) {
        $abtest = parse_url($deeplink);
        $link = $abtest['scheme'] . '://' . $abtest['host'] . $abtest['path'];
        return $link;
    }

    /*
     * Method in charge to return the parsed deeplink
     * @param string $cUs_API_Account String API credential
     * @param string $cUs_API_Key String API credential
     * @since 3.2
     * @return string jSon String by default
     */

    public function getDefaultDeepLink($cUsCF_API_getFormKeys) {

        if (empty($cUsCF_API_getFormKeys))
            return FALSE;

        //$cUsMC_API_getKeysResult = $this->getFormKeysData($cUs_API_Account, $cUs_API_Key);
        $cUs_jsonKeys = json_decode($cUsCF_API_getFormKeys);

        if ($cUs_jsonKeys->status == 'success') {
            $deeplinkview = $this->get_deeplink($cUs_jsonKeys->data);
        }

        return $deeplinkview;
    }

    /*
     * Method that returns the first part of the deeplink, until brpage.php
     * String form already created in user account
     * @return string with the deeplink base
     * @since 1.9
     */
    public function get_partner_id($deeplink) {
        $aryURL = parse_url($deeplink);
        $aryURL = explode('/', $aryURL['path'] );

        return trim($aryURL[2]);
    }

    /*
     * Method in charge to return Signu Up categories and Sub Categories
     * @since 4.0.1
     * @return array
     */
    public function getCategoriesSubs() {

        $aryCategoriesSubs = array(
            'Agents' => array('Insurance Agent','Mortgage Broker','Real Estate Agent','Travel Agent','Other Agent',),
            'Business Services' =>   array('Advertising / Marketing / PR','Art / Media / Design','Customer Service','Finance','Food / Beverage / Hospitality','Human Resources','IT','Legal','Logistics / Moving','Manufacturing','Medical / Health','Sales','Telecom','Utilities','Web Design / Development','Other Business Services',),
            'Content'   => array('Blog','Entertainment','Finance','Jobs','News','Politics','Sports','Other',),
            'Education' =>   array('Career Training','For-Profit School','Language Learning','Non-Profit School','Recreational Learning','Tutoring / Lessons',),
            'Freelancers'   => array('Actor / Model','Band / Musician','Business Consultant','Graphic Designer','Marketing Consultant','Software Engineer','Web Designer / Developer','Writer','Video Production','Other Independent Consultant',),
            'Home Services' =>   array('Audio / Video','Carpet Cleaning','Catering','Contractor','Dog Walking / Pet Sitting','Electrical','Furniture Repair','Gutter Cleaning','Handy Man/Repair','Home Security','House Cleaning','HVAC Services','Interior Design','Landscaping / Lawncare','Locksmith','Moving','Painting','Pest Control','Plumbing','Window Washing','Window Repair','Other Home Service',),
            'Non-Profit or Community Group' =>   array('Charity','Community Organization','Educational Organization','Government Organization','Health Organization','Political Organization','Religious Organization','Other Non-Profit',),
            'Offline Retail' =>  array('Apparel','Auto Sales','Auto Services','Electronics','Flowers and Gifts','Food and Beverage','Furniture','Jewelry','Music','Pets','Restaurants','Salons / Barbers','Spa','Specialty Items','Toys / Games','Other Local',),
            'Online Retail'=>   array('Apparel','Electronics','Flowers and Gifts','Food and Beverage','Invitations','Gifts','Pets','Specialty Items','Toys / Games','Other Online',),
            'Other Service Industry'=>  array('Events','Recreation','Other',),
            'Personal Services'=>   array('Beauty (hair, nails, etc.)','Child Care','Day Care','Massage Therapist','Personal Trainer','Photographers','Tutoring / Lessons','Other Personal Service',),
            'Professional Services'=>   array('Accountant','Architect / Engineering','Admin / Office','Computer Repair / IT Help','Dentist','Doctor','Education','Financial Planning','Lawyer','Life Coach','Logistics / Moving','Medical / Health','Optometrist / Optician','Security','Skilled Trade','Software','Therapist','Transportation','Veterinarian','Wedding / Special Events','Other Professional Service',),
            'Travel and Hospitality'=>  array('Car Rental','Excursion','Hotel / Motel','Tours','Transportation','Vacation Homes','Vacation Packages',),
            'Web Service'=> array('Consumer Web Service','Small Business Web Service','Enterprise Web Service',)
        );

        return $aryCategoriesSubs;
    }
    /*
     * Method in charge to return Signu Up Goals
     * @since 4.0.1
     * @return array
     */
    public function getGoals() {

        $aryGoals = array(
            'Generating online sales',
            'Generating offline sales',
            'Generating sales leads',
            'Generating phone calls',
            'Growing your email marketing list',
            'Providing customer service',
            'None, I just want a contact form on my site that sends to my email',
            'Other',
        );

        return $aryGoals;
    }

    /*
     * Method in charge to return true or proxy IP address
     * @since 3.0
     * @return string IP address by String
     */

    public function getIP() {

        // Get some headers that may contain the IP address
        $SimpleIP = (isset($REMOTE_ADDR) ? $REMOTE_ADDR : getenv("REMOTE_ADDR"));

        $TrueIP = (isset($HTTP_CUSTOM_FORWARDED_FOR) ? $HTTP_CUSTOM_FORWARDED_FOR : getenv("HTTP_CUSTOM_FORWARDED_FOR"));
        if ($TrueIP == "")
            $TrueIP = (isset($HTTP_X_FORWARDED_FOR) ? $HTTP_X_FORWARDED_FOR : getenv("HTTP_X_FORWARDED_FOR"));
        if ($TrueIP == "")
            $TrueIP = (isset($HTTP_X_FORWARDED) ? $HTTP_X_FORWARDED : getenv("HTTP_X_FORWARDED"));
        if ($TrueIP == "")
            $TrueIP = (isset($HTTP_FORWARDED_FOR) ? $HTTP_FORWARDED_FOR : getenv("HTTP_FORWARDED_FOR"));
        if ($TrueIP == "")
            $TrueIP = (isset($HTTP_FORWARDED) ? $HTTP_FORWARDED : getenv("HTTP_FORWARDED"));

        $GetProxy = ($TrueIP == "" ? "0" : "1");

        if ($GetProxy == "0") {
            $TrueIP = (isset($HTTP_VIA) ? $HTTP_VIA : getenv("HTTP_VIA"));
            if ($TrueIP == "")
                $TrueIP = (isset($HTTP_X_COMING_FROM) ? $HTTP_X_COMING_FROM : getenv("HTTP_X_COMING_FROM"));
            if ($TrueIP == "")
                $TrueIP = (isset($HTTP_COMING_FROM) ? $HTTP_COMING_FROM : getenv("HTTP_COMING_FROM"));
            if ($TrueIP != "")
                $GetProxy = "2";
        }

        if ($TrueIP == $SimpleIP)
            $GetProxy = "0";

        // Return the true IP if found, else the proxy IP with a 'p' at the begining
        switch ($GetProxy) {
            case '0':
                // True IP without proxy
                $IP = $SimpleIP;
                break;
            case '1':
                $b = preg_match("%^([0-9]{1,3}\.){3,3}[0-9]{1,3}%", $TrueIP, $IP_array);
                if ($b && (count($IP_array) > 0)) {
                    // True IP behind a proxy
                    $IP = $IP_array[0];
                } else {
                    // Proxy IP
                    $IP = $SimpleIP;
                }
                break;
            case '2':
                // Proxy IP
                $IP = $SimpleIP;
        }

        $IP = trim($IP);
        if (filter_var($IP, FILTER_VALIDATE_IP) && $IP != '127.0.0.1' && $IP != '::1') { //LOCALHOST IS NOT ALLOWED
            $vIP = $IP;
        } else {
            $externalContent = file_get_contents('http://checkip.dyndns.com/'); //IF EVERITHING ELSE FAIL
            preg_match('/Current IP Address: ([\[\]:.[0-9a-fA-F]+)</', $externalContent, $m);
            $vIP = $m[1];
        }

        return $vIP;
    }

    /*
     * Method in charge to remove form WP DB all this plugin options or settings
     * @since 1.0
     * @return true value
     */

    public function resetData() {

        //PLUGIN OPTIONS
        delete_option('cUsCF_settings');
        delete_option('cUsCF_settings_userData');
        delete_option('cUsCF_settings_FORM');
        delete_option('cUsCF_HOME_settings');
        delete_option('cUsCF_settings_step1');
        delete_option('cUsCF_settings_form_key');
        delete_option('cUsCF_settings_inlinepages');
        delete_option('cUsCF_settings_tabpages');
        delete_option('cUsCF_settings_userCredentials');
        delete_option('cUsCF_settings_default_deep_link_view');
        delete_option('cUsCF_settings_form_id');
        delete_option('cUsCF_settings_intro_hints');

        cUsCF_page_settings_cleaner(); //REMOVE CUSTOM SETTINGS BY PAGE
        cUsCF_inline_shortcode_cleaner(); //REMOVE SHORTCODES

        return true;
    }

    public function _isCurl(){
        return function_exists('curl_version');
    }

}