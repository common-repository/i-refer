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

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;
use I_Refer\Engine\Base;

/**
 * This class contain the Enqueue stuff for the backend
 */
class Enqueue extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return;
		}

		\add_action( AssetManager::ACTION_SETUP, array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue assets with Inpyside library https://inpsyde.github.io/assets
	 *
	 * @param \Inpsyde\Assets\AssetManager $asset_manager The class.
	 * @return void
	 */
	public function enqueue_assets( AssetManager $asset_manager ) {
		// Load admin style sheet and JavaScript.
		$assets = $this->enqueue_admin_styles();

		if ( !empty( $assets ) ) {
			foreach ( $assets as $asset ) {
				$asset_manager->register( $asset );
			}
		}

		$assets = $this->enqueue_admin_scripts();

		if ( !empty( $assets ) ) {
			foreach ( $assets as $asset ) {
				$asset_manager->register( $asset );
			}
		}

	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function enqueue_admin_styles() {
		$admin_page = \get_current_screen();
		$styles     = array();

//		if ( !\is_null( $admin_page ) && 'toplevel_page_plugin-name' === $admin_page->id ) {
//			$styles[0] = new Style( \I_Refer\Backend\IREFEER_TEXTDOMAIN . '-settings-style', \plugins_url( 'assets/build/plugin-settings.css', IREFER_PLUGIN_ABSOLUTE ) );
//			$styles[0]->forLocation( Asset::BACKEND )->withVersion( \I_Refer\Backend\IREFER_VERSION);
//			$styles[0]->withDependencies( 'dashicons' );
//		}
//
//		$styles[1] = new Style( \I_Refer\Backend\IREFEER_TEXTDOMAIN . '-admin-style', \plugins_url( 'assets/build/plugin-admin.css', IREFER_PLUGIN_ABSOLUTE ) );
//		$styles[1]->forLocation( Asset::BACKEND )->withVersion( \I_Refer\Backend\IREFER_VERSION);
//		$styles[1]->withDependencies( 'dashicons' );

		return $styles;
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function enqueue_admin_scripts() {
		$admin_page = \get_current_screen();
		$scripts    = array();

//		if ( !\is_null( $admin_page ) && 'toplevel_page_plugin-name' === $admin_page->id ) {
//			$scripts[0] = new Script( \I_Refer\Backend\IREFEER_TEXTDOMAIN . '-settings-script', \plugins_url( 'assets/build/plugin-settings.js', IREFER_PLUGIN_ABSOLUTE ) );
//			$scripts[0]->forLocation( Asset::BACKEND )->withVersion( \I_Refer\Backend\IREFEER_VERSION);
//			$scripts[0]->withDependencies( 'jquery-ui-tabs' );
//			$scripts[0]->canEnqueue(
//				function() {
//					return \current_user_can( 'manage_options' );
//				}
//			);
//		}
//
//		$scripts[1] = new Script( \I_Refer\Backend\IREFER_TEXTDOMAIN . '-settings-admin', \plugins_url( 'assets/build/plugin-admin.js', IREFER_PLUGIN_ABSOLUTE ) );
//		$scripts[1]->forLocation( Asset::BACKEND )->withVersion( \I_Refer\Backend\IREFER_VERSION);
//		$scripts[1]->dependencies();

		return $scripts;
	}
}
