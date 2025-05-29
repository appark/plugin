<?php

require_once (__DIR__ . '/config.php');
require_once (__DIR__ . '/functions.php');

add_filter('pre_set_site_transient_update_plugins', '\Dgpc\Func\dg_plugin_update');

add_action( 'admin_notices', '\Dgpc\Func\dg_plugin_notice' );