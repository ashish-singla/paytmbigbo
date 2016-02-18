<?php
/*
we need some settings in this seperate file because, our locker.php need some settings without whole wordpress loading.
and this same settings is being used in whole wordpress

we  need WP_UPLOADS_DIR_NAME because wordpress uploades directory may be different in some case, 
we need this to decleard here becaue locker.php will be called without wordpress.
*/
define('WP_CONTENT_DIR_NAME','wp-content'); #CHANGE THIS IF YOUR content directory is defferent.
define('WP_UPLOADS_DIR_NAME','uploads');
define('WP_QUIZ_EMBEDER_UPLOADS_DIR_NAME','articulate_uploads');
define('WP_QUIZ_EMBEDER_CAPABILITY','edit_posts');
?>