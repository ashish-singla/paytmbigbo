<?php
/**
 * Template Name: Sitemap
*/

global $theme; get_header(); ?>

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
                endwhile;
                
                else :
                    get_template_part('post', 'noresults');
                endif; 
            ?>
            
            <div class="sitemap">
            
                <div class="clearfix">
                    <div class="alignleft sitemap-col">
                        <h2><?php _e('Pages', 'themater'); ?></h2>
                        <ul class="sitemap-list">
                            <?php wp_list_pages('title_li='); ?>
                        </ul>
                    </div>
                    
                    <div class="alignleft sitemap-col">
                        <h2><?php _e('Categories', 'themater'); ?></h2>
                        <ul class="sitemap-list">
                            <?php wp_list_categories('title_li='); ?>
                        </ul>
                    </div>
                    
                    <div class="alignleft sitemap-col">
                        <h2><?php _e('Archives', 'themater'); ?></h2>
                        <ul class="sitemap-list">
                            <?php wp_get_archives('type=monthly&show_post_count=0'); ?>
                        </ul>
                    </div>
                </div>
                
                <div>
                    <h2><?php _e('Posts per category', 'themater'); ?></h2>
                    
                    <?php
			    
			            $cats = get_categories();
			            foreach ( $cats as $cat ) {
			    
			            query_posts( 'cat=' . $cat->cat_ID );
			
			        ?>
	        
	        			<h3><?php echo $cat->cat_name; ?></h3>
			        	<ul class="sitemap-list">	
	    	        	    <?php while ( have_posts() ) { the_post(); ?>
	        	    	    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
	            		    <?php } wp_reset_query(); ?>
			        	</ul>
	    
	    		    <?php } ?>
                    
                </div>
                
            </div>
            
            <?php $theme->hook('content_after'); ?>
        
        </div><!-- #content -->
    
        <?php get_sidebars(); ?>
        
        <?php $theme->hook('main_after'); ?>
        
    </div><!-- #main -->
    
<?php get_footer(); ?>