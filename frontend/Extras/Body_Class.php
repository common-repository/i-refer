<?php
/**
 * I-Refer
 *
 * @package   i-Refer
 * @author    i-Refer <wcd@i-refer.global>
 * @license   LGPLv2.1
 * @link      https://i-refer.app
 */

namespace I_Refer\Frontend\Extras;

use I_Refer\Engine\Base;

/**
 * Add custom css class to <body>
 */
class Body_Class extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		parent::initialize();

		// \add_filter( 'body_class', array( self::class, 'add_i_refer_class' ), 10, 1 );
	}

	/**
	 * Add class in the body on the frontend
	 *
	 * @param array $classes The array with all the classes of the page.
	 * @since 2.0.0
	 * @return array
	 */
	public static function add_pn_class( array $classes ) {
		$classes[] = IREFER_TEXTDOMAIN;

		return $classes;
	}

}
