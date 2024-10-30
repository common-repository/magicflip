<?php
/**
 * Plugin Name: MagicFlip
 * Plugin URI: https://avirtum.com/magicflip/
 * Description: MagicFlip is a plugin that enables you add pdf documents & books to your WordPress website.
 * Version: 1.0.0
 * Requires at least: 4.6
 * Requires PHP: 7.0
 * Author: Avirtum
 * Author URI: https://avirtum.com
 * License: GPLv3
 * Text Domain: magicflip
 * Domain Path: /languages
 */
namespace MagicFlip;

defined( 'ABSPATH' ) || exit;

define( 'MAGICFLIP_PLUGIN_NAME', 'magicflip' );
define( 'MAGICFLIP_PLUGIN_VERSION', '1.0.0' );
define( 'MAGICFLIP_SHORTCODE_NAME', 'magicflip' );
define( 'MAGICFLIP_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'MAGICFLIP_PLUGIN_FILE', __FILE__ );
define( 'MAGICFLIP_PLUGIN_BASE_NAME', plugin_basename(__FILE__) );

spl_autoload_register(function($class) {
    $prefix = __NAMESPACE__;
    $base = __DIR__ . '/includes/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

Plugin::run();