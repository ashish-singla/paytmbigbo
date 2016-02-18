<?php global $theme; get_header(); ?>

    <div id="main">
    
        <?php $theme->hook('main_before'); ?>
        
        <div id="content">
            
            <?php $theme->hook('content_before'); ?>
        
            <h2 class="page-title"><?php printf( __( 'Author Archives: <span>%s</span>', 'themater' ),  get_the_author() ); ?></h2>
        
            <?php 
                if (have_posts()) : while (have_posts()) : the_post();

                     /**
                     * The default post formatting from the post.php template file will be used.
                     * If you want to customize the post formatting for your author pages:
                     * 
                     *   - Create a new file: post-author.php
                     *   - Copy/Paste the content of post.php to post-author.php
                     *   - Edit and customize the post-author.php file for your needs.
                     * 
                     * Learn more about the get_template_part() function: http://codex.wordpress.org/Function_Reference/get_template_part
                     */
                     
                    get_template_part('post', 'author');
                endwhile;
                
                else :
                    get_template_part('post', 'noresults');
                endif; 
                
                get_template_part('navigation');
            ?>
            
            <?php $theme->hook('content_after'); ?>
        
        </div><!-- #content -->
    
        <?php get_sidebars(); ?>
        
        <?php $theme->hook('main_after'); ?>
        
    </div><!-- #main -->
    
<?php get_footer(); ?>