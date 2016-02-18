=== User Registration Aide ===
Contributors: Brian Novotny
Donate link: http://creative-software-design-solutions.com/
Author URI: http://creative-software-design-solutions.com/
Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
Tags: user, registration, field, register, Facebook, extra fields, profile, anti-spam, login message, login image, custom login css, admin, user management, membership, members, password change, password strength, password
Requires at least: 3.2
Tested up to: 4.3.1
Stable tag: 1.5.0.9

Adds new fields and requires new users to register additional fields (such as first name and last name) or any new field you wish to add.

Note: WordPress 3.2 or higher is required due to new error handling procedures.

== Description ==

When new users register, this plugin forces new users to register additional fields of your choosing or lets you add your own custom fields. Default available fields are:

*	First Name
*	Last Name
*	Nickname
*	Website
*	AIM screen name
*	Yahoo IM screen name
*	Jabber/Google Talk user name
*	Password

WordPress User Registration Aide Force & Add New User Fields on Registration Form, as the title implies, allows you to require more fields when a new user registers. This not only can help to stop spammers, but it can also increase your user management capabilities and services for your user base. All the new fields that you add also are added to existing users profiles, but the users will have to fill them out of course, but any new users will be required to fill out these fields if they are included in the registration process.

Another important option is that you can also add new fields to the users profile page but not require them for registration, so you can increase you user management capabilities. This is an exciting new feature for Web-masters that wish to increase contact options, communications, and information obtained from your user base.

Plugin Features:

    Easy to use
    Quickly and easily add new fields to user registration
    Add your own custom fields
    Add as many or as little of the new fields to the registration form
    Reduce bots and spammers with additional fields on the registration form
    Includes existing fields from user profile like First Name, Last Name & Nickname
    Add as many new fields you want quickly and easily
    Get better control over your user base
    New fields are added to existing users profiles!
    Increase your knowledge of, and interaction with your customers & users!
	Add Custom Logo & Messages to Registration & Login Pages!
	Add Custom Background Image or Background Color to Login & Registration Pages!
	Anti-Spam Math Problem
	Password Strength Meter
	Custom Password Strength Options to Create Your Own Password Strength Definition
	Choose custom display name fields for users by roles or for all users
	Force New Users to change password after getting password from email
	Force Existing Users to change password after specified amount of time
	Not allow same password to be used for specified number of times

Read more: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/#ixzz22CCABfOx



== Installation ==

Download the file and put it in your plugins directory.

Activate it.

Method 2 (WordPress Add New Plugin):

    Go to ‘add new’ menu under ‘plugins’ tab in your word-press admin.
    Search ‘User Registration Aide’ plugin using search option.
    Find the plugin and click ‘Install Now’ link.
    Finally click activate plug-in link to activate the plug-in.

[See Installation Instruction and Configuration information and Demo](Read more: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/#ixzz22CDaarHi)	


To configure what fields are required for new users to register an account, login to the WordPress Dashboard. Then go to the Dashboard Home Screen -> Administration → User Registration Aide → User Registration Aide - Edit New Fields

Add Additional Fields to User Registration Form Option:

This section includes a drop down box with all the existing fields in the WordPress profile, and all the additional new fields that you add, if any. None of them will show up on the registration form until you click the field you want to add to the registration form with your mouse. You can select multiple fields here by holding down the control button (CTRL) while clicking on the fields of your choice to add to the registration form.

Read more: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/#ixzz22CEi4lRR

== Frequently Asked Questions ==

= Is there a shortcode?  =

No, there currently is no shortcode as it uses the current registration/profile actions and filters for WordPress so it shows up without the need for a shortcode.

= Where are the new fields? =

First, you need to make sure you are allowing new users to register for your site which is found on the general settings tab on the admin dashboard under membership, make sure you have the Anyone can register checkbox checked.

Then, if you don't know where it is the registration form is on the login form, as there should be a register link at the bottom of the login form page if you are allowing anyone to sign up. Click on the register link to see the registration form and your new extra fields.
 
== Screenshots ==

1. Plugin Page
2. WordPress Administration Dashboard
3. Admin Page 1 Registration Fields
4. Admin Page 2 Edit New Fields
5. Admin Page 3 Registration Form Options
6. Admin Page 4 Registration Form Messages & CSS Options

== Changelog ==

1.5.0.9

a) fixed bug

1.5.0.8

a) fixed bug on registration form

1.5.0.7

a) modified anti-bot-spammer protection for better success to keep bots away

b) updated bug with email notifications

c) added option to add optional fields for registration form

d) added option to use * ( Asterisk ) for required fields on Registration Form or take it off

e) updated languages files and added Spanish translation

1.5.0.6

a) updated email notifications

1.5.0.5

a) bug

1.5.0.4

a) Fixed Password Field Issues

1.5.0.3

a) bug fix 

1.5.0.2

a) Added nonce to Password Change Page

b) Removed url display at top if page (From testing)

1.5.0.1

a) Changed a few labels on password change options section

1.5.0.0

a) Added Password Change Shortcode to have front end custom password change page.

b) Added password change to force new users and existing users to change password after they get a new password in email or they haven't changed password in specified amount of time. 

c) Can't use old passwords for a specified amount of time.

d) Redirect to Change Password to SSL if available

e) Remove password fields form profile page for non-admins

f) Disable Lost Password Option from login page for non-admins

1.4.0.2

a) svn bug

1.4.0.1

a) math problem bug

1.4.0.0

a) added option to change display names for users by select role or all roles and choice of fields

b) added custom css options for those that find the interface not to their liking

c) changed registration form Bio field so that it will now be a text area instead of a text box input

d) deleted some old code that was not needed any more and cluttering up files

e) make some new adjustments to account for Theme My Login Plugin

1.3.7.4

a) Fixed math problem bug where it wouldn't update math problem settings correctly

1.3.7.3

a) Fixed Potential Dashboard Widget Issue

1.3.7.2

a) Updated Registration/Login form custom logo code

1.3.7

a) Minor bug fixes and cleanup

1.3.6.1

a) Fixed potential bug with anti-spam math problem allowing user to select no math operator (+, -, /, *) while using the anti-spam math problem for registration.

1.3.6

a) Added options to modify WordPress dashboard widget fields.

b) Added options to modify math problems so you can choose which operators to use and made the random numbers smaller so it won't be as difficult.

1.3.5

a) Added options to modify password strength requirements, either use default or make custom or use none.

1.3.4

a) Updated bug for email to new users who enter own password to not email password.

1.3.3

a) Updated email to new users who enter own password to not email password.

1.3.2

a) Redid Password Meter

b) renamed .mo file

1.3.1

a) Added .pot and .mo files to languages

1.3.0

a) updated interfaces

b) added anti-spammer math question to registration form

c) added dashboard widget

d) added option to add new fields to administrators add new user page but you have to hack the core code at this time to do that.

e) fixed a few bugs

f) updated comments and code

1.2.8

a) fixed another potential bug with database options update & with agreement checkbox

1.2.7

a) fixed another potential bug with database options update & with agreement checkbox

1.2.6

a) fixed potential bug with database options update

1.2.5

a) Fixed bug with login/registration form for emailing password & password reset

1.2.4

Thirteenth Revision minor

a) Fixed delete new field bug

b) Combined code for headers for quicker loads

c) Got rid of 'unexpected characters' when plugin is activated bug

d) Made admin pages easier to view

e) Added login-registration page link shadows options so you can either remove them or adjust them to your liking as the old version looked a little funky to me, so if it does to you to you can now either change the size and color or just don't show the shadow at all.

1.2.3

Twelfth Revision minor

a) Fixed registration form bug

1.2.2 

Eleventh Revision Minor

a) Found missing errors script 
1.2.1 

Tenth Revision Minor

a) Somehow errors for other plugins were showing up even though I had those error reportings commented out so I deleted them

1.2.0

Ninth Revision - Major Revision

a) Updated options and items and consolidated database usage

b) Added Website to existing fields

c) Added Password and Password Confirm to existing fields

d) Added FUNCTIONAL Password Strength Meter!

e) Users can login right after registering with new password!

f) Option to change message to user when registering if they can enter their own password instead of default a password will be mailed to you

g) Added option to change login/registration page logo and background image or color to your own logo, image, or color!

h) Change the label/nav colors of login/registration forms so they better work with your custom background images/colors

i) Added option to add message and check box and link to agreement/policy form if user must submit and agree to certain conditions when registering
	for website. (User Requested)

j) Other code upgrades and modifications and button enhancements

1.1.4 

Eighth Revision – Minor Revision

a) Fixed minor bug with non-admin users editing profile

1.1.3

Seventh Revision - Minor Revision

a) Fixed minor bug with error reporting

1.1.2

Sixth Revision - Minor Revision

a) Fixed tabbing index in registration page

b) Minor updates and fixed potential problem with profile updates

1.1.1

Fifth Revision
Minor Revision

a) Fixed some minor bugs that appeared after release of 1.1.0

1.1.0

Major Revision Final 8/14/2012 5PM EST

a) Added the option to delete additional new fields that you added and don't want or need

b) Added the option to change the order of the new fields at any time which will be reflected on both the registration page and the profile page

c) Changed the looks somewhat by adding tables to make it more orderly

d) Fix some minor bugs and oversights on my part

e) Made the code more easy to read and added more comments

f) Added more security features like nonce and updated filtering new content being added and also checking user levels and making sure they have access to features

g) Changed menu location to it's own menu instead of in the users menu, giving me the option to add more pages and located it right below the users menu tab

h) Upgraded the delete plugin function so it will delete all the options, so if you aren't sure whether you'll use it again or not, you may want to avoid deleting it until you are sure otherwise all user inputs into new fields will be lost as well as all your options, settings and new fields

i) Added internationalization, so now users from foreign countries should have translated versions available, at least if I did it right, I hope so, if not give us a holler!

1.0.2

Third Version 8/1/2012 9 PM EST

Fixed bug of open line between PHP stop and start points which created header errors

1.0.1		
	
Second version. Final 8/1/2012

Added a few updates to looks, more importantly fixed profile update bug so it works with new fields now and updates them properly. Also added filters to new user registrations for new fields.

1.0.0		
	
First version. Final 7/31/2012

New improved version for Force Users Field Registrations with some added features. First release stable, has add new field options, add existing and new fields to user registration, adds all new fields to current users profile pages, and gives you the option to require or not require when new members sign-up.




























