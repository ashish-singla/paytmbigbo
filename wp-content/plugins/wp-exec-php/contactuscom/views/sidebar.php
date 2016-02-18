<?php
/**
 *
 * CONTACT FORM BY CONTACTUS.COM
 *
 * Initialization Sidebar View
 * @since 1.0 First time this was introduced into plugin.
 * @author ContactUs.com <support@contactus.com>
 * @copyright 2014 ContactUs.com Inc.
 * Company      : contactus.com
 * Updated  	: 20140603
 * */
?>

<div id="sidebar-cta" class="text-center">

    <?php
        $aryAds = array( 'very-cool.png', 'very-cool-2.jpg');
        shuffle($aryAds);
    ?>
    <a href="<?php echo cUsCF_CRED_URL; ?>&confirmed=1&redir_url=<?php echo urlencode(cUsCF_PARTNER_URL . '/'.$partnerID); ?> /en/plans/confirm/premium-monthly-14-day-trial" target="_blank">
        <img src="<?php echo plugins_url('assets/img/' . $aryAds[0], dirname(__FILE__) ) ;  ?>">
    </a>
    <hr />
    <iframe width="100%" height="100%" src="//www.youtube.com/embed/videoseries?list=PL0S7fxBYpaTF4zqBgKQlwfqynydJAm64h" frameborder="0" allowfullscreen></iframe>
    <a href="http://help.contactus.com/hc/en-us/articles/202691459-ContactUs-com-Premium-Training" target="_blank" class="btn btn-info btn-block">Find individual How-To videos here</a>
    <hr />
    <h3>Upgrade Your Account</h3>
    <hr>
    <ul class="list-unstyled">
        <li>Remove ContactUs.com Branding</li>
        <li>Add Lead Analytics</li>
        <li>Add 2-Way Email Tracking</li>
        <li>Add Call Tracking</li>
        <li>Add Premium Chat Features</li>
        <li>Add Multiple User Accounts</li>
    </ul>

    <a href="http://www.contactus.com/premium-features/" target="_blank" class="btn btn-info btn-block">View Premium Features</a>
    <?php if(!empty($cUs_API_Account)){ ?>
    <a href="<?php echo cUsCF_CRED_URL; ?>&confirmed=1&redir_url=<?php echo urlencode(trim($upgrade)); ?>" target="_blank" class="btn btn-success btn-block">Get Started with an Upgrade Account</a>
        <hr/>
        <a href="http://wordpress.org/support/view/plugin-reviews/contactuscom" class="btn btn-review btn-lg btn-block" target="_blank"><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i> <br> <span>Write a Review<i class="icon-pencil"></i></span></a>
    <?php } ?>

</div>