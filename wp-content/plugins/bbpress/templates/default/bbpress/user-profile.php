<?php

/**
 * User Profile
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

	<?php do_action( 'bbp_template_before_user_profile' ); ?>

	<div id="bbp-user-profile" class="bbp-user-profile" style="font-size: 14px;line-height: 30px;">
		<h2 class="entry-title" style="font-weight: bold"><?php _e( 'Profile', 'bbpress' ); ?></h2>
		
		<?php $u=get_user_meta( bbp_get_user_id()); $Merchant_ID=$u['Merchant_ID']['0']; $state=$u['State']['0']; $category=$u['Category']['0'];?>
		
			<?php $user = get_userdata(bbp_get_user_id());
			
			if ( !empty( $user->display_name ) ) {
				$user_nicename = $user->user_nicename;
				$user_email=$user->user_email;
				
echo '<b>Name: </b>'.$user_nicename; echo '<br/>';/*echo 'Eamil ID: '.$user_email;echo '<br/>'*/;echo '<b>Merchant ID:</b> '.$Merchant_ID;echo '<br/>';echo '<b>State:</b> '.$state.'<br/>';echo '<b>Category:</b> '.$category.'<br/>';
} ?>
			
		<!--<div class="bbp-user-section">

			<?php if ( bbp_get_displayed_user_field( 'description' ) ) : ?>

				<p class="bbp-user-description"><?php bbp_displayed_user_field( 'description' ); ?></p>

			<?php endif; ?>

			<p class="bbp-user-forum-role" style="margin: 0 0 0px 0;"><?php  printf( __( '<b>Forum Role:</b> %s',      'bbpress' ), bbp_get_user_display_role()    ); ?></p>
			<p class="bbp-user-topic-count" style="margin: 0 0 0px 0;"><?php printf( __( '<b>Topics Started:</b> %s',  'bbpress' ), bbp_get_user_topic_count_raw() ); ?></p>
			<p class="bbp-user-reply-count" style="margin: 0 0 0px 0;"><?php printf( __( '<b>Replies Created:</b> %s', 'bbpress' ), bbp_get_user_reply_count_raw() ); ?></p>
		</div>-->
	</div><!-- #bbp-author-topics-started -->

	<?php do_action( 'bbp_template_after_user_profile' ); ?>
