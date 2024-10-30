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
 * The various Cron of this plugin
 */
class Cron extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		/*
		 * Load CronPlus
		 */
		$args = array(
			'recurrence'       => 'hourly',
			'schedule'         => 'schedule',
			'name'             => 'hourly_cron',
			'cb'               => array( $this, 'hourly_cron' ),
			'plugin_root_file' => 'i-refer.php',
		);

		$cronplus = new \CronPlus( $args );
		// Schedule the event
		// $cronplus->schedule_event();

		// Remove the event by the schedule
		// $cronplus->clear_schedule_by_hook();

		// Jump the scheduled event
		// $cronplus->unschedule_specific_event();
	}

}
