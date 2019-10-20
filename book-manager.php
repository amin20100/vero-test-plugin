<?php
/**
 * Plugin Name: Book Manager
 * Description: A simple book manager plugin
 * Plugin URI:  https://amin.nz
 * Version:     1.0
 * Author:      Amin A. Rezapour
 * Author URI:  https://amin.nz
 * License:     MIT
 * Text Domain: dw-books
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

if ( ! defined( 'DW_PLUGIN_FILE' ) ) {
	define( 'DW_PLUGIN_FILE', __FILE__ );
}

// Include the main DW_Books class.
if ( ! class_exists( 'DW_Books', false ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-dw-books.php';
}

// Global for backwards compatibility.
$GLOBALS['dw_books'] = DW_Books::instance();

