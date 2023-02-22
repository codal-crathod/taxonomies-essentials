<?php
/**
 * Plugin manifest class.
 *
 * @package wp-menu-custom-fields
 */

namespace Taxonomies_Validation\Inc;

use \Taxonomies_Validation\Inc\Traits\Singleton;

/**
 * Class Plugin
 */
class Plugin {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {

		// Load plugin classes.
		Assets::get_instance();
		Taxonomies_Validation::get_instance();

	}

}
