<?php
namespace MagicFlip;

final class Plugin {
    public static function run() {
        static $instance = false;
        $instance = $instance ? $instance : new Plugin();
    }
    private function __construct() {
        add_action( 'plugins_loaded', [ $this, 'localization' ] );
        add_action( 'after_setup_theme', [ $this, 'init' ] );
    }
    public function localization() {
        load_plugin_textdomain('magicflip', false, dirname(MAGICFLIP_PLUGIN_BASE_NAME) . '/languages/');
    }
    public function init() {
        if( !is_admin() ) {
            new Frontend();
        }
    }
}