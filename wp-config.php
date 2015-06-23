<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp');

/** MySQL database username */
define('DB_USER', 'wp');

/** MySQL database password */
define('DB_PASSWORD', 'wp');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'Ew>U3bw=qT=NRNU5H^oswc&|vzr1AwW~TAf#A`L3lH;)4P]>O$vrPCUV;+9R^N&u');
define('SECURE_AUTH_KEY',  'Sq<FGKun|b%fp1xylu&%p$PoblS]Z9Fg]o]eM0pzt9+^vJ&6C=Zqzjvz#gC`T> !');
define('LOGGED_IN_KEY',    '^9 XBtH,cyCt{9s($U,Mfq-v~?e))Pgu|Em-c|x1m6uTkLrMpy8MT];|;6e#GU`A');
define('NONCE_KEY',        'l`T/`_:[)LZE,2MlRIGYrzF|-a@%[z&3ZHh>WnP+sF^=W25H y;kMVQlo6,gDT.b');
define('AUTH_SALT',        '5*L41b!MxfC?4cY@-4_41N-0M@S<<z|[|:X/Jz-_S)`XRQ}KahqVc[k+:l_|B3Gm');
define('SECURE_AUTH_SALT', '{k(5%sk+u7F}KI*-h(BtP{=zh~5172?;OmH:<~Yn,Quv/+EM:O/|}/+w*v/NE%sD');
define('LOGGED_IN_SALT',   '-Z/-ieBCr n?K%m3}t26]:3}hwV[Ts&%5v9|n>-rmI],9`+a!N{}~8Ooavqn0Tnz');
define('NONCE_SALT',       'F!PxU8tFMH.txQ1=fLXc:1RD58/:oqGcqT,/]-7h=>/$g=q!7v$CuwiD+I-|K*`$');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
