<?php
    new Themater_Social_Profiles();
    
    class Themater_Social_Profiles
    {
        var $theme;
        var $status = false;
        var $display_networks = false;
        var $url;
        
        var $defaults;
        
        function __construct()
        {
            global $theme;
            $this->theme = $theme;
            $this->url = THEMATER_INCLUDES_URL . '/social_profiles';
            
            $this->defaults = array(
                'hook' => 'social_profiles',
                'networks' => array(
                    array('title' => 'Twitter', 'url' => 'http://twitter.com/', 'button' => get_template_directory_uri() . '/images/social-profiles/twitter.png'),
                    array('title' => 'Facebook', 'url' => 'http://facebook.com/', 'button' => get_template_directory_uri() . '/images/social-profiles/facebook.png'),
                    array('title' => 'Google Plus', 'url' => 'https://plus.google.com/', 'button' => get_template_directory_uri() . '/images/social-profiles/gplus.png'),
                    array('title' => 'LinkedIn', 'url' => 'http://www.linkedin.com/', 'button' => get_template_directory_uri() . '/images/social-profiles/linkedin.png'),
                    array('title' => 'RSS Feed', 'url' => $theme->rss_url(), 'button' => get_template_directory_uri() . '/images/social-profiles/rss.png'),
                    array('title' => 'Email', 'url' => 'mailto:your@email.com', 'button' => get_template_directory_uri() . '/images/social-profiles/email.png')
                )
            );                        
            
            if(is_array($this->theme->options['plugins_options']['social_profiles']) ) {
                $this->defaults = array_merge($this->defaults, $this->theme->options['plugins_options']['social_profiles']);
            }
            
            $this->theme->add_hook($this->defaults['hook'], array(&$this, 'display_social_profiles'), 1);
            
            $this->themater_options();
        }
        
        function display_social_profiles()
        {
            $widget_name = 'ThematerSocialProfiles';
            $args = array('before_widget' => '','after_widget' => '');
            $get_instance = $this->theme->get_option('themater_social_profiles_networks');
            $instance = array('profiles' => $get_instance);
            the_widget($widget_name, $instance, $args);
        }
        
        function get_widget_form()
        {
            $widget_name = 'ThematerSocialProfiles';
            $run_widget = new $widget_name();
            $run_widget->id_base = 'plugin';
            $get_instance = $this->theme->get_option('themater_social_profiles_networks');
            $instance = array('profiles' => $get_instance);
            $run_widget->form($instance);
            ?>
            <script>
    			  var update_scial_content = $thematerjQ('.themater_social_profiles_widget').html();
    			  update_scial_content = update_scial_content.replace(/widget-plugin\[\]\[profiles\]/g, 'themater_social_profiles_networks');
    			  $thematerjQ('.themater_social_profiles_widget').html(update_scial_content);
    			  $thematerjQ('.themater_social_profiles_widget_title').hide();
    		  </script>
            <?php
        }

        
        function themater_options()
        {
            
            $this->theme->admin_option(array('Social Profiles', 16), 
                'Social Profiles' , 'social_profiles', 
                'content', 'Add buttons to your social network profiles.'
            );
            
            $this->theme->admin_option('Social Profiles', 
                'Networks', 'themater_social_profiles_networks', 
                'callback', $this->defaults['networks'], 
                array('callback' => array(&$this, 'themater_social_profiles_networks'), 'display' => 'clean')
            );
            
        }
        
        function themater_social_profiles_networks()
        {
            $this->get_widget_form();
        }
    }
?>