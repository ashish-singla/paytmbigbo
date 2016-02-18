<header id="header" class="sticky-header">
<div class="container">
	<?php $evolve_pos_logo = evolve_get_option('evl_pos_logo','left'); if ($evolve_pos_logo == "disable") { ?>
	<?php } else { ?>
	<?php $evolve_header_logo = evolve_get_option('evl_header_logo', '');
		if ($evolve_header_logo) {
			echo "<a class='logo-url' href=".home_url()."><img id='logo-image' src=".$evolve_header_logo." /></a>";
		}
	?>
	<?php } ?>
	<?php $evolve_blog_title = evolve_get_option('evl_blog_title','0'); if ($evolve_blog_title == "0") { ?>
		<div id="logo"><a class='logo-url-text' href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ) ?></a></div>
	<?php } ?>
	<nav id="nav" class="nav-holder">
		<?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'menu_class' => 'nav-menu' ) ); ?>
	</nav>
</div>
</header>