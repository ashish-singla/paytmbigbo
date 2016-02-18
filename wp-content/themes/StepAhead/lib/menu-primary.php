<?php

    /**
     * Primary Menu Admin Options
     */
     
    $this->admin_option(array('Primary Menu', 21), 
        'Primary Menu', 'menu_primary_info', 
        'content', 'Please, use the <a href="nav-menus.php"><strong>menus panel</strong></a> to manage and organize menu items for the <strong>primary menu</strong>.<br />The primary menu will display the pages list if no menu is selected from the menus panel. <a href="http://codex.wordpress.org/Appearance_Menus_Screen" target="_blank">More info.</a>'
    );
    
    $this->admin_option('Primary Menu', 
        'Primary Menu Enabled?', 'menu_primary', 
        'checkbox', $this->options['menus']['menu-primary']['active'], 
        array('display'=>'inline')
    );
    
    $this->admin_option('Primary Menu',
        'Mobile Title', 'menu_primary_mobile_title', 
        'text', 'Menu', 
        array('help'=>'The menu title that will be displayed in mobile/responsive mode for the primary menu.', 'display'=>'inline')
    );
    
     $this->admin_option('Primary Menu',
        'Drop Down Settings', 'menu_primary_drop_down', 
        'content', ''
    );
    
    $this->admin_option('Primary Menu',
        'Depth', 'menu_primary_depth', 
        'text', $this->options['menus']['menu-primary']['depth'], 
        array('help'=>'Drop Down levels depth. 0 = unlimited', 'display'=>'inline', 'style'=>'width: 80px;')
    );
    
    $this->admin_option('Primary Menu',
        'Effect', 'menu_primary_effect', 
        'select', $this->options['menus']['menu-primary']['effect'],
        array('help'=>'Drop Down animation effect.', 'display'=>'inline', 'options'=>array('standart' => 'Standart (No Effect)', 'slide' => 'Slide Down', 'fade' => 'Fade', 'fade_slide_right' => 'Fade & Slide from Right', 'fade_slide_left' => 'Fade & Slide from Left'))
    );
    
    $this->admin_option('Primary Menu',
        'Speed', 'menu_primary_speed', 
        'text', $this->options['menus']['menu-primary']['speed'], 
        array('help'=>'Speed of the drop down animation.', 'display'=>'inline', 'style'=>'width: 80px;', 'suffix'=> ' <em>milliseconds</em>')
    );
    
    $this->admin_option('Primary Menu',
        'Delay', 'menu_primary_delay', 
        'text', $this->options['menus']['menu-primary']['delay'], 
        array('help'=>'The delay in milliseconds that the mouse can remain outside a submenu without it closing ', 'display'=>'inline', 'style'=>'width: 80px;', 'suffix'=> ' <em>milliseconds</em>')
    );
    
    $this->admin_option('Primary Menu', 
        'Arrows', 'menu_primary_arrows', 
        'checkbox', $this->options['menus']['menu-primary']['arrows'], 
        array('help'=>'Display the sub-menu indicator arrows', 'display'=>'inline')
    );
    
     $this->admin_option('Primary Menu',
        'Drop Shadows', 'menu_primary_shadows', 
        'checkbox', $this->options['menus']['menu-primary']['shadows'], 
        array('help'=>'Display Drop Shadows for the sub-menus', 'display'=>'inline')
    );
    
    
    /**
     * Display Primary Menu
     */
     
    if($this->display('menu_primary')) {
        
        // Register
        register_nav_menu( 'primary',  __( 'Primary Menu', 'themater' ) );
        
        // Display Hook
        $this->add_hook($this->options['menus']['menu-primary']['hook'], 'themater_menu_primary_display');
        
        function themater_menu_primary_scripts() {
            wp_enqueue_script( 'hoverIntent', THEMATER_URL . '/js/hoverIntent.js', array('jquery') );
            wp_enqueue_script( 'superfish', THEMATER_URL . '/js/superfish.js', array('jquery') );
            wp_enqueue_script( 'mobilemenu', THEMATER_URL . '/js/jquery.mobilemenu.js', array('jquery') );
        }
        add_action('wp_enqueue_scripts', 'themater_menu_primary_scripts');
        
        $this->custom_js(themater_menu_primary_js());
    }
    
    /**
     * Primary Menu Functions
     */
    
    function themater_menu_primary_display()
    {
        global $theme;
        ?>
			<?php wp_nav_menu( 'depth=' . $theme->get_option('menu_primary_depth') . '&theme_location=' . $theme->options['menus']['menu-primary']['theme_location'] . '&container_class=' . $theme->options['menus']['menu-primary']['wrap_class'] . '&menu_class=' . $theme->options['menus']['menu-primary']['menu_class'] . '&fallback_cb=' . $theme->options['menus']['menu-primary']['fallback'] . ''); ?>
              <!--.primary menu--> 	
        <?php
    }
    
    function themater_menu_primary_default()
    {
        global $theme;
        ?>
        <div class="<?php echo $theme->options['menus']['menu-primary']['wrap_class']; ?>">
			<ul class="<?php echo $theme->options['menus']['menu-primary']['menu_class']; ?>">
                <li <?php if(is_home() || is_front_page()) { ?>class="current_page_item"<?php } ?>><a href="<?php echo home_url(); ?>"><?php _e('Home','themater'); ?></a></li>
				<?php wp_list_pages('depth=' .  $theme->get_option('menu_primary_depth') . '&sort_column=menu_order&title_li=' ); ?>
			</ul>
		</div>
        <?php
    }
    
    function themater_menu_primary_js()
    {
        global $theme;

        $return = '';
        
            $menu_primary_arrows = $theme->display('menu_primary_arrows') ? 'true' : 'false';
            $menu_primary_shadows = $theme->display('menu_primary_shadows') ? 'true' : 'false';
            $menu_primary_delay = $theme->display('menu_primary_delay') ? $theme->get_option('menu_primary_delay') : '800';
            $menu_primary_speed = $theme->display('menu_primary_speed') ? $theme->get_option('menu_primary_speed') : '200';
            
            switch ($theme->get_option('menu_primary_effect')) {
                case 'standart' :
                $menu_primary_effect = "animation: {width:'show'},\n";
                break;
                
                case 'slide' :
                $menu_primary_effect = "animation: {height:'show'},\n";
                break;
                
                case 'fade' :
                $menu_primary_effect = "animation: {opacity:'show'},\n";
                break;
                
                case 'fade_slide_right' :
                $menu_primary_effect = "onBeforeShow: function(){ this.css('marginLeft','20px'); },\n animation: {'marginLeft':'0px',opacity:'show'},\n";
                break;
                
                case 'fade_slide_left' :
                $menu_primary_effect = "onBeforeShow: function(){ this.css('marginLeft','-20px'); },\n animation: {'marginLeft':'0px',opacity:'show'},\n";
                break;
                
                default:
                $menu_primary_effect = "animation: {opacity:'show'},\n";
            }
            
            $return .= "jQuery(function(){ \n\tjQuery('ul." . $theme->options['menus']['menu-primary']['superfish_class'] . "').superfish({ \n\t";
            $return .= $menu_primary_effect;
            $return .= "autoArrows:  $menu_primary_arrows,
                dropShadows: $menu_primary_shadows, 
                speed: $menu_primary_speed,
                delay: $menu_primary_delay
                });
            });\n";
            
            $return .= "jQuery('.menu-primary-container').mobileMenu({
                defaultText: '" . $theme->get_option('menu_primary_mobile_title') . "',
                className: 'menu-primary-responsive',
                containerClass: 'menu-primary-responsive-container',
                subMenuDash: '&ndash;'
            });\n";
   
        return $return;
    }
?>