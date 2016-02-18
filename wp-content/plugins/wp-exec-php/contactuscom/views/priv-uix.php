<?php
/**
 *
 * CONTACT FORM BY CONTACTUS.COM
 *
 * Initialization Plugin Public Tabs / login / signup
 * @since 5.0 First time this was introduced into plugin.
 * @author ContactUs.com <support@contactus.com>
 * @copyright 2014 ContactUs.com Inc.
 * Company      : contactus.com
 * Updated    : 20140602
 * */
?>

<?php
$cUsCF_api          = new cUsComAPI_CF(); //CONTACTUS.COM API

//delete_option('cUsCF_settings_intro_hints');

$options            = get_option('cUsCF_settings_userData'); //get the values, wont work the first time
$formOptions        = get_option('cUsCF_settings_FORM');//GET THE NEW FORM OPTIONS
$form_key           = get_option('cUsCF_settings_form_key');
$default_deep_link  = get_option('cUsCF_settings_default_deep_link_view');
$showHints          = get_option('cUsCF_settings_intro_hints'); //intro hints
$cus_version        = $formOptions['cus_version'];
$boolTab            = $formOptions['tab_user'];
$boolBackLink       = get_option('cUsCF_switch_backlink');
$cUs_API_Account    = $aryUserCredentials['API_Account'];
$cUs_API_Key        = $aryUserCredentials['API_Key'];

$cUsCF_API_getFormKeys = $cUsCF_api->getFormKeysData($cUs_API_Account, $cUs_API_Key); //api hook;
update_option('cUsCF_settings_FORMS', $cUsCF_API_getFormKeys);

$default_deep_link = $cUsCF_api->parse_deeplink ( $default_deep_link );
if( !strlen($default_deep_link) ){
    $default_deep_link = $cUsCF_api->getDefaultDeepLink( $cUsCF_API_getFormKeys ); // get a default deeplink
    update_option('cUsCF_settings_default_deep_link_view', $default_deep_link );
}

$acount = $default_deep_link.'?pageID=7';
$reports = $default_deep_link.'?pageID=12';
$upgrade = $default_deep_link.'?pageID=82';
$createform = $default_deep_link.'?pageID=81&id=0&do=addnew&formType=';
$partnerID = $cUsCF_api->get_partner_id($default_deep_link);
$cus_CRED_url = cUsCF_PARTNER_URL . '/index.php?loginName='.$cUs_API_Account.'&userPsswd='.urlencode($cUs_API_Key);
define('cUsCF_CRED_URL', $cus_CRED_url);
//echo $chataui = cUsCF_PARTNER_URL . '/'.$partnerID . '/';

//print_r($aryUserCredentials);

?>

<div class="row">
    <div class="col-sm-12">
        <div class="row" id="menuPanel">
            <div class="col-xs-8 col-md-10 col-lg-9 noPadding">
                <div id="menu">
                    <div id="menuWrapper">
                        <ul id="tabs" data-tabs="tabs">

                            <li>
                                <a href="#tabs1" role="button" id="cu_nav_forms" class="btn forms-color" data-toggle="tab">
                                    <span class="icon-suitcase white"></span><span class="hidden-sm hidden-xs"> Forms</span>
                                </a>
                            </li>
                            <li>
                                <a href="#tabs2" role="button" id="cu_nav_page" class="btn chat-color" data-toggle="tab">
                                    <span class="icon-layout white"></span><span class="hidden-sm hidden-xs"> Form/Page Selection</span>
                                </a>
                            </li>
                            <li>
                                <a id="cu_nav_docu" href="http://help.contactus.com/hc/en-us/sections/200204997-Contact-Form-Plugin-for-Wordpress-Documentation" role="button" class="btn contacts-color" target="_blank">
                                    <span class="icon-book white"></span><span class="hidden-sm hidden-xs"> Documentation</span>
                                </a>
                            </li>

                            <!-- li>
                                <a id="cu_nav_customization" href="<?php echo cUsCF_CRED_URL; ?>&confirmed=1&redir_url=<?php echo urlencode(cUsCF_PARTNER_URL .'/'. $partnerID.'/en/forms/'); ?>" role="button" class="btn btn-yellow tooltips" target="_blank" title="Opens ContactUs.com admin panel in new window" data-placement="right">
                                    <span class="icon-brush white"></span><span class="hidden-sm hidden-xs"> Form Customization</span>
                                </a>
                            </li -->

                            <li>
                                <a id="cu_nav_contacts" href="<?php echo cUsCF_CRED_URL; ?>&confirmed=1&redir_url=<?php echo urlencode(cUsCF_PARTNER_URL .'/'. $partnerID.'/en/contacts/showContacts/latestSubmissions/'); ?>" role="button" class="btn btn-yellow tooltips" target="_blank" title="Opens ContactUs.com admin panel in new window" data-placement="right">
                                    <span class="icon-users white"></span><span class="hidden-sm hidden-xs"> Contacts</span>
                                </a>
                            </li>

                        </ul>

                    </div>
                    <div id="menuToggleButton">
                        <a id="toggleCollapsedMenu" href="#" role="button" class="btn chat-color"><span class="white justify"></span></a>
                    </div>
                </div>

            </div>
            <div class="col-xs-4 col-md-2 col-lg-3 noPadding">
                <div id="gravatar" class="pull-right" style="color: #ffffff">
                    <img src="https://secure.gravatar.com/avatar/d41d8cd98f00b204e9800998ecf8427e?s=50&amp;d=mm&amp;r=g" height="50px" width="50px">
                </div>
                <div class="btn-group pull-right" id="userMenu">

                    <button class="btn dropdown-toggle usermenu-color" role="button" data-toggle="dropdown">
                        <span class="white user hidden-sm hidden-xs hidden-md"></span><span class="hidden-sm hidden-xs hidden-md">Account</span><span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="logout" target="_blank"  href="<?php echo cUsCF_CRED_URL; ?>&confirmed=1&redir_url=<?php echo urlencode(cUsCF_PARTNER_URL .'/'. $partnerID.'/en/account/accountSettings'); ?>"><span class="icon-cog"></span> Account Settings </a>
                        </li>
                        <li>
                            <a class="logout" target="_blank"  href="<?php echo cUsCF_CRED_URL; ?>&confirmed=1&redir_url=<?php echo urlencode(cUsCF_PARTNER_URL .'/'. $partnerID.'/en/account/changePassword'); ?>"><span class="icon-lock"></span> Change Password </a>
                        </li>
                        <!-- li>
                            <a class="logout" target="_blank"  href="https://admin.contactus.com/partners/<?php echo $partnerID; ?>/en/billing"><span class="credit_card"></span> Billing Settings </a>
                        </li -->
                        <li>
                            <a class="logout" target="_blank"  href="<?php echo cUsCF_CRED_URL; ?>&confirmed=1&redir_url=<?php echo urlencode(cUsCF_PARTNER_URL .'/'. $partnerID.'/en/plans'); ?>"><span class="icon-chart-bar"></span> Plans </a>
                        </li>
                        <li>
                            <a class="logout" target="_blank"  href="<?php echo cUsCF_CRED_URL; ?>&confirmed=1&redir_url=<?php echo urlencode(cUsCF_PARTNER_URL .'/'. $partnerID.'/en/account/usage'); ?>"><span class="icon-chart-line"></span> Usage </a>
                        </li>
                        <li>
                            <a class="logout" target="_blank"  href="<?php echo cUsCF_CRED_URL; ?>&confirmed=1&redir_url=<?php echo urlencode(cUsCF_PARTNER_URL .'/'. $partnerID.'/en/account/api'); ?>"><span class="icon-key"></span> API </a>
                        </li>
                        <li>
                            <a class="logout" target="_blank"  href="<?php echo cUsCF_CRED_URL; ?>&confirmed=1&redir_url=<?php echo urlencode(cUsCF_PARTNER_URL .'/'. $partnerID.'/en/account/returningLeadAlert'); ?>"><span class="icon-attention"></span> Returning Lead Alert </a>
                        </li>
                        <!-- li>
                            <a class="logout" target="_blank"  href="https://admin.contactus.com/partners/<?php echo $partnerID; ?>/en/account/manageUsers"><span class="group"></span> Manage Users </a>
                        </li>
                        <li>
                            <a class="logout" target="_blank"  href="https://admin.contactus.com/partners/<?php echo $partnerID; ?>/en/account/managePermissions"><span class="group"></span> Manage Permissions </a>
                        </li -->
                        <li>
                            <a class="logout LogoutUser tooltips" data-placement="left" href="javascript:;" title="Unlink ContactUs.com Account with WordPress Plugin"><span class="icon-logout"></span> Unlink Account </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 tab-content" id="my-tab-content">

        <div id="tabs1" class="tab-pane active">
            <div class="row">
                <div class="col-sm-8">
                    <?php
                    /*
                    * My Forms
                    * @since 1.0
                    */
                    require_once( cUsCF_DIR . 'views/my-forms.php');
                    ?>
                </div>

                <div class="col-sm-4 sidebar">
                    <?php
                    /*
                     * SIDEBAR & SUPPORT
                     * @since 1.0
                     */
                    include( cUsCF_DIR . 'views/sidebar.php');
                    ?>
                </div>
            </div>
        </div>

        <div id="tabs2" class="tab-pane">
            <div class="row">
                <div class="col-sm-8">
                    <?php
                    /*
                    * LOGIN FORM
                    * @since 1.0
                    */
                    require_once( cUsCF_DIR . 'views/form-placement.php');
                    ?>
                </div>

                <div class="col-sm-4 sidebar">
                    <?php
                    /*
                     * SIDEBAR & SUPPORT
                     * @since 1.0
                     */
                    include( cUsCF_DIR . 'views/sidebar.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <hr/>
        <div class="pull-left">
            <span class="loading"></span>
            <label>ContactUs.com link:</label>
                <input type="checkbox" class="checkbox_backlink" name="checkbox_backlink" id="checkbox_backlink" <?php echo( $boolBackLink || !strlen($boolBackLink) ) ? 'checked': ''; ?>>

        </div>
    </div>
</div>
<script>
    var home_url = '<?php echo get_option('home'); ?>';
</script>
<?php
if(!empty($cUs_API_Account)){
    if(!strlen($showHints) || $showHints == 1) { ?>
        <script>
            jQuery(document).ready(function($) {
                startIntro();
            });
        </script>
    <?php }
}
?>