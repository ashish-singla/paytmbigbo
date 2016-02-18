<?php global $theme; get_header(); ?>

    <div id="main">
   
        <?php $theme->hook('main_before'); ?>

        <div id="content">

            <?php $theme->hook('content_before'); ?>
            
            <?php 
                $is_post_wrap = 0;
                    if (have_posts()) : while (have_posts()) : the_post();
                    /**
                     * The default post formatting from the post.php template file will be used.
                     * If you want to customize the post formatting for your homepage:
                     * 
                     *   - Create a new file: post-homepage.php
                     *   - Copy/Paste the content of post.php to post-homepage.php
                     *   - Edit and customize the post-homepage.php file for your needs.
                     * 
                     * Learn more about the get_template_part() function: http://codex.wordpress.org/Function_Reference/get_template_part
                     */

                    $is_post_wrap++;
                        if($is_post_wrap == '1') {
                            ?><div class="post-wrap clearfix"><?php
                        }
                        get_template_part('post', 'homepage');
                        
                        if($is_post_wrap == '2') {
                            $is_post_wrap = 0;
                            ?></div><?php
                        }
                    
                endwhile;
                
                else :
                    get_template_part('post', 'noresults');
                endif; 
                    
                    if($is_post_wrap == '1') {
                        ?></div><?php
                    } 
                
                get_template_part('navigation');
            ?>
            
            <?php $theme->hook('content_after'); ?>

    <div class="post-wrap clearfix">
<?php if ( is_active_sidebar( 'home_right_1' ) ) : ?>
    
    <div id="primary-sidebar" class="primary-sidebar-1">
    
    <div id="home-right-w" class="home-right-cs">
        <?php dynamic_sidebar( 'home_right_1' ); ?>
    </div>
    
    </div><!-- #primary-sidebar -->
    <?php endif; ?><?php if ( is_active_sidebar( 'home_right_2' ) ) : ?>
    <div id="primary-sidebar1" class="primary-sidebar-1">
    
    <div id="home-right-w1" class="home-right-cs">
        <?php dynamic_sidebar( 'home_right_2' ); ?>
    </div>
    </div><!-- #primary-sidebar -->
    <?php endif; ?>

<?php if ( is_active_sidebar( 'home_right_3' ) ) : ?>
    <div id="primary-sidebar2" class="primary-sidebar-1">
    
    <div id="home-right-w2" class="home-right-cs">
        <?php dynamic_sidebar( 'home_right_3' ); ?>
    </div>
    </div><!-- #primary-sidebar -->
    <?php endif; ?>
</div>
<!-- <div class="post-wrap clearfix">
 <?php if ( is_active_sidebar( 'video_bottom_1' ) ) : ?>
 <div class="video clearfix">
  <?php dynamic_sidebar( 'video_bottom_1' ); ?>
 </div>
 <?php endif; ?>
   <?php if ( is_active_sidebar( 'video_bottom_2' ) ) : ?>
 <div class="video clearfix">
  <?php dynamic_sidebar( 'video_bottom_2' ); ?>
 </div>
 <?php endif; ?>
 </div>-->
        
        </div><!-- #content -->
    
        <?php get_sidebars(); ?>
        
        <?php $theme->hook('main_after'); ?>

    
    </div><!-- #main -->

  
<?php get_footer(); ?>