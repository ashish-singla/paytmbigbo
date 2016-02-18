<?php
    // TGM Class
    get_template_part( 'library/admin/tgm/class-tgm-plugin-activation' );

    function evolve_register_suggested_plugins() {
        $plugins = array(
            array(
                'name'        => 'Redux Framework',
                'slug'        => 'redux-framework',
                'version'     => '3.5.7',
                'is_callable' => 'redux-framework-master',
                'required'    => false,
            ),
        );

        $config = array(
            'id'           => 'evolve_theme_tgm',
            // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',
            // Default absolute path to bundled plugins.
            'menu'         => 'evolve-tgmpa-install-plugins',
            // Menu slug.
            'parent_slug'  => 'themes.php',
            // Parent menu slug.
            'capability'   => 'edit_theme_options',
            // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true,
            // Show admin notices or not.
            'dismissable'  => true,
            // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',
            // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,
            // Automatically activate plugins after installation or not.
            'message'      => '',
            // Message to output right before the plugins table.

            /*
            'strings'      => array(
                'page_title'                      => __( 'Install Required Plugins', 'theme-slug' ),
                'menu_title'                      => __( 'Install Plugins', 'theme-slug' ),
                'installing'                      => __( 'Installing Plugin: %s', 'theme-slug' ), // %s = plugin name.
                'oops'                            => __( 'Something went wrong with the plugin API.', 'theme-slug' ),
                'notice_can_install_required'     => _n_noop(
                    'This theme requires the following plugin: %1$s.',
                    'This theme requires the following plugins: %1$s.',
                    'theme-slug'
                ), // %1$s = plugin name(s).
                'notice_can_install_recommended'  => _n_noop(
                    'This theme recommends the following plugin: %1$s.',
                    'This theme recommends the following plugins: %1$s.',
                    'theme-slug'
                ), // %1$s = plugin name(s).
                'notice_cannot_install'           => _n_noop(
                    'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
                    'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
                    'theme-slug'
                ), // %1$s = plugin name(s).
                'notice_ask_to_update'            => _n_noop(
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                    'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                    'theme-slug'
                ), // %1$s = plugin name(s).
                'notice_ask_to_update_maybe'      => _n_noop(
                    'There is an update available for: %1$s.',
                    'There are updates available for the following plugins: %1$s.',
                    'theme-slug'
                ), // %1$s = plugin name(s).
                'notice_cannot_update'            => _n_noop(
                    'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
                    'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
                    'theme-slug'
                ), // %1$s = plugin name(s).
                'notice_can_activate_required'    => _n_noop(
                    'The following required plugin is currently inactive: %1$s.',
                    'The following required plugins are currently inactive: %1$s.',
                    'theme-slug'
                ), // %1$s = plugin name(s).
                'notice_can_activate_recommended' => _n_noop(
                    'The following recommended plugin is currently inactive: %1$s.',
                    'The following recommended plugins are currently inactive: %1$s.',
                    'theme-slug'
                ), // %1$s = plugin name(s).
                'notice_cannot_activate'          => _n_noop(
                    'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
                    'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
                    'theme-slug'
                ), // %1$s = plugin name(s).
                'install_link'                    => _n_noop(
                    'Begin installing plugin',
                    'Begin installing plugins',
                    'theme-slug'
                ),
                'update_link' 					  => _n_noop(
                    'Begin updating plugin',
                    'Begin updating plugins',
                    'theme-slug'
                ),
                'activate_link'                   => _n_noop(
                    'Begin activating plugin',
                    'Begin activating plugins',
                    'theme-slug'
                ),
                'return'                          => __( 'Return to Required Plugins Installer', 'theme-slug' ),
                'plugin_activated'                => __( 'Plugin activated successfully.', 'theme-slug' ),
                'activated_successfully'          => __( 'The following plugin was activated successfully:', 'theme-slug' ),
                'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'theme-slug' ),  // %1$s = plugin name(s).
                'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'theme-slug' ),  // %1$s = plugin name(s).
                'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'theme-slug' ), // %s = dashboard link.
                'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'tgmpa' ),

                'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            ),
            */
        );

        tgmpa( $plugins, $config );
    }
    add_action( 'tgmpa_register', 'evolve_register_suggested_plugins' );