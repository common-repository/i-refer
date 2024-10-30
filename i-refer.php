<?php

/**
 * @package   i-Refer
 * @author    i-Refer <wcd@i-refer.global>
 * @license   LGPLv2.1
 * @link      https://i-refer.app
 *
 * Plugin Name:     i-Refer
 * Plugin URI:      https://i-refer.app/
 * Description:     i-Refer Vendor Plugin
 * Version:         2.0.8
 * Author:          i-Refer
 * Author URI:      https://i-refer.app
 * Text Domain:     i-refer
 * License:         LGPLv2.1
 * License URI:     http://www.gnu.org/licenses/lgpl-2.1.html
 * Domain Path:     /languages
 * Requires PHP:    7.4
 *
 */

if ( !defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}
define( 'IREFER_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'IREFER_PLUGIN_ABSOLUTE', __FILE__ );
require_once IREFER_PLUGIN_ROOT . 'i-refer-anti-crash.php';
define( 'IREFER_VERSION', '2.0.8' );
define( 'IREFER_TEXTDOMAIN', 'i-refer' );
define( 'IREFER_NAME', 'I-Refer' );
define( 'IREFER_MIN_PHP_VERSION', '7.4' );
define( 'IREFER_WP_VERSION', '5.3' );
define( 'IREFER_CALLBACK_URL', get_site_url() . '/wp-json/irefer/v2/update_order/' );
define( 'IREFER_REMOTE_LOG', 'https://bclney9gta.execute-api.ap-southeast-2.amazonaws.com/dev/pluginlog' );

//define( 'IREFER_API_URL', esc_url("https://merchant-integration.i-refer.app/api/v1/") );         // dev
//define( 'IREFER_PAY_URL', esc_url("https://checkout.dev.i-refer.app/summary") );              // dev
define( 'IREFER_API_URL', esc_url("https://merchant-integration.i-refer.app/api/v1/") );    // prod
define( 'IREFER_PAY_URL', esc_url("https://checkout.i-refer.app/summary") );                // prod

# LOAD LANGUAGES
add_action(
	'init',
	static function () {
		load_plugin_textdomain( IREFER_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
);

# CHECK PHP VERSION
if ( version_compare( PHP_VERSION, IREFER_MIN_PHP_VERSION, '<=' ) ) {
	add_action(
		'admin_init',
		static function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	add_action(
		'admin_notices',
		static function() {
            irefer_send_log('"I-Refer" requires PHP '.IREFER_MIN_PHP_VERSION.' or newer.', irefer_get_vendor_info());
			echo wp_kses_post(
			sprintf(
				'<div class="notice notice-error"><p>%s</p></div>',
				__( '"I-Refer" requires PHP '.IREFER_MIN_PHP_VERSION.' or newer.', 'i-refer' )
			)
			);
		}
	);
	return;
}

# CHECK WORDPRESS VERSION AND WOOCOMMERCE VERSION
$irefer_libraries = require IREFER_PLUGIN_ROOT . 'vendor/autoload.php';
$requirements = new \Micropackage\Requirements\Requirements(
    'I-Refer',
    array(
        'php'            => IREFER_MIN_PHP_VERSION,
        'php_extensions' => array( 'mbstring' ),
        'wp'             => IREFER_WP_VERSION,
        'plugins'        => array(
            array( 'file' => 'woocommerce/woocommerce.php', 'name' => 'WooCommerce', 'version' => '3.8' )
        ),
    )
);

if ( ! $requirements->satisfied() ) {
    irefer_send_log('"I-Refer" cannot be activated, some requirements are not met', irefer_get_vendor_info());
    deactivate_plugins( plugin_basename( __FILE__ ) );
    $requirements->print_notice();
    return;
}

require_once IREFER_PLUGIN_ROOT . 'functions/functions.php';


if ( !wp_installing() ) {
	register_activation_hook( IREFER_TEXTDOMAIN . '/' . IREFER_TEXTDOMAIN . '.php', array( new \I_Refer\Backend\ActDeact, 'activate' ) );
	register_deactivation_hook( IREFER_TEXTDOMAIN . '/' . IREFER_TEXTDOMAIN . '.php', array( new \I_Refer\Backend\ActDeact, 'deactivate' ) );
    add_action(
        'plugins_loaded',
        static function () use ( $irefer_libraries ) {
            # Safety Measure 2: if loading classes fails, deactivate the plugin
            try{
                new \I_Refer\Engine\Initialize( $irefer_libraries );
            }catch (Exception $e){
                irefer_deactivate_and_send_log($e->getMessage()." Stack:".$e->getTraceAsString());
                add_action(
                    'admin_notices',
                    static function() {
                        echo wp_kses_post(
                            sprintf(
                                '<div class="notice notice-error"><p>%s</p></div>',
                                __( '"I-Refer" is automatically disabled due to an unexpected error.', 'i-refer' )
                            )
                        );
                    }
                );
            }
        }
    );
}
