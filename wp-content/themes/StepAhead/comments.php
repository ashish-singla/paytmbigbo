   <?php if ( post_password_required() ) { ?>
        <p><?php _e( 'This post is password protected. Enter the password to view any comments.', 'themater' ); ?></p>
    <?php return; } ?>
    
    <?php if ( have_comments() ) { ?>
        <div id="comments">
            
            <h3 id="comments-title"><?php
        	printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'themater' ),
        	number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
        	?></h3>
            
            <ol class="commentlist">
    		  <?php wp_list_comments(); ?>
            </ol>
            
            <?php if ( get_comment_pages_count() > 1 ) { ?>
    			<div class="navigation clearfix">
    				<div class="alignleft"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'themater' ) ); ?></div>
    				<div class="alignright"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'themater' ) ); ?></div>
    			</div><!-- .navigation .clearfix -->
            <?php } ?>
            
        </div><!-- #comments -->
    <?php } ?>
    
    <?php comment_form(); ?>