<?php

# BACKUP FUNCTION AS LAST RESORT TO SHUT DOWN PLUGIN
function irefer_custom_shutdown_function() {
    $last_error = error_get_last();
    if($last_error !== null){
        irefer_send_log('Vendor has some errors. Type:'.$last_error['type']. ' File:'.$last_error['file'].' Line:'.$last_error['line'].' Message:'.$last_error['message'], irefer_get_vendor_info());

        # isolatate directory from wp-content. example: /home/user/public_html/wp-content/plugins/i-refer/ into /wp-content/plugins/i-refer/
        $isolated_file = explode('wp-content', $last_error['file']);
        $isolated_file = '/wp-content'. end($isolated_file);

        # check if error details has 'i-refer'
        if (
            strpos($isolated_file, IREFER_PLUGIN_ROOT) !== false ||
            strpos($isolated_file, 'refer') !== false ||
            strpos($isolated_file, 'IRefer') !== false ||
            strpos($last_error['message'], 'IRefer') !== false ||
            strpos($last_error['message'], 'i-refer') !== false ||
            strpos($last_error['message'], 'refer') !== false
        ) {
            # check if plugin is activated
            if (function_exists('is_plugin_active') && is_plugin_active(plugin_basename(IREFER_PLUGIN_ABSOLUTE))) {
                irefer_send_log('Error might be from i-refer plugin, disabling itself. Type:'.$last_error['type']. ' File:'.$isolated_file.' Line:'.$last_error['line'].' Message:'.$last_error['message'], irefer_get_vendor_info());
                # deactivate the plugin
                deactivate_plugins(plugin_basename(IREFER_PLUGIN_ABSOLUTE));
            }
            else{
                irefer_send_log('ATTENTION! Error might be from i-refer, recommend to remote disable or delete plugin', irefer_get_vendor_info());
            }
        }
    }
}
register_shutdown_function('irefer_custom_shutdown_function');

set_error_handler('irefer_error_handler');

function irefer_error_handler($severity, $message, $file, $line) {
    # Only handle Critical Errors
    if ($severity != E_ERROR && $severity != 1024) {
        return;
    }

    # Only handle errors that somehow originates from the plugin
    $back_trace = print_r(debug_backtrace(), true);
    if (strpos($back_trace, IREFER_PLUGIN_ROOT) !== false) {
        irefer_deactivate_and_send_log($back_trace);
        # redirect to the wp-admin page
        wp_redirect(admin_url());
        exit;
    }

    /* Return false to continue with the normal error handler if the keyword is not found */
    return false;
}

function irefer_send_log($text_message, $json_message = null){
    error_log('irefer_send_log: '.$text_message);
    $data = array(
        'logText' => $text_message,
        'logJson' => $json_message,
        'siteUrl' => get_option('siteurl')
    );

    # Low PHP and WP version compatible to send POST
    $ch = curl_init(IREFER_REMOTE_LOG);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 1); // Limit wait time for the entire operation
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1); // Limit wait time for the connection
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // No need to return the result
    curl_exec($ch);
    curl_close($ch);
}

function irefer_deactivate_and_send_log($error_msg){
    error_log('i-Refer plugin has been deactivated due to an error: '.$error_msg);
    if (function_exists('deactivate_plugins')) {
        deactivate_plugins(plugin_basename(IREFER_PLUGIN_ABSOLUTE));
    }
    irefer_send_log('i-Refer plugin has been deactivated due to an error: '.$error_msg, irefer_get_vendor_info());
}

function irefer_get_vendor_info(){
    # FUNCTION TO COLLECT VENDOR INFORMATION, INCLUDING PLUGINS INSTALLED
    $vendor_data = array();

    try{
        global $wpdb;
        $mysql_version = $wpdb->get_var("SELECT VERSION() AS version");
        $vendor_data = array(
            'vendor_site' => get_option('siteurl'),
            'vendor_name' => get_bloginfo('name'),
            'vendor_email' => get_bloginfo('admin_email'),
            'vendor_phone' => get_option('admin_phone'),
            'php_version' => phpversion(),
            'mysql_version' => $mysql_version,
            'wordpress_version' => get_bloginfo('version'),
            'woocommerce_version' => irefer_get_plugin_version('woocommerce'),
            'plugin_list_and_versions' => irefer_list_active_plugins_with_versions(),
        );
    }catch (Exception $e){
        $vendor_data['error'] = $e->getMessage();
    }
    return $vendor_data;
}

function irefer_get_plugin_version($plugin_folder_name) {
    if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $plugins = get_plugins();

    foreach ($plugins as $plugin_path => $plugin_info) {
        if (strpos($plugin_path, $plugin_folder_name.'/') === 0) {
            return $plugin_info['Version'];
        }
    }
    return $plugin_folder_name. ' plugin not found';
}

function irefer_list_active_plugins_with_versions() {
    if ( ! function_exists( 'get_plugins' ) ) {
        if (file_exists(ABSPATH . 'wp-admin/includes/plugin.php')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        } else {
            return array();
        }
    }

    if (function_exists('get_plugins')) {
        $all_plugins = get_plugins();
        $active_plugins = get_option('active_plugins');
        $active_plugins_with_versions = array();

        foreach ($active_plugins as $plugin_path) {
            if (isset($all_plugins[$plugin_path])) {
                $plugin_name = $all_plugins[$plugin_path]['Name'];
                $plugin_version = $all_plugins[$plugin_path]['Version'];
                $active_plugins_with_versions[$plugin_name] = $plugin_version;
            }
        }

        return $active_plugins_with_versions;
    } else {
        return array();
    }
}

