<?php
namespace AIOSEO\Plugin\Addon\Redirects\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Admin class.
 *
 * @since 1.2.2
 */
class Admin {
	/**
	 * Class constructor.
	 *
	 * @since 1.2.2
	 */
	public function __construct() {
		$this->redirect404 = new Redirect404();

		add_action( 'current_screen', [ $this, 'loadAddRedirectModal' ] );
	}

	/**
	 * Loads needed admin scripts.
	 *
	 * @since 1.2.2
	 *
	 * @return void
	 */
	public function loadAddRedirectModal() {
		if ( aioseo()->helpers->isScreenPostList() || aioseo()->helpers->isScreenPostEdit() ) {
			aioseo()->core->assets->load( 'src/vue/standalone/redirects/add-redirect/main.js' );
		}
	}
}