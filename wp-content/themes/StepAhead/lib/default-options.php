<?php

    // Translation Options
    $translation = array(
        'enabled' => true,
        'dir' =>  TEMPLATEPATH . '/languages'
    );
    
    // General Options
    $general = array(
        'jquery' => true,
        'featured_image' => true,
        'custom_background' => true,
        'clean_exerpts' => true,
        'hide_wp_version' => true,
        'meta_rss' => true,
        'pingback_url' => true,
        'automatic_feed' => false
        
    );
    
    // Widgets
    $widgets = array(
        'banners-125' => '125x125 Banners', 
        'comments' => 'Comments', 
        'posts' => 'Posts', 
        'social-profiles' => 'Social Profiles',
        'tabs' => 'Tabs', 
        'infobox' => 'Info Box',
        'facebook' => 'Facebook'
    );
    
    //Menus
     $menus = array(
        'menu-primary' => array(
            'active' => 'true',
            'hook' => 'menu_primary',
            'theme_location' => 'primary',
            'wrap_class' => 'menu-primary-container',
            'menu_class' => 'menus menu-primary',
            'superfish_class' => 'menu-primary',
            'fallback' => 'themater_menu_primary_default',
            'depth' => '0',
            'effect' => 'fade',
            'speed' => '200',
            'delay' => '800',
            'arrows' => 'true',
            'shadows' => ''
        ), 
        
        'menu-secondary' => array(
            'active'=> 'true', 
            'hook' => 'menu_secondary',
            'theme_location' => 'secondary',
            'wrap_class' => 'menu-secondary-container',
            'menu_class' => 'menus menu-secondary',
            'superfish_class' => 'menu-secondary',
            'fallback' => 'themater_menu_secondary_default',
            'depth' => '0',
            'effect' => 'fade',
            'speed' => '200',
            'delay' => '800',
            'arrows' => 'true',
            'shadows' => ''
        )
    );

?>