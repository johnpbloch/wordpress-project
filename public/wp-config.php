<?php

use Dotenv\Dotenv;

// Stolen shamelessly with some modifications from Mark Jaquith's WordPress
// Skeleton project: https://github.com/markjaquith/WordPress-Skeleton

// Load composer autoloader if it's available
if ( file_exists( __DIR__ . '/../vendor/autoload.php' ) ) {
	include( __DIR__ . '/../vendor/autoload.php' );
}

$dotenv = new Dotenv( __DIR__ . '/../' );
try {
	$dotenv->load();
} catch ( Exception $e ) {
}

foreach ( $_SERVER as $key => $value ) {
	if ( 'WPC_' === substr( $key, 0, 4 ) ) {
		define( substr( $key, 4 ), env( $key ) );
	} elseif ( 'MEMCACHED_SERVERS' === $key ) {
		global $memcached_servers;
		$memcached_servers = json_decode( env( $key ) );
	} elseif ( 'WP_PREFIX' === $key ) {
		global $table_prefix;
		$table_prefix = env( $key );
	} elseif ( 'WPMS_BASE' === $key ) {
		global $base;
		$base = env( $key );
	}
}

// ========================
// Custom Content Directory
// ========================
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WP_CONTENT_DIR', __DIR__ . '/content' );
}
if ( ! defined( 'WP_CONTENT_URL' ) ) {
	define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content' );
}

// ===========
// Hide errors
// ===========
ini_set( 'display_errors', 0 );

// ===================
// Bootstrap WordPress
// ===================
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/wp/' );
}
require_once( ABSPATH . 'wp-settings.php' );
