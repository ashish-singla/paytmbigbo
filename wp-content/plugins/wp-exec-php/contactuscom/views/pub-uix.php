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

<div class="row">
    <div class="col-sm-12">
        <div id="cUsCF_tabs">
            <ul class="cu_border-bottom">
                <li><a href="#tabs-1" class="cu_bluebox dashboard-color">Contact Form Plugin</a></li>
            </ul>
            <div id="tabs-1">
                <div class="row">
                    <div class="col-sm-8">
                        <?php
                        /*
                        * LOGIN FORM
                        * @since 1.0
                        */
                        require_once( cUsCF_DIR . 'views/login-form.php');
                        ?>

                        <?php
                        /*
                        * SINGUP FORM - SIGNUP WIZARD
                        * @since 1.0
                        */
                        require_once( cUsCF_DIR . 'views/signup-form.php');
                        ?>

                        <div class="contaus_features">
                            <div class="head_title"><h2>What Do You Get With a ContactUs.com Account?</h2></div>
                            <div class="road_features">
                                <div class="_row r1">
                                    <div class="_col"><a class="feature arrow_start setLabels" href="http://www.contactus.com/product-tour/" target="_blank" title="Benefit from our customer acquistion tools. Start by creating a custom Contact Form."></a></div>
                                    <div class="_col"><a class="feature contacts setLabels" href="http://www.contactus.com/contact-management/" target="_blank" title="Manage and email leads, sync data, track analytics, and much more."></a></div>
                                    <div class="_col"><a class="feature contactform setLabels" href="http://www.contactus.com/custom-form-builder/" target="_blank" title="Drag and Drop Editor: Easily customize fields, layout, colors and calls to action."></a></div>
                                    <div class="_col"><a class="feature wp setLabels" href="http://www.contactus.com/wordpress-plugins/" target="_blank" title="The ContactUs.com plugins are the best way to add contact forms, newsletter opt-ins, and PayPal forms on your WordPress powered website."></a></div>
                                </div>
                                <div class="_row r2">
                                    <div class="_col"></div>
                                    <div class="_col"></div>
                                    <div class="_col"></div>
                                    <div class="_col"><a class="feature tracking setLabels" href="http://www.contactus.com/phone-call-tracking/" target="_blank" title="Get a unique phone number, track inbound calls & optimize your sales process."></a></div>
                                </div>
                                <div class="_row r3">
                                    <div class="_col"><a class="feature chat setLabels" href="http://www.contactus.com/contactus-chat/" target="_blank" title="Enagage website visitors, increase sales, and provide better customer support."></a></div>
                                    <div class="_col"><a class="feature _3rd setLabels" href="http://www.contactus.com/3rd-party-software-integrations/" target="_blank" title="Tons of 3rd-Party Integrations to sync your data."></a></div>
                                    <div class="_col"><a class="feature ab setLabels" href="http://www.contactus.com/conversion-optimization-tools/" target="_blank" title="We've made it easy to optimize your forms and test new variations."></a></div>
                                    <div class="_col"><a class="feature leadalerts setLabels" href="http://www.contactus.com/returning-lead-alerts" target="_blank" title="Create Time-Aware opportunities with our tracking cookie."></a></div>
                                </div>
                                <div class="_row r4">
                                    <div class="_col"><a class="feature loadforms setLabels" href="http://www.contactus.com/smart-triggers/" target="_blank" title="Page load triggers, exit intent triggers, and hyperlink triggers are cutting edge."></a></div>
                                    <div class="_col"></div>
                                    <div class="_col"></div>
                                    <div class="_col"></div>
                                </div>
                                <div class="_row r5">
                                    <div class="_col"><a class="feature analytics setLabels" href="http://www.contactus.com/reports-analytics/" target="_blank" title="Track conversions, conduct A/B tests, and sync with 3rd party tools."></a></div>
                                    <div class="_col"><a class="feature customizable setLabels" href="http://www.contactus.com/product-tour/#five-types" target="_blank" title="Traditional Contact Forms, an Appointment Scheduler with calendar sync, Newsletter & Opt-in Forms, Payments & Donations, and Custom Field Forms"></a></div>
                                    <div class="_col"></div>
                                    <div class="_col"></div>
                                </div>
                                <div class="_row r6">
                                    <div class="_col"></div>
                                    <div class="_col"></div>
                                    <div class="_col"></div>
                                    <div class="_col"><a id="cUsCF_signup_cloud" class="feature cloud setLabels" href="#signupform"></a></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-4 sidebar">
                        <?php
                        /*
                         * SIDEBAR & SUPPORT
                         * @since 1.0
                         */
                        include( cUsCF_DIR . 'views/sidebar-pub.php');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>