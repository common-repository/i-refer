<?php
/**
 * I-Refer
 *
 * @package   i-Refer
 * @author    i-Refer <wcd@i-refer.global>
 * @license   LGPLv2.1
 * @link      https://i-refer.app
 */

namespace I_Refer\Rest;

use I_Refer\Engine\Base;

/**
 * I-Refer REST Endpoints
 */
class IRefer_WC_Rest extends Base
{

    /**
     * Initialize the class.
     *
     * @return void|bool
     */
    public function initialize()
    {
        parent::initialize();

        \add_action('rest_api_init', array($this, 'i_refer_wc_callback'));
    }

    /**
     * Endpoint to update WC Order
     *
     * @return void
     * @since 2.0.0
     */
    public function i_refer_wc_callback()
    {
        \register_rest_route(
            'irefer/v2',
            '/update_order/',
            array(
                'methods' => 'POST',
                'permission_callback' => '__return_true',
                'callback' => array($this, 'update_order'),
                'args' => [
                    'order_id' => [
                        'required' => true,
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ],
                    'email' => [
                        'required' => true,
                        'validate_callback' => function ($param, $request, $key) {
                            return is_email($param);
                        }
                    ],
                    'status' => [
                        'required' => true,
                        'validate_callback' => function ($param, $request, $key) {
                            return in_array($param, ['cancelled', 'paid']);
                        }
                    ],
                ],
            )
        );
    }

    /**
     * Update WC Order
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_order(\WP_REST_Request $request)
    {
        $provided_hash = $request->get_header('x-irefer-hash');
        if (empty($provided_hash)) {
            return new \WP_Error('missing_hash', __('The x-irefer-hash header is missing.', 'i-refer'), array('status' => 400));
        }

        $order_id = $request->get_param('order_id');
        $order = \wc_get_order($order_id);

        $ireferkey = get_option("i-refer-api-keys");
        $ireferkey = $ireferkey[1];
        $data = $request->get_body();
        // Remove all white spaces in $data
        $trimmed_data = preg_replace('/\s+/', '', $data);

        $computed_hash = hash_hmac('sha256', $trimmed_data, $ireferkey);
        $provided_hash = $request->get_header('x-irefer-hash');

        if ($computed_hash !== $provided_hash) {
            return new \WP_Error('hash_mismatch', __('The provided hash does not match the computed hash.', 'i-refer'), array('status' => 403));
        }

        if (!$order) {
            return new \WP_Error('no_order', __('No order found for provided ID', 'i-refer'), array('status' => 404));
        }

        $request_email = $request->get_param('email');
        $order_email = $order->get_billing_email();
        if ($request_email !== $order_email) {
            return new \WP_Error('email_mismatch', __('The email provided does not match the order email.', 'i-refer'), array('status' => 403));
        }

        $requested_status = $request->get_param('status');
        if ($order->get_status() == 'on-hold' || $order->get_status() == 'pending-payment'|| $order->get_status() == 'pending') {
            if ($requested_status == 'paid') {
                // TODO: Instead of pending, change to processing
                $order->update_status('processing', __('Order updated to processing via REST API.', 'i-refer'));
                return new \WP_REST_Response(__('Order status updated successfully', 'i-refer'), 200);
            } elseif ($requested_status == 'cancelled') {
                $order->update_status('cancelled', __('Order cancelled via REST API.', 'i-refer'));
                return new \WP_REST_Response(__('Order status updated to cancelled successfully', 'i-refer'), 200);
            }
        } else {
            return new \WP_Error('invalid_status', __('Order status not updated. The order is not on-hold or the requested status is not recognized.', 'i-refer'), array('status' => 400));
        }
    }
}
