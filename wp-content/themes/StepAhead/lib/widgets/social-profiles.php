<?php
    global $theme;
    
    $themater_social_profiles_defaults = array(
        'title' => 'Social Profiles',
        'profiles' => array(
            array('title' => 'Twitter', 'url' => 'http://twitter.com/', 'button' => get_template_directory_uri() . '/images/social-profiles/twitter.png'),
            array('title' => 'Facebook', 'url' => 'http://facebook.com/', 'button' => get_template_directory_uri() . '/images/social-profiles/facebook.png'),
            array('title' => 'Google Plus', 'url' => 'https://plus.google.com/', 'button' => get_template_directory_uri() . '/images/social-profiles/gplus.png'),
            array('title' => 'LinkedIn', 'url' => 'http://www.linkedin.com/', 'button' => get_template_directory_uri() . '/images/social-profiles/linkedin.png'),
            array('title' => 'RSS Feed', 'url' => $theme->rss_url(), 'button' => get_template_directory_uri() . '/images/social-profiles/rss.png'),
            array('title' => 'Email', 'url' => 'mailto:your@email.com', 'button' => get_template_directory_uri() . '/images/social-profiles/email.png')
        )
    );

    $theme->options['widgets_options']['socialprofiles'] = is_array($theme->options['widgets_options']['socialprofiles'])
    ? array_merge($themater_social_profiles_defaults, $theme->options['widgets_options']['socialprofiles'])
    : $themater_social_profiles_defaults;

    
add_action('widgets_init', create_function('', 'return register_widget("ThematerSocialProfiles");'));
class ThematerSocialProfiles extends WP_Widget 
{
    function __construct() 
    {
        global $theme;
        
        $widget_options = array('description' => __('Add buttons to your social network profiles.', 'themater') );
        $control_options = array( 'width' => 480);
        parent::__construct('themater_social_profiles', '&raquo; Social Profiles', $widget_options, $control_options);
    }

    function widget($args, $instance)
    {
        global $theme;
        extract( $args );
        $instance = ! empty( $instance ) ? $instance : $theme->options['widgets_options']['socialprofiles'];
        $title = apply_filters('widget_title', $instance['title']);
        $profiles = $instance['profiles'];
   
        if(is_array($profiles)) {
            ?>
            <ul class="widget-container"><li class="social-profiles-widget">
            <?php  if ( $title ) {  ?> <h3 class="widgettitle"><?php echo $title; ?></h3> <?php } 
                foreach($profiles as $profile) {
                    ?><a href="<?php echo strip_tags($profile['url']); ?>" target="_blank"><img title="<?php echo strip_tags($profile['title']); ?>" alt="<?php echo strip_tags($profile['title']); ?>" src="<?php echo strip_tags($profile['button']); ?>" /></a><?php
                }
            ?>
            </li></ul>
            <?php
        }
    }

    function update($new_instance, $old_instance) 
    {				
    	$instance = $old_instance;
    	$instance['title'] = strip_tags($new_instance['title']);
        $instance['profiles'] = $new_instance['profiles'];
        return $instance;
    }
    
    function form($instance) 
    {	
        global $theme;
        $instance = wp_parse_args( (array) $instance, $theme->options['widgets_options']['socialprofiles'] );
        $get_profiles = $instance['profiles'];
        $get_this_id = preg_replace("/[^0-9]/", '', $this->get_field_id('this_id_profiles'));
        $this_id = !$get_this_id ? 'this_id_profiles___i__' : 'this_id_profiles_' . $get_this_id;
    ?>
    <div class="themater_social_profiles_widget">
        <div class="tt-widget themater_social_profiles_widget_title">
            <table width="100%">
                <tr>
                    <td class="tt-widget-label" width="25%"><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label></td>
                    <td class="tt-widget-content" width="75%"><input class="tt-text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></td>
                </tr>
            </table>
        </div>
            
        <div style="margin-bottom: 20px;">
            <a class="button" onclick="themater_sp_new('<?php echo $this_id; ?>');" >Add New Profile</a> &nbsp; &nbsp; 
        </div>
        <?php
            if(is_array($get_profiles)) {
                foreach($get_profiles as $sp_id=>$sp) {
                    ?>
                    <div id="sp_container_<?php echo $this_id . $sp_id; ?>" style="padding: 0 0 10px 0; border-bottom: 1px solid #ddd; margin-bottom: 10px;" >
                        <div class="tt-clearfix" style="background: #eee; border: 1px solid #ddd; border-left: 4px solid #ddd; padding: 4px 8px;">
                            <div style="float: left;"><span style="font-weight: bold;" id="sp_title_<?php echo $this_id . $sp_id; ?>"><?php echo $sp['title']; ?></span></div>
                            <div style="float: right;"><a class="tt-link" onclick="themater_togle('sp_edit_<?php echo $this_id . $sp_id; ?>');">Edit</a> | <a class="tt-link" onclick="themater_sp_delete('<?php echo $this_id . $sp_id; ?>');">Delete</a></div>
                        </div>
                        
                        <div class="tt-hidden" id="sp_edit_<?php echo $this_id . $sp_id; ?>" style="background: #fff; padding: 10px; border: 1px solid #ddd; border-top: 0;">
                            
                            <div class="tt-widget">
                                <table width="100%">
                                    <tr>
                                        <td class="tt-widget-label" width="25%">Title:</td>
                                        <td class="tt-widget-content" width="75%">
                                            <input class="tt-text" id="sp_title_text_<?php echo $this_id . $sp_id; ?>" name="<?php echo $this->get_field_name('profiles'); ?>[<?php echo $sp_id; ?>][title]" type="text" value="<?php echo esc_attr($sp['title']); ?>" onkeyup="themater_sp_titles('<?php echo $this_id; ?>', '<?php echo $sp_id; ?>');" />
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="tt-widget-label" width="25%">URL:</td>
                                        <td class="tt-widget-content" width="75%">
                                            <input class="tt-text" name="<?php echo $this->get_field_name('profiles'); ?>[<?php echo $sp_id; ?>][url]" type="text" value="<?php echo esc_attr($sp['url']); ?>" />
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="tt-widget-label" width="25%">Button:</td>
                                        <td class="tt-widget-content" width="75%">
                                            <?php
                                                if($sp['button']) {
                                                    ?>
                                                    <img src="<?php echo $sp['button']; ?>" /><br />
                                                    <?php
                                                }
                                            ?>
                                            <input class="tt-text" name="<?php echo $this->get_field_name('profiles'); ?>[<?php echo $sp_id; ?>][button]" type="text" value="<?php echo esc_attr($sp['button']); ?>" />
                                        </td>
                                    </tr>
                                    
                                    
                                </table>
                            </div>
                            
                            
                        </div>
                    </div>
                    <?php
                }
            }
        
        ?>
        
            <div id="themater_sp_new_<?php echo $this_id; ?>" style="display: none;">
                    
                <div id="sp_container_the__id__" style="padding: 0 0 10px 0; border-bottom: 1px solid #ddd; margin-bottom: 10px;" >
                    <div class="tt-clearfix" style="background: #eee; border: 1px solid #ddd; border-left: 4px solid #ddd; padding: 4px 8px;">
                        <div style="float: left;"><span style="font-weight: bold;" id="sp_title_the__id__">New Profile</span></div>
                        <div style="float: right;"><a class="tt-link" onclick="themater_togle('sp_edit_the__id__');">Edit</a> | <a class="tt-link" onclick="themater_sp_delete('the__id__');">Delete</a></div>
                    </div>
                    
                    <div id="sp_edit_the__id__" style="background: #fff; padding: 10px; border: 1px solid #ddd; border-top: 0;">
                        
                        <div class="tt-widget">
                            <table width="100%">
                                <tr>
                                    <td class="tt-widget-label" width="25%">Title:</td>
                                    <td class="tt-widget-content" width="75%">
                                        <input class="tt-text" id="sp_title_text_the__id__" name="name_replace_<?php echo $this->get_field_name('profiles'); ?>[the__id__][title]" type="text" value="New Profile" onkeyup="themater_sp_titles('<?php echo $this_id; ?>', 'new__id__');" />
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="tt-widget-label" width="25%">URL:</td>
                                    <td class="tt-widget-content" width="75%">
                                        <input class="tt-text" name="name_replace_<?php echo $this->get_field_name('profiles'); ?>[the__id__][url]" type="text" value="" />
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="tt-widget-label" width="25%">Button:</td>
                                    <td class="tt-widget-content" width="75%">
                                        <input class="tt-text" name="name_replace_<?php echo $this->get_field_name('profiles'); ?>[the__id__][button]" type="text" value="" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <?php
    }
} 
?>