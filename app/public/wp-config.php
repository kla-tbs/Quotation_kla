<?php
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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          ';kHan@`&SlJ$<!}%gP6A=T)oZ8/)e#9#:{`+F,K1mB_N}7Te +dnG# |5AfehkU(' );
define( 'SECURE_AUTH_KEY',   'o}ZYGsBKMbcmYQi40(%{r!xs+tN:-qcU@)?5$_rhL;,N1X@NzhwY=4~R?O!;XRcu' );
define( 'LOGGED_IN_KEY',     '6K2sx*a+r&NM[Z/z~b3clHqp$j75JGE.W!L#LkA:1h4-qw^UtrX+/MbdJZjeWVyZ' );
define( 'NONCE_KEY',         '6z[0KZS~UZDe[JQk,|_]Ow1^.;,;}:{z4S0#pPH[h=e?dOkCpG?Tl~HN@svRoKK$' );
define( 'AUTH_SALT',         '[@K*?3%CvlE=%XzVzs06(!-7LPE;rr|zI8fNA_|(SpFd(={%,H4vjKsz5po}T }E' );
define( 'SECURE_AUTH_SALT',  'yq&W[9=^`@H1`{;)ixw~gGoM^4fRkOt*a1{rX]pEs39iY0$d-jFXikWW0<#TT.Ph' );
define( 'LOGGED_IN_SALT',    'eQ2Ge{Sy89zNQ`>:294e%pR|*Rn/Ml$rLBg+< ](T%$vAzVC11 m&hx(^k3w/Lgx' );
define( 'NONCE_SALT',        '+x%`bVS:xJ/r@_fOwz(H+[<R`p!kgsNlL9]sd:6<?Q4P*?j$)3)yF4%~H//(9QFs' );
define( 'WP_CACHE_KEY_SALT', '0zqk%E[$(;R0}[EY9gFotXdfB7|[:>).7 +LDVLcjGwRV]2rq`A98S,/CR7[M:E<' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
