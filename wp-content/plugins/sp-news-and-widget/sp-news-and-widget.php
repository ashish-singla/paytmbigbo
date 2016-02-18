<?php
/*
Plugin Name: WP News and three widgets(static, scrolling and scrolling with thumbs)
Plugin URL: http://www.wponlinesupport.com/
Description: A simple News and three widgets(static, scrolling and scrolling with thumbs) plugin
Version: 3.2.1
Author: WP Online Support
Author URI: http://www.wponlinesupport.com/
Contributors: WP Online Support
*/
/*
 * Register CPT sp_News
 *
 */
// Initialization function
add_action('init', 'sp_cpt_news_init');
function sp_cpt_news_init() {
  // Create new News custom post type
    $news_labels = array(
    'name'                 => _x('News', 'post type general name'),
    'singular_name'        => _x('News', 'post type singular name'),
    'add_new'              => _x('Add News Item', 'news'),
    'add_new_item'         => __('Add New News Item'),
    'edit_item'            => __('Edit News Item'),
    'new_item'             => __('New News Item'),
    'view_item'            => __('View News Item'),
    'search_items'         => __('Search  News Items'),
    'not_found'            =>  __('No News Items found'),
    'not_found_in_trash'   => __('No  News Items found in Trash'), 
    '_builtin'             =>  false, 
    'parent_item_colon'    => '',
    'menu_name'            => 'News'
  );
  $news_args = array(
    'labels'              => $news_labels,
    'public'              => true,
    'publicly_queryable'  => true,
    'exclude_from_search' => false,
    'show_ui'             => true,
    'show_in_menu'        => true, 
    'query_var'           => true,
    'rewrite'             => array( 
							'slug' => 'news',
							'with_front' => false
							),
    'capability_type'     => 'post',
    'has_archive'         => true,
    'hierarchical'        => false,
    'menu_position'       => 8,
	'menu_icon'   => 'dashicons-feedback',
    'supports'            => array('title','editor','thumbnail','excerpt','comments'),
    'taxonomies'          => array('post_tag')
  );
  register_post_type('news',$news_args);
}
/* Register Taxonomy */
add_action( 'init', 'news_taxonomies');
function news_taxonomies() {
    $labels = array(
        'name'              => _x( 'Category', 'taxonomy general name' ),
        'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Category' ),
        'all_items'         => __( 'All Category' ),
        'parent_item'       => __( 'Parent Category' ),
        'parent_item_colon' => __( 'Parent Category:' ),
        'edit_item'         => __( 'Edit Category' ),
        'update_item'       => __( 'Update Category' ),
        'add_new_item'      => __( 'Add New Category' ),
        'new_item_name'     => __( 'New Category Name' ),
        'menu_name'         => __( 'Category' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'news-category' ),
    );

    register_taxonomy( 'news-category', array( 'news' ), $args );
}

function my_rewrite_flush() {  
		sp_cpt_news_init();  
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'my_rewrite_flush' );
add_action( 'wp_enqueue_scripts','style_css_script' );
    function style_css_script() {
        wp_enqueue_style( 'cssnews',  plugin_dir_url( __FILE__ ). 'css/stylenews.css' );
        wp_enqueue_script( 'vticker', plugin_dir_url( __FILE__ ) . 'js/jcarousellite.js', array( 'jquery' ));
    }
class SP_News_Widget extends WP_Widget {

    function SP_News_Widget() {

        $widget_ops = array('classname' => 'SP_News_Widget', 'description' => __('Displayed Latest News Items from the News  in a sidebar', 'news_cpt') );
        $control_ops = array( 'width' => 350, 'height' => 450, 'id_base' => 'sp_news_widget' );
        $this->WP_Widget( 'sp_news_widget', __('Latest News Widget', 'news_cpt'), $widget_ops, $control_ops );
    }

    function form($instance) {
        $defaults = array(
        'limit'             => 5,
        'title'             => '',
        "date"              => false, 
        'show_category'     => false,
        'category'          => 0,
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_items = isset($instance['num_items']) ? absint($instance['num_items']) : 5;
    ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('num_items'); ?>">Number of Items: <input class="widefat" id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="text" value="<?php echo attribute_escape($num_items); ?>" /></label></p>
      <p>
            <input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" type="checkbox"<?php checked( $instance['date'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e( 'Display Date', 'news' ); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id( 'show_category' ); ?>" name="<?php echo $this->get_field_name( 'show_category' ); ?>" type="checkbox"<?php checked( $instance['show_category'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'show_category' ); ?>"><?php _e( 'Display Category', 'news' ); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:', 'news' ); ?></label>
            <?php
                $dropdown_args = array( 'taxonomy' => 'news-category', 'class' => 'widefat', 'show_option_all' => __( 'All', 'news' ), 'id' => $this->get_field_id( 'category' ), 'name' => $this->get_field_name( 'category' ), 'selected' => $instance['category'] );
                wp_dropdown_categories( $dropdown_args );
            ?>
        </p>	
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['num_items'] = $new_instance['num_items'];
        $instance['date'] = (bool) esc_attr( $new_instance['date'] );
        $instance['show_category'] = (bool) esc_attr( $new_instance['show_category'] );
        $instance['category']      = intval( $new_instance['category'] );   
        return $instance;
    }
    function widget($news_args, $instance) {
        extract($news_args, EXTR_SKIP);

        $current_post_name = get_query_var('name');

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $num_items = empty($instance['num_items']) ? '5' : apply_filters('widget_title', $instance['num_items']);
        if ( isset( $instance['date'] ) && ( 1 == $instance['date'] ) ) { $date = "true"; } else { $date = "false"; }
        if ( isset( $instance['show_category'] ) && ( 1 == $instance['show_category'] ) ) { $show_category = "true"; } else { $show_category = "false"; }
        if ( isset( $instance['category'] ) && is_numeric( $instance['category'] ) ) $category = intval( $instance['category'] );
        $postcount = 0;

        echo $before_widget;

?>
             <h4 class="widgettitle"><?php echo $title ?></h4>
            <!--visual-columns-->
            <?php if($date == "false" && $show_category == "false"){ 
                $no_p = "no_p";
                }?>
            <div class="recent-news-items <?php echo $no_p?>">
                <ul>
            <?php // setup the query
            $news_args = array( 'suppress_filters' => true,
                           'posts_per_page' => $num_items,
                           'post_type' => 'news',
                           'order' => 'DESC'
                         );

            if($category != 0){
            	$news_args['tax_query'] = array( array( 'taxonomy' => 'news-category', 'field' => 'id', 'terms' => $category) );
            }
            $cust_loop = new WP_Query($news_args);
               $post_count = $cust_loop->post_count;
          $count = 0;
           
            if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post(); $postcount++;
                    $count++;
               $terms = get_the_terms( $post->ID, 'news-category' );
                    $news_links = array();
                    if($terms){

                    foreach ( $terms as $term ) {
                        $term_link = get_term_link( $term );
                        $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
                    }
                }
                    $cate_name = join( ", ", $news_links );
                    ?>
                    <li class="news_li">
                       <h6> <a class="post-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
						<?php echo ($date == "true")? '<p>'.get_the_date('j, M y') : "" ;?>
                  		<?php echo ($date == "true" && $show_category == "true" && $cate_name != '') ? " , " : "";?>
                  		<?php echo ($show_category == 'true' && $cate_name != '') ? $cate_name.'</p>' : ""?>
                    </li>
            <?php endwhile;
            endif;
             wp_reset_query(); ?>

                </ul>

            </div>
<?php
        echo $after_widget;
    }
}
/* Register the widget */
function sp_news_widget_load_widgets() {
    register_widget( 'SP_News_Widget' );
}
/* Load the widget */
add_action( 'widgets_init', 'sp_news_widget_load_widgets' );
/* scrolling news */
class SP_News_scrolling_Widget extends WP_Widget {
    function SP_News_scrolling_Widget() {
        $widget_ops = array('classname' => 'SP_News_scrolling_Widget', 'description' => __('Displayed Latest News Items from the News  in a sidebar', 'news_cpt') );
        $control_ops = array( 'width' => 350, 'height' => 450, 'id_base' => 'sp_news_s_widget' );
        $this->WP_Widget( 'sp_news_s_widget', __('Latest News Scrolling Widget', 'news_cpt'), $widget_ops, $control_ops );
    }
    function form($instance) {
        $defaults = array(
        'limit'             => 5,
        'title'             => '',
        "date"              => false, 
        'show_category'     => false,
        'category'          => 0,
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_items = isset($instance['num_items']) ? absint($instance['num_items']) : 5;              
    ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('num_items'); ?>">Number of Items: <input class="widefat" id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="text" value="<?php echo attribute_escape($num_items); ?>" /></label></p>
      <p>
            <input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" type="checkbox"<?php checked( $instance['date'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e( 'Display Date', 'news' ); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id( 'show_category' ); ?>" name="<?php echo $this->get_field_name( 'show_category' ); ?>" type="checkbox"<?php checked( $instance['show_category'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'show_category' ); ?>"><?php _e( 'Display Category', 'news' ); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:', 'news' ); ?></label>
            <?php
                $dropdown_args = array( 'taxonomy' => 'news-category', 'class' => 'widefat', 'show_option_all' => __( 'All', 'news' ), 'id' => $this->get_field_id( 'category' ), 'name' => $this->get_field_name( 'category' ), 'selected' => $instance['category'] );
                wp_dropdown_categories( $dropdown_args );
            ?>
        </p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['num_items'] = $new_instance['num_items'];
        $instance['date'] = (bool) esc_attr( $new_instance['date'] );
        $instance['show_category'] = (bool) esc_attr( $new_instance['show_category'] );
        $instance['category']      = intval( $new_instance['category'] );        
        return $instance;
    }
    function widget($news_args, $instance) {
        extract($news_args, EXTR_SKIP);
        $current_post_name = get_query_var('name');
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);  
		$num_items = empty($instance['num_items']) ? '5' : apply_filters('widget_title', $instance['num_items']);  
        if ( isset( $instance['date'] ) && ( 1 == $instance['date'] ) ) { $date = "true"; } else { $date = "false"; }
        if ( isset( $instance['show_category'] ) && ( 1 == $instance['show_category'] ) ) { $show_category = "true"; } else { $show_category = "false"; }
        if ( isset( $instance['category'] ) && is_numeric( $instance['category'] ) ) $category = intval( $instance['category'] );
        $postcount = 0;

        echo $before_widget;

?>
             <h4 class="widgettitle"><?php echo $title ?></h4>
            <!--visual-columns-->
            <?php if($date == "false" && $show_category == "false"){ 
                $no_p = "no_p";
                }?>
            <div class="recent-news-items <?php echo $no_p;?>" style="margin-top:24px">
               <div class="newsticker-jcarousellite">
			   <ul>
            <?php // setup the query
            $news_args = array( 'suppress_filters' => true,
       							'posts_per_page' => $num_items,                   
                           'post_type' => 'news',
                           'order' => 'DESC'
                         );
            if($category != 0){
            	$news_args['tax_query'] = array( array( 'taxonomy' => 'news-category', 'field' => 'id', 'terms' => $category) );
            }
            $cust_loop = new WP_Query($news_args);
               $post_count = $cust_loop->post_count;
          $count = 0;
           
            if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post(); $postcount++;
                    $count++;
               $terms = get_the_terms( $post->ID, 'news-category' );
                    $news_links = array();
                    if($terms){

                    foreach ( $terms as $term ) {
                        $term_link = get_term_link( $term );
                        $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
                    }
                }
                    $cate_name = join( ", ", $news_links );
                    ?>
                    <li class="news_li">
                        <h6><a class="post-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
                    	<?php echo ($date == "true")? '<p>'.get_the_date('j, M y') : "" ;?>
                  		<?php echo ($date == "true" && $show_category == "true" && $cate_name != '') ? " , " : "";?>
                  		<?php echo ($show_category == 'true' && $cate_name != '') ? $cate_name.'</p>' : ""?>
                  		<hr size="1px" style=" display: block;background-color:gainsboro;border-style: inset;border-width: 0px;" />
                    </li>
            <?php endwhile;
            endif;
             wp_reset_query(); ?>
                </ul>
	            </div>
            </div>
<?php
        echo $after_widget;
    }
}

/* Register the widget */
function sp_news_scroll_widget_load_widgets() {
    register_widget( 'SP_News_scrolling_Widget' );
}

/* Load the widget */
add_action( 'widgets_init', 'sp_news_scroll_widget_load_widgets' );

/* news with thumb */
class SP_News_thmb_Widget extends WP_Widget {

    function SP_News_thmb_Widget() {

        $widget_ops = array('classname' => 'SP_News_thmb_Widget', 'description' => __('Displayed Latest News Items from the News  in a sidebar with thumbnails', 'news_cpt') );
        $control_ops = array( 'width' => 350, 'height' => 450, 'id_base' => 'sp_news_sthumb_widget' );
        $this->WP_Widget( 'sp_news_sthumb_widget', __('Latest News with thumb  Widget', 'news_cpt'), $widget_ops, $control_ops );
    }

    function form($instance) {	
        $defaults = array(
        'limit'             => 5,
        'title'             => '',
        "date"              => false, 
        'show_category'     => false,
        'category'          => 0,
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_items = isset($instance['num_items']) ? absint($instance['num_items']) : 5;
    ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('num_items'); ?>">Number of Items: <input class="widefat" id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="text" value="<?php echo attribute_escape($num_items); ?>" /></label></p>
    	<p>
            <input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" type="checkbox"<?php checked( $instance['date'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e( 'Display Date', 'news' ); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id( 'show_category' ); ?>" name="<?php echo $this->get_field_name( 'show_category' ); ?>" type="checkbox"<?php checked( $instance['show_category'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'show_category' ); ?>"><?php _e( 'Display Category', 'news' ); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:', 'news' ); ?></label>
            <?php
                $dropdown_args = array( 'taxonomy' => 'news-category', 'class' => 'widefat', 'show_option_all' => __( 'All', 'news' ), 'id' => $this->get_field_id( 'category' ), 'name' => $this->get_field_name( 'category' ), 'selected' => $instance['category'] );
                wp_dropdown_categories( $dropdown_args );
            ?>
        </p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['num_items'] = $new_instance['num_items'];
        $instance['date'] = (bool) esc_attr( $new_instance['date'] );
        $instance['show_category'] = (bool) esc_attr( $new_instance['show_category'] );
        $instance['category']      = intval( $new_instance['category'] );
        return $instance;
    }
    function widget($news_args, $instance) {
        extract($news_args, EXTR_SKIP);

        $current_post_name = get_query_var('name');

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $num_items = empty($instance['num_items']) ? '5' : apply_filters('widget_title', $instance['num_items']);
        if ( isset( $instance['date'] ) && ( 1 == $instance['date'] ) ) { $date = "true"; } else { $date = "false"; }
        if ( isset( $instance['show_category'] ) && ( 1 == $instance['show_category'] ) ) { $show_category = "true"; } else { $show_category = "false"; }
        if ( isset( $instance['category'] ) && is_numeric( $instance['category'] ) ) $category = intval( $instance['category'] );
        $postcount = 0;
        echo $before_widget;
?>
             <h4 class="widgettitle"><?php echo $title ?></h4>
            <!--visual-columns-->
            <?php if($date == "false" && $show_category == "false"){ 
                $no_p = "no_p";
                }?>
            <div class="recent-news-items <?php echo $no_p;?>">
			  <div class="newstickerthumb-jcarousellite">
                <ul>
            <?php // setup the query
            $news_args = array( 'suppress_filters' => true,
                           'posts_per_page' => $num_items,
                           'post_type' => 'news',
                           'order' => 'DESC'
                         );
            if($category != 0){
            	$news_args['tax_query'] = array( array( 'taxonomy' => 'news-category', 'field' => 'id', 'terms' => $category) );
            }

            $cust_loop = new WP_Query($news_args);
            $post_count = $cust_loop->post_count;
          $count = 0;
            if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post(); $postcount++;
                    $count++;
               $terms = get_the_terms( $post->ID, 'news-category' );
                    $news_links = array();
                    if($terms){

                    foreach ( $terms as $term ) {
                        $term_link = get_term_link( $term );
                        $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
                    }
                }
                    $cate_name = join( ", ", $news_links );
                    ?>
                    <li class="news_li">
						<div class="news_thumb_left">
					   <a  href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> 
                  		
                  	<?php
                  if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                   the_post_thumbnail( array(80,80) );
                  }
                  ?> </a></div>
				  <div class="news_thumb_right">
                        <h6><a class="post-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
						<?php echo ($date == "true")? '<p>'.get_the_date('j, M y') : "" ;?>
                  		<?php echo ($date == "true" && $show_category == "true" && $cate_name != '') ? " , " : "";?>
                  		<?php echo ($show_category == 'true' && $cate_name != '') ? $cate_name.'</p>' : ""?></div>
						
                    </li>
            <?php endwhile;
            endif;
             wp_reset_query(); ?>

                </ul>
            </div> </div>
<?php
        echo $after_widget;
    }
}
/* Register the widget */
function sp_news_thumb_widget_load_widgets() {
    register_widget( 'SP_News_thmb_Widget' );
}

/* Load the widget */
add_action( 'widgets_init', 'sp_news_thumb_widget_load_widgets' );


function get_news( $atts, $content = null ){
            // setup the query
            extract(shortcode_atts(array(
		"limit" => '',	
		"category" => '',
		"grid" => '',
        "show_date" => '',
        "show_category_name" => '',
        "show_content" => '',
        "content_words_limit" => '',
	), $atts));
	// Define limit
	if( $limit ) { 
		$posts_per_page = $limit; 
	} else {
		$posts_per_page = '-1';
	}
	if( $category ) { 
		$cat = $category; 
	} else {
		$cat = '';
	}
	if( $grid ) { 
		$gridcol = $grid; 
	} else {
		$gridcol = '1';
	}
    if( $show_date ) { 
        $showDate = $show_date; 
    } else {
        $showDate = 'true';
    }
	if( $show_category_name ) { 
        $showCategory = $show_category_name; 
    } else {
        $showCategory = 'true';
    }
    if( $show_content ) { 
        $showContent = $show_content; 
    } else {
        $showContent = 'true';
    }
	 if( $content_words_limit ) { 
        $words_limit = $content_words_limit; 
    } else {
        $words_limit = '20';
    }
	ob_start();
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	
	$post_type 		= 'news';
	$orderby 		= 'post_date';
	$order 			= 'DESC';
				 
        $args = array ( 
            'post_type'      => $post_type, 
            'orderby'        => $orderby, 
            'order'          => $order,
            'posts_per_page' => $posts_per_page,   
            'paged'          => $paged,
            );
	if($cat != ""){
            	$args['tax_query'] = array( array( 'taxonomy' => 'news-category', 'field' => 'id', 'terms' => $cat) );
            }        
      $query = new WP_Query($args);
      $post_count = $query->post_count;
          $count = 0;
             if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
             $count++;
               $terms = get_the_terms( $post->ID, 'news-category' );
                    $news_links = array();
                    if($terms){

                    foreach ( $terms as $term ) {
                        $term_link = get_term_link( $term );
                        $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
                    }
                }
                    $cate_name = join( ", ", $news_links );
                $css_class="team";
                if ( ( is_numeric( $grid ) && ( $grid > 0 ) && ( 0 == ($count - 1) % $grid ) ) || 1 == $count ) { $css_class .= ' first'; }
                if ( ( is_numeric( $grid ) && ( $grid > 0 ) && ( 0 == $count % $grid ) ) || $post_count == $count ) { $css_class .= ' last'; }
                if($showDate == 'true'){ $date_class = "has-date";}else{$date_class = "has-no-date";}
                ?>
			
            	<div id="post-<?php the_ID(); ?>" class="news type-news news-col-<?php echo $gridcol.' '.$css_class.' '.$date_class; ?>">
					<div class="news-thumb">
					<?php
						// Post thumbnail.
						if ( has_post_thumbnail())  {
							if($gridcol == '1'){ ?>
						 <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('url'); ?></a>
						<?php } else if($gridcol > '2') { ?>
							<div class="grid-news-thumb">	
						 <a href="<?php the_permalink(); ?>">	<?php the_post_thumbnail('thumbnail'); ?></a>
							</div>
					<?php	} else { ?>
					<div class="grid-news-thumb">	
							 <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
							</div>
					<?php } }?>
					</div>
					<div class="news-content">
					<?php if($gridcol == '1') { 
                        if($showDate == 'true'){?>
						<div class="date-post">					
						<h2><span><?php echo get_the_date('j'); ?></span></h2>
						<p><?php echo get_the_date('M y'); ?></p>
						</div>
                        <?php }?>
					<?php } else { 

                        ?>
						<div class="grid-date-post">
						<?php echo ($showDate == "true")? get_the_date('j, M y') : "" ;?>
                        <?php echo ($showDate == "true" && $showCategory == "true" && $cate_name != '') ? " , " : "";?>
                        <?php echo ($showCategory == 'true' && $cate_name != '') ? $cate_name : ""?>
						</div>
					<?php  } ?>
					<div class="post-content-text">
						<?php the_title( sprintf( '<h4 class="news-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );	?>
					    
						<?php if($showCategory == 'true' && $gridcol == '1'){ ?>
						<div class="news-cat">
                            <?php echo $cate_name; ?>
							</div>
                       <?php }?>
                     <?php if($showContent == 'true'){?>   
					<div class="news-content-excerpt">
					<?php $excerpt = get_the_excerpt();?>
                    <p class="news-short-content"><?php echo string_limit_words($excerpt,$words_limit); ?>...</p>
                   
                        <a href="<?php the_permalink(); ?>" class="more-link">Read More</a>	
					</div><!-- .entry-content -->
                    <?php }?>
					</div>
				</div>
</div><!-- #post-## -->			  
          <?php  endwhile;
            endif; ?>
			<div class="news_pagination">				 	

<div class="button-news-p"><?php previous_posts_link( '<< Previous' ); ?></div>
<div class="button-news-n"><?php next_posts_link( 'Next >>' ); ?> </div>
</div>	
			<?php
             wp_reset_query(); 
				
		return ob_get_clean();			             
	}
add_shortcode('sp_news','get_news');	
function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}	
	function mynewsscript() {
	$option = 'NewsWidget_option';
	$newsscrollingoptionadmin = get_option( $option); 
	$customscrollpost = $newsscrollingoptionadmin['news_width']; 
	$customscrollpostheight = $newsscrollingoptionadmin['news_height'];
	$customscrollpostdelay = $newsscrollingoptionadmin['news_delay'];
	$customscrollpostspeed = $newsscrollingoptionadmin['news_speed'];
  
		if ($customscrollpost == 0 )
		{
			$vtrue = 'true';
		} else { $vtrue = 'false';
		}
		if ($customscrollpostheight == '' )
		{
			$vvisible = 3;
		} else { $vvisible = $customscrollpostheight;
		}
		if ($customscrollpostdelay == '' )
		{
			$vdelay = 500;
		} else { $vdelay = $customscrollpostdelay;
		}
		if ($customscrollpostspeed == '' )
		{
			$vspeed = 2000;
		} else { $vspeed = $customscrollpostspeed;
		}
	?>
	<script type="text/javascript">
	
jQuery(function() {
	 jQuery(".newsticker-jcarousellite").jCarouselLite({
		vertical: <?php echo $vtrue; ?>,
		hoverPause:true,
		visible: <?php echo $vvisible; ?>,
		auto: <?php echo $vdelay; ?>,
		speed:<?php echo $vspeed; ?>,
		
	});  
	 jQuery(".newstickerthumb-jcarousellite").jCarouselLite({
		vertical: <?php echo $vtrue; ?>,
		hoverPause:true,
		visible: <?php echo $vvisible; ?>,
		auto: <?php echo $vdelay; ?>,
		speed:<?php echo $vspeed; ?>,  
	}); 
});
</script>
	<?php
	}
add_action('wp_head', 'mynewsscript');

class SP_News_setting
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_news_page' ) );
        add_action( 'admin_init', array( $this, 'page_init_news' ) );
    }

    /**
     * Add options page
     */
    public function add_news_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'News Widget Settings', 
            'manage_options', 
            'news-setting-admin', 
            array( $this, 'create_newsadmin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_newsadmin_page()
    {
        // Set class property
        $this->options = get_option( 'NewsWidget_option' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Scrolling News Widget Setting</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'news_option_group' );   
                do_settings_sections( 'news-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init_news()
    {        
        register_setting(
            'news_option_group', // Option group
            'NewsWidget_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Scorlling News Widget Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'news-setting-admin' // Page
        );  

        add_settings_field(
            'news_width', // ID
            'Widget Scrolling Direction (Vertical OR Horizontal) ', // Title 
            array( $this, 'news_width_callback' ), // Callback
            'news-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'news_height', 
            'Number of news visible at a time', 
            array( $this, 'news_height_callback' ), 
            'news-setting-admin', 
            'setting_section_id'
        );      
		add_settings_field(
            'news_delay', // ID
            'Enter delay ', // Title 
            array( $this, 'news_delay_callback' ), // Callback
            'news-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'news_speed', 
            'Enter speed', 
            array( $this, 'news_speed_callback' ), 
            'news-setting-admin', 
            'setting_section_id'
        );     
	
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['news_width'] ) )
            $new_input['news_width'] = sanitize_text_field( $input['news_width'] );

        if( isset( $input['news_height'] ) )
            $new_input['news_height'] = sanitize_text_field( $input['news_height'] );
		
		 if( isset( $input['news_delay'] ) )
            $new_input['news_delay'] = sanitize_text_field( $input['news_delay'] );
			
		 if( isset( $input['news_speed'] ) )
            $new_input['news_speed'] = sanitize_text_field( $input['news_speed'] );	

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function news_width_callback()
    {
        printf(
            '<input type="text" id="news_width" name="NewsWidget_option[news_width]" value="%s" />',
            isset( $this->options['news_width'] ) ? esc_attr( $this->options['news_width']) : ''
        );
		printf(' Enter "0" for <b>Vertical Scrolling</b> and "1" for <b>Horizontal Scrolling</b>');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function news_height_callback()
    {
        printf(
            '<input type="text" id="news_height" name="NewsWidget_option[news_height]" value="%s" />',
            isset( $this->options['news_height'] ) ? esc_attr( $this->options['news_height']) : ''
        );
		printf(' ie 1, 2, 3, 4 etc');
    }
	 public function news_delay_callback()
    {
        printf(
            '<input type="text" id="news_delay" name="NewsWidget_option[news_delay]" value="%s" />',
            isset( $this->options['news_delay'] ) ? esc_attr( $this->options['news_delay']) : ''
        );
		printf(' ie 500, 1000 milliseconds delay');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function news_speed_callback()
    {
        printf(
            '<input type="text" id="news_speed" name="NewsWidget_option[news_speed]" value="%s" />',
            isset( $this->options['news_speed'] ) ? esc_attr( $this->options['news_speed']) : ''
        );
		printf(' ie 500, 1000 milliseconds speed');
    }
}

if( is_admin() )
    $my_newssettings_page = new SP_News_setting();
?>
