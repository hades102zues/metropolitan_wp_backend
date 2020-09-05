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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

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
define('AUTH_KEY',         'pyRkjZe0AUdSe0WiiHjmcqI9rsyMI+ERWWWsPDT5lYJRkAbDG2ae5+ulPQB3HEWm0t7BRXRRHkGYCvLTUS9qWg==');
define('SECURE_AUTH_KEY',  '6DsbXHp8tk2aSgCv0piXfyTKZ2B3WA/jD/MGYrVeJuHRS2JoC3M+bILW/u48Zobz9f0U7NS9+Sz+/PrcjvcsSg==');
define('LOGGED_IN_KEY',    'aBG7eMvUrEiA/z/f6ibX3WD2vpXvRt3rTYo4Qk/9B1AkFdhmOW8XNtC8+k4xEZsgNPzOQIGDuzzrElD3mJb+hg==');
define('NONCE_KEY',        'SG6wp6kPrhDGCeeOKmWK2WCZqUMXBOSu2G4VN56RvHVa4lzbqgZnkiTT0pGEK4hkzDVluHjL3H77booPFDEIww==');
define('AUTH_SALT',        'Iw1pPT5MeDtzKpadh/3k3F/Kgrt/sspnRL7Ghd4YAd39NbCGXpvd2ukN/wXwmPNOTaZfbs2ekOVqnFVylDC6GA==');
define('SECURE_AUTH_SALT', 'RaJd+6dQq5hoOdBrNgQNMRRyQD1HOwNP4BCPvxqKGrVWHRIcoBu4ygqmkEg4H7OAiAHrXlrfzM+ygnMI8fmzbw==');
define('LOGGED_IN_SALT',   'QaTOiBGFqaRgxCyyr1WDXZPrhPlMWvE1QZIAHEXwPwvKQk+dnaouWqzpvQtJd0QLMzyZhD3V6Jsi899zTsFFqA==');
define('NONCE_SALT',       'wTUcRDvcIWGMm0myTtf6OhOHYOXwC0Gp7UWALcXQZC+BXFpEvPGfMdD/hEGa1Jh4nIZ1JBiLOk/QXVO8LQN5Yw==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
define('FS_METHOD','direct');
