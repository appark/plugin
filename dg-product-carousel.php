<?php
/*
Plugin Name: Divi Product Carousel
Plugin URI:  https://www.divigear.com/
Description: A woocommerce product carousel module for DIVI
Version:     2.0.2
Author:      DiviGear
Author URI:  https://www.divigear.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: et_builder
Domain Path: /languages
*/


define ('DGPC_DIR_PATH', plugin_dir_path( __FILE__ ));
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function dgpc_initialize_extension() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/ProductCarousel.php';
}
add_action( 'divi_extensions_init', 'dgpc_initialize_extension' );

function dgpc_scripts(){
    wp_enqueue_style('swipe-style', trailingslashit(plugin_dir_url(__FILE__)) .  'styles/swiper.min.css');
    wp_enqueue_script('swipe-script', trailingslashit(plugin_dir_url(__FILE__)) . 'scripts/swiper.min.js' , array('jquery'), '1.0.0', true );
}
add_action('wp_enqueue_scripts', 'dgpc_scripts');

// settings page
require_once (__DIR__ . '/core/init.php');
// functions
require_once DGPC_DIR_PATH . '/functions/functions.php';

if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) :
    add_action( 'admin_notices', 'dgpc_plugin_woo_activation_notice' );
    function dgpc_plugin_woo_activation_notice(){
        $class = 'notice is-dismissible notice-error';
        $message = "Please install & activate WooCommerce to 'Divi Product Carousel' work.";
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }
endif;

/**
 * Deprecated 
 * After update process complete
 */

add_action( 'plugins_loaded', 'dgpc_upgrade_function' );

function dgpc_upgrade_function( ) {

    $old_option = get_option( 'dgpc__settings' );

    if ($old_option !== false && $old_option['dgpc__text_field_1'] == 'activate' ) {
        if( get_option( 'dg_settings' ) === false ) {
            update_option('dg_settings', array(
                'dgpc_license_key_setting' => $old_option['dgpc__text_field_0'],
                'dgpc_license_key_status' => 'active'
            ));
        }
    }
    
}


