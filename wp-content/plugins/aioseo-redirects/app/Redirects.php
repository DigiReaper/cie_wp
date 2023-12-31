<?php
namespace AIOSEO\Plugin\Addon\Redirects {
	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Main class.
	 *
	 * @since 1.0.0
	 */
	final class Redirects {
		/**
		 * Holds the instance of the plugin currently in use.
		 *
		 * @since 1.0.0
		 *
		 * @var AIOSEO\Plugin\Addon\Redirects\Redirects
		 */
		private static $instance;

		/**
		 * Plugin version for enqueueing, etc.
		 * The value is retrieved from the version constant.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $version = '';

		/**
		 * Main Redirection Manager Instance.
		 *
		 * Insures that only one instance of the addon exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 *
		 * @return Redirects
		 */
		public static function instance() {
			if ( null === self::$instance || ! self::$instance instanceof self ) {
				self::$instance = new self();
				self::$instance->constants();
				self::$instance->includes();
				self::$instance->load();
			}

			return self::$instance;
		}

		/**
		 * Setup plugin constants.
		 * All the path/URL related constants are defined in main plugin file.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function constants() {
			$defaultHeaders = [
				'name'    => 'Plugin Name',
				'version' => 'Version',
			];

			$pluginData = get_file_data( AIOSEO_REDIRECTION_MANAGER_FILE, $defaultHeaders );

			$constants = [
				'AIOSEO_REDIRECTION_MANAGER_VERSION' => $pluginData['version']
			];

			foreach ( $constants as $constant => $value ) {
				if ( ! defined( $constant ) ) {
					define( $constant, $value );
				}
			}

			$this->version = AIOSEO_REDIRECTION_MANAGER_VERSION;
		}

		/**
		 * Including the new files with PHP 5.3 style.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function includes() {
			$dependencies = [
				'/vendor/autoload.php'
			];

			foreach ( $dependencies as $path ) {
				if ( ! file_exists( AIOSEO_REDIRECTION_MANAGER_DIR . $path ) ) {
					// Something is not right.
					status_header( 500 );
					wp_die( esc_html__( 'Plugin is missing required dependencies. Please contact support for more information.', 'aioseo-redirects' ) );
				}
				require AIOSEO_REDIRECTION_MANAGER_DIR . $path;
			}
		}

		/**
		 * Load our classes.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function load() {
			aioseo()->helpers->loadTextDomain( 'aioseo-redirects' );

			$this->internalOptions   = new Utils\InternalOptions();
			$this->options           = new Utils\Options();
			$this->helpers           = new Utils\Helpers();
			$this->updates           = new Main\Updates();
			$this->api               = new Api\Api();
			$this->monitor           = new Main\Monitor();
			$this->importExport      = new ImportExport\ImportExport();
			$this->server            = aioseo()->helpers->isApache()
				? new Main\Server\Apache()
				: (
					aioseo()->helpers->isNginx()
						? new Main\Server\Nginx()
						: new Main\Server\Unknown()
				);
			$this->redirect          = new Main\Redirect();
			$this->fullSiteRedirects = new Main\FullSiteRedirects();
			$this->cache             = new Utils\Cache();
			$this->redirect404       = new Main\Redirect404();
			$this->admin             = new Admin\Admin();
			$this->usage             = new Admin\Usage();

			//load into main aioseo instance.
			aioseo()->addons->loadAddon( 'redirects', $this );
		}
	}
}

namespace {
	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	/**
	 * The function which returns the one Addon instance.
	 *
	 * @since 1.0.0
	 *
	 * @return AIOSEO\Plugin\Addon\Redirects\Addon
	 */
	function aioseoRedirects() {
		return AIOSEO\Plugin\Addon\Redirects\Redirects::instance();
	}
}