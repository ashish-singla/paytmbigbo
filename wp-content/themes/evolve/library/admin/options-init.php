<?php

    // OLD DATA MIGRATION
    add_action( 'after_setup_theme', 'evolve_migrate_options' );
    function evolve_migrate_options() {
        $migrate_done = get_option( 'evl_33_migrate', false );
        if ($migrate_done !== 'done') {
            $newData = get_option( 'evl_options', false );
            if ( empty( $newData ) ) {
                $config = get_option( 'evolve' );
                if ( isset( $config['id'] ) ) {
                    $oldData = get_option( $config['id'], array() );
                    if ( ! empty( $oldData ) ) {
                        foreach ( $oldData as $key => $value ) {
                            $fontKeys  = array(
                                'evl_bootstrap_slide_subtitle_font',
                                'evl_bootstrap_slide_title_font',
                                'evl_carousel_slide_subtitle_font',
                                'evl_carousel_slide_title_font',
                                'evl_content_font',
                                'evl_content_h1_font',
                                'evl_content_h2_font',
                                'evl_content_h3_font',
                                'evl_content_h4_font',
                                'evl_content_h5_font',
                                'evl_content_h6_font',
                                'evl_menu_font',
                                'evl_parallax_slide_subtitle_font',
                                'evl_parallax_slide_title_font',
                                'evl_post_font',
                                'evl_tagline_font',
                                'evl_title_font',
                                'evl_widget_content_font',
                                'evl_widget_title_font',
                            );
                            $mediaKeys = array(
                                'evl_bootstrap_slide1_img',
                                'evl_bootstrap_slide2_img',
                                'evl_bootstrap_slide3_img',
                                'evl_bootstrap_slide4_img',
                                'evl_bootstrap_slide5_img',
                                'evl_content_background_image',
                                'evl_favicon',
                                'evl_footer_background_image',
                                'evl_header_logo',
                                'evl_scheme_background',
                                'evl_slide1_img',
                                'evl_slide2_img',
                                'evl_slide3_img',
                                'evl_slide4_img',
                                'evl_slide5_img',
                            );
                            // Typography SHIM
                            if ( in_array( $key, $fontKeys ) ) {
                                if ( isset( $value['size'] ) ) {
                                    $value['font-size'] = $value['size'];
                                    unset( $value['size'] );
                                }
                                if ( isset( $value['face'] ) ) {
                                    $value['font-family'] = $value['face'];
                                    unset( $value['face'] );
                                }
                                if ( isset( $value['style'] ) ) {
                                    $value['font-style'] = $value['style'];
                                    unset( $value['style'] );
                                }
                                $oldData[ $key ] = $value;
                            } elseif ( in_array( $key, $mediaKeys ) ) {
                                $oldData[ $key ] = array( 'url' => isset( $value ) ? $value : '' );
                            }
                        }

                        update_option( 'evl_options', $oldData );
                        update_option( 'evl_33_migrate', 'done' );
                    }
                }
            }
        }

    }


    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    $opt_name = "evl_options";
    $theme    = wp_get_theme();

    $t4p_url = esc_url( "http://theme4press.com/" );
	$fb_url = esc_url( "https://www.facebook.com/Theme4Press" );

    // Upgrade from version 3.3 and below
    $upgrade_from_33 = get_option( 'evolve', false );

    Redux::setArgs( $opt_name, array(
        'display_name'    => __( 'evolve', 'evolve' ),
        'display_name'    => '<img width="135" height="28" src="' . get_template_directory_uri() . '/library/functions/images/logo.png" alt="evolve">',
        // Name that appears at the top of your panel
        'display_version' => $theme->get( 'Version' ),
        'menu_type'       => 'submenu',
        'dev_mode'        => false,
        'menu_title'      => __( 'Theme Options', 'evolve' ),
        'page_title'      => $theme->get( 'Name' ) . ' ' . __( 'Options', 'evolve' ),
        'admin_bar'       => true,
        'customizer'      => true,
        'save_defaults'   => empty($upgrade_from_33),
        'share_icons'     => array(
            array(
                'url'   => $t4p_url.'evolve-multipurpose-wordpress-theme/',
                'title' => __( 'Theme Homepage', 'evolve' ),
                'icon'  => 't4p-icon-appbarhome'
            ),
            array(
                'url'   => $t4p_url.'docs/',
                'title' => __( 'Documentation', 'evolve' ),
                'icon'  => 't4p-icon-appbarpagetext'
            ),
            array(
                'url'   => $t4p_url.'support-forums/',
                'title' => __( 'Support', 'evolve' ),
                'icon'  => 't4p-icon-appbarlifesaver'
            ),
            array(
                'url'   => $fb_url,
                'title' => __( 'Facebook', 'evolve' ),
                'icon'  => 't4p-icon-appbarsocialfacebook'
            )
        ),
		'intro_text' => '<a href="'.$t4p_url.'evolve-multipurpose-wordpress-theme/" title="Theme Homepage" target="_blank"><i class="t4p-icon-appbarhome"></i> Theme Homepage</a><a href="'.$t4p_url.'docs/" title="Documentation" target="_blank"><i class="t4p-icon-appbarpagetext"></i> Documentation</a><a href="'.$t4p_url.'support-forums/" title="Support" target="_blank"><i class="t4p-icon-appbarlifesaver"></i> Support</a><a href="'.$fb_url.'" title="Facebook" target="_blank"><i class="t4p-icon-appbarsocialfacebook"></i> Facebook</a>',
    ) );

    // If the Redux plugin is installed
    if ( ReduxFramework::$_is_plugin ) {
        Redux::setArgs( $opt_name, array(
            'customizer_only' => false,
            'customizer'      => true,
        ) );
    } else {
        // No Redux plugin. Use embedded. Customizer only!
        Redux::setArgs( $opt_name, array(
            'customizer_only' => true,
        ) );
    }

    // Pull all the categories into an array
    $options_categories     = array();
    $options_categories_obj = get_categories();
    foreach ( $options_categories_obj as $category ) {
        $options_categories[ $category->cat_ID ] = $category->cat_name;
    }

// Pull all the pages into an array
    $options_pages     = array();
    $options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
    $options_pages[''] = 'Select a page:';
    foreach ( $options_pages_obj as $page ) {
        $options_pages[ $page->ID ] = $page->post_title;
    }

// If using image radio buttons, define a directory path
    $imagepath        = get_template_directory_uri() . '/library/functions/images/';
    $imagepathfolder  = get_template_directory_uri() . '/library/media/images/';
    $evolve_shortname = "evl";
    $template_url     = get_template_directory_uri();


    function evolve_addPanelCSS() {
        wp_register_style(
            'evolve-redux-custom-css',
            get_template_directory_uri() . '/library/admin/panel.css',
            array( 'redux-admin-css' ), // Be sure to include redux-admin-css so it's appended after the core css is applied
            time(),//$theme->get( 'Version' )
            'all'
        );
        wp_enqueue_style( 'evolve-redux-custom-css' );
    }

// This example assumes your opt_name is set to redux_demo, replace with your opt_name value
    add_action( "redux/page/{$opt_name}/enqueue", 'evolve_addPanelCSS' );

	function evolve_newIconFont() {
		wp_register_style(
			'evolve-icomoon',
			get_template_directory_uri() . '/library/admin/icomoon-admin/style.css',
			array(),
			time(),
			'all'
		);
		wp_enqueue_style( 'evolve-icomoon' );
	}
// This example assumes the opt_name is set to redux_demo.  Please replace it with your opt_name value.
    add_action( "redux/page/{$opt_name}/enqueue", 'evolve_newIconFont' );


    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-1',
            'title'  => __( 'General', 'evolve' ),
            'icon'   => 't4p-icon-appbartools',
            'fields' => array(
                array(
                    'subtitle' => __( 'Upload custom favicon.', 'evolve' ),
                    'id'       => 'evl_favicon',
                    'type'     => 'media',
                    'title'    => __( 'Custom Favicon', 'evolve' ),
                    'url'      => true,
                ),
                array(
                    'subtitle' => __( 'Select main content and sidebar alignment.', 'evolve' ),
                    'id'       => 'evl_layout',
                    'type'     => 'image_select',
                    'compiler' => true,
                    'options'  => array(
                        '1c'  => $imagepath . '1c.png',
                        '2cl' => $imagepath . '2cl.png',
                        '2cr' => $imagepath . '2cr.png',
                        '3cm' => $imagepath . '3cm.png',
                        '3cr' => $imagepath . '3cr.png',
                        '3cl' => $imagepath . '3cl.png',
                    ),
                    'title'    => __( 'Select a layout', 'evolve' ),
                    'default'  => '2cl',
                ),
                array(
                    'subtitle' => __( '<strong>Boxed version</strong> automatically enables custom background', 'evolve' ),
                    'id'       => 'evl_width_layout',
                    'type'     => 'select',
                    'compiler' => true,
                    'options'  => array(
                        'fixed' => __( 'Boxed', 'evolve' ),
                        'fluid' => __( 'Wide', 'evolve' ),
                    ),
                    'title'    => __( 'Layout Style', 'evolve' ),
                    'default'  => 'fixed',
                ),
                array(
                    'subtitle' => __( 'Select the width for your website', 'evolve' ),
                    'id'       => 'evl_width_px',
                    'compiler' => true,
                    'type'     => 'select',
                    'options'  => array(
                        800  => '800px',
                        985  => '985px',
                        1200 => '1200px',
                        1600 => '1600px',
                    ),
                    'title'    => __( 'Layout Width', 'evolve' ),
                    'default'  => '1200',
                ),
                array(
                    'subtitle' => __( '<strong>Boxed version</strong> Disables the shadow effect around the layout', 'evolve' ),
                    'id'       => 'evl_shadow_effect',
                    'compiler' => true,
                    'type'     => 'select',
                    'options'  => array(
                        'enable'  => __( 'Enabled', 'evolve' ),
                        'disable' => __( 'Disable', 'evolve' ),
                    ),
                    'title'    => __( 'Shadow Effect', 'evolve' ),
                    'default'  => 'enable',
                ),
            ),
        ),
    ) );

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-4',
            'title'  => __( 'Header', 'evolve' ),
            'icon'   => 't4p-icon-file3',
            'fields' => array(
                array(
                    'subtitle' => __( 'Enter height in px, minimum recommended height is 125px', 'evolve' ),
                    'id'       => 'evl_header_background_height',
                    'type'     => 'text',
                    'title'    => __( 'Header Height', 'evolve' ),
                    'default'  => '125px',
                ),
                array(
                    'subtitle' => __( 'Select if the header background image should be displayed in cover or contain size.', 'evolve' ),
                    'id'       => 'evl_header_image',
                    'compiler' => true,
                    'type'     => 'select',
                    'options'  => array(
                        'cover'   => __( 'Cover', 'evolve' ),
                        'contain' => __( 'Contain', 'evolve' ),
                    ),
                    'title'    => __( 'Header Image Background Responsiveness Style', 'evolve' ),
                    'default'  => 'cover',
                ),
                array(
                    'id'      => 'evl_header_image_background_repeat',
                    'type'    => 'select',
                    'options' => array(
                        'no-repeat' => __( 'no-repeat', 'evolve' ),
                        'repeat'    => __( 'repeat', 'evolve' ),
                        'repeat-x'  => __( 'repeat-x', 'evolve' ),
                        'repeat-y'  => __( 'repeat-y', 'evolve' ),
                    ),
                    'title'   => __( 'Background Repeat', 'evolve' ),
                    'default' => 'no-repeat',
                ),
                array(
                    'id'      => 'evl_header_image_background_position',
                    'type'    => 'select',
                    'options' => array(
                        'center top'    => __( 'center top', 'evolve' ),
                        'center center' => __( 'center center', 'evolve' ),
                        'center bottom' => __( 'center bottom', 'evolve' ),
                        'left top'      => __( 'left top', 'evolve' ),
                        'left center'   => __( 'left center', 'evolve' ),
                        'left bottom'   => __( 'left bottom', 'evolve' ),
                        'right top'     => __( 'right top', 'evolve' ),
                        'right center'  => __( 'right center', 'evolve' ),
                        'right bottom'  => __( 'right bottom', 'evolve' ),
                    ),
                    'title'   => __( 'Background Position', 'evolve' ),
                    'default' => 'center top',
                ),
                array(
                    'subtitle' => __( 'Custom background color of header', 'evolve' ),
                    'id'       => 'evl_header_background_color',
                    'type'     => 'color',
                    'compiler' => true,
                    'title'    => __( 'Header color', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Upload a logo for your theme, or specify an image URL directly.', 'evolve' ),
                    'id'       => 'evl_header_logo',
                    'type'     => 'media',
                    'title'    => __( 'Custom logo', 'evolve' ),
                    'url'      => true,
                ),
                array(
                    'subtitle' => __( 'Choose the position of your custom logo', 'evolve' ),
                    'id'       => 'evl_pos_logo',
                    'compiler' => true,
                    'type'     => 'select',
                    'options'  => array(
                        'left'    => __( 'Left', 'evolve' ),
                        'center'  => __( 'Center', 'evolve' ),
                        'right'   => __( 'Right', 'evolve' ),
                        'disable' => __( 'Disable', 'evolve' ),
                    ),
                    'title'    => __( 'Logo position', 'evolve' ),
                    'default'  => 'left',
                ),
                array(
                    'subtitle' => __( 'Check this box if you don\'t want to display title of your blog', 'evolve' ),
                    'id'       => 'evl_blog_title',
                    'type'     => 'checkbox',
                    'title'    => __( 'Disable Blog Title', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Choose the position of blog tagline', 'evolve' ),
                    'id'       => 'evl_tagline_pos',
                    'type'     => 'select',
                    'compiler' => true,
                    'options'  => array(
                        'next'    => __( 'Next to blog title', 'evolve' ),
                        'above'   => __( 'Above blog title', 'evolve' ),
                        'under'   => __( 'Under blog title', 'evolve' ),
                        'disable' => __( 'Disable', 'evolve' ),
                    ),
                    'title'    => __( 'Blog Tagline position', 'evolve' ),
                    'default'  => 'next',
                ),
                array(
                    'subtitle' => __( 'Check this box if you don\'t want to display main menu', 'evolve' ),
                    'id'       => 'evl_main_menu',
                    'type'     => 'checkbox',
                    'title'    => __( 'Disable main menu', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check this box if you don\'t want to display main menu hover effect', 'evolve' ),
                    'id'       => 'evl_main_menu_hover_effect',
                    'type'     => 'checkbox',
                    'title'    => __( 'Disable main menu Hover Effect', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to display sticky header', 'evolve' ),
                    'id'       => 'evl_sticky_header',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 1,
                    'title'    => __( 'Enable sticky header', 'evolve' ),

                ),
                array(
                    'subtitle' => __( 'Check this box if you want to display searchbox in main menu', 'evolve' ),
                    'id'       => 'evl_searchbox',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 1,
                    'title'    => __( 'Enable searchbox in main menu', 'evolve' ),

                ),
                array(
                    'subtitle' => __( 'Select how many header widget areas you want to display.', 'evolve' ),
                    'id'       => 'evl_widgets_header',
                    'type'     => 'image_select',
                    'options'  => array(
                        'disable' => $imagepath . '1c.png',
                        'one'     => $imagepath . 'header-widgets-1.png',
                        'two'     => $imagepath . 'header-widgets-2.png',
                        'three'   => $imagepath . 'header-widgets-3.png',
                        'four'    => $imagepath . 'header-widgets-4.png',
                    ),
                    'title'    => __( 'Number of widget cols in header', 'evolve' ),
                    'default'  => 'disable',
                ),
                array(
                    'subtitle' => __( 'Choose where to display header widgets', 'evolve' ),
                    'id'       => 'evl_header_widgets_placement',
                    'type'     => 'select',
                    'options'  => array(
                        'home'   => __( 'Home page', 'evolve' ),
                        'single' => __( 'Single Post', 'evolve' ),
                        'page'   => __( 'Pages', 'evolve' ),
                        'all'    => __( 'All pages', 'evolve' ),
                        'custom' => __( 'Select Per Post/Page', 'evolve' ),
                    ),
                    'title'    => __( 'Header widgets placement', 'evolve' ),
                    'default'  => 'home',
                ),
            ),
        ),
    ) );

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-5',
            'title'  => __( 'Footer', 'evolve' ),
            'icon'   => 't4p-icon-file4',
            'fields' => array(
                array(
                    'subtitle' => __( 'Upload a footer background image for your theme, or specify an image URL directly.', 'evolve' ),
                    'id'       => 'evl_footer_background_image',
                    'type'     => 'media',
                    'title'    => __( 'Footer Image', 'evolve' ),
                    'url'      => true,
                ),
                array(
                    'subtitle' => __( 'Select if the footer background image should be displayed in cover or contain size.', 'evolve' ),
                    'id'       => 'evl_footer_image',
                    'type'     => 'select',
                    'options'  => array(
                        'cover'   => __( 'Cover', 'evolve' ),
                        'contain' => __( 'Contain', 'evolve' ),
                    ),
                    'title'    => __( 'Footer Image Background Responsiveness Style', 'evolve' ),
                    'default'  => 'cover',
                ),
                array(
                    'id'      => 'evl_footer_image_background_repeat',
                    'type'    => 'select',
                    'options' => array(
                        'no-repeat' => __( 'no-repeat', 'evolve' ),
                        'repeat'    => __( 'repeat', 'evolve' ),
                        'repeat-x'  => __( 'repeat-x', 'evolve' ),
                        'repeat-y'  => __( 'repeat-y', 'evolve' ),
                    ),
                    'title'   => __( 'Background Repeat', 'evolve' ),
                    'default' => 'no-repeat',
                ),
                array(
                    'id'      => 'evl_footer_image_background_position',
                    'type'    => 'select',
                    'options' => array(
                        'center top'    => __( 'center top', 'evolve' ),
                        'center center' => __( 'center center', 'evolve' ),
                        'center bottom' => __( 'center bottom', 'evolve' ),
                        'left top'      => __( 'left top', 'evolve' ),
                        'left center'   => __( 'left center', 'evolve' ),
                        'left bottom'   => __( 'left bottom', 'evolve' ),
                        'right top'     => __( 'right top', 'evolve' ),
                        'right center'  => __( 'right center', 'evolve' ),
                        'right bottom'  => __( 'right bottom', 'evolve' ),
                    ),
                    'title'   => __( 'Background Position', 'evolve' ),
                    'default' => 'center top',
                ),
                array(
                    'subtitle' => __( 'Custom background color of footer', 'evolve' ),
                    'id'       => 'evl_header_footer_back_color',
                    'type'     => 'color',
                    'compiler' => true,
                    'title'    => __( 'Footer color', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Select how many footer widget areas you want to display.', 'evolve' ),
                    'id'       => 'evl_widgets_num',
                    'type'     => 'image_select',
                    'options'  => array(
                        'disable' => $imagepath . '1c.png',
                        'one'     => $imagepath . 'footer-widgets-1.png',
                        'two'     => $imagepath . 'footer-widgets-2.png',
                        'three'   => $imagepath . 'footer-widgets-3.png',
                        'four'    => $imagepath . 'footer-widgets-4.png',
                    ),
                    'title'    => __( 'Number of widget cols in footer', 'evolve' ),
                    'default'  => 'disable',
                ),
                array(
                    'desc'    => __( 'Available <strong>HTML</strong> tags and attributes:<br /><br /> <code> &lt;b&gt; &lt;i&gt; &lt;a href="" title=""&gt; &lt;blockquote&gt; &lt;del datetime=""&gt; <br /> &lt;ins datetime=""&gt; &lt;img src="" alt="" /&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; <br /> &lt;code&gt; &lt;em&gt; &lt;strong&gt; &lt;div&gt; &lt;span&gt; &lt;h1&gt; &lt;h2&gt; &lt;h3&gt; &lt;h4&gt; &lt;h5&gt; &lt;h6&gt; <br /> &lt;table&gt; &lt;tbody&gt; &lt;tr&gt; &lt;td&gt; &lt;br /&gt; &lt;hr /&gt;</code>', 'evolve' ),
                    'id'      => 'evl_footer_content',
                    'type'    => 'textarea',
                    'title'   => __( 'Custom footer', 'evolve' ),
                    'default' => '<p id="copyright"><span class="credits"><a href="'.$t4p_url.'evolve-multipurpose-wordpress-theme/">evolve</a> theme by Theme4Press&nbsp;&nbsp;&bull;&nbsp;&nbsp;Powered by <a href="http://wordpress.org">WordPress</a></span></p>',
                ),
            ),
        ),
    ) );

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-6',
            'title'  => __( 'Typography', 'evolve' ),
            'icon'   => 't4p-icon-appbartextserif',
            'fields' => array(
                array(
                    'subtitle'    => __( 'Select the typography you want for your blog title. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_title_font',
                    'type'        => 'typography',
                    'title'       => __( 'Blog Title font', 'evolve' ),
                    'text-align'  => false,
                    'line-height' => false,
                    'default'     => array(
                        'font-size'   => '39px',
                        'color'       => '',
                        'font-family' => 'Roboto',
                        'font-style'  => '700',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your blog tagline. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_tagline_font',
                    'type'        => 'typography',
                    'text-align'  => false,
                    'line-height' => false,
                    'title'       => __( 'Blog tagline font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '13px',
                        'color'       => '',
                        'font-family' => 'Roboto',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your main menu. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_menu_font',
                    'type'        => 'typography',
                    'text-align'  => false,
                    'line-height' => false,
                    'title'       => __( 'Main menu font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '14px',
                        'color'       => '',
                        'font-family' => 'Roboto',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your widget title. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_widget_title_font',
                    'type'        => 'typography',
                    'text-align'  => false,
                    'line-height' => false,
                    'title'       => __( 'Widget title font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '19px',
                        'color'       => '',
                        'font-family' => 'Roboto',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your widget content. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_widget_content_font',
                    'type'        => 'typography',
                    'text-align'  => false,
                    'line-height' => false,
                    'title'       => __( 'Widget content font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '13px',
                        'font-family' => 'Roboto',
						'color'       => '',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your post titles. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_post_font',
                    'type'        => 'typography',
                    'text-align'  => false,
                    'line-height' => false,
                    'title'       => __( 'Post title font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '28px',
                        'color'       => '',
                        'font-family' => 'Roboto',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your blog content. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_content_font',
                    'type'        => 'typography',
                    'text-align'  => false,
                    'line-height' => false,
                    'title'       => __( 'Content font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '16px',
                        'color'       => '',
                        'font-family' => 'Roboto',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your H1 tag in blog content. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_content_h1_font',
                    'type'        => 'typography',
                    'text-align'  => false,
                    'line-height' => false,
                    'title'       => __( 'H1 font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '46px',
                        'color'       => '',
                        'font-family' => 'Roboto',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your H2 tag in blog content. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_content_h2_font',
                    'type'        => 'typography',
                    'text-align'  => false,
                    'line-height' => false,
                    'title'       => __( 'H2 font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '40px',
                        'font-family' => 'Roboto',
                        'color'       => '',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your H3 tag in blog content. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_content_h3_font',
                    'type'        => 'typography',
                    'text-align'  => false,
                    'line-height' => false,
                    'title'       => __( 'H3 font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '34px',
                        'font-family' => 'Roboto',
                        'color'       => '',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your H4 tag in blog content. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_content_h4_font',
                    'type'        => 'typography',
                    'title'       => __( 'H4 font', 'evolve' ),
                    'text-align'  => false,
                    'line-height' => false,
                    'default'     => array(
                        'font-size'   => '27px',
                        'font-family' => 'Roboto',
                        'color'       => '',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your H5 tag in blog content. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_content_h5_font',
                    'type'        => 'typography',
                    'title'       => __( 'H5 font', 'evolve' ),
                    'text-align'  => false,
                    'line-height' => false,
                    'default'     => array(
                        'font-size'   => '20px',
                        'font-family' => 'Roboto',
                        'color'       => '',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for your H6 tag in blog content. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_content_h6_font',
                    'type'        => 'typography',
                    'text-align'  => false,
                    'line-height' => false,
                    'title'       => __( 'H6 font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '14px',
                        'font-family' => 'Roboto',
                        'color'       => '',
						'font-style' => '',
                    ),
                ),
            ),
        ),
    ) );

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-10',
            'title'  => __( 'Styling', 'evolve' ),
            'icon'   => 't4p-icon-appbardrawpaintbrush',
            'fields' => array(
                array(
                    'subtitle' => __( 'Upload a content background image for your theme, or specify an image URL directly.', 'evolve' ),
                    'id'       => 'evl_content_background_image',
                    'type'     => 'media',
                    'compiler' => true,
                    'title'    => __( 'Content Image', 'evolve' ),
                    'url'      => true,
                ),
                array(
                    'subtitle' => __( 'Select if the content background image should be displayed in cover or contain size.', 'evolve' ),
                    'id'       => 'evl_content_image_responsiveness',
                    'type'     => 'select',
                    'compiler' => true,
                    'options'  => array(
                        'cover'   => __( 'Cover', 'evolve' ),
                        'contain' => __( 'Contain', 'evolve' ),
                    ),
                    'title'    => __( 'Content Image Background Responsiveness Style', 'evolve' ),
                    'default'  => 'cover',
                ),
                array(
                    'subtitle' => __( 'Background color of content', 'evolve' ),
                    'id'       => 'evl_content_back',
                    'type'     => 'select',
                    'options'  => array(
                        'light' => __( 'Light', 'evolve' ),
                        'dark'  => __( 'Dark', 'evolve' ),
                    ),
                    'title'    => __( 'Content color', 'evolve' ),
                    'default'  => 'light',
                ),
                array(
                    'subtitle' => __( 'Custom background color of content area', 'evolve' ),
                    'id'       => 'evl_content_background_color',
                    'type'     => 'color',
                    'compiler' => true,
                    'title'    => __( 'Or Custom content color', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to disable menu background', 'evolve' ),
                    'id'       => 'evl_disable_menu_back',
                    'compiler' => true,
                    'type'     => 'checkbox',
                    'title'    => __( 'Disable Menu Background', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Background color of main menu', 'evolve' ),
                    'id'       => 'evl_menu_back',
                    'compiler' => true,
                    'type'     => 'select',
                    'options'  => array(
                        'light' => __( 'Light', 'evolve' ),
                        'dark'  => __( 'Dark', 'evolve' ),
                    ),
                    'title'    => __( 'Menu color', 'evolve' ),
                    'default'  => 'light',
                ),
                array(
                    'subtitle' => __( 'Custom background color of main menu. <strong>Dark menu must be enabled.</strong>', 'evolve' ),
                    'id'       => 'evl_menu_back_color',
                    'type'     => 'color',
                    'compiler' => true,
                    'title'    => __( 'Or custom menu color', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Choose the pattern for header and footer background', 'evolve' ),
                    'id'       => 'evl_pattern',
                    'compiler' => true,
                    'type'     => 'image_select',
                    'options'  => array(
                        'none'          => $imagepathfolder . '/header-two/none.jpg',
                        'pattern_1.png' => $imagepathfolder . '/pattern/pattern_1_thumb.png',
                        'pattern_2.png' => $imagepathfolder . '/pattern/pattern_2_thumb.png',
                        'pattern_3.png' => $imagepathfolder . '/pattern/pattern_3_thumb.png',
                        'pattern_4.png' => $imagepathfolder . '/pattern/pattern_4_thumb.png',
                        'pattern_5.png' => $imagepathfolder . '/pattern/pattern_5_thumb.png',
                        'pattern_6.png' => $imagepathfolder . '/pattern/pattern_6_thumb.png',
                        'pattern_7.png' => $imagepathfolder . '/pattern/pattern_7_thumb.png',
                        'pattern_8.png' => $imagepathfolder . '/pattern/pattern_8_thumb.png',
                    ),
                    'title'    => __( 'Header and Footer pattern', 'evolve' ),
                    'default'  => 'pattern_8.png',
                    'tiles'    => true,
                ),
                array(
                    'subtitle' => __( 'Choose the color scheme for the area below header menu', 'evolve' ),
                    'id'       => 'evl_scheme_widgets',
                    'compiler' => true,
                    'type'     => 'color',
                    'title'    => __( 'Color scheme of the slideshow and widgets area', 'evolve' ),
                    'default'  => '#595959',
                ),
                array(
                    'subtitle' => __( 'Upload an image for the area below header menu', 'evolve' ),
                    'id'       => 'evl_scheme_background',
                    'compiler' => true,
                    'type'     => 'media',
                    'title'    => __( 'Background Image of the slideshow and widgets area', 'evolve' ),
                    'url'      => true,
                ),
                array(
                    'subtitle' => __( 'Have background image always at 100% in width and height and scale according to the browser size.', 'evolve' ),
                    'id'       => 'evl_scheme_background_100',
                    'compiler' => true,
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( '100% Background Image', 'evolve' ),
                ),
                array(
                    'id'       => 'evl_scheme_background_repeat',
                    'type'     => 'select',
                    'compiler' => true,
                    'options'  => array(
                        'repeat'    => __( 'repeat', 'evolve' ),
                        'repeat-x'  => __( 'repeat-x', 'evolve' ),
                        'repeat-y'  => __( 'repeat-y', 'evolve' ),
                        'no-repeat' => __( 'no-repeat', 'evolve' ),
                    ),
                    'title'    => __( 'Background Repeat', 'evolve' ),
                    'default'  => 'no-repeat',
                ),
                array(
                    'subtitle' => __( 'Custom color for links', 'evolve' ),
                    'id'       => 'evl_general_link',
                    'compiler' => true,
                    'type'     => 'color',
                    'title'    => __( 'General Link Color', 'evolve' ),
                    'default'  => '#7a9cad',
                ),
                array(
                    'subtitle' => __( 'Custom color for buttons: Read more, Reply', 'evolve' ),
                    'id'       => 'evl_button_1',
                    'compiler' => true,
                    'type'     => 'color',
                    'title'    => __( 'Buttons 1 Color', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Custom color for buttons: Post Comment, Submit', 'evolve' ),
                    'id'       => 'evl_button_2',
                    'compiler' => true,
                    'type'     => 'color',
                    'title'    => __( 'Buttons 2 Color', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to enable black background for widget titles', 'evolve' ),
                    'id'       => 'evl_widget_background',
                    'type'     => 'switch',
                    'compiler' => true,
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Enable Widget Title Black Background', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to disable widget background', 'evolve' ),
                    'id'       => 'evl_widget_background_image',
                    'type'     => 'checkbox',
                    'compiler' => true,
                    'title'    => __( 'Disable Widget Background', 'evolve' ),
                ),
            ),
        ),
    ) );

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-2',
            'title'  => __( 'Blog', 'evolve' ),
            'icon'   => 't4p-icon-appbarclipboardvariantedit',
            'fields' => array(
                array(
                    'subtitle' => __( 'Grid layout with <strong>3</strong> posts per row is recommended to use with disabled <strong>Sidebar(s)</strong>', 'evolve' ),
                    'id'       => 'evl_post_layout',
                    'type'     => 'image_select',
                    'compiler' => true,
                    'options'  => array(
                        'one'   => $imagepath . 'one-post.png',
                        'two'   => $imagepath . 'two-posts.png',
                        'three' => $imagepath . 'three-posts.png',
                    ),
                    'title'    => __( 'Blog layout', 'evolve' ),
                    'default'  => 'two',
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to display post excerpts on one column blog layout', 'evolve' ),
                    'id'       => 'evl_excerpt_thumbnail',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Enable post excerpts', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to display featured images', 'evolve' ),
                    'id'       => 'evl_featured_images',
                    'type'     => 'checkbox',
                    'title'    => __( 'Enable featured images', 'evolve' ),
                    'default'  => '1',
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to display featured image on Single Blog Posts', 'evolve' ),
                    'id'       => 'evl_blog_featured_image',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Enable featured image on Single Blog Posts', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Turn on if you don\'t want to display default thumbnail images', 'evolve' ),
                    'id'       => 'evl_thumbnail_default_images',
                    'type'     => 'switch',
                    'title'    => __( 'Hide default thumbnail images', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to display post author avatar', 'evolve' ),
                    'id'       => 'evl_author_avatar',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Enable post author avatar', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Enter number of characters for Post Title Excerpt. This works only if a grid layout is enabled.', 'evolve' ),
                    'id'       => 'evl_posts_excerpt_title_length',
                    'type'     => 'spinner',
                    'title'    => __( 'Post Title Excerpt Length', 'evolve' ),
                    'default'  => '40',
                ),
                array(
                    'subtitle' => __( 'Choose placement of the post meta header - Date, Author, Comments', 'evolve' ),
                    'id'       => 'evl_header_meta',
                    'type'     => 'select',
                    'options'  => array(
                        'single_archive' => __( 'Single posts + Archive pages', 'evolve' ),
                        'single'         => __( 'Single posts', 'evolve' ),
                        'disable'        => __( 'Disable', 'evolve' ),
                    ),
                    'title'    => __( 'Post meta header placement', 'evolve' ),
                    'default'  => 'single_archive',
                ),
                array(
                    'subtitle' => __( 'Enable page title in category pages ?', 'evolve' ),
                    'id'       => 'evl_category_page_title',
                    'type'     => 'select',
                    'options'  => array(
                        1 => __( 'Enable', 'evolve' ),
                        0 => __( 'Disable', 'evolve' ),
                    ),
                    'title'    => __( 'Category Page Title', 'evolve' ),
                    'default'  => '1',
                ),
                array(
                    'subtitle' => __( 'Choose placement of the \'Share This\' buttons', 'evolve' ),
                    'id'       => 'evl_share_this',
                    'type'     => 'select',
                    'options'  => array(
                        'single'         => __( 'Single posts', 'evolve' ),
                        'single_archive' => __( 'Single posts + Archive pages', 'evolve' ),
                        'all'            => __( 'All pages', 'evolve' ),
                        'disable'        => __( 'Disable', 'evolve' ),
                    ),
                    'title'    => __( '\'Share This\' buttons placement', 'evolve' ),
                    'default'  => 'single',
                ),
                array(
                    'subtitle' => __( 'Choose the position of the <strong>Previous/Next Post</strong> links', 'evolve' ),
                    'id'       => 'evl_post_links',
                    'type'     => 'select',
                    'options'  => array(
                        'after'  => __( 'After posts', 'evolve' ),
                        'before' => __( 'Before posts', 'evolve' ),
                        'both'   => __( 'Both', 'evolve' ),
                    ),
                    'title'    => __( 'Position of previous/next posts links', 'evolve' ),
                    'default'  => 'after',
                ),
                array(
                    'subtitle' => __( 'Choose if you want to display <strong>Similar posts</strong> in articles', 'evolve' ),
                    'id'       => 'evl_similar_posts',
                    'type'     => 'select',
                    'options'  => array(
                        'disable'  => __( 'Disable', 'evolve' ),
                        'category' => __( 'Match by categories', 'evolve' ),
                        'tag'      => __( 'Match by tags', 'evolve' ),
                    ),
                    'title'    => __( 'Display Similar posts', 'evolve' ),
                    'default'  => 'disable',
                ),
                array(
                    'subtitle' => __( 'Select the pagination type for the assigned blog page in Settings > Reading.', 'evolve' ),
                    'id'       => 'evl_pagination_type',
                    'compiler' => true,
                    'type'     => 'select',
                    'options'  => array(
                        'pagination' => __( 'Pagination', 'evolve' ),
                        'infinite'   => __( 'Infinite Scroll', 'evolve' ),
                    ),
                    'title'    => __( 'Pagination Type', 'evolve' ),
                    'default'  => 'pagination',
                ),
            ),
        ),
    ) );

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-16',
            'title'  => __( 'Social Sharing Box Shortcode', 'evolve' ),
            'icon'   => 't4p-icon-appbargroup',
            'locked' => sprintf( __( 'These options are only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),
            'fields' => array(
                array(
                    'subtitle' => __( 'Show the facebook sharing icon in blog posts.', 'evolve' ),
                    'id'       => 'evl_sharing_facebook',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Facebook', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Show the twitter sharing icon in blog posts.', 'evolve' ),
                    'id'       => 'evl_sharing_twitter',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Twitter', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Show the reddit sharing icon in blog posts.', 'evolve' ),
                    'id'       => 'evl_sharing_reddit',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Reddit', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Show the linkedin sharing icon in blog posts.', 'evolve' ),
                    'id'       => 'evl_sharing_linkedin',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'LinkedIn', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Show the g+ sharing icon in blog posts.', 'evolve' ),
                    'id'       => 'evl_sharing_google',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Google Plus', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Show the tumblr sharing icon in blog posts.', 'evolve' ),
                    'id'       => 'evl_sharing_tumblr',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Tumblr', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Show the pinterest sharing icon in blog posts.', 'evolve' ),
                    'id'       => 'evl_sharing_pinterest',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Pinterest', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Show the email sharing icon in blog posts.', 'evolve' ),
                    'id'       => 'evl_sharing_email',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 0,
                    'title'    => __( 'Email', 'evolve' ),
                ),
            ),
        ),
    ) );

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-3',
            'title'  => __( 'Social Media Links', 'evolve' ),
            'icon'   => 't4p-icon-appbarsocialtwitter',
            'fields' => array(
                array(
                    'subtitle' => __( 'Check this box if you want to display Subscribe/Social links in header', 'evolve' ),
                    'id'       => 'evl_social_links',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 1,
                    'title'    => __( 'Enable Subscribe/Social links in header', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Choose the color scheme of subscribe/social icons', 'evolve' ),
                    'id'       => 'evl_social_color_scheme',
                    'type'     => 'color',
                    'compiler' => true,
                    'title'    => __( 'Subscribe/Social icons color', 'evolve' ),
                    'default'  => '#999999',
                ),
                array(
                    'subtitle' => __( 'Choose the size of subscribe/social icons', 'evolve' ),
                    'id'       => 'evl_social_icons_size',
                    'type'     => 'select',
                    'compiler' => true,
                    'options'  => array(
                        'normal'  => __( 'Normal', 'evolve' ),
                        'small'   => __( 'Small', 'evolve' ),
                        'large'   => __( 'Large', 'evolve' ),
                        'x-large' => __( 'X-Large', 'evolve' ),
                    ),
                    'title'    => __( 'Subscribe/Social icons size', 'evolve' ),
                    'default'  => 'normal',
                ),
                array(
                    'subtitle' => __( 'Check this box to enable RSS Feed', 'evolve' ),
                    'id'       => 'evl_show_rss',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 1,
                    'title'    => __( 'Enable RSS Feed', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert custom RSS Feed URL, e.g. <strong>http://feeds.feedburner.com/Example</strong>', 'evolve' ),
                    'id'       => 'evl_rss_feed',
                    'type'     => 'text',
                    'required' => array(
                        array( 'evl_show_rss', '=', '1' )
                    ),
                    'title'    => __( 'RSS Feed', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert custom newsletter URL, e.g. <strong>http://feedburner.google.com/fb/a/mailverify?uri=Example&amp;loc=en_US</strong>', 'evolve' ),
                    'id'       => 'evl_newsletter',
                    'type'     => 'text',
                    'title'    => __( 'Newsletter', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your Facebook URL', 'evolve' ),
                    'id'       => 'evl_facebook',
                    'type'     => 'text',
                    'title'    => __( 'Facebook', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your Twitter URL', 'evolve' ),
                    'id'       => 'evl_twitter_id',
                    'type'     => 'text',
                    'title'    => __( 'Twitter', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your Instagram URL', 'evolve' ),
                    'id'       => 'evl_instagram',
                    'type'     => 'text',
                    'title'    => __( 'Instagram', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your Skype ID', 'evolve' ),
                    'id'       => 'evl_skype',
                    'type'     => 'text',
                    'title'    => __( 'Skype', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your YouTube URL', 'evolve' ),
                    'id'       => 'evl_youtube',
                    'type'     => 'text',
                    'title'    => __( 'YouTube', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your Flickr URL', 'evolve' ),
                    'id'       => 'evl_flickr',
                    'type'     => 'text',
                    'title'    => __( 'Flickr', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your LinkedIn profile URL', 'evolve' ),
                    'id'       => 'evl_linkedin',
                    'type'     => 'text',
                    'title'    => __( 'LinkedIn', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your Google Plus profile URL', 'evolve' ),
                    'id'       => 'evl_googleplus',
                    'type'     => 'text',
                    'title'    => __( 'Google Plus', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your Pinterest profile URL', 'evolve' ),
                    'id'       => 'evl_pinterest',
                    'type'     => 'text',
                    'title'    => __( 'Pinterest', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your Tumblr profile URL', 'evolve' ),
                    'id'       => 'evl_tumblr',
                    'type'     => 'text',
                    'title'    => __( 'Tumblr', 'evolve' ),
                ),
            ),
        ),
    ) );

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-15',
            'title'  => __( 'Front Page Content Boxes', 'evolve' ),
            'icon'   => 't4p-icon-appbarimagebacklight',
            'fields' => array(
                array(
                    'subtitle' => __( 'Check this box to enable Front Page Content Boxes', 'evolve' ),
                    'id'       => 'evl_content_boxes',
                    'type'     => 'switch',
                    'on'       => __( 'Enabled', 'evolve' ),
                    'off'      => __( 'Disabled', 'evolve' ),
                    'default'  => 1,
                    'title'    => __( 'Enable Front Page Content Boxes', 'evolve' ),
                ),
                array(
                    'id'      => 'evl_content_box1_enable',
                    'title'   => __( 'Enable Content Box 1 ?', 'evolve' ),
                    'type'    => 'switch',
                    'on'      => __( 'Enabled', 'evolve' ),
                    'off'     => __( 'Disabled', 'evolve' ),
                    'default' => 1,
                ),
                array(
                    'id'      => 'evl_content_box1_title',
                    'type'    => 'text',
                    'title'   => __( 'Content Box 1 Title', 'evolve' ),
                    'default' => 'Beautifully Simple',
                ),
                array(
                    'id'      => 'evl_content_box1_icon',
                    'type'    => 'text',
                    'title'   => __( 'Content Box 1 Icon (FontAwesome)', 'evolve' ),
                    'default' => 'fa-cube',
                ),
                array(
                    'id'       => 'evl_content_box1_icon_color',
                    'compiler' => true,
                    'type'     => 'color',
                    'title'    => __( 'Content Box 1 Icon Color', 'evolve' ),
                    'default'  => '#faa982',
                ),
                array(
                    'subtitle' => __( 'Upload an image as your icon, or specify an image URL directly. <br/> This overwrites the Content Box 1 Icon (FontAwesome) setting', 'evolve' ),
                    'id'       => 'evl_content_box1_icon_upload',
                    'type'     => 'media',
                    'title'    => __( 'Content Box 1 Custom Icon', 'evolve' ),
                    'locked'   => sprintf( __( 'This option is only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),

                    'url'      => true,
                ),                 
                array(
                    'id'      => 'evl_content_box1_desc',
                    'type'    => 'textarea',
                    'title'   => __( 'Content Box 1 description', 'evolve' ),
                    'default' => 'Clean and modern theme with smooth and pixel perfect design focused on details',
                ),
                array(
                    'id'      => 'evl_content_box1_button',
                    'type'    => 'textarea',
                    'title'   => __( 'Content Box 1 Button', 'evolve' ),
                    'default' => '<a class="read-more btn" href="#">Learn more</a>',
                ),
                array(
                    'id'      => 'evl_content_box2_enable',
                    'type'    => 'switch',
                    'on'      => __( 'Enabled', 'evolve' ),
                    'off'     => __( 'Disabled', 'evolve' ),
                    'default' => 1,
                    'title'   => __( 'Enable Content Box 2 ?', 'evolve' ),
                ),
                array(
                    'id'      => 'evl_content_box2_title',
                    'type'    => 'text',
                    'title'   => __( 'Content Box 2 Title', 'evolve' ),
                    'default' => 'Easy Customizable',
                ),
                array(
                    'id'      => 'evl_content_box2_icon',
                    'type'    => 'text',
                    'title'   => __( 'Content Box 2 Icon (FontAwesome)', 'evolve' ),
                    'default' => 'fa-circle-o-notch',
                ),
                array(
                    'id'       => 'evl_content_box2_icon_color',
                    'compiler' => true,
                    'type'     => 'color',
                    'title'    => __( 'Content Box 2 Icon Color', 'evolve' ),
                    'default'  => '#8fb859',
                ),
                array(
                    'subtitle' => __( 'Upload an image as your icon, or specify an image URL directly. <br/> This overwrites the Content Box 2 Icon (FontAwesome) setting', 'evolve' ),
                    'id'       => 'evl_content_box2_icon_upload',
                    'type'     => 'media',
                    'title'    => __( 'Content Box 2 Custom Icon', 'evolve' ),
                    'locked'   => sprintf( __( 'This option is only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),

                    'url'      => true,
                ),                  
                array(
                    'id'      => 'evl_content_box2_desc',
                    'type'    => 'textarea',
                    'title'   => __( 'Content Box 2 description', 'evolve' ),
                    'default' => 'Over a hundred theme options ready to make your website unique',
                ),
                array(
                    'id'      => 'evl_content_box2_button',
                    'type'    => 'textarea',
                    'title'   => __( 'Content Box 2 Button', 'evolve' ),
                    'default' => '<a class="read-more btn" href="#">Learn more</a>',
                ),
                array(
                    'id'      => 'evl_content_box3_enable',
                    'type'    => 'switch',
                    'on'      => __( 'Enabled', 'evolve' ),
                    'off'     => __( 'Disabled', 'evolve' ),
                    'default' => 1,
                    'title'   => __( 'Enable Content Box 3 ?', 'evolve' ),
                ),
                array(
                    'id'      => 'evl_content_box3_title',
                    'type'    => 'text',
                    'title'   => __( 'Content Box 3 Title', 'evolve' ),
                    'default' => 'Contact Form Ready',
                ),
                array(
                    'id'      => 'evl_content_box3_icon',
                    'type'    => 'text',
                    'title'   => __( 'Content Box 3 Icon (FontAwesome)', 'evolve' ),
                    'default' => 'fa-send',
                ),
                array(
                    'id'       => 'evl_content_box3_icon_color',
                    'type'     => 'color',
                    'compiler' => true,
                    'title'    => __( 'Content Box 3 Icon Color', 'evolve' ),
                    'default'  => '#78665e',
                ),
                array(
                    'subtitle' => __( 'Upload an image as your icon, or specify an image URL directly. <br/> This overwrites the Content Box 3 Icon (FontAwesome) setting', 'evolve' ),
                    'id'       => 'evl_content_box3_icon_upload',
                    'type'     => 'media',
                    'title'    => __( 'Content Box 3 Custom Icon', 'evolve' ),
                    'locked'   => sprintf( __( 'This option is only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),

                    'url'      => true,
                ),                
                array(
                    'id'      => 'evl_content_box3_desc',
                    'type'    => 'textarea',
                    'title'   => __( 'Content Box 3 description', 'evolve' ),
                    'default' => 'Built-In Contact Page with Google Maps is a standard for this theme',
                ),
                array(
                    'id'      => 'evl_content_box3_button',
                    'type'    => 'textarea',
                    'title'   => __( 'Content Box 3 Button', 'evolve' ),
                    'default' => '<a class="read-more btn" href="#">Learn more</a>',
                ),
                array(
                    'id'      => 'evl_content_box4_enable',
                    'type'    => 'switch',
                    'on'      => __( 'Enabled', 'evolve' ),
                    'off'     => __( 'Disabled', 'evolve' ),
                    'default' => 1,
                    'title'   => __( 'Enable Content Box 4 ?', 'evolve' ),
                ),
                array(
                    'id'      => 'evl_content_box4_title',
                    'type'    => 'text',
                    'title'   => __( 'Content Box 4 Title', 'evolve' ),
                    'default' => 'Responsive Blog',
                ),
                array(
                    'id'      => 'evl_content_box4_icon',
                    'type'    => 'text',
                    'title'   => __( 'Content Box 4 Icon (FontAwesome)', 'evolve' ),
                    'default' => 'fa-tablet',
                ),
                array(
                    'id'       => 'evl_content_box4_icon_color',
                    'type'     => 'color',
                    'compiler' => true,
                    'title'    => __( 'Content Box 4 Icon Color', 'evolve' ),
                    'default'  => '#82a4fa',
                ),
                array(
                    'subtitle' => __( 'Upload an image as your icon, or specify an image URL directly. <br/> This overwrites the Content Box 4 Icon (FontAwesome) setting', 'evolve' ),
                    'id'       => 'evl_content_box4_icon_upload',
                    'type'     => 'media',
                    'title'    => __( 'Content Box 4 Custom Icon', 'evolve' ),
                    'locked'   => sprintf( __( 'This option is only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),

                    'url'      => true,
                ),                    
                array(
                    'id'      => 'evl_content_box4_desc',
                    'type'    => 'textarea',
                    'title'   => __( 'Content Box 4 description', 'evolve' ),
                    'default' => 'Up to 3 Blog Layouts, Bootstrap 3 ready, responsive on all media devices',
                ),
                array(
                    'id'      => 'evl_content_box4_button',
                    'type'    => 'textarea',
                    'title'   => __( 'Content Box 4 Button', 'evolve' ),
                    'default' => '<a class="read-more btn" href="#">Learn more</a>',
                ),
            ),
        ),
    ) );


    // Dynamic section generation, less human error.  ;)
    $slide_defaults = array(
        array(
            'title'       => __( 'Super Awesome WP Theme', 'evolve' ),
            'description' => __( 'Absolutely free of cost theme with amazing design and premium features which will impress your visitors', 'evolve' ),
        ),
        array(
            'title'       => __( 'Bootstrap and Font Awesome Ready', 'evolve' ),
            'description' => __( 'Built-in Bootstrap Elements and Font Awesome let you do amazing things with your website', 'evolve' ),
        ),
        array(
            'title'       => __( 'Easy to use control panel', 'evolve' ),
            'description' => __( 'Select of 500+ Google Fonts, choose layout as you need, set up your social links', 'evolve' ),
        ),
        array(
            'title'       => __( 'Fully responsive theme', 'evolve' ),
            'description' => __( 'Adaptive to any screen depending on the device being used to view the site', 'evolve' ),
        ),
        array(
            'title'       => __( 'Unlimited color schemes', 'evolve' ),
            'description' => __( 'Upload your own logo, change background color or images, select links color which you love - it\'s limitless', 'evolve' ),
        )

    );
    $fields         = array(
        array(
            'subtitle' => __( 'Display Bootstrap Slider on the homepage, all pages or select the slider in the post/page edit mode.', 'evolve' ),
            'id'       => 'evl_bootstrap_slider',
            'type'     => 'select',
            'options'  => array(
                'homepage' => __( 'Homepage only', 'evolve' ),
                'post'     => __( 'Manually select in a Post/Page edit mode', 'evolve' ),
                'all'      => __( 'All pages', 'evolve' ),
            ),
            'title'    => __( 'Bootstrap Slider placement', 'evolve' ),
            'default'  => 'homepage',
        ),
        array(
            'subtitle' => __( 'Check this box to disable Bootstrap Slides 100% Background', 'evolve' ),
            'id'       => 'evl_bootstrap_100',
            'type'     => 'checkbox',
            'title'    => __( 'Disable Bootstrap Slides 100% Background', 'evolve' ),
        ),
        array(
            'subtitle' => __( 'Input the time between transitions (Default: 7000);', 'evolve' ),
            'id'       => 'evl_bootstrap_speed',
            'type'     => 'spinner',
            'title'    => __( 'Speed', 'evolve' ),
			'step' => 100,
            'default'  => '7000',
        ),
        array(
            'subtitle'    => __( 'Select the typography you want for the slide title. * non web-safe font.', 'evolve' ),
            'id'          => 'evl_bootstrap_slide_title_font',
            'type'        => 'typography',
            'title'       => __( 'Slider Title font', 'evolve' ),
            'line-height' => false,
            'text-align'  => false,
            'default'     => array(
                'font-size'   => '36px',
                'font-family' => 'Roboto',
                'color'       => '',
				'font-style' => '',
            ),
        ),
        array(
            'subtitle'    => __( 'Select the typography you want for the slide description. * non web-safe font.', 'evolve' ),
            'id'          => 'evl_bootstrap_slide_subtitle_font',
            'type'        => 'typography',
            'title'       => __( 'Slider description font', 'evolve' ),
            'line-height' => false,
            'text-align'  => false,
            'default'     => array(
                'font-size'   => '18px',
                'font-family' => 'Roboto',
                'color'       => '',
				'font-style' => '',
            ),
        ),
    );
    for ( $i = 1; $i <= 5; $i ++ ) {
        $fields[] = array(
            "title"    => sprintf( __( 'Enable Slide %d', 'evolve' ), $i ),
            "subtitle" => sprintf( __( 'Enable or Disable Slide %d', 'evolve' ), $i ),
            "id"       => "{$evolve_shortname}_bootstrap_slide{$i}",
            "type"     => "switch",
            "default"  => "1"
        );

        $fields[] = array(
            "title"    => sprintf( __( 'Slide %d Image', 'evolve' ), $i ),
            "subtitle" => sprintf( __( 'Upload an image for the Slide %d, or specify an image URL directly', 'evolve' ), $i ),
            "id"       => "{$evolve_shortname}_bootstrap_slide{$i}_img",
            "type"     => "media",
            'url'      => true,
            'readonly' => false,
            'required' => array( array( "{$evolve_shortname}_bootstrap_slide{$i}", '=', '1' ) ),
            "default"  => array( 'url' => "{$imagepathfolder}bootstrap-slider/{$i}.jpg" )
        );

        $fields[] = array(
            "title"    => sprintf( __( 'Slide %d Title', 'evolve' ), $i ),
            "id"       => "{$evolve_shortname}_bootstrap_slide{$i}_title",
            "type"     => "text",
            'required' => array( array( "{$evolve_shortname}_bootstrap_slide{$i}", '=', '1' ) ),
            "default"  => $slide_defaults[ ( $i - 1 ) ]['title']
        );

        $fields[] = array(
            "title"    => sprintf( __( 'Slide %d description', 'evolve' ), $i ),
            "id"       => "{$evolve_shortname}_bootstrap_slide{$i}_desc",
            "type"     => "textarea",
            "rows"     => 5,
            'required' => array( array( "{$evolve_shortname}_bootstrap_slide{$i}", '=', '1' ) ),
            "default"  => $slide_defaults[ ( $i - 1 ) ]['description']
        );

        $fields[] = array(
            "title"    => sprintf( __( 'Slide %d Button', 'evolve' ), $i ),
            "id"       => "{$evolve_shortname}_bootstrap_slide{$i}_button",
            "type"     => "textarea",
            "rows"     => 3,
            'required' => array( array( "{$evolve_shortname}_bootstrap_slide{$i}", '=', '1' ) ),
            "default"  => '<a class="button" href="#">' . __( 'Learn more', 'evolve' ) . '</a>',
        );
    }

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-14',
            'title'  => __( 'Bootstrap Slider', 'evolve' ),
            'icon'   => 't4p-icon-appbarimageselect',
            'fields' => $fields,
        ),
    ) );


    // Dynamic section generation, less human error.  ;)
    $slide_defaults = array(
        array(
            'image'       => "{$imagepathfolder}parallax/6.png",
            'title'       => __( 'Super Awesome WP Theme', 'evolve' ),
            'description' => __( 'Absolutely free of cost theme with amazing design and premium features which will impress your visitors', 'evolve' ),
        ),
        array(
            'image'       => "{$imagepathfolder}parallax/5.png",
            'title'       => __( 'Bootstrap and Font Awesome Ready', 'evolve' ),
            'description' => __( 'Built-in Bootstrap Elements and Font Awesome let you do amazing things with your website', 'evolve' ),
        ),
        array(
            'image'       => "{$imagepathfolder}parallax/4.png",
            'title'       => __( 'Easy to use control panel', 'evolve' ),
            'description' => __( 'Select of 500+ Google Fonts, choose layout as you need, set up your social links', 'evolve' ),
        ),
        array(
            'image'       => "{$imagepathfolder}parallax/1.png",
            'title'       => __( 'Fully responsive theme', 'evolve' ),
            'description' => __( 'Adaptive to any screen depending on the device being used to view the site', 'evolve' ),
        ),
        array(
            'image'       => "{$imagepathfolder}parallax/3.png",
            'title'       => __( 'Unlimited color schemes', 'evolve' ),
            'description' => __( 'Upload your own logo, change background color or images, select links color which you love - it\'s limitless', 'evolve' ),
        )
    );
    $fields         = array(
        array(
            'subtitle' => __( 'Display Parallax Slider on the homepage, all pages or select the slider in the post/page edit mode.', 'evolve' ),
            'id'       => 'evl_parallax_slider',
            'type'     => 'select',
            'options'  => array(
                'homepage' => __( 'Homepage only', 'evolve' ),
                'post'     => __( 'Manually select in a Post/Page edit mode', 'evolve' ),
                'all'      => __( 'All pages', 'evolve' ),
            ),
            'title'    => __( 'Parallax Slider placement', 'evolve' ),
            'default'  => 'post',
        ),
        array(
            'subtitle' => __( 'Input the time between transitions (Default: 4000);', 'evolve' ),
            'id'       => 'evl_parallax_speed',
            'type'     => 'spinner',
            'title'    => __( 'Parallax Speed', 'evolve' ),
            'step' => 100,
            'default'  => '4000',
        ),
        array(
            'subtitle'    => __( 'Select the typography you want for the slide title. * non web-safe font.', 'evolve' ),
            'id'          => 'evl_parallax_slide_title_font',
            'type'        => 'typography',
            'title'       => __( 'Slider Title font', 'evolve' ),
            'line-height' => false,
            'text-align'  => false,
            'default'     => array(
                'font-size'   => '36px',
                'font-family' => 'Roboto',
                'color'       => '',
				'font-style' => '',
            ),
        ),
        array(
            'subtitle'    => __( 'Select the typography you want for the slide description. * non web-safe font.', 'evolve' ),
            'id'          => 'evl_parallax_slide_subtitle_font',
            'type'        => 'typography',
            'title'       => __( 'Slider description font', 'evolve' ),
            'line-height' => false,
            'text-align'  => false,
            'default'     => array(
                'font-size'   => '18px',
                'font-family' => 'Roboto',
                'color'       => '',
				'font-style' => '',
            ),
        ),
    );
    for ( $i = 1; $i <= 5; $i ++ ) {
        $fields[] = array(
            "title"    => sprintf( __( 'Enable Slide %d', 'evolve' ), $i ),
            "subtitle" => sprintf( __( 'Enable or Disable Slide %d', 'evolve' ), $i ),
            "id"       => "{$evolve_shortname}_show_slide{$i}",
            "type"     => "switch",
            "default"  => "1"
        );

        $fields[] = array(
            "title"    => sprintf( __( 'Slide %s Image', 'evolve' ), $i ),
            "subtitle" => sprintf( __( 'Upload an image for the Slide %d, or specify an image URL directly', 'evolve' ), $i ),
            "id"       => "{$evolve_shortname}_slide{$i}_img",
            "type"     => "media",
            'url'      => true,
            'readonly' => false,
            'required' => array( array( "{$evolve_shortname}_show_slide{$i}", '=', '1' ) ),
            "default"  => array( 'url' => $slide_defaults[ ( $i - 1 ) ]['image'] )
        );

        $fields[] = array(
            "title"    => sprintf( __( 'Slide %s Title', 'evolve' ), $i ),
            "subtitle" => "",
            "id"       => "{$evolve_shortname}_slide{$i}_title",
            "type"     => "text",
            'required' => array( array( "{$evolve_shortname}_show_slide{$i}", '=', '1' ) ),
            "default"  => $slide_defaults[ ( $i - 1 ) ]['title']
        );

        $fields[] = array(
            "title"    => sprintf( __( 'Slide %s description', 'evolve' ), $i ),
            "subtitle" => "",
            "id"       => "{$evolve_shortname}_slide{$i}_desc",
            "type"     => "textarea",
            'required' => array( array( "{$evolve_shortname}_show_slide{$i}", '=', '1' ) ),
            "default"  => $slide_defaults[ ( $i - 1 ) ]['description']
        );

        $fields[] = array(
            "name"     => sprintf( __( 'Slide %s Button', 'evolve' ), $i ),
            "id"       => "{$evolve_shortname}_slide{$i}_button",
            "type"     => "textarea",
            'required' => array( array( "{$evolve_shortname}_show_slide{$i}", '=', '1' ) ),
            "default"  => '<a class="da-link" href="#">' . __( 'Learn more', 'evolve' ) . '</a>'
        );
    }

    /*
     * If you Ever wanted to switch to our slides field, here's a start


    array(
                    'id'          => 'evl_slides',
                    'type'        => 'slides',
                    'title'       => __( 'Parallax Slides', 'evolve' ),
                    'placeholder' => array(
                        'url' => 'Button'
                    ),
                    'show'        => array( 'enabled' => true ),
                    'label'       => array(
                        'title'       => __( 'Title', 'evolve' ),
                        'description' => __( 'description', 'evolve' ),
                        'url'         => __( 'Button', 'evolve' ),
                        'enabled'     => __( 'Enabled?', 'evolve' )
                    ),
                    'default'     => array(
                        array(
                            'image'       => get_template_directory_uri().'library/media/images/parallax/6.png',
                            'title'       => __( 'Super Awesome WP Theme', 'evolve' ),
                            'description' => __( 'Absolutely free of cost theme with amazing design and premium features which will impress your visitors', 'evolve' ),
                            'url'         => '<a class="da-link" href="#">Learn more</a>',
                            'enabled'     => 1,
                        ),
                        array(
                            'image'       => get_template_directory_uri().'library/media/images/parallax/5.png',
                            'title'       => __( 'Bootstrap and Font Awesome Ready', 'evolve' ),
                            'description' => __( 'Built-in Bootstrap Elements and Font Awesome let you do amazing things with your website', 'evolve' ),
                            'url'         => '<a class="da-link" href="#">Learn more</a>',
                            'enabled'     => 1,
                        ),
                        array(
                            'image'       => get_template_directory_uri().'library/media/images/parallax/4.png',
                            'title'       => __( 'Easy to use control panel', 'evolve' ),
                            'description' => __( 'Select of 500+ Google Fonts, choose layout as you need, set up your social links', 'evolve' ),
                            'url'         => '<a class="da-link" href="#">Learn more</a>',
                            'enabled'     => 1,
                        ),
                        array(
                            'image'       => get_template_directory_uri().'library/media/images/parallax/1.png',
                            'title'       => __( 'Fully responsive theme', 'evolve' ),
                            'description' => __( 'Adaptive to any screen depending on the device being used to view the site', 'evolve' ),
                            'url'         => '<a class="da-link" href="#">Learn more</a>',
                            'enabled'     => 1,
                        ),
                        array(
                            'image'       => get_template_directory_uri().'library/media/images/parallax/3.png',
                            'title'       => __( 'Unlimited color schemes', 'evolve' ),
                            'description' => "Upload your own logo, change background color or images, select links color which you love - it's limitless",
                            'url'         => '<a class="da-link" href="#">Learn more</a>',
                            'enabled'     => 1,
                        )
                    )
                ),


     */

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-8',
            'title'  => __( 'Parallax Slider', 'evolve' ),
            'icon'   => 't4p-icon-appbarmonitor',
            'fields' => $fields,
        ),
    ) );


    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-9',
            'title'  => __( 'Posts Slider', 'evolve' ),
            'icon'   => 't4p-icon-appbarvideogallery',
            'fields' => array(
                array(
                    'subtitle' => __( 'Display Posts Slider on the homepage, all pages or select the slider in the post/page edit mode.', 'evolve' ),
                    'id'       => 'evl_posts_slider',
                    'type'     => 'select',
                    'options'  => array(
                        'homepage' => __( 'Homepage only', 'evolve' ),
                        'post'     => __( 'Manually select in a Post/Page edit mode', 'evolve' ),
                        'all'      => __( 'All pages', 'evolve' ),
                    ),
                    'title'    => __( 'Posts Slider placement', 'evolve' ),
                    'default'  => 'post',
                ),
                array(
                    'id'      => 'evl_posts_number',
                    'type'    => 'spinner',
                    'min'     => 1,
                    'max'     => 10,
                    'title'   => __( 'Number of posts to display', 'evolve' ),
                    'default' => '5',
                ),
                array(
                    'subtitle' => __( 'Choose to display latest posts or posts of a category.', 'evolve' ),
                    'id'       => 'evl_posts_slider_content',
                    'type'     => 'select',
                    'options'  => array(
                        'recent'   => __( 'Recent posts', 'evolve' ),
                        'category' => __( 'Posts in category', 'evolve' ),
                    ),
                    'title'    => __( 'Slideshow content', 'evolve' ),
                    'default'  => 'recent',
                ),
                array(
                    'subtitle' => __( 'Select post categories to pull content for the post slideshow.', 'evolve' ),
                    'id'       => 'evl_posts_slider_id',
                    'type'     => 'select',
                    'multi'    => true,
                    'data'     => 'categories',
                    'required' => array(
                        array( 'evl_posts_slider_content', '=', 'category' )
                    ),
                    'title'    => __( 'Category ID(s)', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Input the time between transitions (Default: 3500);', 'evolve' ),
                    'id'       => 'evl_carousel_speed',
                    'type'     => 'spinner',
                    'title'    => __( 'Slider Speed', 'evolve' ),
                    'step' => 100,
                    'default'  => '7000',
                ),
                array(
                    'subtitle' => __( 'Sets the length of Slider Title. Default is 40', 'evolve' ),
                    'id'       => 'evl_posts_slider_title_length',
                    'type'     => 'spinner',
                    'title'    => __( 'Slider Title Length', 'evolve' ),
                    'default'  => '40',
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for the slide title. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_carousel_slide_title_font',
                    'type'        => 'typography',
                    'line-height' => false,
                    'text-align'  => false,
                    'title'       => __( 'Slider Title font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '36px',
                        'font-family' => 'Roboto',
                        'color'       => '',
						'font-style' => '',
                    ),
                ),
                array(
                    'subtitle'    => __( 'Select the typography you want for the slide description. * non web-safe font.', 'evolve' ),
                    'id'          => 'evl_carousel_slide_subtitle_font',
                    'type'        => 'typography',
                    'line-height' => false,
                    'text-align'  => false,
                    'title'       => __( 'Slider description font', 'evolve' ),
                    'default'     => array(
                        'font-size'   => '18px',
                        'font-family' => 'Roboto',
                        'color'       => '',
						'font-style' => '',
                    ),
                ),
            ),
        ),
    ) );


    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-17',
            'title'  => __( 'Flexslider', 'evolve' ),
            'locked' => sprintf( __( 'These options are only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),
            'icon'   => 't4p-icon-appbarlayer',
            'fields' => array(
                array(
                    'subtitle' => __( 'Turn on to autoplay the slideshow.', 'evolve' ),
                    'id'       => 'evl_slideshow_autoplay',
                    'type'     => 'switch',
                    'title'    => __( 'Autoplay', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Controls the speed of slideshows for the [slider] shortcode and sliders within posts. 1000 = 1 second.', 'evolve' ),
                    'id'       => 'evl_slideshow_speed',
                    'type'     => 'spinner',
                    'title'    => __( 'Slideshow speed', 'evolve' ),
                    'default'  => '7000',
                ),
                array(
                    'subtitle' => __( 'Controls the number of slides per group for the flexslider plugin.', 'evolve' ),
                    'id'       => 'evl_flexslider_number',
                    'type'     => 'spinner',
                    'title'    => __( 'Number of FlexSlider Slides', 'evolve' ),
                    'default'  => '5',
                ),
                array(
                    'subtitle' => __( 'Turn on if you want to show pagination circles below a video slide for flexslider. Leave it off to hide them on video slides.', 'evolve' ),
                    'id'       => 'evl_pagination_video_slide',
                    'type'     => 'switch',
                    'title'    => __( 'Pagination circles below video slides', 'evolve' ),
                ),
            ),
        ),
    ) );


    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-19',
            'title'  => __( 'Lightbox', 'evolve' ),
            'icon'   => 't4p-icon-appbarwindowmaximize',
            'locked' => sprintf( __( 'These options are only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),
            'fields' => array(
                array(
                    'subtitle' => __( 'Set the speed of the animation.', 'evolve' ),
                    'id'       => 'evl_lightbox_animation_speed',
                    'type'     => 'select',
                    'options'  => array(
                        'fast'   => __( 'Fast', 'evolve' ),
                        'slow'   => __( 'Slow', 'evolve' ),
                        'normal' => __( 'Normal', 'evolve' ),
                    ),
                    'title'    => __( 'Animation Speed', 'evolve' ),
                    'default'  => 'fast',
                ),
                array(
                    'subtitle' => __( 'Show the gallery.', 'evolve' ),
                    'id'       => 'evl_lightbox_gallery',
                    'type'     => 'switch',
                    'title'    => __( 'Show gallery', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Autoplay the lightbox gallery.', 'evolve' ),
                    'id'       => 'evl_lightbox_autoplay',
                    'type'     => 'switch',
                    'title'    => __( 'Autoplay the Lightbox Gallery', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'If autoplay is enabled, set the slideshow speed, 1000 = 1 second.', 'evolve' ),
                    'id'       => 'evl_lightbox_slideshow_speed',
                    'type'     => 'spinner',
                    'title'    => __( 'Slideshow Speed', 'evolve' ),
                    'default'  => '5000',
                ),
                array(
                    'subtitle'   => __( 'Set the opacity of background, <br />0.1 (lowest) to 1 (highest).', 'evolve' ),
                    'id'         => 'evl_lightbox_opacity',
                    'type'       => 'slider',
                    'min'        => 0.1,
                    'max'        => 1,
                    'step'       => 0.1,
                    'resolution' => 0.1,
                    'title'      => __( 'Background Opacity', 'evolve' ),
                    'default'    => '0.8',
                ),
                array(
                    'subtitle' => __( 'Show the image caption.', 'evolve' ),
                    'id'       => 'evl_lightbox_title',
                    'type'     => 'switch',
                    'title'    => __( 'Show Caption', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Show the image description. The Alternative text field is used for the description.', 'evolve' ),
                    'id'       => 'evl_lightbox_subtitle',
                    'type'     => 'switch',
                    'title'    => __( 'Show description', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Show social sharing buttons on lightbox.', 'evolve' ),
                    'id'       => 'evl_lightbox_social',
                    'type'     => 'switch',
                    'title'    => __( 'Social Sharing', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Show post images that are inside the post content area in the lightbox.', 'evolve' ),
                    'id'       => 'evl_lightbox_post_images',
                    'type'     => 'switch',
                    'title'    => __( 'Show Post Images in Lightbox', 'evolve' ),
                ),
            ),
        ),
    ) );


    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-13',
            'title'  => __( 'Contact', 'evolve' ),
            'icon'   => 't4p-icon-appbarlocationcheckin',
            'fields' => array(
                array(
                    'subtitle' => __( 'Select the type of google map to show on the contact page.', 'evolve' ),
                    'id'       => 'evl_gmap_type',
                    'type'     => 'select',
                    'options'  => array(
                        'roadmap'   => __( 'roadmap', 'evolve' ),
                        'satellite' => __( 'satellite', 'evolve' ),
                        'hybrid'    => __( 'hybrid (default)', 'evolve' ),
                        'terrain'   => __( 'terrain', 'evolve' ),
                    ),
                    'title'    => __( 'Google Map Type', 'evolve' ),
                    'default'  => 'hybrid',
                ),
                array(
                    'subtitle' => __( '(in pixels or percentage, e.g.:100% or 100px)', 'evolve' ),
                    'id'       => 'evl_gmap_width',
                    'compiler' => true,
                    'type'     => 'text',
                    'title'    => __( 'Google Map Width', 'evolve' ),
                    'default'  => '100%',
                ),
                array(
                    'subtitle' => __( '(in pixels, e.g.: 100px)', 'evolve' ),
                    'id'       => 'evl_gmap_height',
                    'compiler' => true,
                    'type'     => 'text',
                    'title'    => __( 'Google Map Height', 'evolve' ),
                    'default'  => '415px',
                ),
                array(
                    'subtitle' => __( 'Example: 775 New York Ave, Brooklyn, Kings, New York 11203.<br /> For multiple markers, separate the addresses with the | symbol. ex: Address 1|Address 2|Address 3.', 'evolve' ),
                    'id'       => 'evl_gmap_address',
                    'compiler' => true,
                    'type'     => 'text',
                    'title'    => __( 'Google Map Address', 'evolve' ),
                    'default'  => 'Via dei Fori Imperiali',
                ),
                array(
                    'subtitle' => __( 'Insert name of header which will be in the header of sent email.', 'evolve' ),
                    'id'       => 'evl_sent_email_header',
                    'type'     => 'text',
                    'title'    => __( 'Sent Email Header (From)', 'evolve' ),
                    'default'  => 'Evolve',
                ),
                array(
                    'subtitle' => __( 'Enter the email adress the form will be sent to.', 'evolve' ),
                    'id'       => 'evl_email_address',
                    'type'     => 'text',
                    'title'    => __( 'Email Address', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Higher number will be more zoomed in.', 'evolve' ),
                    'id'       => 'evl_map_zoom_level',
                    'type'     => 'slider',
                    'min'      => 0,
                    'max'      => 25,
                    'title'    => __( 'Map Zoom Level', 'evolve' ),
                    'default'  => '18',
                ),
                array(
                    'subtitle' => __( 'Display the address pin.', 'evolve' ),
                    'id'       => 'evl_map_pin',
                    'type'     => 'switch',
                    'on'       => __( 'Hide', 'evolve' ),
                    'off'      => __( 'Show', 'evolve' ),
                    'title'    => __( 'Hide Address Pin', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Keep the popup graphic with address info hidden when the google map loads. It will only show when the pin on the map is clicked.', 'evolve' ),
                    'id'       => 'evl_map_popup',
                    'type'     => 'switch',
                    'title'    => __( 'Show Map Popup On Click', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Disable scrollwheel on google maps.', 'evolve' ),
                    'id'       => 'evl_map_scrollwheel',
                    'on'       => __( 'Disabled', 'evolve' ),
                    'off'      => __( 'Enabled', 'evolve' ),
                    'type'     => 'switch',
                    'title'    => __( 'Disable Map Scrollwheel', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Disable scale on google maps.', 'evolve' ),
                    'id'       => 'evl_map_scale',
                    'type'     => 'switch',
                    'on'       => __( 'Disabled', 'evolve' ),
                    'off'      => __( 'Enabled', 'evolve' ),
                    'title'    => __( 'Disable Map Scale', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check the box to disable zoom control icon and pan control icon on google maps.', 'evolve' ),
                    'id'       => 'evl_map_zoomcontrol',
                    'type'     => 'switch',
                    'on'       => __( 'Disabled', 'evolve' ),
                    'off'      => __( 'Enabled', 'evolve' ),
                    'title'    => __( 'Disable Map Zoom & Pan Control Icons', 'evolve' ),
                ),
                array(
                    'subtitle' => sprintf( __( 'Get Google reCAPTCHA keys <a href="%s">here</a>  to enable spam protection on the contact page.', 'evolve' ), 'https://www.google.com/recaptcha/admin' ),
                    'id'       => 'evl_captcha_plugin',
                    'style'    => 'warning',
                    'type'     => 'info',
					'notice'   => false,
                ),
                array(
                    'subtitle' => __( 'Follow the steps in our docs to get your key', 'evolve' ),
                    'id'       => 'evl_recaptcha_public',
                    'type'     => 'text',
                    'title'    => __( 'Google reCAPTCHA Site Key', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Follow the steps in our docs to get your key', 'evolve' ),
                    'id'       => 'evl_recaptcha_private',
                    'type'     => 'text',
                    'title'    => __( 'Google reCAPTCHA Secret key', 'evolve' ),
                ),
            ),
        ),
    ) );


    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-7',
            'title'  => __( 'Extra', 'evolve' ),
            'icon'   => 't4p-icon-appbarsettings',
            'fields' => array(
                array(
                    'subtitle' => __( 'Select the slideshow speed, 1000 = 1 second.', 'evolve' ),
                    'id'       => 'evl_testimonials_speed',
                    'type'     => 'spinner',
                    'locked'   => sprintf( __( 'This option is only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),
                    'title'    => __( 'Testimonials Speed', 'evolve' ),
                    'default'  => '4000',
                ),
                array(
                    'subtitle' => __( 'Check the box to add rel="nofollow" attribute to social sharing box shortcode.', 'evolve' ),
                    'id'       => 'evl_nofollow_social_links',
                    'type'     => 'checkbox',
                    'locked'   => sprintf( __( 'This option is only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),
                    'title'    => __( 'Add rel="nofollow" to social links', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to enable breadcrumbs navigation', 'evolve' ),
                    'id'       => 'evl_breadcrumbs',
                    'type'     => 'checkbox',
                    'title'    => __( 'Enable Breadcrumbs Navigation', 'evolve' ),
                    'default'  => '1',
                ),
                array(
                    'subtitle' => __( 'Choose the position of the <strong>Older/Newer Posts</strong> links', 'evolve' ),
                    'id'       => 'evl_nav_links',
                    'type'     => 'select',
                    'options'  => array(
                        'after'  => __( 'After posts', 'evolve' ),
                        'before' => __( 'Before posts', 'evolve' ),
                        'both'   => __( 'Both', 'evolve' ),
                    ),
                    'title'    => __( 'Position of navigation links', 'evolve' ),
                    'default'  => 'after',
                ),
                array(
                    'id'       => 'evl_pos_button',
                    'type'     => 'select',
                    'compiler' => true,
                    'options'  => array(
                        'disable' => __( 'Disable', 'evolve' ),
                        'left'    => __( 'Left', 'evolve' ),
                        'right'   => __( 'Right', 'evolve' ),
                        'middle'  => __( 'Middle', 'evolve' ),
                    ),
                    'title'    => __( 'Position of \'Back to Top\' button', 'evolve' ),
                    'default'  => 'right',
                ),
                array(
                    'subtitle' => __( '<h3 style=\'margin: 0;\'>Options For Plugins Integrated Within The Theme</h3>', 'evolve' ),
                    'id'       => 'evl_plugins_only',
                    'type'     => 'info',
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to enable FlexSlider support', 'evolve' ),
                    'id'       => 'evl_flexslider',
                    'type'     => 'checkbox',
                    'locked'   => sprintf( __( 'This option is only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),
                    'title'    => __( 'Enable FlexSlider support', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to enable Parallax Slider support', 'evolve' ),
                    'id'       => 'evl_parallax_slider_support',
                    'type'     => 'checkbox',
                    'title'    => __( 'Enable Parallax Slider support', 'evolve' ),
                    'default'  => '1',
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to enable Carousel Slider support', 'evolve' ),
                    'id'       => 'evl_carousel_slider',
                    'type'     => 'checkbox',
                    'title'    => __( 'Enable Carousel Slider support', 'evolve' ),
                    'default'  => '1',
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to enable Google Map Scripts', 'evolve' ),
                    'id'       => 'evl_status_gmap',
                    'compiler' => true,
                    'type'     => 'checkbox',
                    'title'    => __( 'Enable Google Map Scripts', 'evolve' ),
                    'default'  => '1',
                ),
                array(
                    'subtitle' => __( 'Check this box if you want to enable Animate.css plugin support - (menu hover effect, featured image hover effect, button hover effect, etc.)', 'evolve' ),
                    'id'       => 'evl_animatecss',
                    'compiler' => true,
                    'type'     => 'checkbox',
                    'title'    => __( 'Enable Animate.css plugin support', 'evolve' ),
                    'default'  => '1',
                ),
                array(
                    'subtitle' => __( 'Check the box to disable Youtube API scripts.', 'evolve' ),
                    'id'       => 'evl_status_yt',
                    'type'     => 'checkbox',
                    'locked'   => sprintf( __( 'This option is only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),
                    'title'    => __( 'Disable Youtube API Scripts', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check the box to disable Vimeo API scripts.', 'evolve' ),
                    'id'       => 'evl_status_vimeo',
                    'type'     => 'checkbox',
                    'locked'   => sprintf( __( 'This option is only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),
                    'title'    => __( 'Disable Vimeo API Scripts', 'evolve' ),
                ),
            ),
        ),
    ) );

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-12',
            'title'  => __( 'WooCommerce', 'evolve' ),
            'locked' => sprintf( __( 'These options are only available with the <a href="%s" target="_blank">evolve+ Premium</a> version.', 'evolve' ), $t4p_url.'evolve-multipurpose-wordpress-theme/' ),
            'icon'   => 't4p-icon-appbarcart',
            'fields' => array(
                array(
                    'subtitle' => __( 'Insert the number of posts to display per page.', 'evolve' ),
                    'id'       => 'evl_woo_items',
                    'type'     => 'text',
                    'title'    => __( 'Number of Products per Page', 'evolve' ),
                    'default'  => '12',
                ),
                array(
                    'subtitle' => __( 'Check the box to disable the ordering boxes displayed on the shop page.', 'evolve' ),
                    'id'       => 'evl_woocommerce_evolve_ordering',
                    'type'     => 'checkbox',
                    'title'    => __( 'Disable Woocommerce Shop Page Ordering Boxes', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check the box to use evolve\'s one page checkout template.', 'evolve' ),
                    'id'       => 'evl_woocommerce_one_page_checkout',
                    'type'     => 'checkbox',
                    'title'    => __( 'Use Woocommerce One Page Checkout', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check the box to show the order notes on the checkout page.', 'evolve' ),
                    'id'       => 'evl_woocommerce_enable_order_notes',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Woocommerce Order Notes on Checkout', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check the box to show My Account link, uncheck to disable.', 'evolve' ),
                    'id'       => 'evl_woocommerce_acc_link_main_nav',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Woocommerce My Account Link in Header', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check the box to show the Cart icon, uncheck to disable.', 'evolve' ),
                    'id'       => 'evl_woocommerce_cart_link_main_nav',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Woocommerce Cart Link in Header', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Check the box to show the social icons on product pages, uncheck to disable.', 'evolve' ),
                    'id'       => 'evl_woocommerce_social_links',
                    'type'     => 'checkbox',
                    'title'    => __( 'Show Woocommerce Social Icons', 'evolve' ),
                ),
                array(
                    'subtitle' => __( 'Insert your text and it will appear in the first message box on the account page.', 'evolve' ),
                    'id'       => 'evl_woo_acc_msg_1',
                    'type'     => 'textarea',
                    'title'    => __( 'Account Area Message 1', 'evolve' ),
                    'default'  => 'Call us - <i class="fa fa-phone"></i> 7438 882 764',
                ),
                array(
                    'subtitle' => __( 'Insert your text and it will appear in the second message box on the account page.', 'evolve' ),
                    'id'       => 'evl_woo_acc_msg_2',
                    'type'     => 'textarea',
                    'title'    => __( 'Account Area Message 2', 'evolve' ),
                    'default'  => 'Email us - <i class="fa fa-envelope"></i> contact@example.com',
                ),
            ),
        ),
    ) );

    Redux::setSections( $opt_name, array(
        array(
            'id'     => 'evl-tab-11',
            'title'  => __( 'Custom CSS', 'evolve' ),
            'icon'   => 't4p-icon-appbarsymbolbraces',
            'fields' => array(
                array(
                    'subtitle' => __( 'Paste your CSS code, do not include any tags or HTML in this field. Any custom CSS entered here will override the theme CSS. In some cases, the !important tag may be needed.', 'evolve' ),
                    'id'       => 'evl_css_content',
                    'type'     => 'textarea',
                    'title'    => __( 'Custom CSS', 'evolve' ),
                ),
            ),
        ),
    ) );

	Redux::setSections( $opt_name, array(
		array(
            'id'         => 'import/export',
            'title'      => __( 'Import / Export', 'evolve' ),
            'heading'    => '',
            'icon'       => 't4p-icon-appbarinbox',
            'customizer' => false,
            'fields'     => array(
                array(
                    'id'         => 'redux_import_export',
                    'type'       => 'import_export',
                    //'class'      => 'redux-field-init redux_remove_th',
                    //'title'      => __( '',
                    'full_width' => true,
                )
            ),
		),
	) );

    add_action( "redux/extension/customizer/control/includes", 'evolve_extend_customizer' );
    function evolve_extend_customizer() {
        // Extra customizer field types
        if ( ! class_exists( 'Redux_Customizer_Control_spinner' ) ) {
            class Redux_Customizer_Control_spinner extends Redux_Customizer_Control {
                public $type = "redux-spinner";
            }
        }
        if ( ! class_exists( 'Redux_Customizer_Control_slider' ) ) {
            class Redux_Customizer_Control_slider extends Redux_Customizer_Control {
                public $type = "redux-slider";
            }
        }
        if ( ! class_exists( 'Redux_Customizer_Control_typography' ) ) {
            class Redux_Customizer_Control_typography extends Redux_Customizer_Control {
                public $type = "redux-typography";
            }
        }
        if ( ! class_exists( 'Redux_Customizer_Control_info' ) ) {
            class Redux_Customizer_Control_info extends Redux_Customizer_Control {
                public $type = "redux-info";
            }
        }
    }


    /**
     * Removes the demo link and the notice of integrated demo from the redux-framework plugin
     */
    if ( ! function_exists( 'evolve_remove_redux_demo' ) ) {
        function evolve_remove_redux_demo() {
            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                remove_filter( 'plugin_row_meta', array(
                    ReduxFrameworkPlugin::instance(),
                    'plugin_metalinks'
                ), null, 2 );

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
            }
        }

        add_action( 'redux/loaded', 'evolve_remove_redux_demo' );
    }


    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter( 'redux/options/' . $opt_name . '/compiler', 'evolve_compiler_action', 10, 3 );
    if ( ! function_exists( 'compiler_action' ) ) {
        function evolve_compiler_action( $options, $css, $changed_values ) {
            $GLOBALS['evl_options'] = $options;
            get_template_part( get_template_directory() . '/custom-css' );
        }
    }


    if ( ! function_exists( 'evolve_redux_header_html' ) ) {
        function evolve_redux_header_html() {
            //mod by denzel, to prevent theme check plugin listing out as INFO:
            $url = esc_url( "http://theme4press.com/evolve-multipurpose-wordpress-theme/" );
            ?>
            <a href="<?php echo $url ?>" target="_blank">
                <img style="margin-bottom:20px;float:left;position:relative;top:10px;" width="827" height="133" border="0" alt="evolve - Multipurpose WordPress Theme" src="<?php echo get_template_directory_uri(); ?>/library/functions/images/evolve.jpg">
            </a>
            <a href="http://wordpress.org/themes/evolve" target="_blank">
                <img style="margin:20px 0;clear:left;float:left;" width="645" height="27" border="0" alt="evolve on wordpress" src="<?php echo get_template_directory_uri(); ?>/library/functions/images/rate.png">
            </a>
			<div style="clear:both;"></div>
            <?php
        }

        add_action( "redux/{$opt_name}/panel/before", 'evolve_redux_header_html' );
    }

    function evolve_redux_admin_head() {
        ?>
        <style>
        .evolve_expand_options {
            cursor: pointer;
            display: block;
            height: 22px;
            width: 21px;
            float: left;
            font-size: 0;
            text-indent: -9999px;
            margin: 1px 0 0 5px;
            border: 1px solid #bbb;
            border-radius: 2px;
            background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAyCAIAAAAm4OfBAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAQhJREFUeNrslT0KhDAQhTeLR7ATT6IXSKGFYO0lciFrO1N4AU8TLNXKv0CaJbLJRAZxl1hYyJuXN+PoR/Z9fyFdBNNr27Zf8Oq6bhgGSGUYhpTSzyeBNi8hRFVVEK+6rrXaQFOs6yrvTdOYjcqyVEpTLqXI89yaSypBudq2xckF2TipOSvfmmhZFuAGnJV6Licvey5gj7fnwpwXvEfLfqnT0jQ1OBJCQLnUBvZ9b85VFAV076UU8g1ZckVRxBiDzD6OY62WzPOM9i+cpunvvcZxfCQfPWs9a91Ym2UZ5xyHtd/e8hXWng+/zlrD9jmz1tDj7bkw5wXv0Y210itJEs9az9oHsPYQYACveK0/IuB51AAAAABJRU5ErkJggg==) no-repeat -2px -26px;
        }
        .redux-sidebar,
        .redux-main {
            -webkit-transition: all 0.25s;
            transition: all 0.25s;
        }
        </style>
        <script>
        jQuery(document).ready(function($) {
            $( '.expand_options' ).removeClass('expand_options').addClass('evolve_expand_options').click(function (e) {

                    e.preventDefault();

                    var $this = $(this);

                    var $container = $( '.redux-container' );
                    if ( $container.hasClass( 'fully-expanded' ) ) {
                        $container.removeClass( 'fully-expanded' );

                        var tab = $.cookie( "redux_current_tab" );

                        $container.find( '#' + tab + '_section_group' ).css('display', 'block');
                        if ( $container.find( '#redux-footer' ).length !== 0 ) {
                            $.redux.stickyInfo(); // race condition fix
                        }
                        $.redux.initFields();
                    }

                    // var trigger = parent.find( '.expand_options' );
                    var $reduxMain = $container.find( '.redux-main' );
                    var $reduxSidebar = $container.find( '.redux-sidebar' );

                    var width = $reduxSidebar.width() - 1;
                    var id = $reduxSidebar.find( '.active a' ).data( 'rel' );

                    if ( $this.hasClass( 'evolve_expanded' ) ) {
                        $reduxMain.removeClass( 'expand' ).css('margin-left', width);

                        $reduxSidebar.css('margin-left', 0);
                        $container.find( '.redux-group-tab[data-rel!="' + id + '"]' ).css('display', 'none');
                        // Show the only active one
                    } else {
                        $reduxMain.addClass( 'expand' ).css('margin-left', '-1px');

                        $reduxSidebar.css('margin-left', -width - 113);
                        $container.find( '.redux-group-tab' ).css('display', 'block');
                        $.redux.initFields();
                    }

                    $this.toggleClass('evolve_expanded');

                    return false;
                }
            );
        });
        </script>
        <?php
    }
    add_action( 'admin_head', 'evolve_redux_admin_head' );

    function evolve_register_custom_section( $wp_customize ) {
        if( ! class_exists( 'Evolve_Redux_Customizer_Section' ) ) {
            include_once dirname( __FILE__ ) . '/evolve-exts/class-evolve-redux-customizer-section.php';
        }
        if ( method_exists( $wp_customize, 'register_section_type' ) ) {
            $wp_customize->register_section_type( 'Evolve_Redux_Customizer_Section' );
        }
    }
    add_action( 'customize_register', 'evolve_register_custom_section' );

    function evolve_get_custom_redux_section_class() {
        return 'Evolve_Redux_Customizer_Section';
    }
    add_filter( 'redux/customizer/section/class_name', 'evolve_get_custom_redux_section_class' );
