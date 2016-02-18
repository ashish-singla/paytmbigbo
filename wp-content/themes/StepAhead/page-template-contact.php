<?php session_start();
/**
 * Template Name: Contact Form
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
            
            <div class="contact-form">
                <?php
                
                    if ($_POST['contact_form_submit'] ) {
                        if(!$_POST['contact_form_name'] || !$_POST['contact_form_email'] || !$_POST['contact_form_subject'] || !$_POST['contact_form_question'] || !$_POST['contact_form_message']) {
                            ?><div class="error"><?php _e('Please fill in all required fields!','themater'); ?></div><?php
                        } elseif(!is_email($_POST['contact_form_email'])) {
                            ?><div class="error"><?php _e('Invalid email!','themater'); ?></div><?php
                        } elseif(($_SESSION['contact_form_number_one'] + $_SESSION['contact_form_number_two']) != $_POST['contact_form_question']) {
                            ?><div class="error"><?php _e('You entered the wrong number in captcha!','themater'); ?></div><?php
                        } else {
                            wp_mail($theme->get_option('contact_form_email'), sprintf( '[%s] ' . esc_html($_POST['contact_form_subject']), get_bloginfo('name') ), esc_html($_POST['contact_form_message']),'From: "'. esc_html($_POST['contact_form_name']) .'" <' . esc_html($_POST['contact_form_email']) . '>');
                            unset($_POST);
                            ?><div class="message"><?php _e('Thanks for contacting us.','themater'); ?></div><?php
                        }
                        if ( isset($_SESSION['contact_form_number_one'] ) ) unset( $_SESSION['contact_form_number_one'] );
                        if ( isset($_SESSION['contact_form_number_two'] ) ) unset( $_SESSION['contact_form_number_two'] );
                    }
                    
                    if ( !isset($_SESSION['contact_form_number_one'] ) ) $_SESSION['contact_form_number_one'] = $contact_form_number_one = rand(1, 9);
                	else $contact_form_number_one = $_SESSION['contact_form_number_one'];
                	
                	if ( !isset($_SESSION['contact_form_number_two'] ) ) $_SESSION['contact_form_number_two'] = $contact_form_number_two = rand(1, 9);
                	else $contact_form_number_two = $_SESSION['contact_form_number_two'];
                ?>
                
                <form method="post" action="">
                    <input type="hidden" name="contact_form_submit" value="true" />
                    <div class="contact-form-label alignleft <?php if($_POST && !$_POST['contact_form_name']) { echo 'contact-form-required'; } ?>"><?php _e('Name','themater'); ?>:</div>
                    <div class="contact-form-input"><input type="text" name="contact_form_name" value="<?php if ( isset($_POST['contact_form_name']) ) { echo esc_attr($_POST['contact_form_name']); } ?>" /></div>
                    
                    <div class="contact-form-label alignleft <?php if($_POST && !$_POST['contact_form_email']) { echo 'contact-form-required'; } ?>"><?php _e('Email','themater'); ?>:</div>
                    <div class="contact-form-input"><input type="text" name="contact_form_email" value="<?php if ( isset($_POST['contact_form_email']) ) { echo esc_attr($_POST['contact_form_email']); } ?>" /></div>
                    
                    <div class="contact-form-label alignleft <?php if($_POST && !$_POST['contact_form_question']) { echo 'contact-form-required'; } ?>"><?php echo $contact_form_number_one; ?> + <?php echo $contact_form_number_two; ?> = ?</div>
                    <div class="contact-form-input"><input type="text" name="contact_form_question" value="" /></div>
                    
                    <div class="contact-form-label alignleft <?php if($_POST && !$_POST['contact_form_subject']) { echo 'contact-form-required'; } ?>"><?php _e('Subject','themater'); ?>:</div>
                    <div class="contact-form-input"><input type="text" name="contact_form_subject" value="<?php if ( isset($_POST['contact_form_subject']) ) { echo esc_attr($_POST['contact_form_subject']); } ?>" /></div>
                    
                    <div class="contact-form-label alignleft <?php if($_POST && !$_POST['contact_form_message']) { echo 'contact-form-required'; } ?>"><?php _e('Message','themater'); ?>:</div>
                    <div class="contact-form-input"><textarea name="contact_form_message" ><?php if ( isset($_POST['contact_form_message']) ) { echo esc_textarea($_POST['contact_form_message']); } ?></textarea></div>
                    
                    <div class="contact-form-label alignleft">&nbsp;</div>
                    <div class="contact-form-input" style="text-align: center;"><input type="submit" value="<?php _e('Submit','themater'); ?>" /></div>
                </form>
                
            </div>
            
            <?php $theme->hook('content_after'); ?>
        
        </div><!-- #content -->
    
        <?php get_sidebars(); ?>
        
        <?php $theme->hook('main_after'); ?>
        
    </div><!-- #main -->
    
<?php get_footer(); ?>