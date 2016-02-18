<?php global $theme; get_header(); ?>

    <div id="main">
    
        <?php $theme->hook('main_before'); ?>
    
        <div id="content">
            
            <?php $theme->hook('content_before'); ?>
        
            <?php 
                if (have_posts()) : while (have_posts()) : the_post();
                    /**
                     * Find the post formatting for the single post (full post view) in the post-single.php file
                     */
                    get_template_part('post', 'single');
                endwhile;
                
                else :
                    get_template_part('post', 'noresults');
                endif; 
            ?>
            
            <?php $theme->hook('content_after'); ?>
        
        </div><!-- #content -->
    
        <?php get_sidebars(); ?>
        
        <?php $theme->hook('main_after'); ?>
        
    </div><!-- #main -->
    
<?php get_footer(); ?>