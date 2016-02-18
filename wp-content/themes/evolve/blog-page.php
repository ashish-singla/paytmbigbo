<?php
/**
 * Template Name: Blog
 *
 * @package EvoLve
 * @subpackage Template
 */
get_header();
$evolve_layout = evolve_get_option('evl_layout','2cl');

if ($evolve_layout == "1c") { 
//do nothing.
} else { 

	if(get_post_meta($post->ID, 'evolve_full_width', true) == 'yes'){
	//do nothing
	}else{
	  
		if (($evolve_layout == "3cm" || $evolve_layout == "3cl" || $evolve_layout == "3cr")) {
			get_sidebar('2');
		}
	  
	}
  
} 

get_template_part( 'content', 'blog' );

if ($evolve_layout == "1c"){ 
//do nothing
} else { 

	wp_reset_query(); 
	
	if(get_post_meta($post->ID, 'evolve_full_width', true) == 'yes'){
	//do nothing
	}else{     
	  get_sidebar();
	} 

}
get_footer();
?>