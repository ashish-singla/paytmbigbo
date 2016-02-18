<?php

global $theme;

$themater_posts_defaults = array(
    'title' => 'Recent Posts',
    'posts_number' => '5',
    'order_by' => 'none',
    'display_title' => 'true',
    'display_date' => 'true',
    'display_author' => 'true',
    'display_content' => 'true',
    'display_featured_image' => 'true',
    'display_read_more' => 'true',
    'content_type' => 'the_excerpt',
    'excerpt_length' => '26',
    'featured_image_width' => '90',
    'featured_image_height' => '60',
    'featured_image_align' => 'alignleft',
    'filter' => 'recent',
    'filter_cats' => '',
    'filter_tags' => ''
);

$theme->options['widgets_options']['posts'] = is_array($theme->options['widgets_options']['posts'])
    ? array_merge($themater_posts_defaults, $theme->options['widgets_options']['posts'])
    : $themater_posts_defaults;
        
add_action('widgets_init', create_function('', 'return register_widget("ThematerPosts");'));

class ThematerPosts extends WP_Widget 
{
    function __construct() 
    {
        $widget_options = array('description' => __('Advanced widget for displaying the recent posts or posts from the selected categories or tags.', 'themater') );
        $control_options = array( 'width' => 400);
		$this->WP_Widget('themater_posts', '&raquo; Posts with Images', $widget_options, $control_options);
    }

    function widget($args, $instance)
    {
        global $theme;
        extract( $args );
        $instance = ! empty( $instance ) ? $instance : $theme->options['widgets_options']['posts'];
        
        $title = apply_filters('widget_title', $instance['title']);
        
    ?>
        <ul class="widget-container"><li class="posts-widget">
            <?php  if ( $title ) {  ?> <h3 class="widgettitle"><?php echo $title; ?></h3> <?php }  ?>
            <ul>
        	<?php
                switch ($instance['order_by']) {
                    case 'none' : $order_query = ''; break;
                    case 'id_asc' : $order_query = '&orderby=ID&order=ASC'; break;
                    case 'id_desc' : $order_query = '&orderby=ID&order=DESC'; break;
                    case 'date_asc' : $order_query = '&orderby=date&order=ASC'; break;
                    case 'date_desc' : $order_query = '&orderby=date&order=DESC'; break;
                    case 'title_asc' : $order_query = '&orderby=title&order=ASC'; break;
                    case 'title_desc' : $order_query = '&orderby=title&order=DESC'; break;
                    default : $order_query = '&orderby=' . $instance['order_by'];
                    
                }
                switch ($instance['filter']) {
                    case 'cats' : $filter_query = '&cat=' . trim($instance['filter_cats']) ; break;
                    case 'category' : $filter_query = '&cat=' . trim($instance['selected_category']) ; break;
                    case 'tags' : $filter_query = '&tag=' . trim($instance['filter_tags']) ; break;
                    default : $filter_query = '';
                }
                query_posts('posts_per_page=' . $instance['posts_number'] . $filter_query . $order_query);
                if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <li class="clearfix">
                        <?php if ($theme->options['general']['featured_image'] && $instance['display_featured_image'] && has_post_thumbnail() ) { ?><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array($instance['featured_image_width'],$instance['featured_image_height']), array("class" => "posts-widget-featured-image " . $instance['featured_image_align'])); ?></a> <?php } ?>
                        <?php if ( $instance['display_title'] ) { ?> <h3 class="posts-widgettitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3><?php } ?>
                        <?php
                            if($instance['display_date'] || $instance['display_author']) {
                                ?><div class="posts-widget-meta"><?php 
                                    if($instance['display_date'] ) {
                                        the_time($theme->get_option('dateformat'));
                                    }
                                    if($instance['display_author']) {
                                       echo ' '; _e('By', 'themater'); echo ' '; the_author();
                                    } ?>
                                </div><?php 
                            }
                            if($instance['display_content'] || $instance['display_read_more']) {
                                ?><div class="posts-widget-entry"><?php 
                                    if($instance['display_content'] ) {
                                        if($instance['content_type'] == 'the_content') {
                                            the_content("");
                                        } else {
                                            $get_the_excerpt_length = $instance['excerpt_length'] ? $instance['excerpt_length'] : 16;
                                            echo $theme->shorten(get_the_excerpt(), $get_the_excerpt_length);
                                        }
                                    }
                                    
                                    if($instance['display_read_more']) {
                                        ?> <a class="posts-widget-more" href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permalink to ', 'themater' ); the_title_attribute(); ?>"><?php _e('Read More &raquo;','themater'); ?></a><?php 
                                    }?>
                                </div><?php
                            }
                        ?>
                    </li>
                <?php
                endwhile; 
                endif;
                wp_reset_query();
            ?>
            </ul>
        </li></ul>
        <?php
    }

    function update($new_instance, $old_instance) 
    {				
    	$instance = $old_instance;
    	$instance['title'] = strip_tags($new_instance['title']);
        $instance['posts_number'] = strip_tags($new_instance['posts_number']);
        $instance['order_by'] = strip_tags($new_instance['order_by']);
        $instance['display_title'] = strip_tags($new_instance['display_title']);
        $instance['display_date'] = strip_tags($new_instance['display_date']);
        $instance['display_author'] = strip_tags($new_instance['display_author']);
        $instance['content_type'] = strip_tags($new_instance['content_type']);
        $instance['display_content'] = strip_tags($new_instance['display_content']);
        $instance['display_featured_image'] = strip_tags($new_instance['display_featured_image']);
        $instance['display_read_more'] = strip_tags($new_instance['display_read_more']);
        $instance['excerpt_length'] = strip_tags($new_instance['excerpt_length']);
        $instance['featured_image_width'] = strip_tags($new_instance['featured_image_width']);
        $instance['featured_image_height'] = strip_tags($new_instance['featured_image_height']);
        $instance['featured_image_align'] = strip_tags($new_instance['featured_image_align']);
        $instance['filter'] = strip_tags($new_instance['filter']);
        $instance['filter_cats'] = strip_tags($new_instance['filter_cats']);
        $instance['selected_category'] = strip_tags($new_instance['selected_category']);
        $instance['filter_tags'] = strip_tags($new_instance['filter_tags']);
        return $instance;
    }
    
    function form($instance) 
    {	
        global $theme;
		$instance = wp_parse_args( (array) $instance, $theme->options['widgets_options']['posts'] );
        
        ?>
        
        <div class="tt-widget">
            <table width="100%">
                <tr>
                    <td class="tt-widget-label" width="25%"><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label></td>
                    <td class="tt-widget-content" width="75%"><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></td>
                </tr>
                
                <tr>
                    <td class="tt-widget-label"><label for="<?php echo $this->get_field_id('posts_number'); ?>">Number Of Posts:</label></td>
                    <td class="tt-widget-content"><input class="widefat" id="<?php echo $this->get_field_id('posts_number'); ?>" name="<?php echo $this->get_field_name('posts_number'); ?>" type="text" value="<?php echo esc_attr($instance['posts_number']); ?>" /></td>
                </tr>
                
                <tr>
                    <td class="tt-widget-label"><label for="<?php echo $this->get_field_id('order_by'); ?>">Order Posts By:</label></td>
                    <td class="tt-widget-content">
                        <select id="<?php echo $this->get_field_id('order_by'); ?>" name="<?php echo $this->get_field_name('order_by'); ?>">
                            <option value="none" <?php selected('none', $instance['order_by']); ?> >None (Default)</option>
                            <option value="id_asc" <?php selected('id_asc', $instance['order_by']); ?> >ID ( Ascending ) </option>
                            <option value="id_desc" <?php selected('id_desc', $instance['order_by']); ?> >ID ( Descending ) </option>
                            <option value="date_asc"  <?php selected('date_asc', $instance['order_by']); ?>>Date ( Ascending ) </option>
                            <option value="date_desc"  <?php selected('date_desc', $instance['order_by']); ?>>Date ( Descending ) </option>
                            <option value="title_asc" <?php selected('title_asc', $instance['order_by']); ?>>Title ( Ascending ) </option>
                            <option value="title_desc" <?php selected('title_desc', $instance['order_by']); ?>>Title ( Descending  ) </option>
                            <option value="rand" <?php selected('rand', $instance['order_by']); ?>>Random</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td class="tt-widget-label">Display Elements:</td>
                    <td class="tt-widget-content">
                        <input type="checkbox" name="<?php echo $this->get_field_name('display_title'); ?>"  <?php checked('true', $instance['display_title']); ?> value="true" />  Post Title
                        <br /><input type="checkbox" name="<?php echo $this->get_field_name('display_date'); ?>"  <?php checked('true', $instance['display_date']); ?> value="true" /> Date
                        <br /><input type="checkbox" name="<?php echo $this->get_field_name('display_author'); ?>"  <?php checked('true', $instance['display_author']); ?> value="true" />  Author
                        <br /><input type="checkbox" name="<?php echo $this->get_field_name('display_content'); ?>"  <?php checked('true', $instance['display_content']); ?> value="true" /> The Content / The Excerpt
                        <br /><input type="checkbox" name="<?php echo $this->get_field_name('display_featured_image'); ?>"  <?php checked('true', $instance['display_featured_image']); ?> value="true" /> Thumbnail
                        <br /><input type="checkbox" name="<?php echo $this->get_field_name('display_read_more'); ?>"  <?php checked('true', $instance['display_read_more']); ?> value="true" />  "Read More" Link
                    </td>
                </tr>
                
                <tr>
                    <td class="tt-widget-label">Content Type:</td>
                    <td class="tt-widget-content">
                        <input type="radio" name="<?php echo $this->get_field_name('content_type'); ?>" <?php checked('the_content', $instance['content_type']); ?> value="the_content" /> The Content<br />
                        <input type="radio" name="<?php echo $this->get_field_name('content_type'); ?>" <?php checked('the_excerpt', $instance['content_type']); ?> value="the_excerpt" /> The Excerpt &nbsp; <label for="<?php echo $this->get_field_id('excerpt_length'); ?>">The Excerpt Length:</label> <input style="width: 40px;" id="<?php echo $this->get_field_id('excerpt_length'); ?>" name="<?php echo $this->get_field_name('excerpt_length'); ?>" type="text" value="<?php echo esc_attr($instance['excerpt_length']); ?>" /> <span class="tt-widget-help">words</span>
                    </td>
                </tr>
                
                <tr>
                    <td class="tt-widget-label">Thumbnail:</td>
                    <td class="tt-widget-content">
                        Width: <input type="text" style="width: 40px;" name="<?php echo $this->get_field_name('featured_image_width'); ?>" value="<?php echo esc_attr($instance['featured_image_width']); ?>" /> &nbsp; Height: <input type="text" style="width: 40px;" name="<?php echo $this->get_field_name('featured_image_height'); ?>" value="<?php echo esc_attr($instance['featured_image_height']); ?>"  />  
                         &nbsp; Float: <select name="<?php echo $this->get_field_name('featured_image_align'); ?>">
                            <option value="alignleft" <?php selected('alignleft', $instance['featured_image_align']); ?> >Left</option>
                            <option value="alignright"  <?php selected('alignright', $instance['featured_image_align']); ?>>Right</option>
                            <option value="aligncenter" <?php selected('aligncenter', $instance['featured_image_align']); ?>>Center</option>
                        </select>
                    </td>
                </tr>
            
                <tr>
                    <td class="tt-widget-label">Filter:</td>
                    <td class="tt-widget-content" style="padding-top: 5px;">
                        <input type="radio" name="<?php echo $this->get_field_name('filter'); ?>" <?php checked('recent', $instance['filter']); ?> value="recent" /> Show Recent Posts <br /><br />
                       
                        <input type="radio" name="<?php echo $this->get_field_name('filter'); ?>" <?php checked('category', $instance['filter']); ?> value="category" /> Show Posts from a sinle category:<br />
                        <select name="<?php echo $this->get_field_name('selected_category'); ?>">
                        <?php
                            $categories = get_categories('hide_empty=0');
                            foreach ($categories as $category) {
                                $category_selected = $this->get_field_name('selected_category') == $category->cat_ID ? ' selected="selected" ' : '';
                                ?>
                                <option value="<?php echo $category->cat_ID; ?>" <?php selected($category->cat_ID, $instance['selected_category']); ?> ><?php echo $category->cat_name; ?></option>
                                <?php
                            }
                        ?>
                        </select>
                        <br /><br />
                        
                        <input type="radio" name="<?php echo $this->get_field_name('filter'); ?>" <?php checked('cats', $instance['filter']); ?> value="cats" /> <label for="<?php echo $this->get_field_id('filter_cats'); ?>">Show posts from  selected categories:</label>
                        <br /><span class="tt-widget-help">Category IDs ( e.g: 5,9,24 )</span>
                        <br /><input class="widefat" id="<?php echo $this->get_field_id('filter_cats'); ?>" name="<?php echo $this->get_field_name('filter_cats'); ?>" type="text" value="<?php echo esc_attr($instance['filter_cats']); ?>" />
                        
                        
                        <br /><br /><input type="radio" name="<?php echo $this->get_field_name('filter'); ?>" <?php checked('tags', $instance['filter']); ?> value="tags" /> <label for="<?php echo $this->get_field_id('filter_tags'); ?>">Show only posts tagged with:</label>
                        <br /><span class="tt-widget-help">Tag slugs ( e.g: computer,news,business-news )</span>
                        <br /><input class="widefat" id="<?php echo $this->get_field_id('filter_tags'); ?>" name="<?php echo $this->get_field_name('filter_tags'); ?>" type="text" value="<?php echo esc_attr($instance['filter_tags']); ?>" />
                        
                    </td>
                </tr>
                
            </table>
          </div>
        <?php 
    }
} 
?>