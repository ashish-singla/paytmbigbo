<?php get_header(); ?>

    <div id="main-fullwidth">
        
        <div class="woocommerce">
           <?php if(function_exists('woocommerce_content')) { woocommerce_content(); } ?>
       </div>
        
    </div><!-- #main-fullwidth -->
    
<?php get_footer(); ?>