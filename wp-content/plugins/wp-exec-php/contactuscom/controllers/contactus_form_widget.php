<?php

/**
 * 
 * CONTACT FORM BY CONTACTUS.COM
 * 
 * Initialization Form Widget Class from WP_Widget
 * @since 3.0 First time this was introduced into plugin.
 * @author ContactUs.com <support@contactus.com>
 * @copyright 2013 ContactUs.com Inc.
 * Company      : contactus.com
 * Updated  	: 20142102
 **/

//Contact Subscribe Box widget extend 
class cUsCF_contactus_form_Widget extends WP_Widget {

	function cUsCF_contactus_form_Widget() {
		$widget_ops = array( 
			'description' => __('Displays Contact Form by ContactUs.com', 'contactus_form'),
            'example_number' => 1
		);
		$this->WP_Widget('contactus_form_Widget', __('Contact Form by ContactUs.com', 'contactus_form'), $widget_ops);
	}

    // widget form creation
    function form($instance) {
        // Check values
        if( $instance) {
            $form_key = esc_attr($instance['form_key']);
        } else {
            $form_key = get_option('cUsCF_settings_form_key'); //get the saved form key
        }
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('form_key'); ?>"><?php _e('Form Key', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('form_key'); ?>" name="<?php echo $this->get_field_name('form_key'); ?>" type="text" value="<?php echo $form_key; ?>" />
        </p>
    <?php
    }

    // widget update
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        // Fields
        $instance['form_key'] = strip_tags($new_instance['form_key']);
        return $instance;
    }

	function widget( $args, $instance ) {
		if (!is_array($instance)) {
			$instance = array();
		}
		contactuscom_form(array_merge($args, $instance));
	}
}
/*
* Method in charge to retrive ContactUs.com user's Default Form Key and render widget
* @param array $args Widget options 
* @since 3.0
* @return string HTML into the widget area
*/
function contactuscom_form($args = array()) {
    extract($args);

    //print_r($args);

    $cUs_form_key = $args['form_key'];
    if(empty($cUs_form_key))
        $cUs_form_key = get_option('cUsCF_settings_form_key'); //get the saved form key
    
    if(strlen($cUs_form_key)){
        $xHTML  = '<div id="cUsCF_form_widget" style="clear:both;width:100%;min-height:230px;margin:10px auto;">';
        $xHTML .= '<script type="text/javascript" src="' . cUsCF_ENV_URL . $cUs_form_key . '/inline.js"></script>';
        $xHTML .= '</div>';
        
        echo $xHTML;
    }
}