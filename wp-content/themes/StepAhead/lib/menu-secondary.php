<?php

    /**
     * Secondary Menu Admin Options
     */
     
    $this->admin_option(array('Secondary Menu', 22), 
        'Secondary Menu', 'menu_secondary_info', 
        'content', 'Please, use the <a href="nav-menus.php"><strong>menus panel</strong></a> to manage and organize menu items for the <strong>Secondary Menu</strong>.<br />The Secondary Menu will display the categories list if no menu is selected from the menus panel. <a href="http://codex.wordpress.org/Appearance_Menus_Screen" target="_blank">More info.</a>'
    );
    
    $this->admin_option('Secondary Menu', 
        'Secondary Menu Enabled?', 'menu_secondary', 
        'checkbox', $this->options['menus']['menu-secondary']['active'], 
        array('display'=>'inline')
    );
    
    $this->admin_option('Secondary Menu',
        'Mobile Title', 'menu_secondary_mobile_title', 
        'text', 'Navigation', 
        array('help'=>'The menu title that will be displayed in mobile/responsive mode for the secondary menu.', 'display'=>'inline')
    );
    
     $this->admin_option('Secondary Menu', 
        'Drop Down Settings', 'menu_secondary_drop_down', 
        'content', ''
    );
    
    $this->admin_option('Secondary Menu', 
        'Depth', 'menu_secondary_depth', 
        'text', $this->options['menus']['menu-secondary']['depth'], 
        array('help'=>'Drop Down levels depth. 0 = unlimited', 'display'=>'inline', 'style'=>'width: 80px;')
    );
    
    $this->admin_option('Secondary Menu', 
        'Effect', 'menu_secondary_effect', 
        'select', $this->options['menus']['menu-secondary']['effect'],
        array('help'=>'Drop Down animation effect.', 'display'=>'inline', 'options'=>array('standart' => 'Standart (No Effect)', 'slide' => 'Slide Down', 'fade' => 'Fade', 'fade_slide_right' => 'Fade & Slide from Right', 'fade_slide_left' => 'Fade & Slide from Left'))
    );
    
    $this->admin_option('Secondary Menu', 
        'Speed', 'menu_secondary_speed', 
        'text', $this->options['menus']['menu-secondary']['speed'], 
        array('help'=>'Speed of the drop down animation.', 'display'=>'inline', 'style'=>'width: 80px;', 'suffix'=> ' <em>milliseconds</em>')
    );
    
    $this->admin_option('Secondary Menu', 
        'Delay', 'menu_secondary_delay', 
        'text', $this->options['menus']['menu-secondary']['delay'], 
        array('help'=>'The delay in milliseconds that the mouse can remain outside a submenu without it closing ', 'display'=>'inline', 'style'=>'width: 80px;', 'suffix'=> ' <em>milliseconds</em>')
    );
    
    $this->admin_option('Secondary Menu', 
        'Arrows', 'menu_secondary_arrows', 
        'checkbox', $this->options['menus']['menu-secondary']['arrows'], 
        array('help'=>'Display the sub-menu indicator arrows', 'display'=>'inline')
    );
    
     $this->admin_option('Secondary Menu', 
        'Drop Shadows', 'menu_secondary_shadows', 
        'checkbox', $this->options['menus']['menu-secondary']['shadows'], 
        array('help'=>'Display Drop Shadows for the sub-menus', 'display'=>'inline')
    );
    
    
    /**
     * Display Secondary Menu
     */
     
    if($this->display('menu_secondary')) {
        
        // Register
        register_nav_menu( 'secondary',  __( 'Secondary Menu', 'themater' ) );
        
        // Display Hook
        $this->add_hook($this->options['menus']['menu-secondary']['hook'], 'themater_menu_secondary_display');
        
        function themater_menu_secondary_scripts() {
            wp_enqueue_script( 'hoverIntent', THEMATER_URL . '/js/hoverIntent.js', array('jquery') );
            wp_enqueue_script( 'superfish', THEMATER_URL . '/js/superfish.js', array('jquery') );
            wp_enqueue_script( 'mobilemenu', THEMATER_URL . '/js/jquery.mobilemenu.js', array('jquery') );
        }
        add_action('wp_enqueue_scripts', 'themater_menu_secondary_scripts'); 
        
        $this->custom_js(themater_menu_secondary_js());
    }
    
    /**
     * Secondary Menu Functions
     */
    
    function themater_menu_secondary_display()
    {
        global $theme;
        ?>
			<?php wp_nav_menu( 'depth=' . $theme->get_option('menu_secondary_depth') . '&theme_location=' . $theme->options['menus']['menu-secondary']['theme_location'] . '&container_class=' . $theme->options['menus']['menu-secondary']['wrap_class'] . '&menu_class=' . $theme->options['menus']['menu-secondary']['menu_class'] . '&fallback_cb=' . $theme->options['menus']['menu-secondary']['fallback'] . ''); ?>
              <!--.secondary menu--> 	
        <?php
    }
    
    function themater_menu_secondary_default()
    {
        global $theme;
        ?>
        <div class="<?php echo $theme->options['menus']['menu-secondary']['wrap_class']; ?>">
			<ul class="<?php echo $theme->options['menus']['menu-secondary']['menu_class']; ?>">
				<?php wp_list_categories('depth=' .  $theme->get_option('menu_secondary_depth') . '&hide_empty=0&orderby=name&show_count=0&use_desc_for_title=1&title_li='); ?>
			</ul>
		</div>
        <?php
    }
    
    function themater_menu_secondary_js()
    {
        global $theme;

        $return = '';
        
            $menu_secondary_arrows = $theme->display('menu_secondary_arrows') ? 'true' : 'false';
            $menu_secondary_shadows = $theme->display('menu_secondary_shadows') ? 'true' : 'false';
            $menu_secondary_delay = $theme->display('menu_secondary_delay') ? $theme->get_option('menu_secondary_delay') : '800';
            $menu_secondary_speed = $theme->display('menu_secondary_speed') ? $theme->get_option('menu_secondary_speed') : '200';
            
            switch ($theme->get_option('menu_secondary_effect')) {
                case 'standart' :
                $menu_secondary_effect = "animation: {width:'show'},\n";
                break;
                
                case 'slide' :
                $menu_secondary_effect = "animation: {height:'show'},\n";
                break;
                
                case 'fade' :
                $menu_secondary_effect = "animation: {opacity:'show'},\n";
                break;
                
                case 'fade_slide_right' :
                $menu_secondary_effect = "onBeforeShow: function(){ this.css('marginLeft','20px'); },\n animation: {'marginLeft':'0px',opacity:'show'},\n";
                break;
                
                case 'fade_slide_left' :
                $menu_secondary_effect = "onBeforeShow: function(){ this.css('marginLeft','-20px'); },\n animation: {'marginLeft':'0px',opacity:'show'},\n";
                break;
                
                default:
                $menu_secondary_effect = "animation: {opacity:'show'},\n";
            }
            
            $return .= "jQuery(function(){ \n\tjQuery('ul." . $theme->options['menus']['menu-secondary']['superfish_class'] . "').superfish({ \n\t";
            $return .= $menu_secondary_effect;
            $return .= "autoArrows:  $menu_secondary_arrows,
                dropShadows: $menu_secondary_shadows, 
                speed: $menu_secondary_speed,
                delay: $menu_secondary_delay
                });
            });\n";
            
            $return .= "jQuery('.menu-secondary-container').mobileMenu({
                defaultText: '" . $theme->get_option('menu_secondary_mobile_title') . "',
                className: 'menu-secondary-responsive',
                containerClass: 'menu-secondary-responsive-container',
                subMenuDash: '&ndash;'
            });\n";
   
        return $return;
    }
?>