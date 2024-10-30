<?php
/**
 * I-Refer
 *
 * @package   i-Refer
 * @author    i-Refer <wcd@i-refer.global>
 * @license   LGPLv2.1
 * @link      https://i-refer.app
 */

namespace I_Refer\Frontend;

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;

use I_Refer\Engine\Base;

/**
 * Enqueue stuff on the frontend
 */
class Enqueue extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		parent::initialize();
        error_log('Enqueue initialized. AssetManager::ACTION_SETUP:'.AssetManager::ACTION_SETUP);
		\add_action( AssetManager::ACTION_SETUP, array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue assets with Inpyside library https://inpsyde.github.io/assets
	 *
	 * @param \Inpsyde\Assets\AssetManager $asset_manager The class.
	 * @return void
	 */
	public function enqueue_assets( AssetManager $asset_manager ) {
	    error_log('enqueue assets');
		// Load public-facing style sheet and JavaScript.
		$assets = $this->enqueue_styles();

		if ( !empty( $assets ) ) {
			foreach ( $assets as $asset ) {
				$asset_manager->register( $asset );
			}
		}

		$assets = $this->enqueue_scripts();

		if ( !empty( $assets ) ) {
			foreach ( $assets as $asset ) {
				$asset_manager->register( $asset );
			}
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since {{plugin_version}}
	 * @return array
	 */
	public function enqueue_styles() {
	    error_log('enqueue style');
		$styles = array();
		 $styles[0] = new Style( IREFER_TEXTDOMAIN . '-plugin-styles', \plugins_url( 'assets/public-style.css', IREFER_PLUGIN_ABSOLUTE ) );
		 $styles[0]->forLocation( Asset::FRONTEND )->useAsyncFilter()->withVersion( IREFER_VERSION );
		 $styles[0]->dependencies();
		return $styles;

		return $styles;
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since {{plugin_version}}
	 * @return array
	 */
	public static function enqueue_scripts() {
		$scripts = array();
//		$scripts[0] = new Script( IREFER_TEXTDOMAIN . '-plugin-script', \plugins_url( 'assets/build/plugin-public.js', IREFER_PLUGIN_ABSOLUTE ) );
//		$scripts[0]->forLocation( Asset::FRONTEND )->useAsyncFilter()->withVersion( IREFER_VERSION );
//		$scripts[0]->dependencies();
//		$scripts[0]->withLocalize(
//			'exampleDemo',
//			array(
//				'alert'   => \__( 'Error!', 'i-refer' ),
//				'nonce'   => \wp_create_nonce( 'demo_example' ),
//				'wp_rest' => \wp_create_nonce( 'wp_rest' ),
//			)
//		);
		return $scripts;
	}

}
