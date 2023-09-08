<?php
namespace AIOSEO\Plugin\Addon\LinkAssistant\Main;

use AIOSEO\Plugin\Addon\LinkAssistant\Links;
use AIOSEO\Plugin\Addon\LinkAssistant\Suggestions;
use AIOSEO\Plugin\Addon\LinkAssistant\Models;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The main class where all the scan magic happens.
 *
 * @since 1.0.0
 */
class Main {
	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if (
			! aioseo()->core->db->tableExists( 'aioseo_links' ) ||
			! aioseo()->core->db->tableExists( 'aioseo_links_suggestions' )
		) {
			aioseolinkAssistant()->updates->addInitialTables();
			aioseolinkAssistant()->updates->addInitialColumns();

			return;
		}

		$this->paragraph   = new Paragraph();
		$this->links       = new Links\Links();
		$this->suggestions = new Suggestions\Suggestions();

		if ( ! is_admin() ) {
			return;
		}

		add_action( 'delete_post', [ $this, 'removeOrphanedData' ] );
	}

	/**
	 * If a post is deleted, we should also remove any Link Assistant data for it.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function removeOrphanedData( $postId ) {
		Models\Link::deleteLinks( $postId );

		// Delete all suggestions that originate from or point to the deleted post.
		aioseo()->core->db->delete( 'aioseo_links_suggestions' )
			->where( 'post_id', $postId )
			->run();

		aioseo()->core->db->delete( 'aioseo_links_suggestions' )
			->where( 'linked_post_id', $postId )
			->run();
	}
}