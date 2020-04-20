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
define( 'FS_METHOD', 'direct' );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dukcapil' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'OmBudi91!@' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'v5#Xuw~fYu)ul<3oLHj7jGlpwaAZ,)qmvf?UX+9e>;[iNEqXO!*dKKr`4GDK4W*|' );
define( 'SECURE_AUTH_KEY',  'PI3})sSx[18v0U$;C@Yuy%6e4=>z0>p26G!pxh&UfDcBV{HS}1<c|,<4]ea_l_,(' );
define( 'LOGGED_IN_KEY',    'A%7ie9*W*[{:~C(|6a7*D4)R% p:1oeiI>s6s+j*8G#ldcC;IoP%!bQIjNN#xw5!' );
define( 'NONCE_KEY',        'p_807KO$;!FUj|b >39:mmSD=U3bi-Zl^E3w31yPX7D,*d.nPn ~5h(51A3416E<' );
define( 'AUTH_SALT',        'LOwJJ7+kY78~9?onTQLdK$L?oJd`Y~*MP4.4SiAKXX}rgjiqk?m+cYTA/,jpVj>?' );
define( 'SECURE_AUTH_SALT', '$p}1[5t9 ?_SCC$WLr,rf.:0s?jS{EdbE42@yA0VIb_1?>]Ks9K$j/_2k-s|.` f' );
define( 'LOGGED_IN_SALT',   '# RbrE|=2v<I]SL|(SclbWDL/BM_0XFLvYc,QzQ!=TbH<S.S|lZ9H@U}T#`{19sJ' );
define( 'NONCE_SALT',       ':n^E@!s!QO/7%2#k2E}*v{gywaA@9m`?ie0AJDaVORJqyC[*U}W}ME_xXEBfh:VN' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
