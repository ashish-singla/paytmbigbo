<?php global $theme; ?>
    
    <div <?php post_class('post post-box clearfix'); ?> id="post-<?php the_ID(); ?>">
    
        <div class="postmeta-primary padding-add">

            <span class="meta_date"><?php echo get_the_date(); ?></span>
            
            <?php if(comments_open( get_the_ID() ))  {
                    ?> &nbsp; <span class="meta_comments"><?php comments_popup_link( __( 'No comments', 'themater' ), __( '1 Comment', 'themater' ), __( '% Comments', 'themater' ) ); ?></span><?php
                } ?> 
        </div>
        
       
        
        <div class="entry clearfix padding-add min-h">
            <div style="width: 100%">
            <?php
                if(has_post_thumbnail())  {
                    ?><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(
                        array($theme->get_option('featured_image_width'), $theme->get_option('featured_image_height')),
                        array("class" => $theme->get_option('featured_image_position') . " featured_image")
                    ); ?></a>
                    <?php  
                }
                else{?>
					<a href="<?php the_permalink(); ?>"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2016/01/no_photo.png"/></a>
				<?php }
            ?>
            <h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'themater' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><b><?php the_title(); ?></b></a></h2>
             <p>
                <?php
                  echo $theme->shorten(get_the_excerpt(), '15');
                ?>
 <?php if($theme->display('read_more')) { ?>
  <a href="<?php the_permalink(); ?>#more-<?php the_ID(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'themater' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><!--<?php $theme->option('read_more'); ?>-->..Read More »</a>
 <?php } ?>          
  </p>
            </div>
             
        </div>
            
    </div><!-- Post ID <?php the_ID(); ?> -->