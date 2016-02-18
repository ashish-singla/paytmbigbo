<?php
$quiz_embeder_db_version = "1.0";

function quiz_embeder_new_db_install()
{

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;
	$quiz_embeder_group_table=$wpdb->prefix."quiz_embeder_group";
	$quiz_embeder_group_users_table=$wpdb->prefix."quiz_embeder_group_users";
	
		  
   $quiz_embeder_group_sql = "CREATE TABLE IF NOT EXISTS `$quiz_embeder_group_table` (
		  `id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
   dbDelta($quiz_embeder_group_sql);
   
	
	$quiz_embeder_group_users_sql = "CREATE TABLE IF NOT EXISTS `wp_quiz_embeder_group_users` (
		  `id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `group_id` bigint(20) NOT NULL,
		  `user_id` bigint(20) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
	
	dbDelta($quiz_embeder_group_users_sql);
	

}#end function quiz_embeder_new_db_install

function quiz_embeder_db_update()
{
#DB Table structure update process will run here if an old  DB version already exists 
#This function will check all required tables are exists or not.
#if all required tables are exists , only updated SQL command will run here.
#otherwise it will run New installation function. 
wp_die("quiz_embeder_db_update process needed. I came from function quiz_embeder_db_update() in activate.php in 'Insert or Embed Articulate Content into Wordpress' plugin");
}

function quiz_embeder_install() {
   global $wpdb;
   global $quiz_embeder_db_version;
   $stored_quiz_embeder_db_version=get_option('quiz_embeder_db_version');
   if(!$stored_quiz_embeder_db_version) #completly new db create.
   {
   quiz_embeder_new_db_install();
   add_option("quiz_embeder_db_version", $quiz_embeder_db_version);
   }
   else
   {
		$version_comp=version_compare($stored_quiz_embeder_db_version, $quiz_embeder_db_version);
		if ($version_comp ==0)#if same version
		{
			#no change , just add new tables if not exists
			
			quiz_embeder_new_db_install();# New installation process checks all tables are exists or not
		}
		elseif($version_comp < 0)
		{
		 quiz_embeder_db_update();
		}
		else
		{
		wp_die("Plugin Installation Fail: You are trying to install this  plugin with DB version ".$quiz_embeder_db_version." But A Grater Version ".$stored_quiz_embeder_db_version." is found");
		}
  
    update_option("quiz_embeder_db_version", $quiz_embeder_db_version);
   }#end of if(!$stored_quiz_embeder_db_version)
     
 
  
}#end function quiz_embeder_install()

# helps: http://oneTarek.com , http://php.net/manual/en/function.version-compare.php
function quiz_embeder_update_db_check() {
    global $quiz_embeder_db_version;
    if (version_compare(get_option('quiz_embeder_db_version'), $quiz_embeder_db_version)<0)
	{
		quiz_embeder_db_update();      
    }

}
add_action('plugins_loaded', 'quiz_embeder_update_db_check'); 
?>