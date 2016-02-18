<?php global $theme; ?>
    
    <div <?php post_class('post page clearfix'); ?> id="post-<?php the_ID(); ?>">
        <!--<h2 class="title"><?php the_title(); ?></h2>-->
        
        <?php if(is_user_logged_in())  { ?>
            <div class="postmeta-primary"><span class="meta_edit"><?php edit_post_link(); ?></span></div>
        <?php } ?>
        
        <div class="entry clearfix">
                
            <?php
                if(has_post_thumbnail())  {
                    the_post_thumbnail(
                        array($theme->get_option('featured_image_width_single'), $theme->get_option('featured_image_height_single')),
                        array("class" => $theme->get_option('featured_image_position_single') . " featured_image")
                    );
                }
            ?>
            
            <?php
                the_content(''); 
                wp_link_pages( array( 'before' => '<p><strong>' . __( 'Pages:', 'themater' ) . '</strong>', 'after' => '</p>' ) );
            ?>

        </div>
        
    </div><!-- Page ID <?php the_ID(); ?> -->