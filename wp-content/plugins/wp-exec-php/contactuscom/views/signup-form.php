<?php
/**
 *
 * CONTACT FORM BY CONTACTUS.COM
 *
 * Initialization Signup Form
 * @since 5.0 First time this was introduced into plugin.
 * @author ContactUs.com <support@contactus.com>
 * @copyright 2014 ContactUs.com Inc.
 * Company      : contactus.com
 * Updated  	: 20140603
 * */
?>

<?php
$cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API

global $current_user;
get_currentuserinfo();
?>

<div class="signup-form">
    <h2>Create My First ContactUs.com Form</h2>
    <hr>
    <div class="row">
        <div class="col-sm-11 col-sm-offset-1">

            <form class="form-horizontal" role="form" name="signUp-form" id="signUp-form" action="admin.php?page=cUs_form_plugin">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Your Name</label>

                    <div class="col-sm-4">
                        <input type="text" class="form-control" placeholder="First Name" name="first_name" value="<?php echo $current_user->user_firstname; ?>">
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" placeholder="Last Name" name="last_name" value="<?php echo $current_user->user_lastname; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-8">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email_s" value="<?php echo $current_user->user_email; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Phone</label>

                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Optional" name="phone">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Password</label>

                    <div class="col-sm-4">
                        <input type="password" class="form-control" placeholder="Password" name="password" id="password1">
                    </div>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" placeholder="Confirm Password" name="password_match" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Website</label>

                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="http://www.example.com" name="website" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="hidden" id="SignUp_SentData" href="#cats_selection">
                        <button type="submit" class="btn btn-warning"><i class="icon-user-add"></i> Create Form Now</button> <hr/> <a href="javascript:;" class="sign-in">&#xffe9; back to login</a>
                        <span class="loading"></span>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- CATS SUBCATS AND GOALS -->
<div id="cats_container" class="hidden_el">

    <div id="cats_selection">
        <div class="notice_error"></div><div class="notice_success"></div>
        <form action="/" onsubmit="return false;">

            <div id="customer-categories-box" class="questions-box">

                <div class="cc-headline"> <?php echo (!empty($current_user->user_firstname)) ? 'Hi ' . $current_user->user_firstname :''; ?></div>
                <img src="<?php echo plugins_url('assets/img/contactus-users.png', dirname(__FILE__)); ?>" class="user-graphic">
                <div class="cc-message">We’re working on new ways to personalize your account</div>
                <div class="cc-message-small">Please take 7 seconds to tell us about your website, which helps us identify the best tools for your needs:</div>

                <h4 class="cc-title" id="category-message">Select the Category of Your Website:</h4>

                <?php
                /*
                 * GET CATEGORIES AND SUBCATEGORIES
                 */
                $aryCategoriesAndSub = $cUsCF_api->getCategoriesSubs();

                if (is_array($aryCategoriesAndSub)) {
                    ?>
                    <ul id="customer-categories">
                        <?php foreach ($aryCategoriesAndSub as $category => $arySubs) { ?>

                            <li class="parent-category"><span data-maincat="<?php echo $category; ?>" id="<?php echo str_replace(' ', '-', $category); ?>" class="parent-title"><?php echo trim($category); ?></span>
                                <?php if (is_array($arySubs)) { ?>
                                    <ul class="sub-category">
                                        <?php foreach ($arySubs as $Sub) { ?>
                                            <li data-subcat="<?php echo $Sub; ?>"><span><?php echo trim($Sub); ?></span></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>

                        <?php } ?>
                    </ul>
                <?php } ?>


                <div class="int-navigation">
                    <button class="btn next btn-skip">Skip</button>
                    <img src="<?php echo plugins_url('../assets/style/images/ajax-loader.gif', __FILE__); ?>" width="25" height="35" alt="Loading . . ." style="display:none; vertical-align:middle;" class="img_loader loading" />
                    <div class="next btn unactive" id="open-intestes">Next Question</div>
                </div>

            </div>

            <div id="user-interests-box" class="questions-box">
                <div class="cc-headline"><?php echo (!empty($current_user->user_firstname)) ? 'Hi ' . $current_user->user_firstname :''; ?></div>
                <div class="cc-message">What are your goals for your ContactUs.com form?</div>

                <?php
                /*
                 * GET GOALS
                 */
                $aryGoals = $cUsCF_api->getGoals();

                if (is_array($aryGoals)) {
                    ?>
                    <ul id="user-interests">
                        <?php foreach ($aryGoals as $Goal) { ?>
                            <li data-goals="<?php echo trim($Goal); ?>" <?php if ($Goal === 'Other') { ?>id="other"<?php } ?>><span <?php if (strpos($Goal, 'to my email') !== false) { ?> class="grey" <?php } ?>><?php echo trim($Goal); ?></span></li>
                        <?php } ?>
                    </ul>
                <?php } ?>

                <div id="other-interest">Please tell us <input type="text" name="other" id="other_goal" value="" /></div>

                <div class="int-navigation">
                    <button class="btn next btn-skip">Skip</button>
                    <div class="next btn unactive btn-skip" id="save">Save Preferences</div>
                    <img src="<?php echo plugins_url('../assets/style/images/ajax-loader.gif', __FILE__); ?>" width="25" height="35" alt="Loading . . ." style="display:none; vertical-align:middle;" class="img_loader loading" />
                </div>

            </div>

            <!-- input the category and subcategory data -->
            <input type="hidden" value="" name="CU_category" id="CU_category" />
            <input type="hidden" value="" name="CU_subcategory" id="CU_subcategory" />
            <!-- <input type="hidden" value="" name="CU_goals" id="CU_goals" /> -->

            <div id="goals_added"></div>

        </form>
        <br /><br /><br />
    </div>
</div>
<!-- / CATS SUBCATS AND GOALS -->