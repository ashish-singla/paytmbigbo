<?php global $theme; get_header(); ?>

    <div id="main">
    
        <?php $theme->hook('main_before'); ?>
    
        <div id="content">
        
            <?php $theme->hook('content_before'); ?>
        
            <div class="entry">
                <?php _e('The page you requested could not be found.','themater'); ?>
            </div>
            
            <div id="content-search">
                <?php get_search_form(); ?>
            </div>
            
            <?php $theme->hook('content_after'); ?>
        
        </div><!-- #content -->
    
        <?php get_sidebars(); ?>
        
        <?php $theme->hook('main_after'); ?>
        
    </div><!-- #main -->
    
<?php get_footer(); ?>