<?php
/**
 *
 * CONTACT FORM BY CONTACTUS.COM
 *
 * Initialization Login Form
 * @since 5.0 First time this was introduced into plugin.
 * @author ContactUs.com <support@contactus.com>
 * @copyright 2014 ContactUs.com Inc.
 * Company      : contactus.com
 * Updated  	: 20140603
 * */
?>

<div class="login-form">
    <h2>Welcome Back</h2>
    <hr>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-1">
            <div class="notice_error"></div><div class="notice_success"></div>
            <form class="form-horizontal" role="form" id="login-form" action="admin.php?page=cUs_form_plugin">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                        <input type="email" name="username" class="form-control" id="inputEmail3" placeholder="Enter email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Enter password">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-info cUsCF_LoginUser" title="Welcome back ContactUs.com users" data-placement="bottom">Login</button> &nbsp; | &nbsp;
                        <button type="button" class="btn btn-warning sign-in" title="Signup to Create Your First ContactUs.com Form" data-placement="bottom"><i class="icon-user-add"></i> Create Free Account</button>
                        <span class="loading"></span>
                    </div>
                </div>

                <input type="hidden" name="action" value="cUsCF_loginAlreadyUser">
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <hr>
            <p class="advice">
                If you created an account by signing up with Facebook, you probably don’t know your password. <br/>Please click here to request a new one. <br/>
                <a href="http://www.contactus.com/login/#forgottenbox" target="_blank">I forgot my password</a>
            </p>
        </div>
    </div>
</div>