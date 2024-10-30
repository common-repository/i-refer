<?php

/**
 * I-Refer
 *
 * @package   i-Refer
 * @author    i-Refer <wcd@i-refer.global>
 * @license   LGPLv2.1
 * @link      https://i-refer.app
 */

namespace I_Refer\Integrations;

use I_Refer\Engine\Base;

/**
 * Core functions of the IRefer Plugin
 */
class IRefer_Core extends Base {
    public function __construct(){
        # add_action('init', [$this, 'IREFER_store_cookie_data']);
    }

    public function IREFER_store_cookie_data()
    {
        if (!empty($_GET["ireferal_code"]) && ctype_alnum($_GET["ireferal_code"])) {

            setcookie('ireferral_code_eGBata', $_GET["ireferal_code"], 0, "/");

            if (!empty($_GET["irefer_recid_frsnG1"]) && ctype_alnum($_GET["irefer_recid_frsnG1"])) {
                setcookie('irefer_recid_frsnG1', $_GET["irefer_recid_frsnG1"], 0, "/");
                setcookie('irefer_recid_frsnG1', $_GET["irefer_recid_frsnG1"], 0, "/");
            }

            // Cart Token Created
            $cart_token = irefer_random_str(14);
            setcookie('irefer_cart_token_6lg5La', $cart_token, 0, "/");
        }
    }
}