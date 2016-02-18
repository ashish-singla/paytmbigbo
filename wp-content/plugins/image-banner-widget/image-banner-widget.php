<?php 
/*
Plugin Name: Image Banner Widget
Plugin URI: http://shailan.com/wordpress/plugins/image-banner-widget/
Description: Add Images and Banners to your sidebars easily! Add title, link, alternative text, even set the link targets with one widget. No coding required! <em><u>Visit <a href="shailan.com">shailan.com</a> for more widgets and plugins.</u></em>
Version: 1.4.2
Author: Matt Say
Author URI: http://shailan.com
*/

define('SHAILAN_IBW_VERSION','1.4.2');

// Register widget
add_action('widgets_init', create_function('', 'return register_widget("shailan_BannerWidget");'));

// Hook up styles & scripts
add_action('admin_print_scripts', 'ibw_admin_scripts'); 
add_action('admin_print_styles', 'ibw_admin_styles'); 

// The Class
if(!class_exists('shailan_BannerWidget')){
	class shailan_BannerWidget extends WP_Widget {
		/** constructor */
		function shailan_BannerWidget() {
			$widget_ops = array('classname' => 'shailan_banner_widget', 'description' => __( 'Image only banner' ) );
			$this->WP_Widget('banner', __('Image / Banner Widget'), $widget_ops);
			$this->alt_option_name = 'widget_banner';	
			
			if(is_admin()){
				wp_enqueue_script('image-banner-scripts', WP_PLUGIN_URL . '/image-banner-widget/admin.js', 'jQuery', '', TRUE );
				wp_enqueue_style('image-banner-styles', WP_PLUGIN_URL . '/image-banner-widget/admin.css');
			}
			
			$this->help_url = "http://shailan.com/wordpress/plugins/image-banner-widget/help/";
			
			$this->widget_defaults = array(
				'url' => 'http://',
				'link'	=> 'http://',
				'alt' => '',
				'title' => '',
				'image_title' => '',
				'category' => 'shailan-show-all-categories',
				'home' => 'on',
				'autofit' => 'on',
				'target' => '_self'
			);
			
		}

		/** @see WP_Widget::widget */
		function widget($args, $instance) {	
			global $post;
			global $wpdb, $wp_locale, $wp_query;
			
			extract( $args );
			$widget_options = wp_parse_args( $instance, $this->widget_defaults );
			extract( $widget_options, EXTR_SKIP );
		
			$c1 = ( is_home() && ( $home == 'on' ) ); // is home and home is selected
			$c2 = ((is_category() || is_single() || is_page()) && $category == 'shailan-show-all-categories');
			$c3 = (is_home() && $category == 'shailan-home-only');
			$c4 = (is_single() && in_category( $category, $post->ID ));
			$c5 = (is_category($category));
			$c6 = is_page($category);
			
			if ( get_category_by_slug( $category ) ){
				$exp = get_category_by_slug( $category )->cat_name . " Category";
			} elseif( $category == "shailan-home-only" ){
				$exp = "Homepage only";
			} elseif( $category == "shailan-show-all-categories" ){
				$exp = "Show on all categories";
			}
			
			if( is_numeric( $category) ) {
				$exp = get_the_title($category) . " (#".$category.")";
			}
			
			if( $c1 || $c2 || $c3 || $c4 || $c5 || $c6 ){	
			
			echo $before_widget; ?>
			
			
			<!-- Image Banner Widget by shailan v<?php echo SHAILAN_IBW_VERSION; ?> on WP<?php bloginfo('version'); ?>-->
			<?php if( $link != "http://" ) { ?><a href="<?php echo $link; ?>" target="<?php echo $target ?>" ><?php } ?><img src="<?php echo $url; ?>" alt="<?php echo $alt; ?>" title="<?php echo $image_title; ?>" class="banner-image" <?php if( $autofit=='on' ){ echo 'width="100%"'; }?> /><?php if( $link != "http://" ) { ?></a><?php } ?>
			<!-- /Image Banner Widget -->
			<?php echo $after_widget; ?>
			
			<?php if ( $title )
			?><?php if( $link != "http://" ) { ?><a href="<?php echo $link; ?>" target="<?php echo $target ?>" ><?php } ?><h2 class="title" align="center"><?= $title; ?></h2><?php if( $link != "http://" ) { ?></a><?php } //echo $before_title . $title . $after_title; 
				?>
			<?php
			} else {
				if( current_user_can('administrator') ){
					echo $before_widget;
						echo "<div class=\"ibw-box\" style=\"margin:10px 0; border:1px solid #eee; padding:10px; border-radius: 8px; font-size: 11px; line-height: 1.3em; \"><span><strong>Admin notice: <a href=\"http://shailan.com/wordpress/plugins/image-banner-widget/help/#admin-notice\">(?)</a></strong><br /> Image banner widget selected to be displayed on <strong>" . $exp . "</strong>. ";
						
						if( $home == 'off' ){
							echo "Homepage view is <strong>disabled</strong> on <em>advanced settings</em>. ";
						}
						
						echo "You can  change display options under Advanced settings on <a href=\"". admin_url('widgets.php') ."\">widget panel</a>.</span> </div>";
					echo $after_widget; 
				}
			}
		}

		/** @see WP_Widget::update */
		function update($new_instance, $old_instance) {	
		
			if($new_instance['home'] == false) { $new_instance['home'] = 'off'; }
			if($new_instance['autofit'] == false) { $new_instance['autofit'] = 'off'; }
			
			return $new_instance;
		}

		/** @see WP_Widget::form */
		function form($instance) {
			$widget_options = wp_parse_args( $instance, $this->widget_defaults );
			extract( $widget_options, EXTR_SKIP );
			
			$home = (bool) ('on'==$home);
			$autofit = (bool) ('on'==$autofit);
							
			if( $url != 'http://' ){
			?>
				<p><div class="ibw-thumb">
					<div class="ibw-overlay">
						<span>Preview</span>
					</div>
					
					<img src="<?php echo $url ?>" />
				</div></p>
				
			<?php } ?>
			
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:'); ?> <?php $this->help_link('title'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
				
			<p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Image URL:', 'image-banner-widget'); ?>
				<?php $this->help_link('image-url'); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" /></label></p> 
				<p class="upload-button-wrap"><input id="<?php echo $this->get_field_id('upload_button'); ?>" onclick="javascript:formfield = jQuery('#<?php echo $this->get_field_id('url'); ?>').attr('name'); tb_show('','media-upload.php?type=image&amp;TB_iframe=true'); return false;" value="Upload/Select from Media" type="button" class="upload-button" /></p>
				
			<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link:', 'image-banner-widget'); ?> 
				<?php $this->help_link('link-url'); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" /></label></p>
				
			<p><label for="<?php echo $this->get_field_id('target'); ?>"><?php _e('Link Target:'); ?> 
				<select name="<?php echo $this->get_field_name('target'); ?>" id="<?php echo $this->get_field_id('target'); ?>" > 	
					<option value="_self" <?php if('_self' == $target){ echo "selected=\"selected\""; } ?> >Current frame</option>		
					<option value="_blank" <?php if('_blank' == $target){ echo "selected=\"selected\""; } ?> >New page/tab</option>
					<option value="_top" <?php if('_top' == $target){ echo "selected=\"selected\""; } ?> >Top frame</option>
				</select></label> <?php $this->help_link('target'); ?> </p>
				
			<p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Display for:'); ?>
				<select name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>" > 	
					<option value="shailan-show-all-categories" <?php if('shailan-show-all-categories' == $category){ echo "selected=\"selected\""; } ?> >All categories</option>		
					<option value="shailan-home-only" <?php if('shailan-home-only' == $category){ echo "selected=\"selected\""; } ?> >Homepage only</option>		
					 <?php 
					   $options = "";
					  $categories = get_categories(''); 
					  foreach ($categories as $cat) {  
						$options .= "\n" . '<option value="'.$cat->category_nicename .'" '. ( $cat->category_nicename == $category ? ' selected="selected"' : '' ) .'>';
						$options .= $cat->cat_name;
						$options .= '</option>\n';
						//echo $option;
					  }
					  
						$r = array(
							'depth' => 0, 'child_of' => 0,
							'selected' => 0, 'echo' => 1,
							'name' => 'page_id', 'id' => '',
							'show_option_none' => '', 'show_option_no_change' => '',
							'option_none_value' => ''
						);
						$pages = get_pages($r);  
					  $options .= walk_page_dropdown_tree($pages, 0, $r);
					  
					  echo $options;
					 ?>
					
					</select></label> <?php $this->help_link('display-for'); ?> </p>

			<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('home'); ?>" name="<?php echo $this->get_field_name('home'); ?>"<?php checked( $home ); ?> />
			<label for="<?php echo $this->get_field_id('home'); ?>"><?php _e( 'Display on homepage'); ?> <?php $this->help_link('display'); ?></label></p>

			<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('autofit'); ?>" name="<?php echo $this->get_field_name('autofit'); ?>"<?php checked( $autofit ); ?> />
			<label for="<?php echo $this->get_field_id('autofit'); ?>"><?php _e( 'Auto-fit to container width' , 'image-banner-widget'); ?> <?php $this->help_link('autofit'); ?></label></p>
			
			<div class="ibw-advanced-toggle"><span onclick="jQuery(this).next('div').slideToggle();" class="expander" ><?php _e('Advanced Settings..', 'image-banner-widget'); ?></span>
			<div class="ibw-advanced-options" style="display:none;">
			
			<p><label for="<?php echo $this->get_field_id('alt'); ?>"><?php _e('Alternative Text:', 'image-banner-widget'); ?> 
				<?php $this->help_link('alternate-text'); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id('alt'); ?>" name="<?php echo $this->get_field_name('alt'); ?>" type="text" value="<?php echo $alt; ?>" /></label></p>
				
			<p><label for="<?php echo $this->get_field_id('image_title'); ?>"><?php _e('Image Title:', 'image-banner-widget'); ?>
				<?php $this->help_link('image_title'); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id('image_title'); ?>" name="<?php echo $this->get_field_name('image_title'); ?>" type="text" value="<?php echo $image_title; ?>" /></label></p>
			
			

			</div>
			</div>
			
		<div class="widget-control-actions">
			<p><small>Powered by <a href="http://shailan.com/wordpress/plugins/image-banner-widget" title="Wordpress Tips and tricks, Freelancing, Web Design">Shailan.com</a> | <a href="http://shailan.com/wordpress/" title="Get more wordpress widgets and themes" target="_blank" >Get more..</a></small></p>
		</div>
		
		<script>
jQuery(document).ready(function()
{

	jQuery('#<?php echo $this->get_field_id('upload_button'); ?>').click(function() {
	 formfield = jQuery('#upload_image').attr('name');
	 tb_show('','media-upload.php?type=image&amp;TB_iframe=true');
	 return false;
	});
	// send url back to plugin editor

	window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 jQuery('#<?php echo $this->get_field_id('url'); ?>').val(imgurl);
		 tb_remove();
	}
  
});
		</script>
		
				<div class="clear"></div>
				
			<?php 
			
		}
		
		function help_link($key, $text = '(?)'){
			echo '<a href="'.$this->help_url.'#' . $key. '" target="_blank" class="help-link">' . $text . '</a>';
 		}
		
	} // class shailan_BannerWidget

}


function ibw_admin_scripts() { 
	wp_enqueue_script('jquery');
	wp_enqueue_script('media-upload'); 
	wp_enqueue_script('thickbox'); 
}  

function ibw_admin_styles() { 
	wp_enqueue_style('thickbox'); 
}  

