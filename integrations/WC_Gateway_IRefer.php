<?php

/**
 * I-Refer Payment Gateway
 *
 * Provides a iRefer Payment Gateway.
 *
 * @class       WC_Gateway_IRefer
 * @extends     WC_Payment_Gateway
 * @version     1.0.0
 * @package     I-Refer/Integrations
 */

namespace I_Refer\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WC_Gateway_IRefer extends \WC_Payment_Gateway {

    public function __construct() {
        add_filter( 'woocommerce_available_payment_gateways', array( $this, 'conditionally_hide_payment_gateways' ) );
        $this->id                 = 'irefer_payment_gateway';
        $this->icon               = apply_filters('woocommerce_irefer_icon', '');
        $this->has_fields         = true;
        $this->method_title       = __( 'iRefer', 'i-refer' );
        $this->method_description = __( 'Allows payments with iRefer.', 'i-refer' );

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->title        = $this->get_option( 'title', 'iRefer Payment Gateway' );
        $this->description  = $this->get_option( 'Transform online referrals' );

        add_action('init', [$this, 'save_refer_cookie_data'], 5);
        add_action('wp_loaded', [$this, 'save_refer_cookie_data']);
        add_action('init', [$this, 'track_referal_code_via_session']);
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

        # This code snippet only works for version 8.1 and above
        #$array1 = ['a' => 'apple', 'b' => 'banana'];
        #$array2 = ['a' => 'apricot', ...$array1];
    }

    public function is_available(){
        return true;
    }

    public function track_referal_code_via_session() {
        if (!session_id()) {
            session_start();
        }
        // Capture the referral code from the URL and store it in the session, if present
        if (isset($_GET['ireferal_code']) && !empty($_GET['ireferal_code'])) {
            $_SESSION['ireferal_code'] = sanitize_text_field($_GET['ireferal_code']);
        }
    }

    public function save_refer_cookie_data(){
        if (!empty($_GET["ireferal_code"])) {
            setcookie('ireferral_code_eGBata', $_GET["ireferal_code"], 0, "/");

            if (!empty($_GET["irefer_recid_frsnG1"])) {
                setcookie('irefer_recid_frsnG1', $_GET["ireferal_code"], 0, "/");
            }
        }
    }

    static public function get_referal_code(){
        if (!session_id()) {
            session_start();
        }
        # check if ireferral_code_eGBata cookie exists and return value else check if session exists for 'ireferal_code' and not empty, return value, otherwise return empty string
        if (isset($_COOKIE['ireferral_code_eGBata']) && !empty($_COOKIE['ireferral_code_eGBata'])) {
            return sanitize_text_field($_COOKIE['ireferral_code_eGBata']);
        } elseif (isset($_SESSION['ireferal_code']) && !empty($_SESSION['ireferal_code'])) {
            return sanitize_text_field($_SESSION['ireferal_code']);
        } else {
            return '';
        }
    }

    /**
     * Conditionally hide payment gateways based on the presence of a referral cookie.
     *
     * @param array $available_gateways The available WC payment gateways.
     * @return array Filtered list of payment gateways.
     */
    public function conditionally_hide_payment_gateways( $available_gateways ) {
        $referrel_code = self::get_referal_code();
        if ( isset( $referrel_code ) && !empty( $referrel_code ) ) {
            // If the referral cookie exists, unset all gateways except for the I-Refer gateway.
            foreach ( $available_gateways as $gateway_id => $gateway ) {
                if ( $gateway_id !== $this->id ) {
                    unset( $available_gateways[$gateway_id] );
                }
            }
        } else {
            // If the referral cookie does not exist, unset the I-Refer gateway.
            if ( isset( $available_gateways[$this->id] ) ) {
                unset( $available_gateways[$this->id] );
            }
        }
        return $available_gateways;
    }

    public function init_form_fields() {
        // Additional Settings here if needed

        $this->form_fields = array(
            'enabled' => array(
                'title' => __( 'Enable/Disable', 'i-refer' ),
                'type' => 'checkbox',
                'label' => __( 'Enable iRefer', 'i-refer' ),
                'default' => 'yes'
            ),
            'title' => array(
                'title' => __( 'Title', 'i-refer' ),
                'type' => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'i-refer' ),
                'default' => __( 'iRefer Online Payment', 'i-refer' )
            ),
            'description' => array(
                'title' => __( 'Description', 'i-refer' ),
                'type' => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'i-refer' ),
                'default' => __( 'Pay with iRefer', 'i-refer' )
            )
        );
    }

    public function payment_fields() {
        $image_url = plugins_url('/i-refer/images/i-refer-logo-4.png');
        echo '<p><img src="' . esc_url($image_url) . '" alt="' . esc_attr__('Pay with iRefer', 'i-refer') . '"></p>';
    }

    public function process_payment( $order_id ) {
        $order = wc_get_order( $order_id );
        try{
            $items = [];
            foreach ($order->get_items() as $item) {
                $_product = $item->get_product();
                $variant_info = [];
                if ($_product->is_type('variable')) {
                    $variation_id = $item->get_variation_id();
                    $variation = new \WC_Product_Variation($variation_id);
                    foreach ($variation->get_attributes() as $attr_name => $attr_value) {
                        $variant_info[$attr_name] = $attr_value;
                    }
                }
                $items[] = [
                    "prod_id" => $item->get_product_id(),
                    "prod_name" => $_product->get_name(),
                    "desc" => $item->get_name(),
                    "price" => number_format($_product->get_price(), 2, '.', ''),
                    "qty" => $item->get_quantity(),
                    "img_url" => wp_get_attachment_url($_product->get_image_id()),
                    "total" => number_format($item->get_total(), 2, '.', ''),
                    "variant_info" => $variant_info
                ];
            }

            $payload = [
                "vendor_site" => get_site_url(),
                "order_id" => $order->get_id(),
                "fname" => $order->get_billing_first_name(),
                "lname" => $order->get_billing_last_name(),
                "email" => $order->get_billing_email(),
                "contact" => $order->get_billing_phone(),
                "shipping_method" => $order->get_shipping_method(),
                "shipping_address" => array(
                    "first_name" => $order->get_shipping_first_name(),
                    "last_name" => $order->get_shipping_last_name(),
                    "company" => $order->get_shipping_company(),
                    "address_1" => $order->get_shipping_address_1(),
                    "address_2" => $order->get_shipping_address_2(),
                    "city" => $order->get_shipping_city(),
                    "state" => $order->get_shipping_state(),
                    "postcode" => $order->get_shipping_postcode(),
                    "country" => $order->get_shipping_country(),
                ),

                "currency" => $order->get_currency(),
                "tax" => number_format($order->get_total_tax(), 2, '.', ''),
                "shipping_total" => number_format($order->get_shipping_total(), 2, '.', ''),
                "subtotal" => number_format($order->get_subtotal(), 2, '.', ''),
                "amount" => number_format($order->get_total(), 2, '.', ''),
                "items" => $items,
                "return_url" => $this->get_return_url( $order ),
                "callback_url" => IREFER_CALLBACK_URL,
                "platform" => "wordpress",
            ];

            error_log(print_r($payload,true));

            $ireferkey = get_option("i-refer-api-keys");
            $ireferkey = $ireferkey[1];
            error_log("ireferkey: ".$ireferkey);

            // Adjusting the key length to meet the 32 bits requirement
            if (strlen($ireferkey) > 32) {
                $modified_key = substr($ireferkey, 0, 32);
            } elseif (strlen($ireferkey) < 32) {
                $modified_key = str_pad($ireferkey, 32, "0", STR_PAD_RIGHT);
            } else {
                $modified_key = str_pad($ireferkey, 32, "0", STR_PAD_RIGHT);
            }
            error_log("modified_key: ".$modified_key);

            $payload_json = json_encode($payload);
            $encrypted_json = $this->encrypt($payload_json, $modified_key);
            error_log("encrypt base64:".$encrypted_json);

            $order->update_status('pending-payment', __('Awaiting iRefer payment', 'i-refer'));

            $referrel_code = self::get_referal_code();
            $ireferal_code = !empty($referrel_code) ? sanitize_text_field($referrel_code) : "none";
            $irefer_checkout_page = IREFER_PAY_URL . "?referral_token=".$ireferal_code."&p=" . $encrypted_json;
            error_log("irefer_checkout:".$irefer_checkout_page);
        }catch (\Exception $e) {
            # process_payment
            $order->update_status('failed', __('Failed to process payment', 'i-refer'));
            irefer_deactivate_and_send_log('process_payment error: ' . $e->getMessage(). 'stack: '. $e->getTraceAsString());
            wp_die(
                __('Unable to process checkout', 'i-refer'),
                __('Error', 'i-refer'),
                array(
                    'link_text' => __('Go back to Home page', 'i-refer'),
                    'link_url' => get_option('siteurl')
                )
            );
            return null;
        }

        return array(
            'result'   => 'success',
            'redirect' => $irefer_checkout_page,
        );
    }

    private function encrypt($json_text, $key_string) {
        $plaintext = json_encode($json_text);
        $key = substr(hash('sha256', $key_string, true), 0, 32); // Ensure the key is 32 bytes
        $iv = openssl_random_pseudo_bytes(16); // 16 bytes for AES
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        $encoded = base64_encode($iv . $ciphertext); // Prepend IV to ciphertext for use in decryption
        return $encoded;
    }

    private function decrypt($ciphertext, $key_string) {
        $data = base64_decode($ciphertext);
        $key = substr(hash('sha256', $key_string, true), 0, 32);
        $iv = substr($data, 0, 16); // Extract the IV (initial 16 bytes)
        $ciphertext_raw = substr($data, 16); // Get the actual ciphertext
        $original_plaintext = openssl_decrypt($ciphertext_raw, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        return json_decode($original_plaintext, true);
    }
}
