<?php

/**
 * I-Refer
 *
 * @package   i-Refer
 * @author    i-Refer <wcd@i-refer.global>
 * @license   LGPLv2.1
 * @link      https://i-refer.app
 */
namespace I_Refer\Backend;

use I_Refer\Engine\Base;

/**
 * Activate and deactive method of the plugin and relates.
 */
class ActDeact extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return;
		}
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param bool|null $network_wide True if active in a multiste, false if classic site.
	 * @since 2.0.0
	 * @return void
	 */
	public static function activate( $network_wide ) {
        update_option("i-refer-api-url",IREFER_API_URL);
        irefer_send_log('trying to activate irefer plugin', irefer_get_vendor_info());

        $is_success = self::IREFER_create_woo_api();
        if (!$is_success) {
            wp_die(
                __('Unable to activate plugin. Please contact iRefer staff for more information', 'i-refer'),
                __('Error', 'i-refer'),
                array(
                    'link_text' => 'Go back to plugins page',
                    'link_url' => admin_url('plugins.php')
                )
            );
        }

        // Clear the permalinks
        \flush_rewrite_rules();
	}

    /**
     * Creates new Woocomerce API and sends it to the Integrated Server
     *
     * @param bool|null $network_wide True if active in a multiste, false if classic site.
     * @since 2.0.0
     * @return void
     */
    private static function IREFER_create_woo_api(){
        try {
            $admin_user = self::get_first_admin_user();
            if (!$admin_user) {
                throw new \Exception('No admin user found.');
            }

            if (get_option("i-refer-api-keys")) {
                return true; // API keys already generated.
            }

            list($consumer_key, $consumer_secret) = self::generate_woocommerce_api_keys($admin_user);
            self::store_api_keys_option($consumer_key, $consumer_secret);
            self::send_keys_to_external_server($consumer_key, $consumer_secret);
            return true;
        } catch (\Exception $e) {
            error_log('Activation Error: ' . $e->getMessage());
            irefer_deactivate_and_send_log('Activation Error: ' . $e->getMessage());
            return false;
        }
    }

    private static function get_first_admin_user() {
        $admin_users = get_users(['role' => 'Administrator', 'number' => 1]);
        return $admin_users[0] ?? false;
    }

    private static function generate_woocommerce_api_keys($admin_user) {
        $consumer_key = 'ck_' . wc_rand_hash();
        $consumer_secret = 'cs_' . wc_rand_hash();
        $data = [
            'user_id' => $admin_user->ID,
            'description' => esc_html__("I-Refer", "i-refer"),
            'permissions' => "read_write",
            'consumer_key' => wc_api_hash($consumer_key),
            'consumer_secret' => $consumer_secret,
            'truncated_key' => substr($consumer_key, -7),
        ];
        global $wpdb;
        $wpdb->insert($wpdb->prefix . 'woocommerce_api_keys', $data);
        if (0 === $wpdb->insert_id) {
            throw new \Exception('Failed to insert WooCommerce API keys.');
        }
        return [$consumer_key, $consumer_secret];
    }

    private static function store_api_keys_option($consumer_key, $consumer_secret) {
        update_option("i-refer-api-keys", [wc_api_hash($consumer_key), $consumer_secret]);
    }

    private static function send_keys_to_external_server($consumer_key, $consumer_secret) {
        $url = get_option("i-refer-api-url");
        if (empty($url)) {
            throw new \Exception('I-Refer API URL is not set.');
        }
        $args = [
            'body' => [
                'consumer_key' => $consumer_key,
                'consumer_secret' => $consumer_secret,
                'storeurl' => esc_url(get_site_url()),
            ],
        ];
        $response = wp_remote_post($url . 'add_vendor.php', $args);
        if (is_wp_error($response)) {
            throw new \Exception('Error sending API keys to external server('.IREFER_API_URL.'): ' . $response->get_error_message());
        }
        $body = wp_remote_retrieve_body($response);
        $body_content = print_r($body,true);
        $body_content = strtolower($body_content);
        if(strpos($body_content, 'success') !== false && strpos($body_content, 'update keys') !== false){
            throw new \Exception('Error sending API keys to external server('.IREFER_API_URL.'): ' . $body_content);
        }
        else{
            return true;
        }
    }

	/**
	 * Fired when the plugin is deactivated.
     *
	 * @since 2.0.0
	 * @return void
	 */
	public static function deactivate( bool $network_wide ) {
        deactivate_plugins(plugin_basename( __FILE__ ));
        delete_option("i-refer-api-url");
        delete_option("i-refer-api-keys");
        self::delete_woocommerce_api_keys();
        // Clear the permalinks
        \flush_rewrite_rules();
	}

    /**
     * Deletes the WooCommerce API keys created during activation.
     *
     * @since 2.0.0
     * @return void
     */
    private static function delete_woocommerce_api_keys() {
        $api_keys = get_option("i-refer-api-keys");
        if (!$api_keys) {
            return;
        }
        list($consumer_key_hash, $consumer_secret) = $api_keys;
        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'woocommerce_api_keys', ['consumer_key' => $consumer_key_hash]);
    }

}
