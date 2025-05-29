<?php
namespace Dgpc\Func;

/**
 * $api_key need to change according to the plugin
 * $status need to change according to the plugin
 */

function dg_plugin_update($checked_data) {
    global $wp_version;
    $api_url = "https://api.divigear.com/";
    $option = get_option('dg_settings');
    $plugin_slug = basename(dirname(__DIR__));
    $array_key = $plugin_slug .'/'. $plugin_slug .'.php';

    // need to change according to the plugin
    $api_key = isset($option['dgpc_license_key_setting']) ? $option['dgpc_license_key_setting'] : '';
    $status = isset($option['dgpc_license_key_status']) ? $option['dgpc_license_key_status'] : '';

    //Comment out these two lines during testing.
    if (empty($checked_data->checked)) {
        return $checked_data;
    }
    if (!array_key_exists($array_key, $checked_data->checked ) ) {
        return $checked_data;
    }

    $args = array(
        'slug' => $plugin_slug,
        'version' => $checked_data->checked[$plugin_slug .'/'. $plugin_slug .'.php'],
        'apikey' => $api_key,
        'status' => $status,
        'file_name' => $plugin_slug
    );
    $request_string = array(
        'body' => array(
            'action' => 'basic_check',
            'request' => serialize($args),
            'api-key' => md5(get_bloginfo('url'))
        ),
        'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
        );

    // Start checking for an update
    $raw_response = wp_remote_post($api_url, $request_string);

    if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
        $response = unserialize($raw_response['body']);

    if (is_object($response) && !empty($response)) // Feed the update data into WP updater
        $checked_data->response[$plugin_slug .'/'. $plugin_slug .'.php'] = $response;

    return $checked_data;
}


function dg_plugin_notice() {
    $plugin_slug = basename(dirname(__DIR__));
    $required_version = '3.2';
    $class = 'notice is-dismissible notice-error';

    if ( is_admin() ) {
        if ( !function_exists('get_plugin_data')) {
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        } 
        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__). '/'.$plugin_slug.'.php');
        $divi_theme = wp_get_theme( 'Divi' );
        $theme_name = $divi_theme->get( 'Name' );
        $theme_version = $divi_theme->get( 'Version' );
        $message = sprintf('%1$s Plugin Requires at least Divi %2$s or above version to work. So, please update your divi theme.', $plugin_data['Name'], $required_version );
    
        if ( version_compare( $theme_version, $required_version, '<' ) ) {
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        }
    }
}