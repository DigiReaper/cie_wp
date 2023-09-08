<?php
namespace AIOSEO\Plugin\Pro\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use AIOSEO\Plugin\Common\Admin as CommonAdmin;

/**
 * Class that holds our dashboard widget.
 *
 * @since 4.0.0
 */
class Dashboard extends CommonAdmin\Dashboard {
	/**
	 * Whether or not to show the widget.
	 *
	 * @since 4.0.0
	 *
	 * @return boolean True if yes, false otherwise.
	 */
	protected function canShowWidgets() {
		if ( ! aioseo()->license->isActive() ) {
			return true;
		}

		// Check if the option is disabled.
		if ( ! aioseo()->options->advanced->dashboardWidgets ) {
			return false;
		}

		return true;
	}
}