<?php
define("ADMIN_COOKIE_PATH", "/pp");
define('WP_CACHE', true); // WP-Optimize Cache
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'epiz_32732388_w212' );
/** Database username */
define( 'DB_USER', '32732388_3' );
/** Database password */
define( 'DB_PASSWORD', 'So@72gp@d9' );
/** Database hostname */
define( 'DB_HOST', 'sql106.byetcluster.com' );
/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'fz5b4sf5jqqspkfzc5yrhrddls2hcwsgydi3caepia9wzblu0lkhcrinidnuac6d' );
define( 'SECURE_AUTH_KEY',  'ffjjkhuklxpbv1gyl5z8nvrbjnpj1vmyq55azjpzlyzjkamze8khauvxbnojxpgo' );
define( 'LOGGED_IN_KEY',    'j6kmunxt07ms4uvzcty3sqtg9ejlxe0zaxmworsmoqokjwhb37s93id5byitjstb' );
define( 'NONCE_KEY',        'xlwsxazrbu36fpnvn4bwsbevp4whlk2l7zagthibymt8dbajtcq18zolgkav1dqy' );
define( 'AUTH_SALT',        'pz4ruygesitgajzevzhnw9ip2o1cpacyahl2ugupa0l1as1mliinxlezsytyj8im' );
define( 'SECURE_AUTH_SALT', 'kua5ztqjvxxhwyqfs5xppwphlvdzo1qoogrioisknzuvkln5rzjjxxlv4k9zqygq' );
define( 'LOGGED_IN_SALT',   'mokndwgljv4plpcacrlpjmqyfbk3jprhuunfqbwh7p4rfjeimxlbbrejj1ndyxbz' );
define( 'NONCE_SALT',       'z16u46awmoyc0qsclcpina95o1iy8xagii7wtnyvclznqbhwfiujcnckk2goj9ag' );
/**#@-*/
/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpdg_';
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
@ini_set( 'display_errors', 0 );
/* Add any custom values between this line and the "stop editing" line. */
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';