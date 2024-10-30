<?php
namespace MagicFlip;

class Frontend {
    public function __construct() {
        add_shortcode( MAGICFLIP_SHORTCODE_NAME, [ $this, 'shortcode' ] );
    }

    private function get_globals() {
        $globals = [
            'version' => MAGICFLIP_PLUGIN_VERSION
        ];
        return $globals;
    }

    private function init() {
        wp_enqueue_style( 'magicflip-main', MAGICFLIP_PLUGIN_URL . 'assets/css/main.css', [], MAGICFLIP_PLUGIN_VERSION );
        wp_enqueue_script( 'magicflip-main', MAGICFLIP_PLUGIN_URL . 'assets/js/main.js', ['jquery'], MAGICFLIP_PLUGIN_VERSION, true );
        wp_localize_script( 'magicflip-main', 'magicflip_globals', $this->get_globals() );
    }

    private function sanitizeWidthHeight( $value ) {
        $sanitized_value = preg_replace('/[^0-9.%pxrememvwvh]/', '', $value);

        if ( strpos($sanitized_value, '%' ) !== false ) {
            $numeric_value = (float) filter_var( $sanitized_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
            $sanitized_value = min($numeric_value, 100) . '%';
        }

        if ( strpos($sanitized_value, '.' ) !== false ) {
            $numeric_value = (float) filter_var( $sanitized_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
            $sanitized_value = round( $numeric_value, 2 );
        }

        return $sanitized_value;
    }

    private function sanitizeClass( $class ) {
        $sanitized_class = preg_replace('/[^a-zA-Z0-9-_]/', '', $class);
        return $sanitized_class;
    }

    public function shortcode( $atts = [] ) {
        $atts = array_change_key_case( $atts, CASE_LOWER );
        $defaults = [
            'src'    => null,
            'class'  => null,
            'width'  => null,
            'height' => null
        ];
        $atts = shortcode_atts( $defaults, $atts );

        if ( $atts['src'] == null ) {
            return;
        }

        $src = esc_url( sanitize_url( $atts['src'], FILTER_SANITIZE_URL ) );
        $width = $atts['width'] !== null ? esc_attr( $this->sanitizeWidthHeight( $atts['width'] ) ): null;
        $height = $atts['height'] !== null ? esc_attr( $this->sanitizeWidthHeight( $atts['height'] ) ) : null;
        $class = $atts['class'] !== null ? esc_attr( $this->sanitizeClass( $atts['class'] ) ) : null;

        $this->init();

        $inlineStyles = $width !== null ? "width:{$width};" : '';
        $inlineStyles .= $height !== null ? "height:{$height};" : '';

        return "<div class='magicflip" . ( $class !== null ? " {$class}" : "" ) . "'" .  ( !empty( $inlineStyles ) ? " style='{$inlineStyles}'" : "" ) . "data-src='{$src}'></div>";
    }
}