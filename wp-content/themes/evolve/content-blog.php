<?php
$first = "";
global $authordata;
$xyz = "";

$evolve_layout = evolve_get_option('evl_layout','2cl');
$evolve_post_layout = evolve_get_option('evl_post_layout','two');
$evolve_nav_links = evolve_get_option('evl_nav_links','after');
$evolve_header_meta = evolve_get_option('evl_header_meta','single_archive');
$evolve_excerpt_thumbnail = evolve_get_option('evl_excerpt_thumbnail','0');
$evolve_share_this = evolve_get_option('evl_share_this','single');
$evolve_post_links = evolve_get_option('evl_post_links','after');
$evolve_similar_posts = evolve_get_option('evl_similar_posts','disable');
$evolve_featured_images = evolve_get_option('evl_featured_images','1');
$evolve_breadcrumbs = evolve_get_option('evl_breadcrumbs','1');
?>

<!--BEGIN #primary .hfeed-->
<div id="primary" class="<?php evolve_layout_class($type = 1); ?>">

	<?php
	if ($evolve_breadcrumbs == "1"):
		if (!is_home() || !is_front_page()):
			evolve_breadcrumb();
		endif;
	endif;
	?>
	
	<!-- 2 or 3 columns begin -->
	
	<?php
	if ($evolve_post_layout == "two" || $evolve_post_layout == "three") {
	
	$temp = $wp_query;
	$wp_query= null;
	$wp_query = new WP_Query();
	$wp_query->query('posts_per_page=6'.'&paged='.$paged);
	
	?>
	
	<?php if (($evolve_nav_links == "before") || ($evolve_nav_links == "both")) : ?>
	<span class="nav-top">
	<?php get_template_part( 'navigation', 'index' ); ?>
	</span>
	<?php endif; ?>
	
	<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $first++; ?>
	
		<!--BEGIN .hentry-->
		<div id="post-<?php the_ID(); ?>" class="<?php semantic_entries(); evolve_post_class($xyz);$xyz++; ?> margin-40">
		
			<?php if (($evolve_header_meta == "") || ($evolve_header_meta == "single_archive")) : ?>
			
				<h1 class="entry-title">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to ', 'evolve' ).the_title(); ?>">
						<?php if ( get_the_title() ){ $title = the_title('', '', false); echo evolve_truncate($title, 40, '...'); } ?>
					</a>
				</h1>
			
				<!--BEGIN .entry-meta .entry-header-->
				<div class="entry-meta entry-header">
					<a href="<?php the_permalink() ?>"><span class="published updated"><?php the_time(get_option('date_format')); ?></span></a>
					<span class="author vcard">
						<?php _e( 'Written by', 'evolve' ); ?> <strong><?php printf( '<a class="url fn" href="' . get_author_posts_url( $authordata->ID, $authordata->user_nicename ) . '" title="' . sprintf( 'View all posts by %s', $authordata->display_name ) . '">' . get_the_author() . '</a>' ) ?></strong>
					</span>
				
					<?php edit_post_link( __( 'edit', 'evolve' ), '<span class="edit-post">', '</span>' ); ?>
				
				<!--END .entry-meta .entry-header-->
				</div>
				
				<?php else: ?>
				
				<h1 class="entry-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'evolve' ); ?> <?php the_title(); ?>">
					<?php
					if ( get_the_title() ){
					$title = the_title('', '', false);
					echo evolve_truncate($title, 40, '...');
					}
					?>	
					</a>
				</h1>
				
				<?php
				if ( current_user_can( 'edit_post', $post->ID ) ):
					edit_post_link( __( 'EDIT', 'evolve' ), '<span class="edit-post edit-attach">', '</span>' );
				endif;
				?>
			
			<?php endif;//if (($evolve_header_meta == "") ?>
			
			<!--BEGIN .entry-content .article-->
			<div class="entry-content article">
			
					<?php if ($evolve_featured_images == "1") : ?>
					
						<?php if (has_post_thumbnail()) : ?>
						
						<span class="thumbnail-post">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('post-thumbnail'); ?>
								<div class="mask">
									<div class="icon"></div>
								</div>
							</a>
						</span>
						
						<?php else: $image = evolve_get_first_image(); ?>
						
							<?php if ($image): ?>
							
							<span class="thumbnail-post">
								<a href="<?php the_permalink();?>"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" />
									<div class="mask">
										<div class="icon"></div>
									</div>
								</a>
							</span>
							
							<?php else: ?>
							
							<span class="thumbnail-post">
								<a href="<?php the_permalink(); ?>">
									<img src="<?php echo get_template_directory_uri() . '/library/media/images/no-thumbnail.jpg'; ?>" alt="<?php the_title(); ?>" />
										<div class="mask">
											<div class="icon"></div>
										</div>
								</a>
							</span>
							
							<?php endif;//if ($image): ?>
						
						<?php endif;//if (has_post_thumbnail()) : ?>
					
					<?php endif;//if ($evolve_featured_images == "1"): ?>
			
					<?php echo evolve_excerpt_max_charlength(350); ?>
			
					<div class="entry-meta entry-footer">
					
						<div class="read-more btn btn-right icon-arrow-right">
							<a href="<?php the_permalink(); ?>"><?php _e('READ MORE', 'evolve' ); ?></a>
						</div>
						
						<?php if ( comments_open() ) : ?>
							<span class="comment-count"><?php comments_popup_link( __( 'Leave a Comment', 'evolve' ), __( '1 Comment', 'evolve' ), __( '% Comments', 'evolve' ) ); ?></span>
						<?php endif; ?>
					
					</div><!-- /.entry-meta -->
							
				<div class="clearfix"></div>
			</div><!--END .entry-content .article-->
		
		<!--END .hentry-->
		</div>
	
	<?php endwhile; ?>
	
	<?php get_template_part( 'navigation', 'index' ); ?>
	
	<?php $wp_query = null; $wp_query = $temp;?>
	
	
	<!-- 2 or 3 columns end -->
	
	
	<!-- 1 column begin -->
	
	
	<?php } else { ?>
	
	<?php
	$temp = $wp_query;
	$wp_query= null;
	$wp_query = new WP_Query();
	$wp_query->query('posts_per_page=6'.'&paged='.$paged);
	?>
	
	<?php if (($evolve_nav_links == "before") || ($evolve_nav_links == "both")) : ?>
	<span class="nav-top">
	<?php get_template_part( 'navigation', 'index' ); ?>
	</span>
	<?php endif; ?>
	
	<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $first++; ?>
	
		<!--BEGIN .hentry-->
		<div id="post-<?php the_ID(); ?>" class="<?php semantic_entries(); ?> <?php evolve_post_class_2(); ?>">
		
		<?php $evolve_header_meta = evolve_get_option('evl_header_meta','disable'); if (($evolve_header_meta == "") || ($evolve_header_meta == "single_archive")) { ?>
		
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'evolve' ); ?> <?php the_title(); ?>">
					<?php if ( get_the_title() ){ the_title(); } ?>
				</a>
			</h1>
		
			<!--BEGIN .entry-meta .entry-header-->
			<div class="entry-meta entry-header">
				<a href="<?php the_permalink() ?>"><span class="published updated"><?php the_time(get_option('date_format')); ?></span></a>
				
				<?php if ( comments_open() ) : ?>
					<span class="comment-count">
						<a href="<?php comments_link(); ?>"><?php comments_popup_link( __( 'Leave a Comment', 'evolve' ), __( '1 Comment', 'evolve' ), __( '% Comments', 'evolve' ) ); ?></a>
					</span>
				<?php endif; ?>
				
				<span class="author vcard">
					<?php
					$evolve_author_avatar = evolve_get_option('evl_author_avatar','0');
					if ($evolve_author_avatar == "1") {
						echo get_avatar( get_the_author_meta('email'), '30' );
					}
					?>
					
					<?php _e( 'Written by', 'evolve' ); ?> <strong><?php printf( '<a class="url fn" href="' . get_author_posts_url( $authordata->ID, $authordata->user_nicename ) . '" title="' . sprintf( 'View all posts by %s', $authordata->display_name ) . '">' . get_the_author() . '</a>' ) ?></strong>
				</span>
				
				<?php edit_post_link( __( 'edit', 'evolve' ), '<span class="edit-post">', '</span>' ); ?>
				<!--END .entry-meta .entry-header-->
			</div>
			
			<?php } else { ?>
			
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'evolve' ); ?> <?php the_title(); ?>">
					<?php if ( get_the_title() ){ the_title();} ?>
				</a>
			</h1>
			
			<?php if ( current_user_can( 'edit_post', $post->ID ) ): ?>
				<?php edit_post_link( __( 'EDIT', 'evolve' ), '<span class="edit-post edit-attach">', '</span>' ); ?>
			<?php endif; ?>
			
			<?php } ?>
			
			<!--BEGIN .entry-content .article-->
			<div class="entry-content article">
			
			<?php if ($evolve_featured_images == "1") { ?>
			
				<?php if (has_post_thumbnail()) : ?>
				
				<span class="thumbnail-post">
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail('post-thumbnail'); ?>
						<div class="mask">
							<div class="icon"></div>
						</div>
					</a>
				</span>
				
				<?php else: $image = evolve_get_first_image(); ?>
				
					<?php if ($image): ?>
					
						<span class="thumbnail-post">
							<a href="<?php the_permalink();?>"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" />
								<div class="mask">
									<div class="icon"></div>
								</div>
							</a>
						</span>
					
					<?php else: ?>
					
						<span class="thumbnail-post">
							<a href="<?php the_permalink(); ?>">
								<img src="<?php echo get_template_directory_uri() . '/library/media/images/no-thumbnail.jpg'; ?>" alt="<?php the_title(); ?>" />
									<div class="mask">
										<div class="icon"></div>
									</div>
							</a>
						</span>
					
					<?php endif;//if ($image): ?>
				
				<?php endif;//if (has_post_thumbnail()) : ?>
			
			<?php } ?>
			
			<?php if (($evolve_excerpt_thumbnail == "1")) : ?>
			
				<?php the_excerpt();?>
			
				<div class="read-more btn btn-right icon-arrow-right">
					<a href="<?php the_permalink(); ?>"><?php _e('READ MORE', 'evolve' ); ?></a>
				</div>
			
			<?php else: ?>
			
				<?php the_content( __('READ MORE &raquo;', 'evolve' ) ); ?>
			
				<?php wp_link_pages( array( 'before' => '<div id="page-links"><p><strong>'.__( 'Pages', 'evolve' ).':</strong>', 'after' => '</p></div>' ) ); ?>
			
			<?php endif; ?>
			
			<!--END .entry-content .article-->
			<div class="clearfix"></div>
			</div>
			
				<!--BEGIN .entry-meta .entry-footer-->
				<div class="entry-meta entry-footer row">
				
					<div class="col-md-6">
					<?php if ( evolve_get_terms( 'cats' ) ) { ?>
						<div class="entry-categories"> <?php echo evolve_get_terms( 'cats' ); ?></div>
					<?php } ?>
					<?php if ( evolve_get_terms( 'tags' ) ) { ?>
						<div class="entry-tags"> <?php echo evolve_get_terms( 'tags' ); ?></div>
					<?php } ?>
				
				<!--END .entry-meta .entry-footer-->
				</div>
			
				<div class="col-md-6">
					<?php if (($evolve_share_this == "single_archive") || ($evolve_share_this == "all")) {
					evolve_sharethis(); } else { ?> <div class="margin-40"></div> <?php }?>
				</div>
			</div>
		
		<!--END .hentry-->
		</div>
	
	<?php comments_template(); ?>
	
	<?php endwhile; ?>
	
	<?php if (($evolve_nav_links == "") || ($evolve_nav_links == "after") || ($evolve_nav_links == "both")) { ?>
	
	<?php get_template_part( 'navigation', 'index' ); ?>
	
	<?php } else {?>
	
	<?php } ?>
	
	<?php $wp_query = null; $wp_query = $temp;?>
	
	<?php } ?>
	
	<!-- 1 column end -->

<!--END #primary .hfeed-->
</div>