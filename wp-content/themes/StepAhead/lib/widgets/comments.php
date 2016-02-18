<?php

global $theme;

$themater_comments_defaults = array(
    'title' => 'Recent Comments',
    'comments_number' => '5',
    'display_author' => 'true',
    'display_comment' => 'true',
    'display_avatar' => 'true',
    'read_more_text' => '&raquo;',
    'comment_length' => '26',
    'avatar_size' => '32',
    'avatar_align' => 'alignleft'
);

$theme->options['widgets_options']['comments'] =  is_array($theme->options['widgets_options']['comments'])
    ? array_merge($themater_comments_defaults, $theme->options['widgets_options']['comments'])
    : $themater_comments_defaults;
        
add_action('widgets_init', create_function('', 'return register_widget("ThematerComments");'));

class ThematerComments extends WP_Widget 
{
    function __construct() 
    {
        $widget_options = array('description' => __('Advanced widget for displaying the recent posts with avatars', 'themater') );
        $control_options = array( 'width' => 400);
		$this->WP_Widget('themater_comments', '&raquo; Comments with Avatars', $widget_options, $control_options);
    }

    function widget($args, $instance)
    {
        global $wpdb, $theme;
        extract( $args );
        $instance = ! empty( $instance ) ? $instance : $theme->options['widgets_options']['comments'];
        $title = apply_filters('widget_title', $instance['title']);
        
    	$comments_number = $instance['comments_number'];
        
    	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, 
    			SUBSTRING(comment_content,1,50) AS com_excerpt 
    		FROM $wpdb->comments 
    		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) 
    		WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' 
    		ORDER BY comment_date_gmt DESC 
    		LIMIT $comments_number";
    	$comments = $wpdb->get_results($sql);
        ?>
        <ul class="widget-container"><li class="comments-widget">
        <?php  if ( $title ) {  ?> <h3 class="widgettitle"><?php echo $title; ?></h3> <?php }  ?>
            <ul>
                <?php
                    foreach ($comments as $comment) {
                    ?>
                        <li class="clearfix">
                            <?php 
                                $get_the_peralink = get_permalink($comment->ID)  . "#comment-" . $comment->comment_ID;
                                
                                if( $instance['display_avatar']) { ?>
                                    <a href="<?php echo $get_the_peralink; ?>" title="<?php echo $comment->post_title; ?>"><?php echo str_replace ("class='avatar", "class='avatar comments-widget-avatar " . $instance['avatar_align'], get_avatar($comment->comment_author_email,$instance['avatar_size']) ) ; ?></a><?php 
                                } 
                            
                                if($instance['display_comment'] || $instance['display_read_more'] || $instance['display_avatar']) { ?> 
                                    <div class="comments-widget-entry">
                                    <?php 
                                        if($instance['display_author']) { ?>
                                            <a href="<?php echo $get_the_peralink; ?>" class="comments-widget-author"><?php echo $comment->comment_author; ?></a>: <?php
                                        }
                                        
                                        if($instance['display_comment']) { 
                                            $get_the_comment_length = $instance['comment_length'] ? $instance['comment_length'] : 16;
                                            echo $theme->shorten(strip_tags($comment->com_excerpt), $get_the_comment_length); 
                                        }
                                        
                                        if($instance['read_more_text']) { ?> 
                                            <a href="<?php echo $get_the_peralink; ?>" class="comments-widget-more"><?php echo $instance['read_more_text']; ?></a><?php
                                        }
                                    ?>
                                    </div><?php
                                }
                                
                            ?>
                        </li>
                    <?php
                	}
                ?>
            </ul>
        </li></ul>
     <?php
    }

    function update($new_instance, $old_instance) 
    {				
    	$instance = $old_instance;
    	$instance['title'] = strip_tags($new_instance['title']);
        $instance['comments_number'] = strip_tags($new_instance['comments_number']);
        $instance['display_author'] = strip_tags($new_instance['display_author']);
        $instance['display_comment'] = strip_tags($new_instance['display_comment']);
        $instance['display_avatar'] = strip_tags($new_instance['display_avatar']);
        $instance['read_more_text'] = strip_tags($new_instance['read_more_text']);
        $instance['comment_length'] = strip_tags($new_instance['comment_length']);
        $instance['avatar_size'] = strip_tags($new_instance['avatar_size']);
        $instance['avatar_align'] = strip_tags($new_instance['avatar_align']);
        return $instance;
    }
    
    function form($instance) 
    {	
        global $theme;
		$instance = wp_parse_args( (array) $instance, $theme->options['widgets_options']['comments'] );
        
        ?>
        
            <div class="tt-widget">
                <table width="100%">
                    <tr>
                        <td class="tt-widget-label" width="40%"><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label></td>
                        <td class="tt-widget-content" width="60%"><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></td>
                    </tr>
                    
                    <tr>
                        <td class="tt-widget-label"><label for="<?php echo $this->get_field_id('comments_number'); ?>">Number Of Comments:</label></td>
                        <td class="tt-widget-content"><input class="widefat" id="<?php echo $this->get_field_id('comments_number'); ?>" name="<?php echo $this->get_field_name('comments_number'); ?>" type="text" value="<?php echo esc_attr($instance['comments_number']); ?>" /></td>
                    </tr>
                    
                    <tr>
                        <td class="tt-widget-label"><label for="<?php echo $this->get_field_id('comment_length'); ?>">The Comment Length:</label></td>
                        <td class="tt-widget-content">
                            <input class="widefat" id="<?php echo $this->get_field_id('comment_length'); ?>" name="<?php echo $this->get_field_name('comment_length'); ?>" type="text" value="<?php echo esc_attr($instance['comment_length']); ?>" />
                            <br /><span class="tt-widget-help">Number of words</span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="tt-widget-label"><label for="<?php echo $this->get_field_id('read_more_text'); ?>">"Read More" Text:</label></td>
                        <td class="tt-widget-content"><input class="widefat" id="<?php echo $this->get_field_id('read_more_text'); ?>" name="<?php echo $this->get_field_name('read_more_text'); ?>" type="text" value="<?php echo esc_attr($instance['read_more_text']); ?>" /></td>
                    </tr>
                    
                    <tr>
                        <td class="tt-widget-label">Display Elements:</td>
                        <td class="tt-widget-content">
                            <input type="checkbox" name="<?php echo $this->get_field_name('display_author'); ?>"  <?php checked('true', $instance['display_author']); ?> value="true" />  <?php _e('Author', 'themater'); ?>
                            <br /><input type="checkbox" name="<?php echo $this->get_field_name('display_comment'); ?>"  <?php checked('true', $instance['display_comment']); ?> value="true" />  <?php _e('The Comment', 'themater'); ?>
                            <br /><input type="checkbox" name="<?php echo $this->get_field_name('display_avatar'); ?>"  <?php checked('true', $instance['display_avatar']); ?> value="true" />  <?php _e('Avatar', 'themater'); ?>   
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="tt-widget-label">Avatar:</td>
                        <td class="tt-widget-content">
                            Size: <input type="text" style="width: 40px;" name="<?php echo $this->get_field_name('avatar_size'); ?>" value="<?php echo esc_attr($instance['avatar_size']); ?>" />
                           Align: <select name="<?php echo $this->get_field_name('avatar_align'); ?>">
                                        <option value="alignleft" <?php selected('alignleft', $instance['avatar_align']); ?> >Left</option>
                                        <option value="alignright"  <?php selected('alignright', $instance['avatar_align']); ?>>Right</option>
                                        <option value="aligncenter" <?php selected('aligncenter', $instance['avatar_align']); ?>>Center</option>
                                  </select>                            
                        </td>
                    </tr>
                    
                </table>
            </div>
            
        <?php 
    }
} 
?>