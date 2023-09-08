<?php
namespace AIOSEO\Plugin\Addon\Redirects\ImportExport;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use AIOSEO\Plugin\Common\Models;

/**
 * Handles the importing/exporting of settings and SEO data.
 *
 * @since 1.0.0
 */
class ImportExport {
	/**
	 * Set up an array of plugins for importing.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $plugins = [];

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->redirection         = new Redirection( $this );
		$this->yoastSeo            = new YoastSeo( $this );
		$this->seoPress            = new SeoPress( $this );
		$this->simple301Redirects  = new Simple301Redirects( $this );
		$this->safeRedirectManager = new SafeRedirectManager( $this );
		$this->redirects301Pro     = new Redirects301Pro( $this );
		$this->redirects301        = new Redirects301( $this );
		$this->pageLinksTo         = new PageLinksTo( $this );
	}

	/**
	 * Starts an import.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $plugin The slug of the plugin to import.
	 * @return void
	 */
	public function startImport( $plugin ) {
		foreach ( $this->plugins as $pluginData ) {
			if ( $pluginData['slug'] === $plugin ) {
				$pluginData['class']->doImport();

				return;
			}
		}
	}

	/**
	 * Adds plugins to the import/export.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $plugins The plugins to add.
	 * @return void
	 */
	public function addPlugins( $plugins ) {
		$this->plugins = array_merge( $this->plugins, $plugins );
	}

	/**
	 * Get the plugins we allow importing from.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function plugins() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		$plugins          = [];
		$installedPlugins = array_keys( get_plugins() );
		foreach ( $this->plugins as $importerPlugin ) {
			$data = [
				'slug'      => $importerPlugin['slug'],
				'name'      => $importerPlugin['name'],
				'version'   => null,
				'canImport' => false,
				'basename'  => $importerPlugin['basename'],
				'installed' => false
			];

			if ( in_array( $importerPlugin['basename'], $installedPlugins, true ) ) {
				$pluginData = get_file_data( trailingslashit( WP_PLUGIN_DIR ) . $importerPlugin['basename'], [
					'name'    => 'Plugin Name',
					'version' => 'Version',
				] );

				$canImport = false;
				if ( version_compare( $importerPlugin['version'], $pluginData['version'], '<=' ) ) {
					$canImport = true;
				}

				$data['name']      = $pluginData['name'];
				$data['version']   = $pluginData['version'];
				$data['canImport'] = $canImport;
				$data['installed'] = true;
			}

			$plugins[] = $data;
		}

		return $plugins;
	}
}