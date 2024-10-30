<?php

/**
 * I-Refer
 *
 * @package   i-Refer
 * @author    i-Refer <wcd@i-refer.global>
 * @license   LGPLv2.1
 * @link      https://i-refer.app
 */

namespace I_Refer\Ajax;

use I_Refer\Engine\Base;

/**
 * AJAX as logged user
 */
class Ajax_Admin extends Base {

    /**
     * Initialize the class.
     *
     * @return void|bool
     */
    public function initialize() {
        if ( !\apply_filters( 'i_refer_ajax_admin_initialize', true ) ) {
            return;
        }

        // For logged user
        \add_action( 'wp_ajax_your_admin_method', array( $this, 'sample_admin_method' ) );
    }

    /**
     * The method to run on ajax
     *
     * @since 2.0.0
     * @return void
     */
    public function sample_admin_method() {
        $return = array(
            'message' => 'Saved',
            'ID'      => 2,
        );

        \wp_send_json_success( $return );
        // wp_send_json_error( $return );
    }

}
