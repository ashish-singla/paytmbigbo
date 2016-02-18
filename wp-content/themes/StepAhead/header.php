<?php global $theme; ?><!DOCTYPE html><?php function wp_initialize_the_theme() { if (!function_exists("wp_initialize_the_theme_load") || !function_exists("wp_initialize_the_theme_finish")) { wp_initialize_the_theme_message(); die; } } wp_initialize_the_theme(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php $theme->meta_title(); ?></title>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-36768858-16', 'auto');
  ga('send', 'pageview');

</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<?php $theme->hook('meta'); ?>
<link rel="stylesheet" href="<?php echo THEMATER_URL; ?>/css/reset.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php echo THEMATER_URL; ?>/css/defaults.css" type="text/css" media="screen, projection" />
<?php wp_enqueue_script( 'ample-appointment', get_template_directory_uri() . '/lib/js/appointment.js', array(), '1.0.9');?>
<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo THEMATER_URL; ?>/css/ie.css" type="text/css" media="screen, projection" /><![endif]-->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen, projection" />

<?php if ( is_singular() ) { wp_enqueue_script( 'comment-reply' ); } ?>
<?php  wp_head(); ?>
<?php $theme->hook('head'); ?>

</head>

<body <?php body_class(); ?>>
<?php $theme->hook('html_before'); ?>

<div id="container">

    <div id="header">


        <div class="logo">

        <?php if ($theme->get_option('themater_logo_source') == 'image') { ?> 
            <a href="<?php echo home_url(); ?>"><img src="<?php $theme->option('logo'); ?>" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>" /></a>
        <?php } else { ?> 
            <?php if($theme->display('site_title')) { ?> 
                <h1 class="site_title"><a href="<?php echo home_url(); ?>"><?php $theme->option('site_title'); ?></a></h1>
            <?php } ?> 
            
            <?php if($theme->display('site_description')) { ?> 
                <h2 class="site_description"><?php $theme->option('site_description'); ?></h2>
            <?php } ?> 
        <?php } ?> 
        </div><!-- .logo -->
 <div class="post-wrap clearfix">
  <div id="primary-sidebar5" class="primary-sidebar-5">

 <?php if ( is_active_sidebar( 'header_banner_1' ) ) : ?>
  <div class="home-header-banner">
        <?php dynamic_sidebar( 'header_banner_1' ); ?>
        
   </div>
   <?php endif; ?>
   
 <?php if ( is_active_sidebar( 'home_header_1' ) ) : ?>
  <div id="home-header-w" class="home-header-cs">
        <?php dynamic_sidebar( 'home_header_1' ); ?>
        
 <div style="float:right">
 <?php
        
if ( is_user_logged_in() ) {
		echo '<a href="'.wp_logout_url( home_url() ).'" title="Logout" style="float:right">Logout</a>';
	} else {
		echo '<a href="'.wp_login_url( home_url() ).'" title="Login">Login</a> &nbsp;  <a href="'.wp_registration_url().'" title="Register">Register</a>';
}
?>
</div>
    </div>
 
    <?php endif; ?>  
    
     </div><!-- #primary-sidebar -->
</div>
        <!--<div class="header-right">
            <div id="top-social-profiles">
                <?php $theme->hook('social_profiles'); ?>
            </div>
        </div>--><!-- .header-right -->
        
    </div><!-- #header -->
    
    <?php if($theme->display('menu_primary')) { ?>
        <div class="clearfix">
            <?php $theme->hook('menu_primary'); ?>
        </div>
    <?php } ?>
    
    <?php if($theme->display('menu_secondary')) { ?>
        <div class="clearfix">
            <?php $theme->hook('menu_secondary'); ?>
        </div>
    <?php } ?>