<?php
/**
 * Block patterns handling.
 *
 * @package Pulsar
 */

namespace Pulsar\Editor;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Config;

/**
 * Block patterns handling.
 */
class Patterns implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot() {
		add_action( 'init', [ $this, 'register_categories' ] );
		add_action( 'init', [ $this, 'register_patterns' ] );
	}

	/**
	 * Retrieve the config for this class.
	 *
	 * @return array
	 */
	public function config() {
		return Config::get( 'block-patterns' );
	}

	 /**
	  * Registers custom block patterns and categories.
	  *
	  * @access public
	  * @return void
	  */
	public function register_categories() {

		$categories = $this->config()['categories'] ?: [];

		foreach ( $categories as $category_slug => $category ) {
			$this->add_category( $category_slug, $category );
		}
	}

	/**
	 * Registers custom block patterns and categories.
	 *
	 * @access public
	 * @return void
	 */
	public function register_patterns() {

		$block_patterns = $this->config()['patterns'] ?: [];

		foreach ( $block_patterns as $pattern_slug => $pattern ) {
			$this->add_pattern( $pattern_slug, $pattern );
		}
	}

	/**
	 * Adds a block pattern category.
	 *
	 * @access protected
	 * @param  string $slug
	 * @param  string $label
	 * @return void
	 */
	protected function add_category( string $slug, string $label ) {

		// Register block pattern categories.
		register_block_pattern_category(
			$slug,
			[
				'label' => $label,
			]
		);
	}

	/**
	 * Adds a block pattern.
	 *
	 * @access protected
	 * @param  string $slug
	 * @param  array  $args
	 * @return void
	 */
	protected function add_pattern( string $slug, array $args = [] ) {

		/**
		 * If no content is passed in, assume there is a corresponding
		 * `/patterns/{$slug}.php` file and pull the content from there.
		 */
		$content = $args['content'] ?? $this->pattern_content( $slug );

		// A pattern must have content.
		if ( ! $content ) {
			return;
		}

		register_block_pattern(
			"pulsar/{$slug}",
			wp_parse_args(
				$args,
				[
					'categories'    => [ 'pulsar' ],
					'content'       => $content,
					'viewportWidth' => 1024,
				]
			)
		);
	}

	/**
	 * Returns a pattern file's content.
	 *
	 * @access protected
	 * @param  string $slug
	 * @return string
	 */
	protected function pattern_content( string $slug ) {
		ob_start();
		include get_theme_file_path( "patterns/{$slug}.php" );
		return ob_get_clean();
	}
}