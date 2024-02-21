<?php
// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
define( 'FORCE_SSL_ADMIN', true ); // Redirect All HTTP Page Requests to HTTPS - Security > Settings > Enforce SSL
// END iThemes Security - Do not modify or remove this line
//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
define( 'WP_CACHE', false ); // Added by WP Rocket
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
define( 'DB_NAME', 'worldwideauctioneers-wp-DvsBwaAu' );
/** MySQL database username */
define( 'DB_USER', 'dHnkrgOBj0ng' );
/** MySQL database password */
define( 'DB_PASSWORD', 'M*eAc6UXX6dp!g1H' );
/** MySQL hostname */
define( 'DB_HOST', 'localhost' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'C5k^W,?mFpvp^ENdpn%xd t4fu:JI/ki_o)z4,B5k_-`Ug0]W9g(UBlv,y}#jk(`' );
define( 'SECURE_AUTH_KEY',   '6v/Sd1=k8mo!M6BRK-W^4eh#Pg4]{S~D5N6B1VDPA [A(M,I;Tv-/CMR8M$/zhfx' );
define( 'LOGGED_IN_KEY',     'jc%SJvI.o~U]Xe=}Ed@Xg5:Ob7A7328-Fa4X?t/XjB3iL7%z`dR~D!x )e+eS9TL' );
define( 'NONCE_KEY',         '!wGxk&HhO{nf(_|dPlu^y^grNx(wWK:_S/D8##F*}0%jLOH A.zJ[~8f@jma!R?x' );
define( 'AUTH_SALT',         '=x1a}!ie1[G;LM 4`M9nx] ca<h_jR:JgT)ao,V&$^@L=8i=adhVG~u=5F6ddNgo' );
define( 'SECURE_AUTH_SALT',  '/1Ji(NX#x2hHueVJ-31x$%UQ#cy-$xUzqp!a miKPxloWz(rHRDTIMdo}Qn^ COP' );
define( 'LOGGED_IN_SALT',    'GXn9Pd@<<H;cuqb/cOqOZvOccMFltO1_RL}!w>tZ|eHsv[2Qx<Y]iBh47LJEEDZD' );
define( 'NONCE_SALT',        '}*/()iZO>/to2b->c__FNgL*Z2pB-@xRqyziBBXB|8)*cyfPLdhO$4nwFI*%)Y*^' );
define( 'WP_CACHE_KEY_SALT', 'ncYWTCGkx}|&}Roek&+bs[*X|eG_:~+Av~Zz~L,/NOea7CX8G<,B,r)R]{V7/}B(' );
define('WP_SITEURL', 'https://listings.worldwideauctioneers.com/');
define('WP_HOME', 'https://listings.worldwideauctioneers.com/');
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_b2984daf74_';
/* Change WP_MEMORY_LIMIT to increase the memory limit for public pages. */
define('WP_MEMORY_LIMIT', '1024M');
/* Uncomment and change WP_MAX_MEMORY_LIMIT to increase the memory limit for admin pages. */
//define('WP_MAX_MEMORY_LIMIT', '1024M');
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_LOG', true );
// define( 'WP_DEBUG_DISPLAY', true );
// define( 'SCRIPT_DEBUG', true );
ini_set('display_errors', 0);
