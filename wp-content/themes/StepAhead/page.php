<?php global $theme; get_header(); ?>

    <div id="main">
    
        <?php $theme->hook('main_before'); ?>

        <div id="content">
            
            <?php $theme->hook('content_before'); ?>
        
            <?php 
                if (have_posts()) : while (have_posts()) : the_post();
                    /**
                     * Find the post formatting for the pages in the post-page.php file
                     */
                    get_template_part('post', 'page');
                    
                    if(comments_open( get_the_ID() ))  {
                        comments_template('', true); 
                    }
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