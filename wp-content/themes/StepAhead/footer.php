<?php global $theme; ?>

<?php if($theme->display('footer_widgets')) { ?>
    <div id="footer-widgets" class="clearfix">
        <?php
        /**
        * Footer  Widget Areas. Manage the widgets from: wp-admin -> Appearance -> Widgets 
        */
        ?>
        <div class="footer-widget-box">
            <?php
                if(!dynamic_sidebar('footer_1')) {
                    $theme->hook('footer_1');
                }
            ?>
        </div>
        
        <div class="footer-widget-box">
            <?php
                if(!dynamic_sidebar('footer_2')) {
                    $theme->hook('footer_2');
                }
            ?>
        </div>
        
        <div class="footer-widget-box footer-widget-box">
            <?php
                if(!dynamic_sidebar('footer_3')) {
                    $theme->hook('footer_3');
                }
            ?>
        </div>

         <div class="footer-widget-box footer-widget-box-last">
            <?php
                if(!dynamic_sidebar('footer_4')) {
                    $theme->hook('footer_4');
                }
            ?>
        </div>
        
    </div>
<?php  } ?>
   <div id="footer-widgets" class="clearfix" style="margin-left: 15px">

<font style="line-height: 125%;font-size: 11px; color: #9e9e9e !important" class="footer-display">
<p style="text-align: center">
<font style="font-size: 14px;color: black">We care for you</font>
<br/><br/>
<font style="font-size: 12px">
Have a question? Feel free to write to us at <a href="mailto:merchant.helpdesk@paytm.com"><b>merchant.helpdesk@paytm.com</b>.</a><br/>
We’d love to hear from you.
</font>
</p>
<hr style="border-top: 2px solid #F0F0F0;"/>
<p style="font-size: 12px">Sell On Paytm</p>
Sell on Paytm is a sales channel for businesses willing to sell their products online. Our nationwide reach would help you get massive buyers for your products. You have the liberty to list endless products and benefit from Paytm’s traffic, innovation, easy &amp; incredible shopping platform, secure payment gateway and our guarantee on each product. You would notice a boost in your business with our suite of added services. Our trusted platform and fulfillment services offer you the access to all the required resources needed to build your business and take it to the next level. Notice huge traffic and incremental sales with customer friendly content for your products.
<br/><br/>
How to Sell on Paytm.com<br/><br/>
Our groundbreaking mobile-first marketplace brings your catalogue to everyone’s pocket. Selling on Paytm.com is as easy as A B C. All you need is just list your catalogue and start selling.
<br/>
<b>Register:</b> You are required to register via the form given above. Once your email id and phone number are verified, your registration will be complete with us. You just need to upload your KYC documents in order to get your payments.
<br/>
<b>List:</b> Create your catalogue for free on Paytm. We do not charge anything for the listing of the catalogues on our website. You pay for what you sell. The commission structure is shared when you register with us.
<br/>
<b>Sell:</b> See increased growth in sales by getting instant access to millions of customers nationwide. You can sell in categories like: <b>Consumer Electronics </b>Mobiles &amp; Accessories, Home Appliances, Cameras, Consoles Gaming Accessories, <b>Men &amp; Women Clothing </b>Footwear, Watches, Sunglasses, Beauty &amp; Personal Care, Bags &amp; Accessories, <b>Home &amp; Kitchen </b>including Home Furnishings, Home Décor, Home Utility, <b>Sports &amp; Health Accessories </b>Fitness Accessories &amp; Health Care Products, Stationery, Books, Baby Products, Toys and CDs, DVDs in Music &amp; Movies to name a few.
<br/><br/>
<b>Added Services:</b> Paytm.com extends services like Doorstep Delivery, Return Facility, Quick Refunds, Easy and Secure Payment Options, Cash on Delivery, so you don’t have to worry about the delivery and shipping.
<br/><br/>
<b>Earn:</b> Improve your sales with Paytm.com. List the products on our marketplace. As soon as the customer makes a purchase, you would receive an email to ship the product. Once you deliver the product and shipment is confirmed, your sale is also confirmed.
<br/><br/>
Selling online was never so easy. Paytm.com makes it effortless for sellers to sell on Paytm. Reach millions of buyers out there and enjoy the benefits thereafter.
</font>
   </p>
   <br/>
   </div>
    <div id="footer">
    
        <div id="copyrights">
        <a href="https://paytm.com/terms.html">Terms of Service</a> &nbsp; <a href="https://paytm.com/privacy-policy.html"> Policy &nbsp;</a><a href="<?= get_site_url(); ?>/wordpress/forum-guidelines/"> Forum Guidelines</a>
        <br/><br/>
        © Copyright 2015 One97 Communictions Limited
            <?php
               /* if($theme->display('footer_custom_text')) {
                    $theme->option('footer_custom_text');
                } else { 
                    ?> &copy; <?php echo date('Y'); ?>  <a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a><?php
                }*/
            ?> 
        </div>
        
        <?php /* 
            All links in the footer should remain intact. 
            These links are all family friendly and will not hurt your site in any way. 
            Warning! Your site may stop working if these links are edited or deleted 
            
            You can buy this theme without footer links online at https://flexithemes.com/buy/?theme=stepahead
        */ ?>
        
        <!--<div id="credits">Powered by <a href="http://wordpress.org/"><strong>WordPress</strong></a> | Theme Designed by: <?php echo wp_theme_credits(0); ?>  | Thanks to <?php echo wp_theme_credits(1); ?>, <?php echo wp_theme_credits(2); ?> and <?php echo wp_theme_credits(3); ?></div>--><!-- #credits -->
        
    </div><!-- #footer -->
    
</div><!-- #container -->

<?php wp_footer(); ?>
<?php $theme->hook('html_after'); ?>
</body>
</html>