<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'paytmbigb');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'V%4Z?fMVo^-U=kS8H<.{SFY.~u[y1Qj1K+8gh~H6D-[-!VFp+^}mU.Asc}YY**3d');
define('SECURE_AUTH_KEY',  'DW+F-PuTQ[j(0N~dE<O/z_{Y*HQg9EyRdb6#]D-PG=O^xTh.L)||4ThWX5L+_+i_');
define('LOGGED_IN_KEY',    '$Q +R12rpNyIYu?[$fFBL(3O{*Q&5{ETKWhuX0$(GLt/QC*elY#%52:ef2iM-wS9');
define('NONCE_KEY',        'yQ(=O# I6twTf/0{`&Hlj$&my2rB_L>No{Tv;IRzF.c&4F_Y+|6q|?!]DmJV ZrI');
define('AUTH_SALT',        '<~@kD+h3RKX#LiCaFqrAP aOLdKC~cf<`W/uTS4$Us.K<VX~gZ.)bMOhIC0k9V/L');
define('SECURE_AUTH_SALT', '90l1{n dYsZ=?$L6zsys%w}]R)W%6ih=y#UnDE 0iUX5AV{m>b@St9Sq`JM+*Gij');
define('LOGGED_IN_SALT',   '[doBW}=K},9`bKP/^oV$]-1So35i|g{@m^5>4x/yCO)K@yDUV]lQ?<t4,dqqW`O4');
define('NONCE_SALT',       'gL*inEVO(|aQuJE:iSkO6fmH)RxQ3qBi_d,?@vNwj2G21t]3F9PR@|Zy#B?Bu,%[');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache
 /** Enable W3 Total Cache Edge Mode */ 
define('W3TC_EDGE_MODE', true);


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
