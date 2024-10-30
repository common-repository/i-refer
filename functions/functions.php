<?php
/**
 * I-Refer
 *
 * @package   i-Refer
 * @author    i-Refer <wcd@i-refer.global>
 * @license   LGPLv2.1
 * @link      https://i-refer.app
 */

/**
 * Get the settings of the plugin in a filterable way
 *
 * @since 2.0.0
 * @return array
 */
function irefer_get_settings() {
	return apply_filters( 'irefer_get_settings', get_option( IREFER_TEXTDOMAIN . '-settings' ) );
}

function irefer_random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}