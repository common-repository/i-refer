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

use I18n_Notice_WordPressOrg;
use I_Refer\Engine\Base;

/**
 * Everything that involves notification on the WordPress dashboard
 */
class Notices extends Base {

	/**
	 * Initialize the class
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return;
		}
        #\wpdesk_wp_notice( \__( 'A Page Builder/Visual Composer was found on this website!', 'i-refer' ), 'error', true );
	}

}
