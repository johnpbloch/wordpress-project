<?php

use Dotenv\Dotenv;

// Stolen shamelessly with some modifications from Mark Jaquith's WordPress
// Skeleton project: https://github.com/markjaquith/WordPress-Skeleton

// Load composer autoloader if it's available
if ( file_exists( __DIR__ . '/../vendor/autoload.php' ) ) {
	require_once __DIR__ . '/../vendor/autoload.php';
}

$dotenv = Dotenv::createImmutable( __DIR__ . '/../' );
$dotenv->load();
$dotenv->required( [ 'WPC_DB_HOST', 'WPC_DB_NAME', 'WPC_DB_USER', 'WPC_DB_PASSWORD' ] );
$dotenv->ifPresent( 'ALLOW_INSECURE' )->isBoolean();
unset( $dotenv );

collect( $_ENV )->keys()
	->filter( fn( $k ) => starts_with( $k, 'WPC_' ) )
	->map( fn( $k ) => substr( $k, 4 ) )
	->each( fn( $k ) => defined( $k ) || define( $k, env( "WPC_$k" ) ) );

if ( ! env( 'ALLOW_INSECURE', false ) ) {
	$_SERVER['HTTPS']          = 'on';
	$_SERVER['SERVER_PORT']    = '443';
	$_SERVER['REQUEST_SCHEME'] = 'https';
}
$table_prefix = env( 'TABLE_PREFIX', 'wp_' );

// ===============================================
// No file edits unless explicitly allowed in .env
// ===============================================
if ( ! defined( 'DISALLOW_FILE_MODS' ) ) {
	define( 'DISALLOW_FILE_MODS', true );
}

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', __DIR__ . '/content' );
$scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
define( 'WP_CONTENT_URL', "$scheme://$host/content" );

// ===========
// Hide errors
// ===========
ini_set( 'display_errors', 0 );

// ======================================
// Load a Memcached config if we have one
// ======================================
if ( file_exists( __DIR__ . '/../memcached.php' ) ) {
	$memcached_servers = require_once __DIR__ . '/../memcached.php';
}

// ===================
// Bootstrap WordPress
// ===================
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/wp/' );
}
require_once ABSPATH . 'wp-settings.php';
