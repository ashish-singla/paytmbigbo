<?php 

require_once('../../../../wp-load.php');
header("Content-type: text/css"); 

// Get dynamic options
	$options = get_option('csds_userRegAide_Options');
	$border_color = $options['border-color'];
	$background_color = $options['tbl_background_color'];
	$color = $options['tbl_color'];
	$border_width = $options['tbl_border-width'];
	$border_style = $options['border-style'];
	$border_space = $options['border-spacing'];
	$border_collapse = $options['border-collapse'];
	$div_color = $options['div_stuffbox_bckgrd_color'];
	$padding = $options['tbl_padding'];

/**
 * Custom CSS added for admin option pages interface displays
 * @since 1.4.0.0
 * @updated 1.4.0.1
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/
?>
/* Need for public version for some reason can't figure out yet to avoid whitespaces on top of body

*/

.wp-toolbar
{
margin-top:-13px;
}

div.my-updated,
div.my-error {
	padding: 0 0.6em;
	margin: 5px 15px 2px;
}

div.my-updated p,
div.my-error p {
	margin: 0.5em 0;
	padding: 2px;
}

.wrap div.my-updated,
.wrap div.my-error,
.media-upload-form div.error {
	margin: 5px 0 15px;
}

div.my-updated {
	border-left: 4px solid #7ad03a;
	padding: 1px 12px;
	background-color: #fff;
	-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,0.1);
	box-shadow: 0 1px 1px 0 rgba(0,0,0,0.1);
}

div.my-error {
	border-left: 4px solid #dd3d36;
	background: #fff;
	-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,0.1);
	box-shadow: 0 1px 1px 0 rgba(0,0,0,0.1);
	padding: 1px 12px;
}

div.reset-xwrd
{
text-align:center;
}

input.reset-xwrd
{
border-style: solid;
border-width: 1px;
border-color:black;
}

table.style
{
width:95%;
margin-left:2.5%;
margin-right:2.5%;
border-style: <?php echo $border_style; ?>;
border-collapse: <?php echo $border_collapse; ?>;
border-spacing: <?php echo $border_space; ?>;
border-width: <?php echo $border_width; ?>;
border-color: <?php echo $border_color; ?>;
}

table.style th{
width: 25%;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding: <?php echo $padding; ?>;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
font-family: 'Russo One', sans-serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;
}

table.style td{
vertical-align:middle;
width: 25%;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

td.displayNameLast
{
width: 50%;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
border-width: <?php echo $border_width; ?>;
border-spacing: <?php echo $border_space; ?>;
padding:1px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

table.displayName td 
{
width: 33%;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
border-width: <?php echo $border_width; ?>;
padding:1px;
border-spacing: <?php echo $border_space; ?>;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

table.displayName
{
width:95%;
margin-left:2.5%;
margin-right:2.5%;
padding:1px;
border-style: <?php echo $border_style; ?>;
border-collapse: <?php echo $border_collapse; ?>;
border-spacing: <?php echo $border_space; ?>;
border-width: <?php echo $border_width; ?>;
border-color: <?php echo $border_color; ?>;
}

table.displayName th{
width: 33%;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
font-family: 'Russo One', sans-serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;
}

#pass-strength-result{
	padding-top: 3px;
	padding-right: 5px;
	padding-bottom: 3px;
	padding-left: 5px;
	margin-top: 3px;
	text-align: center;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	display: block;
	width: 93%;
}

table.adminPage th{
width: 50%;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
font-family: 'Russo One', sans-serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;
}

table.adminPageMenu th {
width: 25%;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 500;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;

}

div.message{
text-align: center;
font-size: 16px; 
font-weight:bold;
}

div #message
{
text-align: center;
font-size: 16px; 
font-weight:bold;
}

table.adminPage
{
width:95%;
margin-left:2.5%;
margin-right:2.5%;
border-style: <?php echo $border_style; ?>;
border-collapse: <?php echo $border_collapse; ?>;
border-spacing: <?php echo $border_space; ?>;
border-width: <?php echo $border_width; ?>;
border-color: <?php echo $border_color; ?>;
}

table.adminPageMenu
{
width:98%;
margin-left:1%;
margin-right:1%;
/*border-style: <?php echo $border_style; ?>;
border-collapse: <?php echo $border_collapse; ?>;
border-spacing: <?php echo $border_space; ?>;
border-width: <?php echo $border_width; ?>;
border-color: <?php echo $border_color; ?>;*/
}

.adminPageMenu a:link
{
color:#0000FF;
text-decoration: none;
}

.adminPageMenu a:hover
{
color:#FF0000;
text-decoration: underline;
}

.adminPageMenu a:active
{
color:#0000CC;
text-decoration: underline;
}

.adminPageMenu a:visited
{
color:#666666;
text-decoration: none;
}

table.newFields
{
width:95%;
margin-left:2.5%;
margin-right:2.5%;
border-style: <?php echo $border_style; ?>;
border-collapse: <?php echo $border_collapse; ?>;
border-spacing: <?php echo $border_space; ?>;
border-width: <?php echo $border_width; ?>;
border-color: <?php echo $border_color; ?>;
}

table.newFields td
{
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;

}

table.newFields th
{
width: 33.3%;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
font-family: 'Russo One', sans-serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;
}

table.newFields
{
width:95%;
margin-left:2.5%;
margin-right:2.5%;
border-style: <?php echo $border_style; ?>;
border-collapse: <?php echo $border_collapse; ?>;
border-spacing: <?php echo $border_space; ?>;
border-width: <?php echo $border_width; ?>;
border-color: <?php echo $border_color; ?>;
}

table.refForm
{
width:95%;
margin-left:2.5%;
margin-right:2.5%;
border-style: <?php echo $border_style; ?>;
border-collapse: <?php echo $border_collapse; ?>;
border-spacing: <?php echo $border_space; ?>;
border-width: <?php echo $border_width; ?>;
border-color: <?php echo $border_color; ?>;
}

table.adminPage_Dash 
{
width:95%;
margin-left:2.5%;
margin-right:2.5%;
border-style: <?php echo $border_style; ?>;
border-collapse: <?php echo $border_collapse; ?>;
border-spacing: <?php echo $border_space; ?>;
border-width: <?php echo $border_width; ?>;
border-color: <?php echo $border_color; ?>;
}

table.adminPage_Dash td
{
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 12px;
font-weight: 500;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

table.adminPage_Dash th
{
width: 50%;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
font-family: 'Russo One', sans-serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;
}

td.email_notification
{
height: 85px;
}

table.regForm td
{
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 12px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

table.regForm th{
width: 50%;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
font-family: 'Russo One', sans-serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;
text-align: center;
}

textarea.regForm
{
border: 1px solid buttonshadow;
padding: 1%;
width: 98%;
vertical-align: top;
height: 50px;
}

textarea.regFormEmail
{
border: 1px solid buttonshadow;
padding: 1%;
width: 98%;
vertical-align: top;
height: 75px;
}

textarea.regFormMulti
{
border: 1px solid buttonshadow;
padding: 1%;
width: 98%;
vertical-align: top;
height: 50px;

}

textarea.regFormMulti2
{
border: 1px solid buttonshadow;
padding: 1%;
width: 98%;
vertical-align: top;
height: 65px;

}

th.adminsPage
{

}

table.adminPage td 
{
border-width:1px;
padding:1px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

table.csds_support
{
width:95%;
margin-left:2.5%;
margin-right:2.5%;
border-style: <?php echo $border_style; ?>;
border-collapse: <?php echo $border_collapse; ?>;
border-spacing: <?php echo $border_space; ?>;
border-width: <?php echo $border_width; ?>;
border-color: <?php echo $border_color; ?>;
}

table.csds_support td
{
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 12px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
text-align: center;
}

table.csds_support th
{
background-color: <?php echo $background_color; ?>;
color: #000000 !important;
font-size: 16px;
font-weight: 900;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
font-family: 'Russo One', sans-serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;
text-align: center;
}

table.csds_support a
{
color: #000000 !important;
text-decoration: underline !important;
}

table.csds_support a:hover
{
color: #0000CC !important;
text-decoration: underline !important;
}

h4.uraH4
{
text-align: center;
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-weight: 800;
font-size: 16px;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

h4.regForm
{
background-color: #CCCCFF !important;
text-align: center;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 500;
font-family: 'Russo One', sans-serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1.5px -1.5px 1.5px #fff, 1.5px 1.5px 1.5px #000;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

h3.uraH3
{
text-align: center;
color: <?php echo $color; ?>;
font-weight: 900;
}

h3.adminPage
{
background-color: <?php echo $background_color; ?>;
text-align: center;
color: <?php echo $color; ?>;
}

h2.support
{
text-align: center;
color: <?php echo $color; ?>;
font-weight: 700;
}

h2.adminPage
{
background-color: <?php echo $div_color; ?>;
text-align: center;
color: <?php echo $color; ?>;
font-weight: 700;
font-family: 'Times', serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1.5px -1.5px 1.5px #fff, 1.5px 1.5px 1.5px #000;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

p.adminPage
{
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
font-family: 'Russo One', sans-serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;
}

p.editFields
{
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

p.deleteFields
{
background-color: <?php echo $background_color; ?>;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;

}

p.adminPage select
{
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
border-radius: 5;
}

select.fieldOrder 
{
border-width: <?php echo $border_width; ?>;
padding:2px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
border-radius: 5;
line-height: 1;
height: 31px;
}

select.deleteFields
{
border-width: <?php echo $border_width; ?>;
padding:2px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
border-radius: 5;
line-height: 1;
height: 100px;
}

p.fieldOrder
{
text-align: center;
}

p.addNewField
{
background-color: <?php echo $background_color; ?>;
text-align: center;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

td.fieldName
{
text-align: center;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
}

td.fieldOrder
{
text-align: center;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: 700;
}

div.stuffbox
{
background-color: <?php echo $div_color; ?>;
text-align: center;
color: #000000;
/*font-size: 16px;
font-weight: 500;
font-family: 'Russo One', sans-serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1.0px -1.0px 1.0px #fff, 1.0px 1.0px 1.0px #000;
*/
border-width: <?php echo $border_width; ?>;
padding:5px;
border-style: <?php echo $border_style; ?>;
border-color: <?php echo $border_color; ?>;
}

span.regForm
{
background-color: #CCCCFF !important;
text-align: center;
color: <?php echo $color; ?>;
font-size: 16px;
font-weight: normal;
font-family: 'Georgia', serif;
font-effect: engrave;
text-decoration: none;
text-shadow: -1.0px -1.0px 1.0px #fff, 1.0px 1.0px 1.0px #000;
}

input.regFormRedirect
{
width: 80%;
}
