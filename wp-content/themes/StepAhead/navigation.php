<?php if (  $wp_query->max_num_pages > 1 ) { ?>

    <div class="navigation clearfix">
        
        <?php
            if(function_exists('wp_pagenavi')) {
                wp_pagenavi();
            } else {
        ?><div class="alignleft"><?php next_posts_link( __( '<span>&laquo;</span> Older posts', 'themater' ) );?></div>
        <div class="alignright"><?php previous_posts_link( __( 'Newer posts <span>&raquo;</span>', 'themater' ) );?></div><?php
        } ?> 
        
    </div><!-- .navigation -->
    
<?php } ?>