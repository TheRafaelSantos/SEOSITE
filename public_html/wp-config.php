<?php
define( 'WP_CACHE', true );

 // Added by WP Rocket

define('DISABLE_WP_CRON', true);

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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u537378318_newsoul' );

/** Database username */
define( 'DB_USER', 'u537378318_newsoul' );

/** Database password */
define( 'DB_PASSWORD', '@Shell3232dl' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          '3?UeA} 78%I83FVQ[l:.~<ay?x_ua/;x0T|.BU1ab9co*}j|eWcT8Jo:zxblm ]0' );
define( 'SECURE_AUTH_KEY',   '|}O*5w_N@e]W`#xqmX9np6B0AekJs#~h*{jWnYp@PzIK!2d!Q)&z%IX3ZehbQ9~!' );
define( 'LOGGED_IN_KEY',     'I9yb4Tn2,}Tm b,SXUtvbV:N2;y6$|3Vpl#_2.{lqqAST=<Du}`DWXd}sAbG9x;3' );
define( 'NONCE_KEY',         ':#<L9|GW{b~clvH`Ttnbj?8L=vCd{=Q_9JfGq{;+XY> {*~sDk?-sdL@<w$-J*=>' );
define( 'AUTH_SALT',         '1K(ZZ-ay+L(Am.GfS?O`)Re!nYiDe{uBX:%PIK_G?$%8jP}`a}0)3b*rMf*2J302' );
define( 'SECURE_AUTH_SALT',  '#~xR(?}V1=NE5EfBE!4@Zr!31nFkc~+m,^Td<!svAc))]/lHCF&:wq1M^e>}:Gw:' );
define( 'LOGGED_IN_SALT',    ';i<i!ftmp$.LoCy`Rv-bzK?&HR4p*t,N+RMln3E//`T`f=s2/;Q-kWaQopZ|:<*>' );
define( 'NONCE_SALT',        '-W)~gk8WxH(yBdTe3xBy7JVIL=5VL`[#v[>/Vo:z4|n#C6<A~yr0IbI7/i!~,6hG' );
define( 'WP_CACHE_KEY_SALT', 'RPRudw9*Nh,z7;<uPf_N260[ms]Q,+n6/iy1MxZfg;:X&gC?G8GpaOWz3.Io(MWM' );


/**#@-*/

/**
 * WordPress database table prefix.
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


/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', false );
/* That's all, stop editing! Happy publishing. */
define('ALLOW_UNFILTERED_UPLOADS', true);

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
