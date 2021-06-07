<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'coviknow_designF' );
/** MySQL database username */
define( 'DB_USER', 'coviknow_designF' );
/** MySQL database password */
define( 'DB_PASSWORD', '123DesignF!@#' );
/** MySQL hostname */
define( 'DB_HOST', 'localhost' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'IierImG3O1PXj23LSlDuN5Raj5CDXTfOKtOiQLL5GCyMSClBsetFzbyLDUFStTK4');
define('SECURE_AUTH_KEY',  'vFTeG4GDzeKJjVfEl2Ymb9PZAAMD03l6z2oBtkXMSAb36jzLrgrMP7UBIA67T2Md');
define('LOGGED_IN_KEY',    'cZYuoK87aFwHpL7PtvTaRHy6WGfLzvw6QAvdjugg2WkbLyiEKYcQwmhlbSm6pTwn');
define('NONCE_KEY',        'NRJJjIeSX7aueNBalyYR4vyv1zDNsBSxPSEVJBA2tPchgdQHc65w0tpFRBYI2RGe');
define('AUTH_SALT',        'KezTYENJCbqi2akv9HYzBFjAagWef63jQsr5wvWkMVDffOnOHFlvo546BvxThm5L');
define('SECURE_AUTH_SALT', 'Bes5Q500E3PBqubxsyNqI1CnLh3PPGT5isSHx8saq8GhXuR0Dbw0rXASbUem9BfL');
define('LOGGED_IN_SALT',   'StMOolkPfXOfDmQL7boWV1vZirwWR3BzwOynXApOm0QFFrmjwxt1NteSbYa1eIiP');
define('NONCE_SALT',       'TB8qRhFbadG55v5n5ruPDXPDNrg9FTqOvpY2owNoX4cDU9xG7PwocO5aZAN4IpMZ');
/**
 * Other customizations.
 */
define('FS_METHOD','direct');
define('FS_CHMOD_DIR',0755);
define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');
/**
 * Turn off automatic updates since these are managed externally by Installatron.
 * If you remove this define() to re-enable WordPress's automatic background updating
 * then it's advised to disable auto-updating in Installatron.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';