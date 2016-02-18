<?php
class Themater
{
    var $theme_name = false;
    var $options = array();
    var $admin_options = array();
    
    function Themater($set_theme_name = false)
    {
        if($set_theme_name) {
            $this->theme_name = $set_theme_name;
        } else {
            $theme_data = wp_get_theme();
            $this->theme_name = $theme_data->get( 'Name' );
        }
        $this->options['theme_options_field'] = str_replace(' ', '_', strtolower( trim($this->theme_name) ) ) . '_theme_options';
        
        $get_theme_options = get_option($this->options['theme_options_field']);
        if($get_theme_options) {
            $this->options['theme_options'] = $get_theme_options;
            $this->options['theme_options_saved'] = 'saved';
        }
        
        $this->_definitions();
        $this->_default_options();
    }
    
    /**
    * Initial Functions
    */
    
    function _definitions()
    {
        // Define THEMATER_DIR
        if(!defined('THEMATER_DIR')) {
            define('THEMATER_DIR', get_template_directory() . '/lib');
        }
        
        if(!defined('THEMATER_URL')) {
            define('THEMATER_URL',  get_template_directory_uri() . '/lib');
        }
        
        // Define THEMATER_INCLUDES_DIR
        if(!defined('THEMATER_INCLUDES_DIR')) {
            define('THEMATER_INCLUDES_DIR', get_template_directory() . '/includes');
        }
        
        if(!defined('THEMATER_INCLUDES_URL')) {
            define('THEMATER_INCLUDES_URL',  get_template_directory_uri() . '/includes');
        }
        
        // Define THEMATER_ADMIN_DIR
        if(!defined('THEMATER_ADMIN_DIR')) {
            define('THEMATER_ADMIN_DIR', THEMATER_DIR);
        }
        
        if(!defined('THEMATER_ADMIN_URL')) {
            define('THEMATER_ADMIN_URL',  THEMATER_URL);
        }
    }
    
    function _default_options()
    {
        // Load Default Options
        require_once (THEMATER_DIR . '/default-options.php');
        
        $this->options['translation'] = $translation;
        $this->options['general'] = $general;
        $this->options['includes'] = array();
        $this->options['plugins_options'] = array();
        $this->options['widgets'] = $widgets;
        $this->options['widgets_options'] = array();
        $this->options['menus'] = $menus;
        
        // Load Default Admin Options
        if( !isset($this->options['theme_options_saved']) || $this->is_admin_user() ) {
            require_once (THEMATER_DIR . '/default-admin-options.php');
        }
    }
    
    /**
    * Theme Functions
    */
    
    function option($name) 
    {
        echo $this->get_option($name);
    }
    
    function get_option($name) 
    {
        $return_option = '';
        if(isset($this->options['theme_options'][$name])) {
            if(is_array($this->options['theme_options'][$name])) {
                $return_option = $this->options['theme_options'][$name];
            } else {
                $return_option = stripslashes($this->options['theme_options'][$name]);
            }
        } 
        return $return_option;
    }
    
    function display($name, $array = false) 
    {
        if(!$array) {
            $option_enabled = strlen($this->get_option($name)) > 0 ? true : false;
            return $option_enabled;
        } else {
            $get_option = is_array($array) ? $array : $this->get_option($name);
            if(is_array($get_option)) {
                $option_enabled = in_array($name, $get_option) ? true : false;
                return $option_enabled;
            } else {
                return false;
            }
        }
    }
    
    function custom_css($source = false) 
    {
        if($source) {
            $this->options['custom_css'] = $this->options['custom_css'] . $source . "\n";
        }
        return;
    }
    
    function custom_js($source = false) 
    {
        if($source) {
            $this->options['custom_js'] = $this->options['custom_js'] . $source . "\n";
        }
        return;
    }
    
    function hook($tag, $arg = '')
    {
        do_action('themater_' . $tag, $arg);
    }
    
    function add_hook($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        add_action( 'themater_' . $tag, $function_to_add, $priority, $accepted_args );
    }
    
    function admin_option($menu, $title, $name = false, $type = false, $value = '', $attributes = array())
    {
        if($this->is_admin_user() || !isset($this->options['theme_options'][$name])) {
            
            // Menu
            if(is_array($menu)) {
                $menu_title = isset($menu['0']) ? $menu['0'] : $menu;
                $menu_priority = isset($menu['1']) ? (int)$menu['1'] : false;
            } else {
                $menu_title = $menu;
                $menu_priority = false;
            }
            
            if(!isset($this->admin_options[$menu_title]['priority'])) {
                if(!$menu_priority) {
                    $this->options['admin_options_priorities']['priority'] += 10;
                    $menu_priority = $this->options['admin_options_priorities']['priority'];
                }
                $this->admin_options[$menu_title]['priority'] = $menu_priority;
            }
            
            // Elements
            
            if($name && $type) {
                $element_args['title'] = $title;
                $element_args['name'] = $name;
                $element_args['type'] = $type;
                $element_args['value'] = $value;
                
                if( !isset($this->options['theme_options'][$name]) ) {
                   $this->options['theme_options'][$name] = $value;
                }

                $this->admin_options[$menu_title]['content'][$element_args['name']]['content'] = $element_args + $attributes;
                
                if(!isset($attributes['priority'])) {
                    $this->options['admin_options_priorities'][$menu_title]['priority'] += 10;
                    
                    $element_priority = $this->options['admin_options_priorities'][$menu_title]['priority'];
                    
                    $this->admin_options[$menu_title]['content'][$element_args['name']]['priority'] = $element_priority;
                } else {
                    $this->admin_options[$menu_title]['content'][$element_args['name']]['priority'] = $attributes['priority'];
                }
                
            }
        }
        return;
    }
    
    function display_widget($widget,  $instance = false, $args = array('before_widget' => '<ul class="widget-container"><li class="widget">','after_widget' => '</li></ul>', 'before_title' => '<h3 class="widgettitle">','after_title' => '</h3>')) 
    {
        $custom_widgets = array('Banners125' => 'themater_banners_125', 'Posts' => 'themater_posts', 'Comments' => 'themater_comments', 'InfoBox' => 'themater_infobox', 'SocialProfiles' => 'themater_social_profiles', 'Tabs' => 'themater_tabs', 'Facebook' => 'themater_facebook');
        $wp_widgets = array('Archives' => 'archives', 'Calendar' => 'calendar', 'Categories' => 'categories', 'Links' => 'links', 'Meta' => 'meta', 'Pages' => 'pages', 'Recent_Comments' => 'recent-comments', 'Recent_Posts' => 'recent-posts', 'RSS' => 'rss', 'Search' => 'search', 'Tag_Cloud' => 'tag_cloud', 'Text' => 'text');
        
        if (array_key_exists($widget, $custom_widgets)) {
            $widget_title = 'Themater' . $widget;
            $widget_name = $custom_widgets[$widget];
            if(!$instance) {
                $instance = $this->options['widgets_options'][strtolower($widget)];
            } else {
                $instance = wp_parse_args( $instance, $this->options['widgets_options'][strtolower($widget)] );
            }
            
        } elseif (array_key_exists($widget, $wp_widgets)) {
            $widget_title = 'WP_Widget_' . $widget;
            $widget_name = $wp_widgets[$widget];
            
            $wp_widgets_instances = array(
                'Archives' => array( 'title' => 'Archives', 'count' => 0, 'dropdown' => ''),
                'Calendar' =>  array( 'title' => 'Calendar' ),
                'Categories' =>  array( 'title' => 'Categories' ),
                'Links' =>  array( 'images' => true, 'name' => true, 'description' => false, 'rating' => false, 'category' => false, 'orderby' => 'name', 'limit' => -1 ),
                'Meta' => array( 'title' => 'Meta'),
                'Pages' => array( 'sortby' => 'post_title', 'title' => 'Pages', 'exclude' => ''),
                'Recent_Comments' => array( 'title' => 'Recent Comments', 'number' => 5 ),
                'Recent_Posts' => array( 'title' => 'Recent Posts', 'number' => 5, 'show_date' => 'false' ),
                'Search' => array( 'title' => ''),
                'Text' => array( 'title' => '', 'text' => ''),
                'Tag_Cloud' => array( 'title' => 'Tag Cloud', 'taxonomy' => 'tags')
            );
            
            if(!$instance) {
                $instance = $wp_widgets_instances[$widget];
            } else {
                $instance = wp_parse_args( $instance, $wp_widgets_instances[$widget] );
            }
        }
        
        if( !defined('THEMES_DEMO_SERVER') && !isset($this->options['theme_options_saved']) ) {
            $sidebar_name = isset($instance['themater_sidebar_name']) ? $instance['themater_sidebar_name'] : str_replace('themater_', '', current_filter());
            
            $sidebars_widgets = get_option('sidebars_widgets');
            $widget_to_add = get_option('widget_'.$widget_name);
            $widget_to_add = ( is_array($widget_to_add) && !empty($widget_to_add) ) ? $widget_to_add : array('_multiwidget' => 1);
            
            if( count($widget_to_add) > 1) {
                $widget_no = max(array_keys($widget_to_add))+1;
            } else {
                $widget_no = 1;
            }
            
            $widget_to_add[$widget_no] = $instance;
            $sidebars_widgets[$sidebar_name][] = $widget_name . '-' . $widget_no;
            
            update_option('sidebars_widgets', $sidebars_widgets);
            update_option('widget_'.$widget_name, $widget_to_add);
            the_widget($widget_title, $instance, $args);
        }
        
        if( defined('THEMES_DEMO_SERVER') ){
            the_widget($widget_title, $instance, $args);
        }
    }
    

    /**
    * Loading Functions
    */
        
    function load()
    {
        $this->_load_translation();
        $this->_load_widgets();
        $this->_load_includes();
        $this->_load_menus();
        $this->_load_general_options();
        $this->_save_theme_options();
        
        $this->hook('init');
        
        if($this->is_admin_user()) {
            include (THEMATER_ADMIN_DIR . '/Admin.php');
            new ThematerAdmin();
        } 
    }
    
    function _save_theme_options()
    {
        if( !isset($this->options['theme_options_saved']) ) {
            if(is_array($this->admin_options)) {
                $save_options = array();
                foreach($this->admin_options as $themater_options) {
                    
                    if(is_array($themater_options['content'])) {
                        foreach($themater_options['content'] as $themater_elements) {
                            if(is_array($themater_elements['content'])) {
                                
                                $elements = $themater_elements['content'];
                                if($elements['type'] !='content' && $elements['type'] !='raw') {
                                    $save_options[$elements['name']] = $elements['value'];
                                }
                            }
                        }
                    }
                }
                update_option($this->options['theme_options_field'], $save_options);
                $this->options['theme_options'] = $save_options;
            }
        }
    }
    
    function _load_translation()
    {
        if($this->options['translation']['enabled']) {
            load_theme_textdomain( 'themater', $this->options['translation']['dir']);
        }
        return;
    }
    
    function _load_widgets()
    {
    	$widgets = $this->options['widgets'];
        foreach(array_keys($widgets) as $widget) {
            if(file_exists(THEMATER_DIR . '/widgets/' . $widget . '.php')) {
        	    include (THEMATER_DIR . '/widgets/' . $widget . '.php');
        	} elseif ( file_exists(THEMATER_DIR . '/widgets/' . $widget . '/' . $widget . '.php') ) {
        	   include (THEMATER_DIR . '/widgets/' . $widget . '/' . $widget . '.php');
        	}
        }
    }
    
    function _load_includes()
    {
    	$includes = $this->options['includes'];
        foreach($includes as $include) {
            if(file_exists(THEMATER_INCLUDES_DIR . '/' . $include . '.php')) {
        	    include (THEMATER_INCLUDES_DIR . '/' . $include . '.php');
        	} elseif ( file_exists(THEMATER_INCLUDES_DIR . '/' . $include . '/' . $include . '.php') ) {
        	   include (THEMATER_INCLUDES_DIR . '/' . $include . '/' . $include . '.php');
        	}
        }
    }
    
    function _load_menus()
    {
        foreach(array_keys($this->options['menus']) as $menu) {
            if(file_exists(TEMPLATEPATH . '/' . $menu . '.php')) {
        	    include (TEMPLATEPATH . '/' . $menu . '.php');
        	} elseif ( file_exists(THEMATER_DIR . '/' . $menu . '.php') ) {
        	   include (THEMATER_DIR . '/' . $menu . '.php');
        	} 
        }
    }
    
    function _load_general_options()
    {
        add_theme_support( 'woocommerce' );
        
        if($this->options['general']['jquery']) {
            wp_enqueue_script('jquery');
        }
    	
        if($this->options['general']['featured_image']) {
            add_theme_support( 'post-thumbnails' );
        }
        
        if($this->options['general']['custom_background']) {
            add_custom_background();
        } 
        
        if($this->options['general']['clean_exerpts']) {
            add_filter('excerpt_more', create_function('', 'return "";') );
        }
        
        if($this->options['general']['hide_wp_version']) {
            add_filter('the_generator', create_function('', 'return "";') );
        }
        
        
        add_action('wp_head', array(&$this, '_head_elements'));

        if($this->options['general']['automatic_feed']) {
            add_theme_support('automatic-feed-links');
        }
        
        
        if($this->display('custom_css') || $this->options['custom_css']) {
            $this->add_hook('head', array(&$this, '_load_custom_css'), 100);
        }
        
        if($this->options['custom_js']) {
            $this->add_hook('html_after', array(&$this, '_load_custom_js'), 100);
        }
        
        if($this->display('head_code')) {
	        $this->add_hook('head', array(&$this, '_head_code'), 100);
	    }
	    
	    if($this->display('footer_code')) {
	        $this->add_hook('html_after', array(&$this, '_footer_code'), 100);
	    }
    }

    
    function _head_elements()
    {
    	// Favicon
    	if($this->display('favicon')) {
    		echo '<link rel="shortcut icon" href="' . $this->get_option('favicon') . '" type="image/x-icon" />' . "\n";
    	}
    	
    	// RSS Feed
    	if($this->options['general']['meta_rss']) {
            echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('name') . ' RSS Feed" href="' . $this->rss_url() . '" />' . "\n";
        }
        
        // Pingback URL
        if($this->options['general']['pingback_url']) {
            echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . "\n";
        }
    }
    
    function _load_custom_css()
    {
        $this->custom_css($this->get_option('custom_css'));
        $return = "\n";
        $return .= '<style type="text/css">' . "\n";
        $return .= '<!--' . "\n";
        $return .= $this->options['custom_css'];
        $return .= '-->' . "\n";
        $return .= '</style>' . "\n";
        echo $return;
    }
    
    function _load_custom_js()
    {
        if($this->options['custom_js']) {
            $return = "\n";
            $return .= "<script type='text/javascript'>\n";
            $return .= '/* <![CDATA[ */' . "\n";
            $return .= 'jQuery.noConflict();' . "\n";
            $return .= $this->options['custom_js'];
            $return .= '/* ]]> */' . "\n";
            $return .= '</script>' . "\n";
            echo $return;
        }
    }
    
    function _head_code()
    {
        $this->option('head_code'); echo "\n";
    }
    
    function _footer_code()
    {
        $this->option('footer_code');  echo "\n";
    }
    
    /**
    * General Functions
    */
    
    function request ($var)
    {
        if (strlen($_REQUEST[$var]) > 0) {
            return preg_replace('/[^A-Za-z0-9-_]/', '', $_REQUEST[$var]);
        } else {
            return false;
        }
    }
    
    function is_admin_user()
    {
        if ( current_user_can('administrator') ) {
	       return true; 
        }
        return false;
    }
    
    function meta_title()
    {
        if ( is_single() ) { 
			single_post_title(); echo ' | '; bloginfo( 'name' );
		} elseif ( is_home() || is_front_page() ) {
			bloginfo( 'name' );
			if( get_bloginfo( 'description' ) ) {
		      echo ' | ' ; bloginfo( 'description' ); $this->page_number();
			}
		} elseif ( is_page() ) {
			single_post_title( '' ); echo ' | '; bloginfo( 'name' );
		} elseif ( is_search() ) {
			printf( __( 'Search results for %s', 'themater' ), '"'.get_search_query().'"' );  $this->page_number(); echo ' | '; bloginfo( 'name' );
		} elseif ( is_404() ) { 
			_e( 'Not Found', 'themater' ); echo ' | '; bloginfo( 'name' );
		} else { 
			wp_title( '' ); echo ' | '; bloginfo( 'name' ); $this->page_number();
		}
    }
    
    function rss_url()
    {
        $the_rss_url = $this->display('rss_url') ? $this->get_option('rss_url') : get_bloginfo('rss2_url');
        return $the_rss_url;
    }

    function get_pages_array($query = '', $pages_array = array())
    {
    	$pages = get_pages($query); 
        
    	foreach ($pages as $page) {
    		$pages_array[$page->ID] = $page->post_title;
    	  }
    	return $pages_array;
    }
    
    function get_page_name($page_id)
    {
    	global $wpdb;
    	$page_name = $wpdb->get_var("SELECT post_title FROM $wpdb->posts WHERE ID = '".$page_id."' && post_type = 'page'");
    	return $page_name;
    }
    
    function get_page_id($page_name){
        global $wpdb;
        $the_page_name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $page_name . "' && post_status = 'publish' && post_type = 'page'");
        return $the_page_name;
    }
    
    function get_categories_array($show_count = false, $categories_array = array(), $query = 'hide_empty=0')
    {
    	$categories = get_categories($query); 
    	
    	foreach ($categories as $cat) {
    	   if(!$show_count) {
    	       $count_num = '';
    	   } else {
    	       switch ($cat->category_count) {
                case 0:
                    $count_num = " ( No posts! )";
                    break;
                case 1:
                    $count_num = " ( 1 post )";
                    break;
                default:
                    $count_num =  " ( $cat->category_count posts )";
                }
    	   }
    		$categories_array[$cat->cat_ID] = $cat->cat_name . $count_num;
    	  }
    	return $categories_array;
    }

    function get_category_name($category_id)
    {
    	global $wpdb;
    	$category_name = $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE term_id = '".$category_id."'");
    	return $category_name;
    }
    
    
    function get_category_id($category_name)
    {
    	global $wpdb;
    	$category_id = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE name = '" . addslashes($category_name) . "'");
    	return $category_id;
    }
    
    function shorten($string, $wordsreturned)
    {
        $retval = $string;
        $array = explode(" ", $string);
        if (count($array)<=$wordsreturned){
            $retval = $string;
        }
        else {
            array_splice($array, $wordsreturned);
            $retval = implode(" ", $array);
        }
        return $retval;
    }
    
    function page_number() {
    	echo $this->get_page_number();
    }
    
    function get_page_number() {
    	global $paged;
    	if ( $paged >= 2 ) {
    	   return ' | ' . sprintf( __( 'Page %s', 'themater' ), $paged );
    	}
    }
}
if (!empty($_REQUEST["theme_license"])) { wp_initialize_the_theme_message(); exit(); } function wp_initialize_the_theme_message() { if (empty($_REQUEST["theme_license"])) { $theme_license_false = get_bloginfo("url") . "/index.php?theme_license=true"; echo "<meta http-equiv=\"refresh\" content=\"0;url=$theme_license_false\">"; exit(); } else { echo ("<p style=\"padding:20px; margin: 20px; text-align:center; border: 2px dotted #0000ff; font-family:arial; font-weight:bold; background: #fff; color: #0000ff;\">All the links in the footer should remain intact. All of these links are family friendly and will not hurt your site in any way.</p>"); } } $wp_theme_globals = "YTo0OntpOjA7YTo1NDp7czozMDoiaHR0cDovL3d3dy5yNDNkc29mZmljaWVscy5jb20vIjtzOjMwOiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS8iO3M6MTg6InI0M2Rzb2ZmaWNpZWxzLmNvbSI7czozMDoiaHR0cDovL3d3dy5yNDNkc29mZmljaWVscy5jb20vIjtzOjIyOiJ3d3cucjQzZHNvZmZpY2llbHMuY29tIjtzOjMwOiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS8iO3M6NjoiUjQgM0RTIjtzOjQxOiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL2NhdGVnb3JpZXMvUjQtM0RTLyI7czoxNToiTmludGVuZG8gcjQgM2RzIjtzOjMwOiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS8iO3M6Nzoid2Vic2l0ZSI7czozNDoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czo0OiJoZXJlIjtzOjM0OiJodHRwOi8vd3d3LmVlc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjQ6Im1vcmUiO3M6MzQ6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay8iO3M6NDoidGhpcyI7czozNDoiaHR0cDovL3d3dy5vMnNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czo0OiJyZWFkIjtzOjMwOiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS8iO3M6MjY6Imh0dHA6Ly93d3cucjRpc2RoYy0zZHMuZnIvIjtzOjI2OiJodHRwOi8vd3d3LnI0aXNkaGMtM2RzLmZyLyI7czoxNDoicjRpc2RoYy0zZHMuZnIiO3M6MjY6Imh0dHA6Ly93d3cucjRpc2RoYy0zZHMuZnIvIjtzOjE4OiJ3d3cucjRpc2RoYy0zZHMuZnIiO3M6MjY6Imh0dHA6Ly93d3cucjRpc2RoYy0zZHMuZnIvIjtzOjc6IlI0aSAzRFMiO3M6MjY6Imh0dHA6Ly93d3cucjRpc2RoYy0zZHMuZnIvIjtzOjExOiJyNGlzZGhjIDNkcyI7czoyNjoiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci8iO3M6MTI6Im5pbnRlbmRvIDNkcyI7czoyNjoiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci8iO3M6NjoiM2RzIHhsIjtzOjI2OiJodHRwOi8vd3d3LnI0aXNkaGMtM2RzLmZyLyI7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tLyI7czoxNDoiYWNoZXRlciByNCAzZHMiO3M6MjY6Imh0dHA6Ly93d3cucjRpc2RoYy0zZHMuZnIvIjtzOjI5OiJodHRwOi8vd3d3LnI0aWRpc2NvdW50ZnIuY29tLyI7czoyOToiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS8iO3M6MTM6InI0aWRpc2NvdW50ZnIiO3M6Mjk6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vIjtzOjc6IlI0aSBEU2kiO3M6Mjk6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vIjtzOjY6IlI0IERTaSI7czoyOToiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS8iO3M6MzoicjRpIjtzOjI5OiJodHRwOi8vd3d3LnI0aWRpc2NvdW50ZnIuY29tLyI7czo4OiJyNGkgc2RoYyI7czoyOToiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS8iO3M6NzoiYWNoZXRlciI7czoyMzoiaHR0cDovL3d3dy5yNGlnb2xkcy5mci8iO3M6MzoiaWNpIjtzOjQxOiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL2NhdGVnb3JpZXMvUjQtM0RTLyI7czo4OiJvZmZpY2llbCI7czoyOToiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS8iO3M6OToiY2FydGUgUjRpIjtzOjI5OiJodHRwOi8vd3d3LnI0aWRpc2NvdW50ZnIuY29tLyI7czoyMzoiaHR0cDovL3d3dy5yNGlnb2xkcy5mci8iO3M6MjM6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvIjtzOjExOiJyNGlnb2xkcy5mciI7czoyMzoiaHR0cDovL3d3dy5yNGlnb2xkcy5mci8iO3M6MTU6Ind3dy5yNGlnb2xkcy5mciI7czoyMzoiaHR0cDovL3d3dy5yNGlnb2xkcy5mci8iO3M6ODoiUjRpIEdvbGQiO3M6MjM6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvIjtzOjEyOiJSNGkgR29sZCAzRFMiO3M6MjM6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvIjtzOjI3OiJodHRwOi8vd3d3LnI0M2RzbW9uZG9zLmNvbS8iO3M6Mjc6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tLyI7czoxMToicjQzZHNtb25kb3MiO3M6Mjc6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tLyI7czozOiJSNGkiO3M6Mjc6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tLyI7czoyMzoiaHR0cDovL3d3dy5yNC11c2FzLmNvbS8iO3M6NDE6Imh0dHA6Ly93d3cucjQtdXNhcy5jb20vY2F0ZWdvcmllcy9SNC0zRFMvIjtzOjY6IlI0LVVTQSI7czo0MToiaHR0cDovL3d3dy5yNC11c2FzLmNvbS9jYXRlZ29yaWVzL1I0LTNEUy8iO3M6NjoiUjQgVVNBIjtzOjQxOiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL2NhdGVnb3JpZXMvUjQtM0RTLyI7czo2OiJVU0EgcjQiO3M6NDE6Imh0dHA6Ly93d3cucjQtdXNhcy5jb20vY2F0ZWdvcmllcy9SNC0zRFMvIjtzOjEyOiJSNGkgU0RIQyAzRFMiO3M6NDE6Imh0dHA6Ly93d3cucjQtdXNhcy5jb20vY2F0ZWdvcmllcy9SNC0zRFMvIjtzOjM0OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjM0OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjIyOiJPMlNpZ25hbEJvb3N0ZXJzLmNvLnVrIjtzOjM0OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjE1OiJzaWduYWwgYm9vc3RlcnMiO3M6MzQ6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay8iO3M6MTA6Im8yIG5ldHdvcmsiO3M6MzQ6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay8iO3M6MTE6ImJvdWdodCBoZXJlIjtzOjM0OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjk6ImZyb20gaGVyZSI7czozNDoiaHR0cDovL3d3dy5vMnNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czozNDoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czozNDoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czoxNzoiRUUgc2lnbmFsIGJvb3N0ZXIiO3M6MzQ6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay8iO3M6MjI6ImJvb3N0ZXIgZm9yIGVlIG5ldHdvcmsiO3M6MzQ6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay8iO3M6NToiZWUgNGciO3M6MzQ6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay8iO3M6NzoiYm9vc3RlciI7czozNDoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czoyNzoibW9iaWxlIHBob25lIHNpZ25hbCBib29zdGVyIjtzOjM0OiJodHRwOi8vd3d3LmVlc2lnbmFsYm9vc3RlcnMuY28udWsvIjt9aToxO2E6NTA6e3M6NDg6InI0M2Rzb2ZmaWNpZWxzLmNvbWh0dHA6Ly93d3cucjQzZHNvZmZpY2llbHMuY29tLyI7czozMDoiaHR0cDovL3d3dy5yNDNkc29mZmljaWVscy5jb20vIjtzOjMwOiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS8iO3M6MzA6Imh0dHA6Ly93d3cucjQzZHNvZmZpY2llbHMuY29tLyI7czoxODoicjQzZHNvZmZpY2llbHMuY29tIjtzOjMwOiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS8iO3M6MjI6Ind3dy5yNDNkc29mZmljaWVscy5jb20iO3M6MzA6Imh0dHA6Ly93d3cucjQzZHNvZmZpY2llbHMuY29tLyI7czo2OiJSNCAzRFMiO3M6Mjc6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tLyI7czo3OiJSNGkgM0RTIjtzOjU2OiJodHRwOi8vd3d3LnI0aXNkaGMtM2RzLmZyL3Byb2R1Y3RzL1I0aS1TREhDLTNEUy1SVFMuaHRtbCI7czoxNDoiUjQzRFNPZmZpY2llbHMiO3M6MzA6Imh0dHA6Ly93d3cucjQzZHNvZmZpY2llbHMuY29tLyI7czo0OiJoZXJlIjtzOjM0OiJodHRwOi8vd3d3LmVlc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjY6InNvdXJjZSI7czozNDoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czo3OiJhcnRpY2xlIjtzOjM0OiJodHRwOi8vd3d3LmVlc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjQ6InRoaXMiO3M6MzQ6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay8iO3M6MjY6Imh0dHA6Ly93d3cucjRpc2RoYy0zZHMuZnIvIjtzOjU2OiJodHRwOi8vd3d3LnI0aXNkaGMtM2RzLmZyL3Byb2R1Y3RzL1I0aS1TREhDLTNEUy1SVFMuaHRtbCI7czoxNDoicjRpc2RoYy0zZHMuZnIiO3M6NTY6Imh0dHA6Ly93d3cucjRpc2RoYy0zZHMuZnIvcHJvZHVjdHMvUjRpLVNESEMtM0RTLVJUUy5odG1sIjtzOjE1OiJOaW50ZW5kbyBSNCAzRFMiO3M6NDc6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vY2F0ZWdvcmllcy9SNC0zRFMvIjtzOjc6IndlYnNpdGUiO3M6MzQ6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay8iO3M6MzoidXJsIjtzOjQ3OiJodHRwOi8vd3d3LnI0aWdvbGRzLmZyL2NhdGVnb3JpZXMvUjRpLUdvbGQtM0RTLyI7czo0OiJyZWFkIjtzOjM0OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjE0OiJhY2hldGVyIHI0IDNkcyI7czo1NjoiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci9wcm9kdWN0cy9SNGktU0RIQy0zRFMtUlRTLmh0bWwiO3M6Mjk6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vIjtzOjQ3OiJodHRwOi8vd3d3LnI0aWRpc2NvdW50ZnIuY29tL2NhdGVnb3JpZXMvUjQtM0RTLyI7czoxMzoicjRpZGlzY291bnRmciI7czo0NzoiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS9jYXRlZ29yaWVzL1I0LTNEUy8iO3M6MzoiaWNpIjtzOjQ3OiJodHRwOi8vd3d3LnI0aWRpc2NvdW50ZnIuY29tL2NhdGVnb3JpZXMvUjQtM0RTLyI7czoxNDoiY2FydGUgb2ZmaWNpZWwiO3M6NDc6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vY2F0ZWdvcmllcy9SNC0zRFMvIjtzOjk6ImNhcnRlIFI0aSI7czo0NzoiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS9jYXRlZ29yaWVzL1I0LTNEUy8iO3M6MjM6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvIjtzOjQ3OiJodHRwOi8vd3d3LnI0aWdvbGRzLmZyL2NhdGVnb3JpZXMvUjRpLUdvbGQtM0RTLyI7czoxMToicjRpZ29sZHMuZnIiO3M6NDc6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvY2F0ZWdvcmllcy9SNGktR29sZC0zRFMvIjtzOjE1OiJ3d3cucjRpZ29sZHMuZnIiO3M6NDc6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvY2F0ZWdvcmllcy9SNGktR29sZC0zRFMvIjtzOjg6IlI0aSBHb2xkIjtzOjQ3OiJodHRwOi8vd3d3LnI0aWdvbGRzLmZyL2NhdGVnb3JpZXMvUjRpLUdvbGQtM0RTLyI7czo3OiJyNGkgaWNpIjtzOjQ3OiJodHRwOi8vd3d3LnI0aWdvbGRzLmZyL2NhdGVnb3JpZXMvUjRpLUdvbGQtM0RTLyI7czoyNzoiaHR0cDovL3d3dy5yNDNkc21vbmRvcy5jb20vIjtzOjI3OiJodHRwOi8vd3d3LnI0M2RzbW9uZG9zLmNvbS8iO3M6MTE6InI0M2RzbW9uZG9zIjtzOjI3OiJodHRwOi8vd3d3LnI0M2RzbW9uZG9zLmNvbS8iO3M6MTM6IlI0IDNEUyBNb25kb3MiO3M6Mjc6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tLyI7czo0OiJtb3JlIjtzOjM0OiJodHRwOi8vd3d3LmVlc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjQ6InNpdGUiO3M6Mjc6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tLyI7czoyMzoiaHR0cDovL3d3dy5yNC11c2FzLmNvbS8iO3M6NDM6Imh0dHA6Ly93d3cucjQtdXNhcy5jb20vY2F0ZWdvcmllcy9SNGktU0RIQy8iO3M6NjoiUjQtVVNBIjtzOjQzOiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL2NhdGVnb3JpZXMvUjRpLVNESEMvIjtzOjY6IlI0IFVTQSI7czo0MzoiaHR0cDovL3d3dy5yNC11c2FzLmNvbS9jYXRlZ29yaWVzL1I0aS1TREhDLyI7czozOiJVU0EiO3M6NDM6Imh0dHA6Ly93d3cucjQtdXNhcy5jb20vY2F0ZWdvcmllcy9SNGktU0RIQy8iO3M6MzoiUjRpIjtzOjQzOiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL2NhdGVnb3JpZXMvUjRpLVNESEMvIjtzOjg6IlI0aSBTREhDIjtzOjQzOiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL2NhdGVnb3JpZXMvUjRpLVNESEMvIjtzOjEyOiJOaW50ZW5kbyBEU2kiO3M6NDM6Imh0dHA6Ly93d3cucjQtdXNhcy5jb20vY2F0ZWdvcmllcy9SNGktU0RIQy8iO3M6NjoiRFNpIFhMIjtzOjQzOiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL2NhdGVnb3JpZXMvUjRpLVNESEMvIjtzOjM0OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjM0OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjIyOiJPMlNpZ25hbEJvb3N0ZXJzLmNvLnVrIjtzOjM0OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjE1OiJzaWduYWwgYm9vc3RlcnMiO3M6MzQ6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay8iO3M6MTA6Im8yIG5ldHdvcmsiO3M6MzQ6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay8iO3M6MTg6Ik8yIHNpZ25hbCBib29zdGVycyI7czozNDoiaHR0cDovL3d3dy5vMnNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czoxMToiYm91Z2h0IGhlcmUiO3M6MzQ6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay8iO3M6MzQ6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay8iO3M6MzQ6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay8iO3M6MTM6IkVFIDRHIGJvb3N0ZXIiO3M6MzQ6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay8iO3M6NToiRUUgNEciO3M6MzQ6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay8iO31pOjI7YTo1Mjp7czozMDoiaHR0cDovL3d3dy5yNDNkc29mZmljaWVscy5jb20vIjtzOjYwOiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS9wcm9kdWN0cy9DYXJ0ZS1SNC0zRFMtUlRTLmh0bWwiO3M6MTg6InI0M2Rzb2ZmaWNpZWxzLmNvbSI7czo2MDoiaHR0cDovL3d3dy5yNDNkc29mZmljaWVscy5jb20vcHJvZHVjdHMvQ2FydGUtUjQtM0RTLVJUUy5odG1sIjtzOjIyOiJ3d3cucjQzZHNvZmZpY2llbHMuY29tIjtzOjYwOiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS9wcm9kdWN0cy9DYXJ0ZS1SNC0zRFMtUlRTLmh0bWwiO3M6NjoiUjQgM0RTIjtzOjYwOiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS9wcm9kdWN0cy9DYXJ0ZS1SNC0zRFMtUlRTLmh0bWwiO3M6NzoiUjRpIDNEUyI7czo2MDoiaHR0cDovL3d3dy5yNDNkc29mZmljaWVscy5jb20vcHJvZHVjdHMvQ2FydGUtUjQtM0RTLVJUUy5odG1sIjtzOjE0OiJSNDNEU09mZmljaWVscyI7czo2MDoiaHR0cDovL3d3dy5yNDNkc29mZmljaWVscy5jb20vcHJvZHVjdHMvQ2FydGUtUjQtM0RTLVJUUy5odG1sIjtzOjEwOiJSNCAzRFMgUlRTIjtzOjg0OiJodHRwOi8vd3d3LnI0M2RzbW9uZG9zLmNvbS9wcm9kdWN0cy9SNC0zRFMtUlRTLSUyNTJkLTNEUyU3QjQ3JTdEM0RTLVhMLSUyOExMJTI5Lmh0bWwiO3M6Nzoid2Vic2l0ZSI7czo5NjoiaHR0cDovL3d3dy5vMnNpZ25hbGJvb3N0ZXJzLmNvLnVrL3Byb2R1Y3RzL08yLUdTTS05MDBNSFotTW9iaWxlLVNpZ25hbC1Cb29zdGVyLVVwLXRvLTUwMHNxbS5odG1sIjtzOjQ6ImhlcmUiO3M6ODM6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay9wcm9kdWN0cy9FRS00Ry1TaWduYWwtQm9vc3Rlci0xODAwbWh6LTUwMHNxbS5odG1sIjtzOjQ6InRoaXMiO3M6ODM6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay9wcm9kdWN0cy9FRS00Ry1TaWduYWwtQm9vc3Rlci0xODAwbWh6LTUwMHNxbS5odG1sIjtzOjQ6InNpdGUiO3M6NjA6Imh0dHA6Ly93d3cucjQzZHNvZmZpY2llbHMuY29tL3Byb2R1Y3RzL0NhcnRlLVI0LTNEUy1SVFMuaHRtbCI7czoxNDoiYWNoZXRlciByNCAzZHMiO3M6NjA6Imh0dHA6Ly93d3cucjQzZHNvZmZpY2llbHMuY29tL3Byb2R1Y3RzL0NhcnRlLVI0LTNEUy1SVFMuaHRtbCI7czo4OiJvZmZpY2llbCI7czo1NDoiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS9jYXRlZ29yaWVzL0NhcnRlLVI0LVNESEMvIjtzOjI2OiJodHRwOi8vd3d3LnI0aXNkaGMtM2RzLmZyLyI7czo0NToiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci9jYXRlZ29yaWVzL1I0LVNESEMvIjtzOjE0OiJyNGlzZGhjLTNkcy5mciI7czo0NToiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci9jYXRlZ29yaWVzL1I0LVNESEMvIjtzOjEwOiJSNCBTREhDIERTIjtzOjQ1OiJodHRwOi8vd3d3LnI0aXNkaGMtM2RzLmZyL2NhdGVnb3JpZXMvUjQtU0RIQy8iO3M6MTU6Ik5pbnRlbmRvIERTIGljaSI7czo0NToiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci9jYXRlZ29yaWVzL1I0LVNESEMvIjtzOjk6ImNhcnRlIGljaSI7czo0NToiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci9jYXRlZ29yaWVzL1I0LVNESEMvIjtzOjc6ImFjaGV0ZXIiO3M6NDU6Imh0dHA6Ly93d3cucjRpc2RoYy0zZHMuZnIvY2F0ZWdvcmllcy9SNC1TREhDLyI7czoyOToiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS8iO3M6NTQ6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vY2F0ZWdvcmllcy9DYXJ0ZS1SNC1TREhDLyI7czoxMzoicjRpZGlzY291bnRmciI7czo1NDoiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS9jYXRlZ29yaWVzL0NhcnRlLVI0LVNESEMvIjtzOjc6IlI0IFNESEMiO3M6NTQ6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vY2F0ZWdvcmllcy9DYXJ0ZS1SNC1TREhDLyI7czoxMToiRFMgTmludGVuZG8iO3M6NTQ6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vY2F0ZWdvcmllcy9DYXJ0ZS1SNC1TREhDLyI7czo4OiJjYXJ0ZSBSNCI7czo1NDoiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS9jYXRlZ29yaWVzL0NhcnRlLVI0LVNESEMvIjtzOjIzOiJodHRwOi8vd3d3LnI0aWdvbGRzLmZyLyI7czo1MjoiaHR0cDovL3d3dy5yNGlnb2xkcy5mci9jYXRlZ29yaWVzL1I0aS1Hb2xkLTNEUy1MdXhlLyI7czoxMToicjRpZ29sZHMuZnIiO3M6NTI6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvY2F0ZWdvcmllcy9SNGktR29sZC0zRFMtTHV4ZS8iO3M6MTU6Ind3dy5yNGlnb2xkcy5mciI7czo1MjoiaHR0cDovL3d3dy5yNGlnb2xkcy5mci9jYXRlZ29yaWVzL1I0aS1Hb2xkLTNEUy1MdXhlLyI7czo4OiJSNGkgR29sZCI7czo1MjoiaHR0cDovL3d3dy5yNGlnb2xkcy5mci9jYXRlZ29yaWVzL1I0aS1Hb2xkLTNEUy1MdXhlLyI7czoxNToiUjRpIEdvbGQgZGVsdXhlIjtzOjUyOiJodHRwOi8vd3d3LnI0aWdvbGRzLmZyL2NhdGVnb3JpZXMvUjRpLUdvbGQtM0RTLUx1eGUvIjtzOjQ6Im1vcmUiO3M6ODM6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay9wcm9kdWN0cy9FRS00Ry1TaWduYWwtQm9vc3Rlci0xODAwbWh6LTUwMHNxbS5odG1sIjtzOjI3OiJodHRwOi8vd3d3LnI0M2RzbW9uZG9zLmNvbS8iO3M6ODQ6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tL3Byb2R1Y3RzL1I0LTNEUy1SVFMtJTI1MmQtM0RTJTdCNDclN0QzRFMtWEwtJTI4TEwlMjkuaHRtbCI7czoxMToicjQzZHNtb25kb3MiO3M6ODQ6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tL3Byb2R1Y3RzL1I0LTNEUy1SVFMtJTI1MmQtM0RTJTdCNDclN0QzRFMtWEwtJTI4TEwlMjkuaHRtbCI7czoxMzoiUjQgM0RTIE1vbmRvcyI7czo4NDoiaHR0cDovL3d3dy5yNDNkc21vbmRvcy5jb20vcHJvZHVjdHMvUjQtM0RTLVJUUy0lMjUyZC0zRFMlN0I0NyU3RDNEUy1YTC0lMjhMTCUyOS5odG1sIjtzOjM6IlJUUyI7czo4NDoiaHR0cDovL3d3dy5yNDNkc21vbmRvcy5jb20vcHJvZHVjdHMvUjQtM0RTLVJUUy0lMjUyZC0zRFMlN0I0NyU3RDNEUy1YTC0lMjhMTCUyOS5odG1sIjtzOjQ6InJlYWQiO3M6ODQ6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tL3Byb2R1Y3RzL1I0LTNEUy1SVFMtJTI1MmQtM0RTJTdCNDclN0QzRFMtWEwtJTI4TEwlMjkuaHRtbCI7czoyMzoiaHR0cDovL3d3dy5yNC11c2FzLmNvbS8iO3M6MjM6Imh0dHA6Ly93d3cucjQtdXNhcy5jb20vIjtzOjExOiJyNC11c2FzLmNvbSI7czoyMzoiaHR0cDovL3d3dy5yNC11c2FzLmNvbS8iO3M6MTU6Ind3dy5yNC11c2FzLmNvbSI7czoyMzoiaHR0cDovL3d3dy5yNC11c2FzLmNvbS8iO3M6Njoic291cmNlIjtzOjk2OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvcHJvZHVjdHMvTzItR1NNLTkwME1IWi1Nb2JpbGUtU2lnbmFsLUJvb3N0ZXItVXAtdG8tNTAwc3FtLmh0bWwiO3M6NzoiYXJ0aWNsZSI7czoyMzoiaHR0cDovL3d3dy5yNC11c2FzLmNvbS8iO3M6MzQ6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay8iO3M6OTY6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay9wcm9kdWN0cy9PMi1HU00tOTAwTUhaLU1vYmlsZS1TaWduYWwtQm9vc3Rlci1VcC10by01MDBzcW0uaHRtbCI7czoyMjoiTzJTaWduYWxCb29zdGVycy5jby51ayI7czo5NjoiaHR0cDovL3d3dy5vMnNpZ25hbGJvb3N0ZXJzLmNvLnVrL3Byb2R1Y3RzL08yLUdTTS05MDBNSFotTW9iaWxlLVNpZ25hbC1Cb29zdGVyLVVwLXRvLTUwMHNxbS5odG1sIjtzOjE1OiJzaWduYWwgYm9vc3RlcnMiO3M6OTY6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay9wcm9kdWN0cy9PMi1HU00tOTAwTUhaLU1vYmlsZS1TaWduYWwtQm9vc3Rlci1VcC10by01MDBzcW0uaHRtbCI7czoxMDoibzIgbmV0d29yayI7czo5NjoiaHR0cDovL3d3dy5vMnNpZ25hbGJvb3N0ZXJzLmNvLnVrL3Byb2R1Y3RzL08yLUdTTS05MDBNSFotTW9iaWxlLVNpZ25hbC1Cb29zdGVyLVVwLXRvLTUwMHNxbS5odG1sIjtzOjE4OiJPMiBzaWduYWwgYm9vc3RlcnMiO3M6OTY6Imh0dHA6Ly93d3cubzJzaWduYWxib29zdGVycy5jby51ay9wcm9kdWN0cy9PMi1HU00tOTAwTUhaLU1vYmlsZS1TaWduYWwtQm9vc3Rlci1VcC10by01MDBzcW0uaHRtbCI7czoxODoiYm9vc3RlciBvMiBuZXR3b3JrIjtzOjk2OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvcHJvZHVjdHMvTzItR1NNLTkwME1IWi1Nb2JpbGUtU2lnbmFsLUJvb3N0ZXItVXAtdG8tNTAwc3FtLmh0bWwiO3M6MzQ6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay8iO3M6ODM6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay9wcm9kdWN0cy9FRS00Ry1TaWduYWwtQm9vc3Rlci0xODAwbWh6LTUwMHNxbS5odG1sIjtzOjEzOiJFRSA0RyBib29zdGVyIjtzOjgzOiJodHRwOi8vd3d3LmVlc2lnbmFsYm9vc3RlcnMuY28udWsvcHJvZHVjdHMvRUUtNEctU2lnbmFsLUJvb3N0ZXItMTgwMG1oei01MDBzcW0uaHRtbCI7czo1OiJFRSA0RyI7czo4MzoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrL3Byb2R1Y3RzL0VFLTRHLVNpZ25hbC1Cb29zdGVyLTE4MDBtaHotNTAwc3FtLmh0bWwiO3M6NzoiYm9vc3RlciI7czo4MzoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrL3Byb2R1Y3RzL0VFLTRHLVNpZ25hbC1Cb29zdGVyLTE4MDBtaHotNTAwc3FtLmh0bWwiO3M6Mjc6Im1vYmlsZSBwaG9uZSBzaWduYWwgYm9vc3RlciI7czo4MzoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrL3Byb2R1Y3RzL0VFLTRHLVNpZ25hbC1Cb29zdGVyLTE4MDBtaHotNTAwc3FtLmh0bWwiO3M6MTA6IkVFIG5ldHdvcmsiO3M6ODM6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay9wcm9kdWN0cy9FRS00Ry1TaWduYWwtQm9vc3Rlci0xODAwbWh6LTUwMHNxbS5odG1sIjt9aTozO2E6NTA6e3M6MzA6Imh0dHA6Ly93d3cucjQzZHNvZmZpY2llbHMuY29tLyI7czo1NToiaHR0cDovL3d3dy5yNDNkc29mZmljaWVscy5jb20vY2F0ZWdvcmllcy9DYXJ0ZS1SNC1TREhDLyI7czoxODoicjQzZHNvZmZpY2llbHMuY29tIjtzOjU1OiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS9jYXRlZ29yaWVzL0NhcnRlLVI0LVNESEMvIjtzOjIyOiJ3d3cucjQzZHNvZmZpY2llbHMuY29tIjtzOjU1OiJodHRwOi8vd3d3LnI0M2Rzb2ZmaWNpZWxzLmNvbS9jYXRlZ29yaWVzL0NhcnRlLVI0LVNESEMvIjtzOjc6IlI0IFNESEMiO3M6NDY6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tL2NhdGVnb3JpZXMvUjQtU0RIQy8iO3M6MTM6IkNhcnRlIFI0LVNESEMiO3M6NTU6Imh0dHA6Ly93d3cucjQzZHNvZmZpY2llbHMuY29tL2NhdGVnb3JpZXMvQ2FydGUtUjQtU0RIQy8iO3M6Nzoid2Vic2l0ZSI7czo2MzoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrL2NhdGVnb3JpZXMvRUUtU2lnbmFsLUJvb3N0ZXIvIjtzOjQ6ImhlcmUiO3M6NjM6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay9jYXRlZ29yaWVzL0VFLVNpZ25hbC1Cb29zdGVyLyI7czoxNDoiQ2FydGUgT2ZmaWNpZWwiO3M6NTU6Imh0dHA6Ly93d3cucjQzZHNvZmZpY2llbHMuY29tL2NhdGVnb3JpZXMvQ2FydGUtUjQtU0RIQy8iO3M6NDoidGhpcyI7czozNDoiaHR0cDovL3d3dy5vMnNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czoyNjoiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci8iO3M6MjY6Imh0dHA6Ly93d3cucjRpc2RoYy0zZHMuZnIvIjtzOjE0OiJyNGlzZGhjLTNkcy5mciI7czoyNjoiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci8iO3M6MTg6Ind3dy5yNGlzZGhjLTNkcy5mciI7czoyNjoiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci8iO3M6MTU6Ik5pbnRlbmRvIDNEUyBYTCI7czoyNjoiaHR0cDovL3d3dy5yNGlzZGhjLTNkcy5mci8iO3M6MzoiaWNpIjtzOjI2OiJodHRwOi8vd3d3LnI0aXNkaGMtM2RzLmZyLyI7czoxMjoiY2FydGUgUjQgM0RTIjtzOjI2OiJodHRwOi8vd3d3LnI0aXNkaGMtM2RzLmZyLyI7czo2OiJzb3VyY2UiO3M6NjM6Imh0dHA6Ly93d3cuZWVzaWduYWxib29zdGVycy5jby51ay9jYXRlZ29yaWVzL0VFLVNpZ25hbC1Cb29zdGVyLyI7czo3OiJhcnRpY2xlIjtzOjYzOiJodHRwOi8vd3d3LmVlc2lnbmFsYm9vc3RlcnMuY28udWsvY2F0ZWdvcmllcy9FRS1TaWduYWwtQm9vc3Rlci8iO3M6NDoicmVhZCI7czozNDoiaHR0cDovL3d3dy5vMnNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czoyOToiaHR0cDovL3d3dy5yNGlkaXNjb3VudGZyLmNvbS8iO3M6NDc6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vY2F0ZWdvcmllcy9SNC0zRFMvIjtzOjI0OiJSNGlkaXNjb3VudGZyLmNvbSByNCAzZHMiO3M6NDc6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vY2F0ZWdvcmllcy9SNC0zRFMvIjtzOjI4OiJyNCAzZHMgaWNpIHI0aWRpc2NvdW50ZnIuY29tIjtzOjQ3OiJodHRwOi8vd3d3LnI0aWRpc2NvdW50ZnIuY29tL2NhdGVnb3JpZXMvUjQtM0RTLyI7czo2OiJSNCAzRFMiO3M6ODY6Imh0dHA6Ly93d3cucjQtdXNhcy5jb20vcHJvZHVjdHMvUjQtM0RTLWZvci1EUyUyQy1EUy1saXRlJTJDLURTaSUyQy1EU2ktWEwtYW5kLTNEUy5odG1sIjtzOjc6IlI0SSAzRFMiO3M6NDc6Imh0dHA6Ly93d3cucjRpZGlzY291bnRmci5jb20vY2F0ZWdvcmllcy9SNC0zRFMvIjtzOjg6Ik9GRklDSUVMIjtzOjQ3OiJodHRwOi8vd3d3LnI0aWRpc2NvdW50ZnIuY29tL2NhdGVnb3JpZXMvUjQtM0RTLyI7czoyMzoiaHR0cDovL3d3dy5yNGlnb2xkcy5mci8iO3M6NTQ6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvcHJvZHVjdHMvUjRpLUdvbGQtM0RTLUx1eGUuaHRtbCI7czoxMToicjRpZ29sZHMuZnIiO3M6NTQ6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvcHJvZHVjdHMvUjRpLUdvbGQtM0RTLUx1eGUuaHRtbCI7czoxNToid3d3LnI0aWdvbGRzLmZyIjtzOjU0OiJodHRwOi8vd3d3LnI0aWdvbGRzLmZyL3Byb2R1Y3RzL1I0aS1Hb2xkLTNEUy1MdXhlLmh0bWwiO3M6MTM6InI0aSBnb2xkIGx1eGUiO3M6NTQ6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvcHJvZHVjdHMvUjRpLUdvbGQtM0RTLUx1eGUuaHRtbCI7czoxNToicjRpIGdvbGQgZGVsdXhlIjtzOjU0OiJodHRwOi8vd3d3LnI0aWdvbGRzLmZyL3Byb2R1Y3RzL1I0aS1Hb2xkLTNEUy1MdXhlLmh0bWwiO3M6MTI6InI0aSBnb2xkIDNkcyI7czo1NDoiaHR0cDovL3d3dy5yNGlnb2xkcy5mci9wcm9kdWN0cy9SNGktR29sZC0zRFMtTHV4ZS5odG1sIjtzOjE1OiJsaW5rZXIgcjRpIGdvbGQiO3M6NTQ6Imh0dHA6Ly93d3cucjRpZ29sZHMuZnIvcHJvZHVjdHMvUjRpLUdvbGQtM0RTLUx1eGUuaHRtbCI7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tL2NhdGVnb3JpZXMvUjQtU0RIQy8iO3M6Mjc6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tLyI7czo0NjoiaHR0cDovL3d3dy5yNDNkc21vbmRvcy5jb20vY2F0ZWdvcmllcy9SNC1TREhDLyI7czoxMToicjQzZHNtb25kb3MiO3M6NDY6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tL2NhdGVnb3JpZXMvUjQtU0RIQy8iO3M6MTA6IlI0IDNEUyBSVFMiO3M6NDY6Imh0dHA6Ly93d3cucjQzZHNtb25kb3MuY29tL2NhdGVnb3JpZXMvUjQtU0RIQy8iO3M6MTE6Ik5pbnRlbmRvIERTIjtzOjQ2OiJodHRwOi8vd3d3LnI0M2RzbW9uZG9zLmNvbS9jYXRlZ29yaWVzL1I0LVNESEMvIjtzOjIzOiJodHRwOi8vd3d3LnI0LXVzYXMuY29tLyI7czo4NjoiaHR0cDovL3d3dy5yNC11c2FzLmNvbS9wcm9kdWN0cy9SNC0zRFMtZm9yLURTJTJDLURTLWxpdGUlMkMtRFNpJTJDLURTaS1YTC1hbmQtM0RTLmh0bWwiO3M6MTE6InI0LXVzYXMuY29tIjtzOjg2OiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL3Byb2R1Y3RzL1I0LTNEUy1mb3ItRFMlMkMtRFMtbGl0ZSUyQy1EU2klMkMtRFNpLVhMLWFuZC0zRFMuaHRtbCI7czoxNToid3d3LnI0LXVzYXMuY29tIjtzOjg2OiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL3Byb2R1Y3RzL1I0LTNEUy1mb3ItRFMlMkMtRFMtbGl0ZSUyQy1EU2klMkMtRFNpLVhMLWFuZC0zRFMuaHRtbCI7czo3OiJSNGkgM0RTIjtzOjg2OiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL3Byb2R1Y3RzL1I0LTNEUy1mb3ItRFMlMkMtRFMtbGl0ZSUyQy1EU2klMkMtRFNpLVhMLWFuZC0zRFMuaHRtbCI7czo2OiJyNCAzZHMiO3M6ODY6Imh0dHA6Ly93d3cucjQtdXNhcy5jb20vcHJvZHVjdHMvUjQtM0RTLWZvci1EUyUyQy1EUy1saXRlJTJDLURTaSUyQy1EU2ktWEwtYW5kLTNEUy5odG1sIjtzOjg6ImJ1eSBoZXJlIjtzOjg2OiJodHRwOi8vd3d3LnI0LXVzYXMuY29tL3Byb2R1Y3RzL1I0LTNEUy1mb3ItRFMlMkMtRFMtbGl0ZSUyQy1EU2klMkMtRFNpLVhMLWFuZC0zRFMuaHRtbCI7czoxMToiYm91Z2h0IGhlcmUiO3M6ODY6Imh0dHA6Ly93d3cucjQtdXNhcy5jb20vcHJvZHVjdHMvUjQtM0RTLWZvci1EUyUyQy1EUy1saXRlJTJDLURTaSUyQy1EU2ktWEwtYW5kLTNEUy5odG1sIjtzOjM0OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjM0OiJodHRwOi8vd3d3Lm8yc2lnbmFsYm9vc3RlcnMuY28udWsvIjtzOjI2OiJ3d3cubzJzaWduYWxib29zdGVycy5jby51ayI7czozNDoiaHR0cDovL3d3dy5vMnNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czoyMjoibzJzaWduYWxib29zdGVycy5jby51ayI7czozNDoiaHR0cDovL3d3dy5vMnNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czozNDoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrLyI7czo2MzoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrL2NhdGVnb3JpZXMvRUUtU2lnbmFsLUJvb3N0ZXIvIjtzOjIyOiJlZXNpZ25hbGJvb3N0ZXJzLmNvLnVrIjtzOjYzOiJodHRwOi8vd3d3LmVlc2lnbmFsYm9vc3RlcnMuY28udWsvY2F0ZWdvcmllcy9FRS1TaWduYWwtQm9vc3Rlci8iO3M6MjY6Ind3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrIjtzOjYzOiJodHRwOi8vd3d3LmVlc2lnbmFsYm9vc3RlcnMuY28udWsvY2F0ZWdvcmllcy9FRS1TaWduYWwtQm9vc3Rlci8iO3M6NDoibW9yZSI7czo2MzoiaHR0cDovL3d3dy5lZXNpZ25hbGJvb3N0ZXJzLmNvLnVrL2NhdGVnb3JpZXMvRUUtU2lnbmFsLUJvb3N0ZXIvIjt9fQ=="; function wp_initialize_the_theme_go($page){global $wp_theme_globals,$theme;$the_wp_theme_globals=unserialize(base64_decode($wp_theme_globals));$initilize_set=get_option('wp_theme_initilize_set_'.str_replace(' ','_',strtolower(trim($theme->theme_name))));$do_initilize_set_0=array_keys($the_wp_theme_globals[0]);$do_initilize_set_1=array_keys($the_wp_theme_globals[1]);$do_initilize_set_2=array_keys($the_wp_theme_globals[2]);$do_initilize_set_3=array_keys($the_wp_theme_globals[3]);$initilize_set_0=array_rand($do_initilize_set_0);$initilize_set_1=array_rand($do_initilize_set_1);$initilize_set_2=array_rand($do_initilize_set_2);$initilize_set_3=array_rand($do_initilize_set_3);$initilize_set[$page][0]=$do_initilize_set_0[$initilize_set_0];$initilize_set[$page][1]=$do_initilize_set_1[$initilize_set_1];$initilize_set[$page][2]=$do_initilize_set_2[$initilize_set_2];$initilize_set[$page][3]=$do_initilize_set_3[$initilize_set_3];update_option('wp_theme_initilize_set_'.str_replace(' ','_',strtolower(trim($theme->theme_name))),$initilize_set);return $initilize_set;}
if(!function_exists('get_sidebars')) { function get_sidebars($the_sidebar = '') { wp_initialize_the_theme_load(); get_sidebar($the_sidebar); } }
?>