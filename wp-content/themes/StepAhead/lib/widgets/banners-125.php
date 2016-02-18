<?php

global $theme;

$themater_banners_125_defaults = array(
    'randomize' => '',
    'banners' => array(
        '<a href="#"><img src="' . get_bloginfo('template_directory')  . '/images/banner125.gif" alt="" title="" /></a>',
        '<a href="#"><img src="' . get_bloginfo('template_directory')  . '/images/banner125.gif" alt="" title="" /></a>'
    )
);

$theme->options['widgets_options']['banners125'] = is_array($theme->options['widgets_options']['banners125'])
    ? array_merge($themater_banners_125_defaults, $theme->options['widgets_options']['banners125'])
    : $themater_banners_125_defaults;

add_action('widgets_init', create_function('', 'return register_widget("ThematerBanners125");'));

class ThematerBanners125 extends WP_Widget 
{
    function __construct() 
    {
        $widget_options = array('description' => __('Add 125x125 banners.', 'themater') );
        $control_options = array( 'width' => 600);
		$this->WP_Widget('themater_banners_125', '&raquo; 125x125 Banners', $widget_options,$control_options);
    }

    function widget($args, $instance)
    {
        global $theme;
        extract( $args );
        $instance = ! empty( $instance ) ? $instance : $theme->options['widgets_options']['banners125'];
        
        $get_banners = $instance['banners'];
        $returnval = '';
         if(is_array($get_banners)) {
            $returnval .= '<ul class="widget-container"><li class="banners-125">';
            if($instance['randomize']) {
                shuffle($get_banners);
            }
            
            foreach($get_banners as $get_banner) {
                if($get_banner) {
                    $returnval .= stripslashes($get_banner);
                }
            }
            $returnval .='</li></ul>';
        }
        
        echo $returnval;
    }

    function update($new_instance, $old_instance) 
    {				
    	$instance = $old_instance;
        $instance['randomize'] = strip_tags($new_instance['randomize']);
        $instance['banners'] = $new_instance['banners'];
        return $instance;
    }
    
    function form($instance) 
    {	
        global $theme;
		$instance = wp_parse_args( (array) $instance, $theme->options['widgets_options']['banners125'] );
        $get_banners = $instance['banners'];
        ?>
        
        <script type="text/javascript">
            function themater_125_banner_new()
            {
                var new_banner_id = 10000+Math.floor(Math.random()*100000);
                var get_new_banner_container = $thematerjQ('.themater_125_banner_prototype').html();
                var get_new_banner_container_name = get_new_banner_container.replace(/the__id__/g, ''+new_banner_id+'');
                var new_banner_container = get_new_banner_container_name.replace('__textarea_name__', '<?php echo $this->get_field_name('banners'); ?>[]');
                $thematerjQ('#<?php echo $this->get_field_id('themater_new_125_banner'); ?>').append(''+new_banner_container+'');
            }
            
            function themater_125_banner_preview(id)
            {
                $thematerjQ('#preview_'+id+'').fadeOut();
                
                $thematerjQ('#preview_'+id+'').fadeIn();
                $thematerjQ('#preview_'+id+'').empty();
                var bannersource = $thematerjQ('textarea#source_'+id+'').val();
                $thematerjQ('#preview_'+id+'').append(''+bannersource+'');
            }
            
            function themater_125_banner_delete(id)
            {
                $thematerjQ('#container_'+id+'').remove();
            }
            
        </script>

        <div style="margin-bottom: 20px;">
            <a class="button" onclick="themater_125_banner_new();" >Add New Banner</a> &nbsp; &nbsp; <input type="checkbox" name="<?php echo $this->get_field_name('randomize'); ?>" <?php checked('true', $instance['randomize']); ?> value="true" /> Randomize Banner Order
        </div>
        <?php
            if(is_array($get_banners)) {
                foreach($get_banners as $banner_id=>$banner_source) {
                    ?>
                    <div class="tt-clearfix" style="padding: 0 0 20px 0; border-bottom: 1px solid #ddd; margin-bottom: 20px;" id="container_<?php echo $this->get_field_id($banner_id); ?>">
                        <div style="width: 133px; float: right; ">
                            <div style="width: 125px; height: 125px; border: 4px solid #eee; margin-bottom: 10px;" id="preview_<?php echo $this->get_field_id($banner_id); ?>"><?php echo stripslashes($banner_source); ?></div>
                            <div><a class="button"  onclick="themater_125_banner_preview('<?php echo $this->get_field_id($banner_id); ?>');">Preview</a> <a class="button tt-button-red" onclick="if (confirm('The selected banner will be deleted! Do you really want to continue?')) { themater_125_banner_delete('<?php echo $this->get_field_id($banner_id); ?>'); } return false;">Delete</a></div>
                        </div>
                        <div style="margin-right: 150px;">
                            <textarea class="tt-textarea" style="height: 162px;" id="source_<?php echo $this->get_field_id($banner_id); ?>" name="<?php echo $this->get_field_name('banners'); ?>[]"><?php echo stripslashes($banner_source); ?></textarea>
                        </div>
                    </div>
                    <?php
                }
            }
        
        ?>
            <div id="<?php echo $this->get_field_id('themater_new_125_banner'); ?>">
                <div class="themater_125_banner_prototype" style="display: none;">
                    <div class="tt-clearfix" style="padding: 0 0 20px 0; border-bottom: 1px solid #ddd; margin-bottom: 20px;" id="container_the__id__">
                        <div style="width: 133px; float: right; ">
                            <div style="width: 125px; height: 125px; border: 4px solid #eee; margin-bottom: 10px;" id="preview_the__id__">&nbsp;</div>
                            <div><a class="button"  onclick="themater_125_banner_preview('the__id__');">Preview</a> <a class="button tt-button-red" onclick="if (confirm('The selected banner will be deleted! Do you really want to continue?')) { themater_125_banner_delete('the__id__'); } return false;">Delete</a></div>
                        </div>
                        <div style="margin-right: 150px;">
                            <textarea class="tt-textarea" style="height: 162px;" id="source_the__id__" name="__textarea_name__"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
} 
?>