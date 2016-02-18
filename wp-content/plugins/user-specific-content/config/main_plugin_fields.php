<?php 
/* section main */
if ( !defined('ABSPATH')) exit;

$setting = $p->add_section(array(
	'option_group'      =>  'U_S_C',
	'sanitize_callback' => null,
	'id'                => 'U_S_C', 
	'title'             => __('General settings:','bauspc')
	)
);

//textarea field
$p->add_field(array(
    'label'   => __('Global Blocked message:','bauspc'),
    'std'     => '',
    'id'      => 'b_massage',
    'type'    => 'textarea',
    'section' => $setting,
    'desc'    => __('(if set in a metabox the it overwrites this message for that secific post/page)','bauspc')
    )
);

//checkbox field
$p->add_field(array(
    'label'   => __('Use with "the_content" hook?','bauspc'),
    'std'     => true,
    'id'      => 'run_on_the_content',
    'type'    => 'checkbox',
    'section' => $setting,
    'desc'    => __('(default checked)','bauspc')
    )
);

$p->add_field(array(
    'label'   => __('Use with "the_excerpt" hook?','bauspc'),
    'std'     => false,
    'id'      => 'run_on_the_excerpt',
    'type'    => 'checkbox',
    'section' => $setting,
    'desc'    => __('(check to make plugin run on archive / tags / category pages default unchecked)','bauspc')
    )
);

$setting2 = $p->add_section(array(
	'option_group'      =>  'U_S_C',
	'sanitize_callback' => null,
	'id'                => 'U_S_C_metabox', 
	'title'             => __('MetaBox settings','bauspc')
	)
);

//checkbox field
$p->add_field(array(
    'label'   => __('list user names?','bauspc'),
    'std'     => true,
    'id'      => 'list_users',
    'type'    => 'checkbox',
    'section' => $setting2,
    'desc'    => __('(default checked) sites with a large number of users should uncheck this option','bauspc')
    )
);
//select field
$p->add_field(array(
    'label'   => __('User List Type','bauspc'),
    'std'     => 'checkbox',
    'id'      => 'user_list_type',
    'type'    => 'select',
    'section' => $setting2,
    'options' => array(__('Select','bauspc')=>'select',__('Checkboxes','bauspc') => 'checkbox'),
    'desc'    => __('Select the field type of the user list','bauspc')
    )
);


$p->add_field(array(
    'label'   => __('list user roles?','bauspc'),
    'std'     => true,
    'id'      => 'list_roles',
    'type'    => 'checkbox',
    'section' => $setting2,
    'desc'    => __('(default checked) sites with a large number of roles should uncheck this option','bauspc')
    )
);

//select field
$p->add_field(array(
    'label'   => __('User Roles List Type','bauspc'),
    'std'     => 'checkbox',
    'id'      => 'user_role_list_type',
    'type'    => 'select',
    'section' => $setting2,
    'options' => array(__('Select','bauspc')=>'select',__('Checkboxes','bauspc') => 'checkbox'),
    'desc'    => __('Select the field type of the user list','bauspc')
    )
);

global $wp_roles;
$capabilities = array();
foreach ($wp_roles->roles as $role => $r) {
	foreach ((array)$r['capabilities'] as $key => $value) {
		$capabilities[$key] = $key;
	}
}
//select field
$p->add_field(array(
    'label'   => __('Capability','bauspc'),
    'std'     => 'manage_options',
    'id'      => 'capability',
    'type'    => 'select',
    'section' => $setting2,
    'options' => $capabilities,
    'desc'    => __('Capability needed to see and manage the metabox','bauspc')
    )
);

$args = array(
   'public'   => true,
   '_builtin' => false
);
$output = 'names'; // names or objects, note names is the default
$operator = 'and'; // 'and' or 'or'
$post_types = get_post_types( $args, $output, $operator ); 
foreach(array(__('Posts') => 'post',__('Pages') => 'page') as $k => $post_type){
	$p->add_field(array(
		'label'   => 'Enable on  '.$k,
		'std'     => true,
		'id'      => 'posttypes]['.$post_type,
		'type'    => 'checkbox',
		'section' => $setting2,
		)
	);
}

foreach ( $post_types  as $k => $post_type ) {
	$p->add_field(array(
		'label'   => 'Enable on  '.$post_type.' post type',
		'std'     => true,
		'id'      => 'posttypes]['.$post_type,
		'type'    => 'checkbox',
		'section' => $setting2,
		)
	);
}