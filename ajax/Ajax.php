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
 * AJAX in the public
 */
class Ajax extends Base {

    /**
     * Initialize the class.
     *
     * @return void|bool
     */
    public function initialize() {
        if ( !\apply_filters( 'i_refer_ajax_initialize', true ) ) {
            return;
        }

        // For not logged user
        \add_action( 'wp_ajax_nopriv_your_method', array( $this, 'sample_method' ) );
    }

    /**
     * The method to run on ajax
     *
     * @since 2.0.0
     * @return void
     */
    public function sample_method() {
        $return = array(
            'message' => 'Saved',
            'ID'      => 1,
        );

        \wp_send_json_success( $return );
        // wp_send_json_error( $return );
    }

}
