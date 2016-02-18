<?php
    new Themater_FeaturedPosts();
    
    class Themater_FeaturedPosts
    {
        var $theme;
        var $status = false;
        var $url;
        
        var $defaults = array(
            'enabled_in' => array('homepage'),
            'hook' => 'content_before',
            'hook_priority' => '1',
            'label' => '',
            'image_sizes' => '',
            'source' => 'custom',
            'category_num' => '5',
            'excerpt_length' => '32',
            'readmore' => 'More &raquo;',
            'effect' => 'fade',
            'timeout' => '4000',
            'delay' => '0',
            'speed' => '1000', 
            'speedIn' => '',
            'speedOut' => '',
            'default_moreoptions' => array('thumbnail','post_title', 'post_excerpt', 'pager', 'next_prev', 'sync','pause', 'pauseOnPagerHover'),
            'enabled_positions' => array('homepage'=>'Homepage', 'static'=>'Static Page (When a static page is set as front page from the "wp-admin / Settings / Reading" options page)', 'categories' => 'Category Pages', 'tags' => 'Tag Pages', 'archives' => 'Archive Pages', 'single' => 'Single Posts', 'pages' => 'Pages', 'searches'=>'Search Results Pages'),
            'custom_default_slides' => array(
                '1' => array('link' => '#', 'title' => 'This is default featured slide 1 title', 'content' => 'You can completely customize the featured slides from the theme theme options page. You can also easily hide the slider from certain part of your site like: categories, tags, archives etc.'), 
                '2' => array('link' => '#', 'title' => 'This is default featured slide 2 title', 'content' => 'You can completely customize the featured slides from the theme theme options page. You can also easily hide the slider from certain part of your site like: categories, tags, archives etc.'), 
                '3' => array('link' => '#', 'title' => 'This is default featured slide 3 title', 'content' => 'You can completely customize the featured slides from the theme theme options page. You can also easily hide the slider from certain part of your site like: categories, tags, archives etc.'), 
                '4' => array('link' => '#', 'title' => 'This is default featured slide 4 title', 'content' => 'You can completely customize the featured slides from the theme theme options page. You can also easily hide the slider from certain part of your site like: categories, tags, archives etc.'), 
                '5' => array('link' => '#', 'title' => 'This is default featured slide 5 title', 'content' => 'You can completely customize the featured slides from the theme theme options page. You can also easily hide the slider from certain part of your site like: categories, tags, archives etc.')
            )
        );
        
        var $moreoptions =  array(
            'thumbnail' => 'Show Thumbnail',
            'post_title' => 'Show Post Title',
            'post_excerpt' => 'Show Post Excerpt',
            'pager' => 'Show Pager / Page Numbers',
            'next_prev' => 'Show Next / Previous Buttons',
            'sync' => 'In/Out transitions should occur simultaneously',
            'pause' => 'Pause on hover',
            'pauseOnPagerHover' => 'Pause when hovering over pager link',
            'continuous' => 'Start next transition immediately after current one completes (Will overwrite the other timing options like: delay, speed etc.)'
        );
        
        var $effects = array(
            'none' => 'No Effect', 
            'fade' => 'Fade', 
            'fadeZoom' => 'Fade Zoom', 
            'blindX' => 'Blind X',
            'blindY' => 'Blind Y',
            'blindZ' => 'Blind Z',
            'cover' => 'Cover',
            'uncover' => 'Uncover',
            'curtainX' => 'Curtain X',
            'curtainY' => 'Curtain Y',
            'growX' => 'Grow X',
            'growY' => 'Grow Y',
            'scrollUp' => 'Scroll Up',
            'scrollDown' => 'Scroll Down',
            'scrollLeft' => 'Scroll Left',
            'scrollRight' => 'Scroll Right',
            'scrollHorz' => 'Scroll Horizontal',
            'scrollVert' => 'Scroll Vertical',
            'slideX' => 'Slide X',
            'slideY' => 'Slide Y',
            'turnDown' => 'Turn Down',
            'turnLeft' => 'Turn Left',
            'turnRight' => 'Turn Right',
            'wipe' => 'Wipe',
            'zoom' => 'Zoom'
        );
        
        function Themater_FeaturedPosts()
        {
            global $theme;
            $this->theme = $theme;
            $this->url = THEMATER_INCLUDES_URL . '/featuredposts';
            
            if(is_array($this->theme->options['plugins_options']['featuredposts']) ) {
                $this->defaults = array_merge($this->defaults, $this->theme->options['plugins_options']['featuredposts']);
            }
            
            $this->theme->add_hook('head', array(&$this, 'featuredposts_head'));
            $this->theme->add_hook($this->defaults['hook'], array(&$this, 'display_featuredposts'), $this->defaults['hook_priority']);
  
            $this->themater_options();
        }
        
        function featuredposts_head()
        {
            if($this->enabled()) {
                echo  "\n<!-- Featured Posts -->\n";
                echo '<script src="' . $this->url . '/scripts/jquery.cycle.all.js" type="text/javascript"></script>' . "\n";
                echo  "<!-- /jquery.cycle.all.js -->\n\n";
            }
        }
        
        function display_featuredposts()
        {
            if($this->enabled()) {
                
                $featuredposts_moreoptions = $this->theme->get_option('featuredposts_moreoptions');

                $cycle_js = "jQuery(document).ready(function() {\n\t";
                $cycle_js .= "jQuery('.fp-slides').cycle({\n\t\t";
                $cycle_js .= "fx: '" . $this->theme->get_option('featuredposts_effect') ."',\n\t\t";
                $cycle_js .= "timeout: " . $this->theme->get_option('featuredposts_timeout') .",\n\t\t";
                $cycle_js .= "delay: " . $this->theme->get_option('featuredposts_delay') .",\n\t\t";
                $cycle_js .= "speed: " . $this->theme->get_option('featuredposts_speed') .",\n\t\t";
                $cycle_js .= "next: '.fp-next',\n\t\t";
                $cycle_js .= "prev: '.fp-prev',\n\t\t";
                $cycle_js .= "pager: '.fp-pager',\n\t\t";
                
                if($this->theme->display('featuredposts_speedIn')) {
                    $cycle_js .= "speedIn: " . $this->theme->get_option('featuredposts_speedIn') .",\n\t\t";
                }
                
                if($this->theme->display('featuredposts_speedOut')) {
                    $cycle_js .= "speedOut: " . $this->theme->get_option('featuredposts_speedOut') .",\n\t\t";
                }
                
                $featuredposts_continuous = $this->theme->display('continuous', $featuredposts_moreoptions) ? '1' : '0';
                $cycle_js .= "continuous: $featuredposts_continuous,\n\t\t";
                
                $featuredposts_sync = $this->theme->display('sync', $featuredposts_moreoptions) ? '1' : '0';
                $cycle_js .= "sync: $featuredposts_sync,\n\t\t";
                
                $featuredposts_pause = $this->theme->display('pause', $featuredposts_moreoptions) ? '1' : '0';
                $cycle_js .= "pause: $featuredposts_pause,\n\t\t";
                
                $featuredposts_pauseOnPagerHover = $this->theme->display('pauseOnPagerHover', $featuredposts_moreoptions) ? '1' : '0';
                $cycle_js .= "pauseOnPagerHover: $featuredposts_pauseOnPagerHover,\n\t\t";
                
                
                
                $cycle_js .= "cleartype: true,\n\t\t";
                $cycle_js .= "cleartypeNoBg: true\n\t";
                $cycle_js .= "});\n });\n";
                
                $this->theme->custom_js($cycle_js);
                
                if(file_exists(THEMATER_INCLUDES_DIR . '/featuredposts/template.php') ) {
                    $featuredposts_source = $this->theme->get_option('featuredposts_source');
                    $featuredposts_moreoptions = $this->theme->get_option('featuredposts_moreoptions');
                    
                    $featuredposts_query = false;
                    $the_slider = false;
                    
                    if($featuredposts_source == 'custom') {
                        $the_slider = $this->theme->get_option('featuredposts_custom_slides');
                        unset($the_slider['the__id__']);
                    } else {
                        if($featuredposts_source == 'category') {
                            if($this->theme->display('featuredposts_source_category')) {
                                $featuredposts_query = 'posts_per_page=' . $this->theme->get_option('featuredposts_source_category_num') . '&cat=' . $this->theme->get_option('featuredposts_source_category');
                            } 
                        } elseif($featuredposts_source == 'posts') {
                            if($this->theme->display('featuredposts_source_posts')) {
                                $featuredposts_query = array('post__in'=> explode(',', trim($this->theme->get_option('featuredposts_source_posts'))), 'post_type'=>'post', 'posts_per_page' => '-1');
                            } 
                        } elseif($featuredposts_source == 'pages') {
                            if($this->theme->display('featuredposts_source_pages')) {
                                $featuredposts_query = array('post__in'=> explode(',', trim($this->theme->get_option('featuredposts_source_pages'))), 'post_type'=>'page', 'posts_per_page' => '-1');
                            } 
                        }
                        
                        if($featuredposts_query) {
                            $featuredposts_excerpt_length = $this->theme->get_option('featuredposts_excerpt_length');
                            query_posts($featuredposts_query);
                            if (have_posts()) : while (have_posts()) : the_post();
                                $featured_image_url = '';
                                if ( has_post_thumbnail()) {
                                   $get_large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
                                   $featured_image_url = $get_large_image_url[0];
                                 }
                                
                                $the_slider[] = array('img' => $featured_image_url, 'link' => get_permalink(), 'title' => get_the_title(), 'content' => $this->theme->shorten(get_the_excerpt(),$featuredposts_excerpt_length));
                            endwhile;
                            endif;
                            wp_reset_query();
                        }
                    }
                    require_once(THEMATER_INCLUDES_DIR . '/featuredposts/template.php');
                }
            }
        }
        
        function enabled()
        {
            if($this->status) {
                $is_enabled = $this->status == 'enabled' ? true : false;
                return $is_enabled;
            } else {
                $featuredposts_enabled = $this->theme->get_option('featuredposts_enabled');
                if(is_array($featuredposts_enabled)) {
                    if(
                        (is_home() && in_array('homepage', $featuredposts_enabled) ) 
                        || (is_front_page() && in_array('static', $featuredposts_enabled) ) 
                        || (is_category() && in_array('categories', $featuredposts_enabled) ) 
                        || (is_tag() && in_array('tags', $featuredposts_enabled) ) 
                        || (is_single() && in_array('single', $featuredposts_enabled) ) 
                        || (is_page() && in_array('pages', $featuredposts_enabled) ) 
                        || ((is_day() || is_month() || is_year()) && in_array('archives', $featuredposts_enabled) ) 
                        || (is_search() && in_array('searches', $featuredposts_enabled) ) 
                    ){
                        $this->status = 'enabled';
                        return true;
                    }
                } 
                $this->status = 'disabled';
                return false;
            }
        }

        function featuredposts_source()
        {
            $get_featuredposts_source = $this->theme->get_option('featuredposts_source');
            $featuredposts_sources = array('custom'=> 'Custom Slides', 'category'=> 'Category Posts', 'posts' => 'Selected Posts', 'pages' => 'Selected Pages');
            
            foreach($featuredposts_sources as $key=>$val) {
                $featuredposts_sources_selected = $get_featuredposts_source == $key ? 'checked="checked"' : '';
                ?>
                <div id="select_slide_source_<?php echo $key; ?>" class="tt_radio_button_container">
                    <input type="radio" name="featuredposts_source" value="<?php echo $key; ?>" <?php echo $featuredposts_sources_selected; ?> id="featuredposts_source_id_<?php echo $key; ?>" /> <a href="javascript:themater_featuredposts_source('<?php echo $key; ?>');" class="tt_radio_button"><?php echo $val; ?></a>
                </div>
                <?php
            }
            ?>
                <script type="text/javascript">
                    function themater_featuredposts_source(source)
                    {
                        $thematerjQ("#themater_featuredposts_custom").hide();
                        $thematerjQ("#select_slide_source_custom a").removeClass('tt_radio_button_current');
                        $thematerjQ("#select_slide_source_custom").find(":radio").removeAttr("checked");
                        
                        $thematerjQ("#themater_featuredposts_category").hide();
                        $thematerjQ("#select_slide_source_category a").removeClass('tt_radio_button_current');
                        $thematerjQ("#select_slide_source_category").find(":radio").removeAttr("checked");
                        
                        $thematerjQ("#themater_featuredposts_posts").hide();
                        $thematerjQ("#select_slide_source_posts a").removeClass('tt_radio_button_current');
                        $thematerjQ("#select_slide_source_posts").find(":radio").removeAttr("checked");
                        
                        $thematerjQ("#themater_featuredposts_pages").hide();
                        $thematerjQ("#select_slide_source_pages a").removeClass('tt_radio_button_current');
                        $thematerjQ("#select_slide_source_pages").find(":radio").removeAttr("checked");
                        
                        $thematerjQ("#themater_featuredposts_"+source+"").fadeIn();
                        $thematerjQ("#select_slide_source_"+source+" a").addClass('tt_radio_button_current');
                        $thematerjQ("#select_slide_source_"+source+"").find(":radio").attr("checked","checked");
                    }
                    jQuery(document).ready(function(){
                        themater_featuredposts_source('<?php echo $get_featuredposts_source; ?>');
                    });
                    
                </script>
            <?php
        }
        
        function featuredposts_custom_slides()
        {
            $get_featuredposts_custom_slides = $this->theme->get_option('featuredposts_custom_slides');
            ?>
            <script type="text/javascript">
                function featured_slider_new()
                {
                    var new_slide_id = 10000+Math.floor(Math.random()*100000);
                    var get_new_slide_container = $thematerjQ('#new_custom_slide_prototype').html();
                    
                    var new_slide_container = get_new_slide_container.replace(/the__id__/g, ''+new_slide_id+'');
                    
                    
                    $thematerjQ('#new_custom_slide').append(''+new_slide_container+'');
                }
                
                function featured_slider_delete(id)
                {
                    $thematerjQ('#featured_custom_slide_'+id+'').remove();
                }
                
            </script>
            <div style="margin-bottom: 20px;">
                <a class="button" href="#new_custom_slide_a" onclick="featured_slider_new();" >Add New Slide</a>
            </div>
            
            <?php
            $no = 0;
            foreach ($get_featuredposts_custom_slides as $key=>$custom_slide ) {
                $no++;
                if(is_numeric($key)) {
                ?>
                    <div style="padding: 0 0 0 0; border-bottom: 1px solid #ddd; margin-bottom: 20px;" id="featured_custom_slide_<?php echo $key; ?>">
                        <div style="background: #efefef; padding: 5px; margin-bottom: 5px; font-weight: bold;">
                            Slide #<?php echo $no; ?> - <?php echo $custom_slide['title']; ?>
                            &nbsp; <a class="button" href="javascript:themater_showHide('featured_custom_slide_content_<?php echo $key; ?>');">Edit</a>
                            &nbsp; <a class="button tt-button-red" href="javascript:featured_slider_delete('<?php echo $key; ?>');">Delete</a>
                        </div>
                        <div class="fp-form-element" id="featured_custom_slide_content_<?php echo $key; ?>" style="display: none;">
                             <table width="100%">
                                <tr>
                                    <td class="tt-inline-label" style="width: 15%;" valign="top">Image URL:</td>
                                    <td class="tt-inline-content" style="width: 85%;" valign="top"><input type="text" class="tt-text" name="featuredposts_custom_slides[<?php echo $key; ?>][img]" value="<?php echo $custom_slide['img']; ?>"  /></td>
                                </tr>
                                
                                <tr>
                                    <td class="tt-inline-label" style="width: 15%;" valign="top">Link URL:</td>
                                    <td class="tt-inline-content" style="width: 85%;" valign="top"><input type="text" class="tt-text" name="featuredposts_custom_slides[<?php echo $key; ?>][link]" value="<?php echo $custom_slide['link']; ?>"  /></td>
                                </tr>
                                
                                 <tr>
                                    <td class="tt-inline-label" style="width: 15%;" valign="top">Title:</td>
                                    <td class="tt-inline-content" style="width: 85%;" valign="top"><input type="text" class="tt-text" name="featuredposts_custom_slides[<?php echo $key; ?>][title]" value="<?php echo $custom_slide['title']; ?>"  /></td>
                                </tr>
                                
                                <tr>
                                    <td class="tt-inline-label" style="width: 15%;" valign="top">Content:</td>
                                    <td class="tt-inline-content" style="width: 85%;" valign="top"><textarea class="tt-textarea" name="featuredposts_custom_slides[<?php echo $key; ?>][content]" style="height: 100px;"><?php echo $custom_slide['content']; ?></textarea></td>
                                </tr>
                                
                             </table>
                        </div>
                    </div>
                <?php
                }
            }
            ?>
            <a name="new_custom_slide_a"></a>
            <div id="new_custom_slide">
                <div id="new_custom_slide_prototype" style="display: none;">
                
                    <div style="padding: 0 0 0 0; border-bottom: 1px solid #ddd; margin-bottom: 20px;" id="featured_custom_slide_the__id__">
                        <div style="background: #eee; padding: 5px; margin-bottom: 5px; font-weight: bold;">
                            <span style="color: green;">New Slide</span>
                            &nbsp; <a class="button" href="javascript: themater_showHide('featured_custom_slide_content_the__id__');">Edit</a>
                            &nbsp; <a class="button tt-button-red" href="javascript: featured_slider_delete('the__id__');">Delete</a>
                        </div>
                        
                        <div class="fp-form-element" id="featured_custom_slide_content_the__id__">
                             <table width="100%">
                                <tr>
                                    <td class="tt-inline-label" style="width: 15%;" valign="top">Image URL:</td>
                                    <td class="tt-inline-content" style="width: 85%;" valign="top"><input type="text" class="tt-text" name="featuredposts_custom_slides[the__id__][img]" value=""  /></td>
                                </tr>
                                
                                 <tr>
                                    <td class="tt-inline-label" style="width: 15%;" valign="top">Link URL:</td>
                                    <td class="tt-inline-content" style="width: 85%;" valign="top"><input type="text" class="tt-text" name="featuredposts_custom_slides[the__id__][link]" value=""  /></td>
                                </tr>
                                
                                <tr>
                                    <td class="tt-inline-label" style="width: 15%;" valign="top">Title:</td>
                                    <td class="tt-inline-content" style="width: 85%;" valign="top"><input type="text" class="tt-text" name="featuredposts_custom_slides[the__id__][title]" value=""  /></td>
                                </tr>
                                
                                <tr>
                                    <td class="tt-inline-label" style="width: 15%;" valign="top">Content:</td>
                                    <td class="tt-inline-content" style="width: 85%;" valign="top"><textarea class="tt-textarea" name="featuredposts_custom_slides[the__id__][content]" style="height: 100px;"></textarea></td>
                                </tr>
                                
                             </table>
                        </div>
                    </div>
                    
                </div>
            </div>
            <?php
        }
        
        
        function themater_options()
        {
             $this->theme->admin_option(array('Featured Posts', 15), 
                'Featured Posts', 'featuredposts_enabled', 
                'checkboxes', $this->defaults['enabled_in'], 
                array('options' => $this->defaults['enabled_positions'], 'help'=> 'Enable featured posts slideshow at:', 'display'=>'extended-top')
            );
            
        
            $image_sizes = $this->defaults['image_sizes'] ? '<br>Recommended image sizes <strong>' . $this->defaults['image_sizes'] . '</strong>' : '';
            $this->theme->admin_option('Featured Posts', 
                'Featured Posts Images', 'featuredposts_images', 
                'content','<i>The images for the "Category Posts", "Selected Posts" and "Selected Pages" should be added using the "Set Featured Image" link, located under categories list at post write/edit page. ' . $image_sizes . '</i>'
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Posts Source', 'featuredposts_source', 
                'callback', $this->defaults['source'], 
                array('callback' => array(&$this, 'featuredposts_source'))
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Posts CSS', 'featuredposts_source_css', 
                'raw', '<style type="text/css"><!-- .featured_slide_source { padding: 4px; } .featured_slide_source_selected {padding: 3px; border: 1px solid #118d11; background: #daf8dc; } --></style>', 
                array('display'=>'clean')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Posts Custom Wrap', 'featuredposts_source_custom_wrap', 
                'raw', '<div id="themater_featuredposts_custom">', 
                array('display'=>'clean')
            );
           
           $featuredposts_custom_slides_raw =  $this->defaults['custom_default_slides'];
           
           if(is_array($featuredposts_custom_slides_raw)) {
                foreach ($featuredposts_custom_slides_raw as $custom_slide_key => $custom_slide_val) {
                    $featuredposts_custom_slides[] = array('img' => get_template_directory_uri() . '/images/default-slides/' . $custom_slide_key . '.jpg', 'link' =>   $custom_slide_val['link'], 'title' => $custom_slide_val['title'], 'content' => $custom_slide_val['content']);
                }
           }
           
            $this->theme->admin_option('Featured Posts', 
                'Custom Slides', 'featuredposts_custom_slides', 
                'callback', $featuredposts_custom_slides, 
                array('callback' => array(&$this, 'featuredposts_custom_slides'), 'display' => 'clean')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Posts Custom Wrap End', 'featuredposts_source_custom_end_wrap', 
                'raw', '</div>', 
                array('display'=>'clean')
            );
            
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Posts Category Wrap', 'featuredposts_source_category_wrap', 
                'raw', '<div id="themater_featuredposts_category">', 
                array('display'=>'clean')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Number of Feautured Posts', 'featuredposts_source_category_num', 
                'text', $this->defaults['category_num'], 
                array('help'=>'The number of posts you want to show on featured slideshow.', 'display'=>'inline', 'style'=>'width: 60px;')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Posts Category', 'featuredposts_source_category', 
                'select', '', 
                array('options'=>$this->theme->get_categories_array(true, array(''=>'Select Category')), 'help'=>'The selected number of posts form the selected category will be listed in the featured slideshow. The selected category should contain at last 2 posts with featured image set.', 'display'=>'inline')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Posts Category Wrap End', 'featuredposts_source_category_end_wrap', 
                'raw', '</div>', 
                array('display'=>'clean')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Selected Posts Wrap', 'featuredposts_source_posts_wrap', 
                'raw', '<div id="themater_featuredposts_posts">', 
                array('display'=>'clean')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Post IDs', 'featuredposts_source_posts', 
                'text', '', 
                array('help'=>'Enter individual post IDs to display in the slideshow. Separate IDs with commas. <br />You should add at last 2 post ID\'s with featured image set.', 'display'=>'inline')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Selected Posts Wrap End', 'featuredposts_source_posts_wrap_end', 
                'raw', '</div>', 
                array('display'=>'clean')
            );
            
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Selected Pages Wrap', 'featuredposts_source_pages_wrap', 
                'raw', '<div id="themater_featuredposts_pages">', 
                array('display'=>'clean')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Page IDs', 'featuredposts_source_pages', 
                'text', '', 
                array('help'=>'Enter individual page IDs to display in the slideshow. Separate IDs with commas. <br />You should add at last 2 page ID\'s with featured image set.', 'display'=>'inline')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Featured Selected Pages Wrap End', 'featuredposts_source_pages_wrap_end', 
                'raw', '</div>', 
                array('display'=>'clean')
            );
            
             $this->theme->admin_option('Featured Posts', 
                'Slideshow Effect', 'featuredposts_effect', 
                'select', $this->defaults['effect'], 
                array('options'=> $this->effects)
            );
            
             $this->theme->admin_option('Featured Posts', 
                'Misc Options', 'featuredposts_misc_options_info', 
                'content', ''
            );
            
             $this->theme->admin_option('Featured Posts', 
                'More Slideshow Options', 'featuredposts_moreoptions', 
                'checkboxes', $this->defaults['default_moreoptions'], 
                array('display'=>'clean', 'options'=> $this->moreoptions)
            );
            
            $this->theme->admin_option('Featured Posts', 
                '"Read More" link text', 'featuredposts_readmore', 
                'text', $this->defaults['readmore'], 
                array('help'=> 'Leave blank to hide',  'display'=>'inline')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Post Excerpt Length', 'featuredposts_excerpt_length', 
                'text', $this->defaults['excerpt_length'], 
                array('suffix'=> 'words', 'style'=>'width: 80px;', 'display'=>'inline')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Slides Timeout', 'featuredposts_timeout', 
                'text', $this->defaults['timeout'], 
                array('suffix'=> ' ms.', 'style'=>'width: 80px;', 'display'=>'inline', 'help' => 'Milliseconds between slide transitions (0 to disable auto advance)')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Slides Delay', 'featuredposts_delay', 
                'text', $this->defaults['delay'], 
                array('suffix'=> ' ms.', 'style'=>'width: 80px;', 'display'=>'inline', 'help'=>'Additional delay (in ms) for first transition (hint: can be negative)')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Slides Speed', 'featuredposts_speed', 
                'text', $this->defaults['speed'], 
                array('suffix'=> ' ms.', 'style'=>'width: 80px;', 'display'=>'inline', 'help'=>'Speed of the transition (any valid fx speed value)')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Slides Speed In', 'featuredposts_speedIn', 
                'text', $this->defaults['speedIn'], 
                array('suffix'=> ' ms.', 'style'=>'width: 80px;', 'display'=>'inline', 'help'=>'speed of the \'in\' transition ')
            );
            
            $this->theme->admin_option('Featured Posts', 
                'Slides Speed Out', 'featuredposts_speedOut', 
                'text', $this->defaults['speedOut'], 
                array('suffix'=> ' ms.', 'style'=>'width: 80px;', 'display'=>'inline', 'help'=>'speed of the \'out\' transition ')
            );
        }
    }
?>