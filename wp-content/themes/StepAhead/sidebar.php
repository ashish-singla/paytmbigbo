<?php global $theme; ?>

<div id="sidebar-primary">

    <?php
        if(!dynamic_sidebar('sidebar_primary')) {
            /**
            * The primary sidebar widget area. Manage the widgets from: wp-admin -> Appearance -> Widgets 
            */
            $theme->hook('sidebar_primary');
        }
        $theme->hook("sidebar_primary_after");
    ?>
    
</div><!-- #sidebar-primary -->