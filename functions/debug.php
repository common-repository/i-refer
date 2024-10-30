<?php
/**
 * I-Refer
 *
 * @package   i-Refer
 * @author    i-Refer <wcd@i-refer.global>
 * @license   LGPLv2.1
 * @link      https://i-refer.app
 */


$irefer_debug = new WPBP_Debug( __( 'Plugin Name', IREFER_TEXTDOMAIN ) );

/**
 * Log text inside the debugging plugins.
 *
 * @param string $text The text.
 * @return void
 */
function irefer_log( string $text ) {
	global $irefer_debug;
    $irefer_debug->log( $text );
}
