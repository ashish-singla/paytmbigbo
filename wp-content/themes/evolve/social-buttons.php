<?php 
$evolve_show_rss = evolve_get_option('evl_show_rss','1');
$evolve_rss_feed = evolve_get_option('evl_rss_feed','');
$evolve_newsletter = evolve_get_option('evl_newsletter','');
$evolve_facebook = evolve_get_option('evl_facebook','');
$evolve_twitter_id = evolve_get_option('evl_twitter_id','');
$evolve_googleplus = evolve_get_option('evl_googleplus','');
$evolve_instagram = evolve_get_option('evl_instagram','');
$evolve_skype = evolve_get_option('evl_skype','');
$evolve_youtube = evolve_get_option('evl_youtube','');
$evolve_flickr = evolve_get_option('evl_flickr','');
$evolve_linkedin = evolve_get_option('evl_linkedin','');
$evolve_pinterest = evolve_get_option('evl_pinterest','');
$evolve_tumblr = evolve_get_option('evl_tumblr','');
?>   

<ul class="sc_menu">

<?php if ($evolve_show_rss == '1') { ?>   
<li><a target="_blank" href="<?php if ($evolve_rss_feed != "" ) { echo $evolve_rss_feed; } else { bloginfo( 'rss_url' ); } ?>" class="tipsytext" id="rss" original-title="<?php _e( 'RSS Feed', 'evolve' ); ?>"><i class="fa fa-rss"></i></a></li>
<?php } ?>

<?php 
  if (!empty($evolve_newsletter)) { ?>
<li><a target="_blank" href="<?php if ($evolve_newsletter != "" ) echo $evolve_newsletter; ?>" class="tipsytext" id="email-newsletter" original-title="<?php _e( 'Newsletter', 'evolve' ); ?>"><i class="fa fa-envelope-o"></i></a></li><?php } else { ?><?php } ?>

<?php 
  if (!empty($evolve_facebook)) { ?>
<li><a target="_blank" href="<?php if ($evolve_facebook == "" ) $evolve_facebook = $default_facebook;echo esc_attr($evolve_facebook);?>" class="tipsytext" id="facebook" original-title="<?php _e( 'Facebook', 'evolve' ); ?>"><i class="fa fa-facebook"></i></a></li><?php } else { ?><?php } ?>

<?php 
  if (!empty($evolve_twitter_id)) { ?>
<li><a target="_blank" href="<?php if ($evolve_twitter_id == "" ) $evolve_twitter_id = $default_twitter_id;echo esc_attr($evolve_twitter_id);?>" class="tipsytext" id="twitter" original-title="<?php _e( 'Twitter', 'evolve' ); ?>"><i class="fa fa-twitter"></i></a></li><?php } else { ?><?php } ?>

<?php 
  if (!empty($evolve_googleplus)) { ?>
<li><a target="_blank" href="<?php if ($evolve_googleplus != "" ) echo $evolve_googleplus; ?>" class="tipsytext" id="plus" original-title="<?php _e( 'Google Plus', 'evolve' ); ?>"><i class="fa fa-google-plus-square"></i></a></li><?php } else { ?><?php } ?>

<?php 
  if (!empty($evolve_instagram)) { ?>
<li><a target="_blank" href="<?php if ($evolve_instagram != "" ) echo $evolve_instagram; ?>" class="tipsytext" id="instagram" original-title="<?php _e( 'Instagram', 'evolve' ); ?>"><i class="fa fa-instagram"></i></a></li><?php } else { ?><?php } ?>

<?php 
  if (!empty($evolve_skype)) { ?>
<li><a href="skype:<?php if ($evolve_skype != "" ) echo $evolve_skype; ?>?call" class="tipsytext" id="skype" original-title="<?php _e( 'Skype', 'evolve' ); ?>"><i class="fa fa-skype"></i></a></li><?php } else { ?><?php } ?>

<?php 
  if (!empty($evolve_youtube)) { ?>
<li><a target="_blank" href="<?php if ($evolve_youtube != "" ) echo $evolve_youtube; ?>" class="tipsytext" id="youtube" original-title="<?php _e( 'YouTube', 'evolve' ); ?>"><i class="fa fa-youtube"></i></a></li><?php } else { ?><?php } ?>

<?php 
  if (!empty($evolve_flickr)) { ?>
<li><a target="_blank" href="<?php if ($evolve_flickr != "" ) echo $evolve_flickr; ?>" class="tipsytext" id="flickr" original-title="<?php _e( 'Flickr', 'evolve' ); ?>"><i class="fa fa-flickr"></i></a></li><?php } else { ?><?php } ?>

<?php 
  if (!empty($evolve_linkedin)) { ?>
<li><a target="_blank" href="<?php if ($evolve_linkedin != "" ) echo $evolve_linkedin; ?>" class="tipsytext" id="linkedin" original-title="<?php _e( 'LinkedIn', 'evolve' ); ?>"><i class="fa fa-linkedin"></i></a></li><?php } else { ?><?php } ?>

<?php 
  if (!empty($evolve_pinterest)) { ?>
<li><a target="_blank" href="<?php if ($evolve_pinterest != "" ) echo $evolve_pinterest; ?>" class="tipsytext" id="pinterest" original-title="<?php _e( 'Pinterest', 'evolve' ); ?>"><i class="fa fa-pinterest"></i></a></li><?php } else { ?><?php } ?>

<?php
  if (!empty($evolve_tumblr)) { ?>
<li><a target="_blank" href="<?php if ($evolve_tumblr != "" ) echo $evolve_tumblr; ?>" class="tipsytext" id="tumblr" original-title="<?php _e( 'Tumblr', 'evolve' ); ?>"><i class="fa fa-tumblr"></i></a></li><?php } else { ?><?php } ?>

</ul>