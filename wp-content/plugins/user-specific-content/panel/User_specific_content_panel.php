<?php
/**
 * User specific content Extention class to SimplePanel 0.3.2
 *
 * @version 0.1
 * @author Ohad Raz <admin@bainternet.info>
 * @copyright 2013 Ohad Raz
 * 
 */
if (!class_exists('User_specific_content_panel')){
	/**
	* socialCommentspanel
	*/
	class User_specific_content_panel extends SimplePanel{

		public function admin_menu(){
			$this->slug = add_users_page(
				$this->title, 
				$this->name, 
				$this->capability,
				get_class(), 
				array($this,'show_page')
			);

			//help tabs
			add_action('load-'.$this->slug, array($this,'_help_tab'));
			add_action( get_class().'add_meta_boxes', array($this,'add_meta_boxes' ));
		}

		/**
		 * add_meta_boxes to page
		 */
		public function add_meta_boxes(){
			add_meta_box( 'Save_settings', __('Save Settings', 'bauspc'), array($this,'savec'), get_class(), 'side','low');
			add_meta_box( 'Credit_sidebar', __('Credits', 'bauspc'), array($this,'credits'), get_class(), 'side','low');
			add_meta_box( 'News', __('Latest From Bainternet', 'bauspc'), array($this,'news'), get_class(), 'side','low');
			foreach ($this->sections as $s) {
				add_meta_box( $s['id'], $s['title'], array($this,'main_settings'), get_class(), 'normal','low',$s);
			}
		}

		/**
		 * news metabox
		 * @return [type] [description]
		 */
		public function news(){
			$news = get_transient( 'bainternetNews' );
			if ( !$news ) {
				if (!function_exists('fetch_feed'))
					include_once(ABSPATH . WPINC . '/feed.php');
				// Get a SimplePie feed object from the specified feed source.
				$rss = fetch_feed('http://en.bainternet.info/feed');
				ob_start();
				$maxitems = 0;

				if (!is_wp_error( $rss ) ) {
				    $maxitems = $rss->get_item_quantity(5); 
				    $rss_items = $rss->get_items(0, $maxitems); 
				}
				?>

				<ul>
				    <?php if ($maxitems == 0) echo '<li>No items.</li>';
				    else
				    // Loop through each feed item and display each item as a hyperlink.
				    foreach ( $rss_items as $item ) : ?>
				    <li>
				    	<span><?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?></span><br/>
				        <a target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>'
				        title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>'>
				        <?php echo esc_html( $item->get_title() ); ?></a>
				    </li>
				    <?php endforeach; ?>
				</ul>
				<?php
				$news = ob_get_clean();
				set_transient( 'bainternetNews', $news, 60 * 60 * 24 * 3 );
			}
			echo $news;
		}

		/**
		 * generate plugin button metabox
		 * @return [type] [description]
		 */
		public function savec(){
			echo '<span class="working" style="display:none;"><img src="images/wpspin_light.gif"></span>';
			submit_button('Save Changes');
		}

		/**
		 * main settings metaboxs
		 * @return [type] [description]
		 */
		function main_settings($args,$s = null){
        	
				echo '<table class="form-table">';
        		do_settings_fields(get_class(),$s['id']);
        		echo '</table>';
		}

		function credits(){
			?>
			<p><strong>
				<?php echo __( 'Want to help make this plugin even better? All donations are used to improve and support, so donate $20, $50 or $100 now!' , 'bauspc'); ?></strong></p>
			<a class="" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K4MMGF5X3TM5L" target="_blank"><img type="image" src="https://www.paypalobjects.com/<?php echo get_locale(); ?>/i/btn/btn_donate_LG.gif" border="0" alt="PayPal Ñ The safer, easier way to pay online."></a>
            <p><?php _e( 'Or you could:', 'bauspc'); ?></p>
            <ul>
                    <li><a href="http://wordpress.org/support/view/plugin-reviews/user-specific-content"><?php _e( 'Rate the plugin 5&#9733; on WordPress.org' , 'bauspc'); ?></a></li>
                    <li><a href="http://wordpress.org/plugins/user-specific-content/"><?php _e( 'Blog about it &amp; link to the plugin page', 'bauspc'); ?></a></li>
            </ul>
            <?php
		}

		public function show_page(){
			wp_enqueue_script('post');
			do_action(get_class().'add_meta_boxes');
			if(isset($this->inject['before_wrap']))
				echo $this->inject['before_wrap'];
			?>
		    <div class="wrap">
		    	<?php screen_icon('plugins'); ?>
		        <h2><?php echo $this->name; ?></h2>
		        <div id="message" class="below-h2"></div>
		        <?php settings_errors(); ?>
		        <?php do_action($this->slug.'_before_Form',$this); ?>
		         <form id="BPM_FORM" action="options.php" method="POST">
		         	<div id="poststuff" class="metabox-holder has-right-sidebar">
					    <div class="inner-sidebar">
					    	<!-- SIDEBAR BOXES -->
					    	<?php do_action($this->slug.'_before_sidebar',$this); ?>
					    	<?php do_meta_boxes( get_class(), 'side',$this ); ?>
					    	<?php do_action($this->slug.'_after_sidebar',$this); ?>
					    </div>
					    <div id="post-body" style="background-color: transparent;">
					        <div id="post-body-content">
					            <div id="titlediv"></div>
					            <div id="postdivrich" class="postarea"></div>
					            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
					                <!-- BOXES -->
					                <?php do_action($this->slug.'_before_metaboxes',$this); ?>
									<?php
					                	foreach ($this->sections as $s) {
						        			settings_fields($s['option_group']);
						        		}
					                	do_meta_boxes( get_class(), 'normal',$this ); 
					                ?>
					                <?php do_action($this->slug.'_after_metaboxes',$this); ?>
					            </div>
					        </div>
					    </div>
					    <br class="clear">
					</div>
		            <?php do_action($this->slug.'_after_Fields',$this); ?>
		        </form>
		        <?php do_action($this->slug.'_after_Form',$this); ?>
		    </div>
		    <?php
		    if(isset($this->inject['after_wrap']))
				echo $this->inject['after_wrap'];
			?>
		    <style>
		    .error{ background-color: #FFEBE8;border-color: #C00;}
		    .error input, .error textarea{ border-color: #C00;}
		    </style>
		    <?php
		}

		
		function _setting_radioImage($args) {
			$std   = isset($args['std'])? $args['std'] : '';
			$name  = esc_attr( $args['name'] );
			$value = esc_attr( $this->get_value($args['id'],$std));
			$items = $args['options'];
			$uri = $args['uri'];
			foreach($items as  $v) {
				$checked = ($value==$v) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$v' name='$name' type='radio' /><img src='{$uri}{$v}/facebook.png'><img src='{$uri}{$v}/gplus.png'><img src='{$uri}{$v}/wp.png'><img src='{$uri}{$v}/disqus.png'></label><br />";
			}
		}


		public function register_settings(){
			foreach ($this->sections as $s) {
				add_settings_section( $s['id'], $s['title'], array($this,'section_callback') , get_class() );
				register_setting( $s['option_group'], $this->option, array($this,'sanitize_callback') );
				
			}
			foreach ($this->fields as $f) {
				add_settings_field( $f['id'], $f['label'], array($this,'show_field'), get_class(), $f['section'], $f ); 
			}
		}

	}//end class

	$p = new User_specific_content_panel(
		array(
			'title'      => __('User Specific Content', 'bauspc'),
			'name'       => __('User Specific Content', 'bauspc'),
			'capability' => 'manage_options',
			'option'     => 'U_S_C'
		)
	);
	
	//main plugin fields
	include_once(dirname(__FILE__).'/../config/main_plugin_fields.php');
	
	$p->add_help_tab(array(
		'id'      => 'user_specific_content',
		'title'   => __('User Specific Content', 'bauspc'),
		'content' => '<div style="min-height: 350px">
                <h2 style="text-align: center;">'.__('User Specific Content', 'bauspc').'</h2>
                <div>
                		<p>'.__('If you have any questions or problems head over to', 'bauspc').' <a href="http://wordpress.org/support/plugin/user-specific-content">' . __('Plugin Support', 'bauspc') . '</a></p>
                        <p>' .__('If you like my wrok then please ', 'bauspc') .'<a class="button button-primary" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K4MMGF5X3TM5L" target="_blank">' . __('Donate', 'bauspc') . '</a>
                </div>
        </div>
        '
        )
	);
	$p->add_help_tab(array(
		'id'      => 'option_panel',
		'title'   => __('Option panel', 'bauspc'),
		'content' => '<div style="min-height: 350px">
                <p>'.__('All of the options here are pretty much self explanatory','bauspc').'</p>
                <ul>
                	<li><strong>Global Blocked message</strong> - '.__('(If set in a metabox the it overwrites this message for that secific post/page)', 'bauspc').'</li>
					<li><strong>Use with "the_content" hook?</strong> - '.__('Block content using `the_content` filter hook', 'bauspc').'</li>
					<li><strong>Use with "the_excerpt" hook?</strong> - '.__('Block content using `the_excerpt` filter hook', 'bauspc').'</li>
					<li><strong>list user names?</strong> - '.__('If unchecked then the metabox will not show an option to limit by user names.', 'bauspc').'</li>
					<li><strong>User List Type</strong> - '.__('This option lets you set the field type of the user list in the metabox', 'bauspc').'</li>
					<li><strong>list user roles?</strong> - '.__('If unchecked then the metabox will not show an option to limit by user roles.', 'bauspc').'</li>
					<li><strong>User Roles List Type</strong> - '.__('This option lets you set the field type of the user role list in the metabox', 'bauspc').'</li>
					<li><strong>Capability</strong> - '.__('The capability needed by the user to see the metabox', 'bauspc').'</li>
					<li><strong>POST TYPES</strong> - '.__('Lets you enable or the metabox on any (public) post type', 'bauspc').'</li>
				</ul>
        </div>
        '
        )
	);
	$p->add_help_tab(array(
		'id'      => 'shortcode',
		'title'   => __('Shortcode', 'bauspc'),
		'content' => '<div style="min-height: 350px">
                <p>'.__('Since version 0.7 you can use a shortcode <pre>[U_O]</pre> which accepts the following parameters:','bauspc').'</p>
                <ul>
                	<li><strong>user_id</strong> - '.__('specific user ids form more then one separate by comma', 'bauspc').'</li>
					<li><strong>user_name</strong> - '.__('specific user names form more then one separate by comma', 'bauspc').'</li>
					<li><strong>user_role</strong> - '.__('specific user role form more then one separate by comma', 'bauspc').'</li>
					<li><strong>blocked_message</strong> - '.__('specific Content Blocked message', 'bauspc').'</li>
				</ul>
				<p>eg:</p><pre>[O_U user_role="Administrator" blocked_message="admins only!"]admin content goes here[/O_U]</pre>
        </div>
        '
        )
	);
	$GLOBALS['socialComments_pannel'] = $p;
}//end if